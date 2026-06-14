<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminCategoryRequest;
use App\Http\Requests\UpdateAdminCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('image')->orderBy('name')->get()->keyBy('id');
        $categoryTree = $this->buildAdminCategoryTree(
            $categories->whereNull('parentId')->sortBy('name'),
            $categories,
        );

        return response()->json([
            'tree' => $categoryTree,
            'data' => CategoryResource::collection($categories->values()),
        ]);
    }

    public function store(StoreAdminCategoryRequest $request, ImageService $imageService): JsonResponse
    {
        $data = [
            'name' => $request->validated('name'),
            'parentId' => $request->validated('parentId'),
        ];

        if ($request->hasFile('image')) {
            $data['imageId'] = $imageService->storeImage($request->file('image'))->id;
        }

        $category = Category::create($data);

        return response()->json([
            'message' => 'Kategoria została dodana.',
            'data' => new CategoryResource($category->load('image')),
        ], 201);
    }

    public function update(
        UpdateAdminCategoryRequest $request,
        Category $category,
        ImageService $imageService,
    ): JsonResponse {
        $data = [
            'name' => $request->validated('name'),
        ];

        if ($request->hasFile('image')) {
            $data['imageId'] = $imageService->storeImage($request->file('image'))->id;
        }

        $category->update($data);

        return response()->json([
            'message' => 'Kategoria została zaktualizowana.',
            'data' => new CategoryResource($category->fresh('image')),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'Nie można usunąć kategorii, która ma podkategorie.',
                'errors' => [
                    'category' => ['Nie można usunąć kategorii, która ma podkategorie.'],
                ],
            ], 422);
        }

        if ($category->auctions()->exists()) {
            return response()->json([
                'message' => 'Nie można usunąć kategorii przypisanej do ogłoszeń.',
                'errors' => [
                    'category' => ['Nie można usunąć kategorii przypisanej do ogłoszeń.'],
                ],
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategoria została usunięta.',
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
}
