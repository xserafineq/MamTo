<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\JsonResponse;

class FollowedAuctionController extends Controller
{
    public function store(Auction $auction): JsonResponse
    {
        if ($auction->status !== 'aktywna' || ! $auction->approved) {
            abort(404);
        }

        if ((int) $auction->userId === (int) auth()->id()) {
            abort(403);
        }

        auth()->user()->followedAuctions()->syncWithoutDetaching([$auction->id]);

        return response()->json(['followed' => true]);
    }

    public function destroy(Auction $auction): JsonResponse
    {
        auth()->user()->followedAuctions()->detach($auction->id);

        return response()->json(['followed' => false]);
    }
}
