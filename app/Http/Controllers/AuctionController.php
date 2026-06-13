<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Auction;
use App\Models\Category;
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

        $query = Auction::with('image')->where('status', 'aktywna');

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

        match ($request->input('sort', 'newest')) {
            'oldest' => $query->orderBy('createdAt', 'asc'),
            default => $query->latest('createdAt'),
        };

        $auctions = $query->paginate(10)->withQueryString();

        $directAuctionCounts = Auction::query()
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
        if ($auction->status !== 'aktywna' && ! $this->viewerIsAdmin()) {
            abort(404);
        }

        $auction->load(['user.ratingsReceived', 'image', 'additionalImages']);

        $images = collect([$auction->image])
            ->merge($auction->additionalImages)
            ->filter()
            ->unique('id')
            ->values();

        $sellerRating = $auction->user->ratingsReceived->avg('rating');

        $otherAuctions = Auction::with('image')
            ->where('userId', $auction->userId)
            ->where('id', '!=', $auction->id)
            ->where('status', 'aktywna')
            ->latest('createdAt')
            ->limit(3)
            ->get();

        $isOwner = auth()->check() && (int) auth()->id() === (int) $auction->userId;
        $phoneDigits = preg_replace('/\D/', '', $auction->user->phoneNumber);
        $displayPhone = $this->formatDisplayPhone($phoneDigits, $isOwner);

        return view('auction-page', compact(
            'auction',
            'images',
            'sellerRating',
            'otherAuctions',
            'displayPhone',
            'phoneDigits',
            'isOwner',
        ));
    }

    // Formatuje numer telefonu
    private function formatDisplayPhone(string $digits, bool $showFull): string
    {
        if (strlen($digits) !== 9) {
            return $digits;
        }

        if ($showFull) {
            return substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6, 3);
        }

        return substr($digits, 0, 3) . ' *** ' . substr($digits, 6, 3);
    }

    // Ogłoszenia zalogowanego użytkownika
    public function mine(): View
    {
        $auctions = Auction::with('image')
            ->where('userId', Auth::id())
            ->latest('createdAt')
            ->get();

        $activeCount = $auctions->where('status', 'aktywna')->count();
        $closedCount = $auctions->where('status', 'zakończona')->count();

        return view('my-auctions', compact('auctions', 'activeCount', 'closedCount'));
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
            'categories' => $this->getSelectableCategories(),
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
            $data = $request->safe()->except(['thumbnail', 'images']);
            $data['negotiable'] = $request->boolean('negotiable');

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
            ->with('success', 'Ogłoszenie zostało zaktualizowane.');
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
        $categories = $this->getSelectableCategories();

        return view('create-auction', compact('categories'));
    }

    // Zapis nowej aukcji
    public function store(StoreAuctionRequest $request, ImageService $imageService): RedirectResponse
    {
        $auction = DB::transaction(function () use ($request, $imageService) {
            $thumbnail = $imageService->storeImage($request->file('thumbnail'));

            $auction = Auction::create([
                'name' => $request->validated('name'),
                'description' => $request->validated('description'),
                'price' => $request->validated('price'),
                'negotiable' => $request->boolean('negotiable'),
                'location' => $request->validated('location'),
                'status' => 'aktywna',
                'userId' => Auth::id(),
                'categoryId' => $request->validated('categoryId'),
                'imageId' => $thumbnail->id,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $order => $file) {
                    $image = $imageService->storeImage($file);
                    $auction->additionalImages()->attach($image->id, ['order' => $order + 1]);
                }
            }

            return $auction;
        });

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Aukcja została utworzona.');
    }

    private function getSelectableCategories()
    {
        $categories = Category::orderBy('name')->get()->keyBy('id');

        return $categories
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'label' => $this->buildCategoryLabel($category, $categories),
            ])
            ->values();
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
}
