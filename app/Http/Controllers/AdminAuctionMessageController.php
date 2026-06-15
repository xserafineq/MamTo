<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuctionMessageController extends Controller
{
    public function index(Request $request, Auction $auction): View
    {
        $chats = Chat::query()
            ->where('auctionId', $auction->id)
            ->with([
                'buyer',
                'seller',
                'messages' => fn ($query) => $query->with('sender')->orderBy('sentAt'),
            ])
            ->orderBy('id')
            ->get();

        $messagesCount = $chats->sum(fn (Chat $chat) => $chat->messages->count());

        return view('admin.auction-messages', [
            'auction' => $auction,
            'chats' => $chats,
            'messagesCount' => $messagesCount,
            'returnQuery' => $request->only('q', 'page'),
        ]);
    }

    public function destroyChat(Request $request, Auction $auction, Chat $chat): RedirectResponse
    {
        $this->ensureChatBelongsToAuction($auction, $chat);

        $chat->messages()->delete();
        $chat->delete();

        return redirect()
            ->route('admin.auctions.messages.index', array_merge(['auction' => $auction], $request->only('q', 'page')))
            ->with('success', 'Rozmowa została usunięta.');
    }

    public function archiveChat(Request $request, Auction $auction, Chat $chat): RedirectResponse
    {
        $this->ensureChatBelongsToAuction($auction, $chat);

        $chat->update(['archived' => true]);

        return redirect()
            ->route('admin.auctions.messages.index', array_merge(['auction' => $auction], $request->only('q', 'page')))
            ->with('success', 'Rozmowa została zarchiwizowana.');
    }

    public function unarchiveChat(Request $request, Auction $auction, Chat $chat): RedirectResponse
    {
        $this->ensureChatBelongsToAuction($auction, $chat);

        $chat->update(['archived' => false]);

        return redirect()
            ->route('admin.auctions.messages.index', array_merge(['auction' => $auction], $request->only('q', 'page')))
            ->with('success', 'Rozmowa została przywrócona z archiwum.');
    }

    private function ensureChatBelongsToAuction(Auction $auction, Chat $chat): void
    {
        if ((int) $chat->auctionId !== (int) $auction->id) {
            abort(404);
        }
    }
}
