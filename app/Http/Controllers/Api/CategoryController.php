<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\AuctionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(AuctionService $auctionService): JsonResponse
    {
        $categories = Category::with('image')
            ->whereNull('parentId')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
            'tree' => $auctionService->buildCategoryTree(),
            'selectable' => $auctionService->getSelectableCategories(),
            'pracaCategoryIds' => Category::getPracaCategoryIds(),
        ]);
    }
}
