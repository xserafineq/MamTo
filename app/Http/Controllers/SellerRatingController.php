<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerRatingController extends Controller
{
    public function store(Request $request, User $user): JsonResponse
    {
        if ((int) $user->id === (int) auth()->id()) {
            abort(403);
        }

        if (! auth()->user()->hasAccountOlderThanMonths(3)) {
            return response()->json([
                'message' => 'Aby ocenić wystawiającego, konto musi mieć co najmniej 3 miesiące.',
            ], 422);
        }

        $validated = $request->validate([
            'rating' => 'required|in:0,1',
        ]);

        $existingRating = Rating::query()
            ->where('sellerId', $user->id)
            ->where('userId', auth()->id())
            ->exists();

        if ($existingRating) {
            return response()->json([
                'message' => 'Już oceniłeś tego wystawiającego.',
            ], 422);
        }

        Rating::create([
            'sellerId' => $user->id,
            'userId' => auth()->id(),
            'rating' => (int) $validated['rating'],
        ]);

        $ratings = Rating::query()->where('sellerId', $user->id)->get();

        return response()->json([
            'rating' => (int) $validated['rating'],
            'recommendationPercent' => Rating::recommendationPercent($ratings),
        ]);
    }
}
