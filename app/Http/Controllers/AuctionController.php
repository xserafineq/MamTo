<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuctionController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategoryId = $request->filled('category')
            ? (int) $request->input('category')
            : null;

        $query = Auction::with('image');

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
            ->latest('createdAt')
            ->limit(3)
            ->get();

        return view('auction-page', compact(
            'auction',
            'images',
            'sellerRating',
            'otherAuctions',
        ));
    }

    public function create(): View
    {
        return view('create-auction');
    }
}
