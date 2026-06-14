<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminCategoryRequest;
use App\Http\Requests\UpdateAdminCategoryRequest;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('image')->get()->keyBy('id');
        $categoryTree = $this->buildAdminCategoryTree(
            $categories->whereNull('parentId')->sortBy('name'),
            $categories,
        );

        $selectedCategoryId = old('selectedCategoryId');
        $selectedCategoryLabel = 'Brak zaznaczenia';
        $selectedCategoryImageUrl = asset('assets/default-category.png');

        if ($selectedCategoryId && $categories->has($selectedCategoryId)) {
            $selectedCategory = $categories->get($selectedCategoryId);
            $selectedCategoryLabel = $this->buildCategoryLabel($selectedCategory, $categories);
            $selectedCategoryImageUrl = $selectedCategory->image?->file_url
                ?? asset('assets/default-category.png');
        }

        return view('admin.categories', compact(
            'categoryTree',
            'selectedCategoryLabel',
            'selectedCategoryImageUrl',
        ));
    }

    public function store(StoreAdminCategoryRequest $request, ImageService $imageService): RedirectResponse
    {
        $parentId = $request->validated('parentId');
        $data = [
            'name' => $request->validated('name'),
            'parentId' => $parentId,
        ];

        if ($request->hasFile('image')) {
            $data['imageId'] = $imageService->storeImage($request->file('image'))->id;
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategoria została dodana.')
            ->withInput([
                'selectedCategoryId' => $parentId ? (string) $parentId : '',
                'name' => '',
            ]);
    }

    public function update(
        UpdateAdminCategoryRequest $request,
        Category $category,
        ImageService $imageService,
    ): RedirectResponse {
        $data = [
            'name' => $request->validated('name'),
        ];

        if ($request->hasFile('image')) {
            $data['imageId'] = $imageService->storeImage($request->file('image'))->id;
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategoria została zaktualizowana.')
            ->withInput([
                'selectedCategoryId' => (string) $category->id,
                'name' => $category->name,
            ]);
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->withErrors(['category' => 'Nie można usunąć kategorii, która ma podkategorie.'])
                ->withInput([
                    'selectedCategoryId' => (string) $category->id,
                    'name' => $category->name,
                ]);
        }

        if ($category->auctions()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->withErrors(['category' => 'Nie można usunąć kategorii przypisanej do ogłoszeń.'])
                ->withInput([
                    'selectedCategoryId' => (string) $category->id,
                    'name' => $category->name,
                ]);
        }

        $parentId = $category->parentId;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategoria została usunięta.')
            ->withInput([
                'selectedCategoryId' => $parentId ? (string) $parentId : '',
                'name' => '',
            ]);
    }

    private function buildAdminCategoryTree(Collection $nodes, Collection $allCategories): array
    {
        return $nodes->map(function (Category $category) use ($allCategories) {
            $children = $allCategories
                ->where('parentId', $category->id)
                ->sortBy('name');

            return [
                'id' => $category->id,
                'name' => $category->name,
                'count' => 0,
                'imageUrl' => $category->image?->file_url ?? asset('assets/default-category.png'),
                'children' => $this->buildAdminCategoryTree($children, $allCategories),
            ];
        })->values()->all();
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
}
