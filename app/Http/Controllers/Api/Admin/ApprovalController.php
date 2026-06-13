<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuctionResource;
use App\Models\Auction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $auctions = Auction::with(['image', 'user', 'category'])
            ->where('approved', false)
            ->latest('createdAt')
            ->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'data' => AuctionResource::collection($auctions),
            'meta' => [
                'current_page' => $auctions->currentPage(),
                'last_page' => $auctions->lastPage(),
                'per_page' => $auctions->perPage(),
                'total' => $auctions->total(),
            ],
        ]);
    }

    public function approve(Auction $auction): JsonResponse
    {
        if ($auction->approved) {
            return response()->json([
                'message' => 'Ta aukcja jest już zaakceptowana.',
                'errors' => [
                    'auction' => ['Ta aukcja jest już zaakceptowana.'],
                ],
            ], 422);
        }

        $auction->update(['approved' => true]);

        return response()->json([
            'message' => 'Aukcja została zaakceptowana i jest widoczna publicznie.',
            'data' => new AuctionResource($auction->fresh(['image', 'user', 'category'])),
        ]);
    }
}
