<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateAuctionRequest;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Auction;
use App\Models\Category;
use App\Models\Image;
use App\Models\Rating;
use App\Services\AuctionService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuctionController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategoryId = $request->filled('category')
            ? (int) $request->input('category')
            : null;

        $query = Auction::with('image')->publiclyVisible();

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        if ($request->filled('q')) {
            $query->where('name', 'ilike', '%' . $request->input('q') . '%');
        }

        if ($selectedCategoryId) {
            $categoryIds = $this->getCategoryWithDescendantIds($selectedCategoryId);
            $query->whereIn('categoryId', $categoryIds);
        }

        if ($request->filled('city_lat') && $request->filled('city_lng')) {
            $centerLat = (float) $request->input('city_lat');
            $centerLng = (float) $request->input('city_lng');
            $radiusKm = $request->filled('distance')
                ? (float) $request->input('distance')
                : 10.0;

            if ($radiusKm > 0) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->whereRaw(
                        '(6371 * acos(LEAST(1, GREATEST(-1,
                            cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                            + sin(radians(?)) * sin(radians(latitude))
                        )))) <= ?',
                        [$centerLat, $centerLng, $centerLat, $radiusKm],
                    );
            }
        }

        match ($request->input('sort', 'newest')) {
            'oldest' => $query->orderBy('createdAt', 'asc'),
            default => $query->latest('createdAt'),
        };

        $auctions = $query->paginate(10)->withQueryString();

        $directAuctionCounts = Auction::query()
            ->publiclyVisible()
            ->selectRaw('"categoryId", COUNT(*) as count')
            ->groupBy('categoryId')
            ->pluck('count', 'categoryId')
            ->map(fn ($count) => (int) $count)
            ->all();

        $categoryTree = $this->buildCategoryTree(
            Category::with('children.children')->whereNull('parentId')->orderBy('name')->get(),
            $directAuctionCounts,
        );

        $selectedCategoryName = $selectedCategoryId
            ? Category::find($selectedCategoryId)?->name
            : null;

        return view('auctions', compact(
            'auctions',
            'categoryTree',
            'selectedCategoryId',
            'selectedCategoryName',
        ));
    }

    private function buildCategoryTree($categories, array $directAuctionCounts): array
    {
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

    private function getCategoryWithDescendantIds(int $categoryId): array
    {
        $ids = [$categoryId];

        foreach (Category::where('parentId', $categoryId)->pluck('id') as $childId) {
            $ids = array_merge($ids, $this->getCategoryWithDescendantIds($childId));
        }

        return $ids;
    }

    public function show(Auction $auction): View
    {
        $isOwner = auth()->check() && (int) auth()->id() === (int) $auction->userId;

        if ($auction->status !== 'aktywna' && ! $this->viewerIsAdmin() && ! $isOwner) {
            abort(404);
        }

        if (! $auction->approved && ! $this->viewerIsAdmin() && ! $isOwner) {
            abort(404);
        }

        $auction->load(['user', 'image', 'additionalImages']);

        $images = collect([$auction->image])
            ->merge($auction->additionalImages)
            ->filter()
            ->unique('id')
            ->values();

        $otherAuctions = Auction::with('image')
            ->where('userId', $auction->userId)
            ->where('id', '!=', $auction->id)
            ->publiclyVisible()
            ->latest('createdAt')
            ->limit(3)
            ->get();

        $isOwner = auth()->check() && (int) auth()->id() === (int) $auction->userId;
        $displayPhone = app(AuctionService::class)->formatDisplayPhone(
            $auction->user->phoneNumber,
            $isOwner,
        );
        $isJobOffer = Category::requiresApproval($auction->categoryId);
        $isFollowed = auth()->check()
            && (int) auth()->id() !== (int) $auction->userId
            && auth()->user()->followedAuctions()->whereKey($auction->id)->exists();

        return view('auction-page', compact(
            'auction',
            'images',
            'otherAuctions',
            'displayPhone',
            'isOwner',
            'isJobOffer',
            'isFollowed',
        ));
    }

    // Ogłoszenia zalogowanego użytkownika
    public function mine(): View
    {
        $auctions = Auction::with('image')
            ->where('userId', Auth::id())
            ->latest('createdAt')
            ->get();

        $activeCount = $auctions->where('status', 'aktywna')->where('approved', true)->count();
        $closedCount = $auctions->where('status', 'zakończona')->count();
        $pendingCount = $auctions->where('approved', false)->count();

        return view('my-auctions', compact('auctions', 'activeCount', 'closedCount', 'pendingCount'));
    }

    // Formularz edycji ogłoszenia
    public function edit(Auction $auction): View|RedirectResponse
    {
        $this->ensureOwner($auction);

        if ($auction->status !== 'aktywna') {
            return redirect()
                ->route('auctions.mine')
                ->withErrors(['auction' => 'Zamkniętego ogłoszenia nie można edytować.']);
        }

        return view('edit-auction', [
            'auction' => $auction,
            ...$this->formCategoryPickerData($auction->categoryId),
            'pracaCategoryIds' => Category::getPracaCategoryIds(),
        ]);
    }

    // Edycja aukcji przez administratora
    public function adminEdit(Auction $auction): View
    {
        $this->ensureAdmin();

        return view('edit-auction', [
            'auction' => $auction,
            ...$this->formCategoryPickerData($auction->categoryId),
            'pracaCategoryIds' => Category::getPracaCategoryIds(),
            'cancelRoute' => route('admin.auctions.index'),
            'updateRoute' => route('admin.auctions.update', $auction),
            'isAdminEdit' => true,
        ]);
    }

    // Aktualizacja ogłoszenia
    public function update(UpdateAuctionRequest $request, Auction $auction, ImageService $imageService): RedirectResponse
    {
        $this->ensureOwner($auction);

        if ($auction->status !== 'aktywna') {
            return redirect()
                ->route('auctions.mine')
                ->withErrors(['auction' => 'Zamkniętego ogłoszenia nie można edytować.']);
        }

        DB::transaction(function () use ($request, $auction, $imageService) {
            $data = $this->prepareAuctionPayload($request->validated(), (int) $request->validated('categoryId'));

            if (Category::requiresApproval($request->validated('categoryId'))) {
                $data['approved'] = false;
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $imageService->storeImage($request->file('thumbnail'));
                $data['imageId'] = $thumbnail->id;
            }

            $auction->update($data);

            if ($request->hasFile('images')) {
                $auction->additionalImages()->detach();

                foreach ($request->file('images') as $order => $file) {
                    $image = $imageService->storeImage($file);
                    $auction->additionalImages()->attach($image->id, ['order' => $order + 1]);
                }
            }
        });

        return redirect()
            ->route('auctions.mine')
            ->with('success', Category::requiresApproval($request->validated('categoryId'))
                ? 'Ogłoszenie zostało zaktualizowane i ponownie przesłane do akceptacji.'
                : 'Ogłoszenie zostało zaktualizowane.');
    }

    // Aktualizacja aukcji przez administratora
    public function adminUpdate(AdminUpdateAuctionRequest $request, Auction $auction, ImageService $imageService): RedirectResponse
    {
        $this->ensureAdmin();

        DB::transaction(function () use ($request, $auction, $imageService) {
            $data = $this->prepareAuctionPayload($request->validated(), (int) $request->validated('categoryId'));

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $imageService->storeImage($request->file('thumbnail'));
                $data['imageId'] = $thumbnail->id;
            }

            $auction->update($data);

            if ($request->hasFile('images')) {
                $auction->additionalImages()->detach();

                foreach ($request->file('images') as $order => $file) {
                    $image = $imageService->storeImage($file);
                    $auction->additionalImages()->attach($image->id, ['order' => $order + 1]);
                }
            }
        });

        return redirect()
            ->route('admin.auctions.index')
            ->with('success', 'Aukcja została zaktualizowana.');
    }

    // Zamknięcie ogłoszenia — operacja nieodwracalna
    public function close(Auction $auction): RedirectResponse
    {
        $this->ensureOwner($auction);

        if ($auction->status !== 'aktywna') {
            return redirect()
                ->route('auctions.mine')
                ->withErrors(['auction' => 'To ogłoszenie jest już zamknięte.']);
        }

        $auction->update(['status' => 'zakończona']);

        return redirect()
            ->route('auctions.mine')
            ->with('success', 'Ogłoszenie zostało zamknięte.');
    }

    // Formularz tworzenia aukcji
    public function create(): View
    {
        return view('create-auction', [
            ...$this->formCategoryPickerData(),
            'pracaCategoryIds' => Category::getPracaCategoryIds(),
        ]);
    }

    // Zapis nowej aukcji
    public function store(StoreAuctionRequest $request, ImageService $imageService): RedirectResponse
    {
        $requiresApproval = Category::requiresApproval($request->validated('categoryId'));

        $auction = DB::transaction(function () use ($request, $imageService, $requiresApproval) {
            $imageId = $request->hasFile('thumbnail')
                ? $imageService->storeImage($request->file('thumbnail'))->id
                : Image::query()->value('id');

            if (! $imageId) {
                throw new \RuntimeException('Brak domyślnego zdjęcia w systemie.');
            }

            $auction = Auction::create([
                'name' => $request->validated('name'),
                'description' => $request->validated('description'),
                'price' => $request->validated('price'),
                'negotiable' => $request->boolean('negotiable'),
                'location' => $request->validated('location'),
                'latitude' => $request->validated('latitude'),
                'longitude' => $request->validated('longitude'),
                ...$this->prepareAuctionPayload($request->validated(), (int) $request->validated('categoryId')),
                'status' => 'aktywna',
                'approved' => ! $requiresApproval,
                'userId' => Auth::id(),
                'imageId' => $imageId,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $order => $file) {
                    $image = $imageService->storeImage($file);
                    $auction->additionalImages()->attach($image->id, ['order' => $order + 1]);
                }
            }

            return $auction;
        });

        if ($requiresApproval) {
            return redirect()
                ->route('auctions.mine')
                ->with('job_pending_approval', true);
        }

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Aukcja została utworzona.');
    }

    private function formCategoryPickerData(?int $selectedCategoryId = null): array
    {
        $categories = Category::all()->keyBy('id');
        $categoryTree = $this->buildFormCategoryTree(
            Category::with('children.children')->whereNull('parentId')->orderBy('name')->get(),
        );

        $selectedCategoryId = old('categoryId', $selectedCategoryId);
        $selectedCategoryLabel = 'Wybierz kategorię';

        if ($selectedCategoryId && $categories->has($selectedCategoryId)) {
            $selectedCategoryLabel = $this->buildCategoryLabel(
                $categories->get($selectedCategoryId),
                $categories,
            );
        }

        return compact('categoryTree', 'selectedCategoryId', 'selectedCategoryLabel');
    }

    private function buildFormCategoryTree($categories): array
    {
        return $categories->map(function (Category $category) {
            $children = $category->children->isNotEmpty()
                ? $this->buildFormCategoryTree($category->children->sortBy('name'))
                : [];

            return [
                'id' => $category->id,
                'name' => $category->name,
                'count' => 0,
                'children' => $children,
            ];
        })->values()->all();
    }

    private function buildCategoryLabel(Category $category, $allCategories): string
    {
        $parts = [];
        $current = $category;

        while ($current) {
            array_unshift($parts, $current->name);
            $current = $current->parentId ? $allCategories->get($current->parentId) : null;
        }

        return implode(' > ', $parts);
    }

    private function prepareAuctionPayload(array $data, int $categoryId): array
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

    // Sprawdza, czy zalogowany użytkownik jest właścicielem ogłoszenia
    private function ensureOwner(Auction $auction): void
    {
        if ((int) $auction->userId !== (int) Auth::id()) {
            abort(403);
        }
    }

    private function viewerIsAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin;
    }

    private function ensureAdmin(): void
    {
        if (! $this->viewerIsAdmin()) {
            abort(403);
        }
    }
}
