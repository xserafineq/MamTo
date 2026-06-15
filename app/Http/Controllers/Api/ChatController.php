<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Auction;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = request()->user()->id;

        $chats = Chat::with(['auction.image', 'seller', 'buyer', 'messages'])
            ->where(function ($query) use ($userId) {
                $query->where('buyerId', $userId)
                    ->orWhere('sellerId', $userId);
            })
            ->get()
            ->map(function (Chat $chat) use ($userId) {
                $chat->lastMessage = $chat->messages->sortByDesc('sentAt')->first();
                $chat->otherParticipant = $this->getOtherParticipant($chat, $userId);
                $chat->unreadCount = $chat->unreadMessagesCountFor($userId);
                $chat->isUnread = $chat->unreadCount > 0;

                return $chat;
            })
            ->sortByDesc(fn (Chat $chat) => $chat->lastMessage?->sentAt)
            ->values();

        return response()->json([
            'data' => ChatResource::collection($chats),
            'unreadCount' => $chats->sum('unreadCount'),
        ]);
    }

    public function show(Chat $chat): JsonResponse
    {
        $this->authorizeChatAccess($chat);

        $chat->markAsReadBy(request()->user()->id);

        $chat->load(['auction.image', 'seller', 'buyer', 'messages.sender']);
        $chat->setRelation('messages', $chat->messages->sortBy('sentAt')->values());
        $chat->otherParticipant = $this->getOtherParticipant($chat, request()->user()->id);

        return response()->json([
            'data' => new ChatResource($chat),
        ]);
    }

    public function start(Auction $auction): JsonResponse
    {
        if ($auction->status !== 'aktywna') {
            abort(404);
        }

        if (! $auction->approved) {
            abort(404);
        }

        if ((int) $auction->userId === (int) request()->user()->id) {
            abort(403, 'Nie możesz napisać wiadomości do własnej aukcji.');
        }

        $chat = Chat::firstOrCreate(
            [
                'auctionId' => $auction->id,
                'buyerId' => request()->user()->id,
            ],
            [
                'sellerId' => $auction->userId,
            ],
        );

        $chat->load(['auction.image', 'seller', 'buyer']);

        return response()->json([
            'message' => 'Czat został otwarty.',
            'data' => new ChatResource($chat),
        ], 201);
    }

    public function storeMessage(StoreMessageRequest $request, Chat $chat): JsonResponse
    {
        $this->authorizeChatAccess($chat);

        $message = Message::create([
            'chatId' => $chat->id,
            'text' => trim($request->validated('text')),
            'sentAt' => now(),
            'senderId' => request()->user()->id,
        ]);

        $chat->markAsReadBy(request()->user()->id);

        $message->load('sender');

        return response()->json([
            'message' => 'Wiadomość została wysłana.',
            'data' => new MessageResource($message),
        ], 201);
    }

    private function authorizeChatAccess(Chat $chat): void
    {
        $userId = request()->user()->id;

        abort_unless(
            (int) $chat->buyerId === (int) $userId || (int) $chat->sellerId === (int) $userId,
            403,
        );
    }

    private function getOtherParticipant(Chat $chat, int $userId): User
    {
        return (int) $chat->buyerId === $userId
            ? $chat->seller
            : $chat->buyer;
    }
}
