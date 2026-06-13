<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuctionResource;
use App\Models\Auction;
use Illuminate\Http\JsonResponse;

class FollowedAuctionController extends Controller
{
    public function index(): JsonResponse
    {
        $auctions = request()->user()
            ->followedAuctions()
            ->where('approved', true)
            ->with(['image', 'category', 'user'])
            ->orderByDesc('Auctions.createdAt')
            ->get();

        return response()->json([
            'data' => AuctionResource::collection($auctions),
        ]);
    }

    public function store(Auction $auction): JsonResponse
    {
        if ($auction->status !== 'aktywna' || ! $auction->approved) {
            abort(404);
        }

        request()->user()->followedAuctions()->syncWithoutDetaching([$auction->id]);

        return response()->json([
            'message' => 'Ogłoszenie zostało dodane do obserwowanych.',
        ], 201);
    }

    public function destroy(Auction $auction): JsonResponse
    {
        request()->user()->followedAuctions()->detach($auction->id);

        return response()->json([
            'message' => 'Ogłoszenie zostało usunięte z obserwowanych.',
        ]);
    }
}
