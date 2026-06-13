<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateAuctionRequest;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Resources\AuctionResource;
use App\Models\Auction;
use App\Models\Category;
use App\Services\AuctionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request, AuctionService $auctionService): JsonResponse
    {
        $auctions = $auctionService->paginatePublic($request);

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

    public function latest(AuctionService $auctionService): JsonResponse
    {
        $limit = (int) request()->input('limit', 6);

        return response()->json([
            'data' => AuctionResource::collection($auctionService->getLatest($limit)),
        ]);
    }

    public function show(Auction $auction, AuctionService $auctionService): JsonResponse
    {
        $auction = $auctionService->findVisible($auction, request()->user());
        $otherAuctions = $auctionService->getSellerOtherAuctions($auction);

        return response()->json([
            'data' => new AuctionResource($auction),
            'otherAuctions' => AuctionResource::collection($otherAuctions),
        ]);
    }

    public function mine(AuctionService $auctionService): JsonResponse
    {
        $auctions = $auctionService->getUserAuctions(request()->user());

        return response()->json([
            'data' => AuctionResource::collection($auctions),
            'counts' => $auctionService->getAuctionCounts($auctions),
        ]);
    }

    public function store(StoreAuctionRequest $request, AuctionService $auctionService): JsonResponse
    {
        $auction = $auctionService->create(
            $request->validated(),
            $request->user(),
            $request->file('thumbnail'),
            $request->file('images', []),
        );

        $requiresApproval = Category::requiresApproval((int) $request->validated('categoryId'));

        return response()->json([
            'message' => $requiresApproval
                ? 'Ogłoszenie zostało utworzone i przesłane do akceptacji.'
                : 'Aukcja została utworzona.',
            'data' => new AuctionResource($auction),
        ], 201);
    }

    public function update(UpdateAuctionRequest $request, Auction $auction, AuctionService $auctionService): JsonResponse
    {
        $this->ensureOwner($auction);

        if ($auction->status !== 'aktywna') {
            return response()->json([
                'message' => 'Zamkniętego ogłoszenia nie można edytować.',
                'errors' => [
                    'auction' => ['Zamkniętego ogłoszenia nie można edytować.'],
                ],
            ], 422);
        }

        $auction = $auctionService->update(
            $auction,
            $request->validated(),
            $request->file('thumbnail'),
            $request->hasFile('images') ? $request->file('images', []) : null,
        );

        $requiresApproval = Category::requiresApproval((int) $request->validated('categoryId'));

        return response()->json([
            'message' => $requiresApproval
                ? 'Ogłoszenie zostało zaktualizowane i ponownie przesłane do akceptacji.'
                : 'Ogłoszenie zostało zaktualizowane.',
            'data' => new AuctionResource($auction),
        ]);
    }

    public function close(Auction $auction, AuctionService $auctionService): JsonResponse
    {
        $this->ensureOwner($auction);

        if ($auction->status !== 'aktywna') {
            return response()->json([
                'message' => 'To ogłoszenie jest już zamknięte.',
                'errors' => [
                    'auction' => ['To ogłoszenie jest już zamknięte.'],
                ],
            ], 422);
        }

        $auction = $auctionService->close($auction);

        return response()->json([
            'message' => 'Ogłoszenie zostało zamknięte.',
            'data' => new AuctionResource($auction),
        ]);
    }

    public function adminUpdate(
        AdminUpdateAuctionRequest $request,
        Auction $auction,
        AuctionService $auctionService,
    ): JsonResponse {
        $auction = $auctionService->update(
            $auction,
            $request->validated(),
            $request->file('thumbnail'),
            $request->hasFile('images') ? $request->file('images', []) : null,
            resetApprovalForJob: false,
        );

        return response()->json([
            'message' => 'Aukcja została zaktualizowana.',
            'data' => new AuctionResource($auction),
        ]);
    }

    private function ensureOwner(Auction $auction): void
    {
        if ((int) $auction->userId !== (int) request()->user()->id) {
            abort(403, 'Brak uprawnień do tego ogłoszenia.');
        }
    }
}
