<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $chats = Chat::with(['auction.image', 'seller', 'buyer', 'messages' => fn ($query) => $query->visible()])
            ->visible()
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

        $newMessagesCount = $chats->sum('unreadCount');

        return view('messages.index', compact('chats', 'newMessagesCount'));
    }

    To ten sam konflikt co w API — web ChatController::show(). Połącz obie strony tak:


    public function show(Chat $chat): View
    {
        $this->authorizeChatAccess($chat);
        abort_if($chat->archived, 404);
        $chat->markAsReadBy(auth()->id());
        $chat->load([
            'auction.image',
            'seller',
            'buyer',
            'messages' => fn ($query) => $query->visible()->with('sender'),
        ]);
        $messages = $chat->messages->sortBy('sentAt')->values();
        $otherParticipant = $this->getOtherParticipant($chat, auth()->id());
        return view('messages.show', compact('chat', 'messages', 'otherParticipant'));
    }

    public function start(Auction $auction): RedirectResponse
    {
        if ($auction->status !== 'aktywna') {
            abort(404);
        }

        if (! $auction->approved) {
            abort(404);
        }

        if ((int) $auction->userId === (int) auth()->id()) {
            abort(403, 'Nie możesz napisać wiadomości do własnej aukcji.');
        }

        $chat = Chat::query()
            ->where('auctionId', $auction->id)
            ->where('buyerId', auth()->id())
            ->first();

        if ($chat?->archived) {
            abort(403, 'Ta rozmowa została zarchiwizowana przez administratora.');
        }

        $chat = Chat::firstOrCreate(
            [
                'auctionId' => $auction->id,
                'buyerId' => auth()->id(),
            ],
            [
                'sellerId' => $auction->userId,
            ],
        );

        return redirect()->route('chats.show', $chat);
    }

    public function storeMessage(Request $request, Chat $chat): RedirectResponse
    {
        $this->authorizeChatAccess($chat);

        $validated = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'chatId' => $chat->id,
            'text' => trim($validated['text']),
            'sentAt' => now(),
            'senderId' => auth()->id(),
        ]);

        $chat->markAsReadBy(auth()->id());

        return redirect()->route('chats.show', $chat);
    }

    private function authorizeChatAccess(Chat $chat): void
    {
        $userId = auth()->id();

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
