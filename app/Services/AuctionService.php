<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AuctionService
{
    public function __construct(private ImageService $imageService) {}

    public function paginatePublic(Request $request): LengthAwarePaginator
    {
        $selectedCategoryId = $request->filled('category')
            ? (int) $request->input('category')
            : null;

        $query = Auction::with(['image', 'category', 'user'])->publiclyVisible();

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        if ($request->filled('q')) {
            $query->where('name', 'ilike', '%'.$request->input('q').'%');
        }

        if ($selectedCategoryId) {
            $categoryIds = $this->getCategoryWithDescendantIds($selectedCategoryId);
            $query->whereIn('categoryId', $categoryIds);
        }

        match ($request->input('sort', 'newest')) {
            'oldest' => $query->orderBy('createdAt', 'asc'),
            default => $query->latest('createdAt'),
        };

        return $query->paginate((int) $request->input('per_page', 10));
    }

    public function getLatest(int $limit = 6): Collection
    {
        return Auction::with(['image', 'category'])
            ->publiclyVisible()
            ->latest('createdAt')
            ->limit($limit)
            ->get();
    }

    public function findVisible(Auction $auction, ?User $viewer): Auction
    {
        $isOwner = $viewer && (int) $viewer->id === (int) $auction->userId;
        $isAdmin = $viewer && $viewer->isAdmin;

        if (! $auction->approved && ! $isAdmin && ! $isOwner) {
            abort(404);
        }

        $auction->load(['user.ratingsReceived', 'image', 'additionalImages', 'category']);

        return $auction;
    }

    public function getSellerOtherAuctions(Auction $auction, int $limit = 3): Collection
    {
        return Auction::with('image')
            ->where('userId', $auction->userId)
            ->where('id', '!=', $auction->id)
            ->publiclyVisible()
            ->latest('createdAt')
            ->limit($limit)
            ->get();
    }

    public function formatDisplayPhone(string $phoneNumber, bool $showFull): string
    {
        $stored = trim($phoneNumber);
        $digits = preg_replace('/\D/', '', $stored);

        $prefix = '';
        $local = $digits;

        if (str_starts_with($digits, '48') && strlen($digits) >= 11) {
            $prefix = '+48 ';
            $local = substr($digits, 2);
        } elseif (str_starts_with($stored, '+') && strlen($digits) > 9) {
            $countryCodeLength = strlen($digits) - 9;
            $prefix = '+'.substr($digits, 0, $countryCodeLength).' ';
            $local = substr($digits, -9);
        }

        if (strlen($local) !== 9) {
            return $stored;
        }

        if ($showFull) {
            return $prefix.substr($local, 0, 3).' '.substr($local, 3, 3).' '.substr($local, 6, 3);
        }

        return $prefix.substr($local, 0, 3).' *** '.substr($local, 6, 3);
    }

    public function getUserAuctions(User $user): Collection
    {
        return Auction::with(['image', 'category'])
            ->where('userId', $user->id)
            ->latest('createdAt')
            ->get();
    }

    public function getAuctionCounts(Collection $auctions): array
    {
        return [
            'active' => $auctions->where('status', 'aktywna')->where('approved', true)->count(),
            'closed' => $auctions->where('status', 'zakończona')->count(),
            'pending' => $auctions->where('approved', false)->count(),
        ];
    }

    public function create(array $data, User $user, ?UploadedFile $thumbnail, array $images = []): Auction
    {
        $requiresApproval = Category::requiresApproval((int) $data['categoryId']);

        return DB::transaction(function () use ($data, $user, $thumbnail, $images, $requiresApproval) {
            $imageId = $thumbnail
                ? $this->imageService->storeImage($thumbnail)->id
                : Image::query()->value('id');

            if (! $imageId) {
                throw new \RuntimeException('Brak domyślnego zdjęcia w systemie.');
            }

            $auction = Auction::create([
                ...$this->prepareAuctionPayload($data, (int) $data['categoryId']),
                'status' => 'aktywna',
                'approved' => ! $requiresApproval,
                'userId' => $user->id,
                'imageId' => $imageId,
            ]);

            $this->attachAdditionalImages($auction, $images);

            return $auction->load(['image', 'additionalImages', 'category', 'user']);
        });
    }

    public function update(
        Auction $auction,
        array $data,
        ?UploadedFile $thumbnail = null,
        ?array $images = null,
        bool $resetApprovalForJob = true,
    ): Auction {
        return DB::transaction(function () use ($auction, $data, $thumbnail, $images, $resetApprovalForJob) {
            $payload = $this->prepareAuctionPayload($data, (int) $data['categoryId']);

            if ($resetApprovalForJob && Category::requiresApproval((int) $data['categoryId'])) {
                $payload['approved'] = false;
            }

            if ($thumbnail) {
                $payload['imageId'] = $this->imageService->storeImage($thumbnail)->id;
            }

            $auction->update($payload);

            if ($images !== null) {
                $auction->additionalImages()->detach();
                $this->attachAdditionalImages($auction, $images);
            }

            return $auction->fresh(['image', 'additionalImages', 'category', 'user']);
        });
    }

    public function close(Auction $auction): Auction
    {
        $auction->update(['status' => 'zakończona']);

        return $auction->fresh(['image', 'category', 'user']);
    }

    public function buildCategoryTree(?Collection $categories = null, ?array $directAuctionCounts = null): array
    {
        $categories ??= Category::with('children.children')->whereNull('parentId')->orderBy('name')->get();

        if ($directAuctionCounts === null) {
            $directAuctionCounts = Auction::query()
                ->publiclyVisible()
                ->selectRaw('"categoryId", COUNT(*) as count')
                ->groupBy('categoryId')
                ->pluck('count', 'categoryId')
                ->map(fn ($count) => (int) $count)
                ->all();
        }

        return $categories->map(function (Category $category) use ($directAuctionCounts) {
            $children = $category->children->isNotEmpty()
                ? $this->buildCategoryTree($category->children->sortBy('name'), $directAuctionCounts)
                : [];

            $directCount = $directAuctionCounts[$category->id] ?? 0;
            $childrenCount = array_sum(array_column($children, 'count'));

            return [
                'id' => $category->id,
                'name' => $category->name,
                'count' => $directCount + $childrenCount,
                'children' => $children,
            ];
        })->values()->all();
    }

    public function getSelectableCategories(): Collection
    {
        $categories = Category::orderBy('name')->get()->keyBy('id');

        return $categories
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'label' => $this->buildCategoryLabel($category, $categories),
            ])
            ->values();
    }

    public function prepareAuctionPayload(array $data, int $categoryId): array
    {
        $isJob = Category::requiresApproval($categoryId);
        $payload = collect($data)->except(['thumbnail', 'images'])->all();

        if ($isJob) {
            $payload['salaryType'] = $data['salaryType'];
            $payload['negotiable'] = $data['salaryType'] === 'do uzgodnienia';
            $payload['price'] = $payload['negotiable'] ? 0 : $data['price'];
        } else {
            $payload['salaryType'] = null;
            $payload['negotiable'] = (bool) ($data['negotiable'] ?? false);
        }

        return $payload;
    }

    private function attachAdditionalImages(Auction $auction, array $images): void
    {
        foreach ($images as $order => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $image = $this->imageService->storeImage($file);
            $auction->additionalImages()->attach($image->id, ['order' => $order + 1]);
        }
    }

    private function buildCategoryLabel(Category $category, Collection $allCategories): string
    {
        $parts = [];
        $current = $category;

        while ($current) {
            array_unshift($parts, $current->name);
            $current = $current->parentId ? $allCategories->get($current->parentId) : null;
        }

        return implode(' > ', $parts);
    }

    private function getCategoryWithDescendantIds(int $categoryId): array
    {
        $ids = [$categoryId];

        foreach (Category::where('parentId', $categoryId)->pluck('id') as $childId) {
            $ids = array_merge($ids, $this->getCategoryWithDescendantIds($childId));
        }

        return $ids;
    }
}
