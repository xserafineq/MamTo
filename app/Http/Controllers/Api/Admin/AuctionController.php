<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\UserResource;
use App\Models\Auction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $activeCount = Auction::where('status', 'aktywna')->count();
        $closedCount = Auction::where('status', 'zakończona')->count();

        $auctions = Auction::with(['image', 'user', 'category'])
            ->latest('createdAt')
            ->paginate((int) $request->input('per_page', 10));

        return response()->json([
            'data' => AuctionResource::collection($auctions),
            'counts' => [
                'active' => $activeCount,
                'closed' => $closedCount,
            ],
            'meta' => [
                'current_page' => $auctions->currentPage(),
                'last_page' => $auctions->lastPage(),
                'per_page' => $auctions->perPage(),
                'total' => $auctions->total(),
            ],
        ]);
    }
}
