<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdatePermissionsRequest;
use App\Models\Auction;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Panel administratora — przekierowanie na listę aukcji
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.auctions.index');
    }

    // Wszystkie aukcje w systemie
    public function auctions(Request $request): View
    {
        $activeCount = Auction::where('status', 'aktywna')->count();
        $closedCount = Auction::where('status', 'zakończona')->count();

        $query = Auction::with(['image', 'user'])->latest('createdAt');

        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'ilike', '%'.$search.'%')
                    ->orWhere('location', 'ilike', '%'.$search.'%')
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->matchingSearch($search));
            });
        }

        $auctions = $query->paginate(15)->withQueryString();

        return view('admin.auctions', compact('auctions', 'activeCount', 'closedCount'));
    }

    // Aukcje z kategorii Praca oczekujące na akceptację
    public function approvals(): View
    {
        $auctions = Auction::with(['image', 'user', 'category'])
            ->where('approved', false)
            ->latest('createdAt')
            ->paginate(15);

        return view('admin.approvals', compact('auctions'));
    }

    // Akceptacja aukcji z kategorii Praca
    public function approve(Auction $auction): RedirectResponse
    {
        if ($auction->approved) {
            return redirect()
                ->route('admin.approvals.index')
                ->withErrors(['auction' => 'Ta aukcja jest już zaakceptowana.']);
        }

        $auction->update(['approved' => true]);

        return redirect()
            ->route('admin.approvals.index')
            ->with('success', 'Aukcja została zaakceptowana i jest widoczna publicznie.');
    }

    public function destroy(Auction $auction): RedirectResponse
    {
        DB::transaction(function () use ($auction) {
            $chatIds = Chat::where('auctionId', $auction->id)->pluck('id');

            if ($chatIds->isNotEmpty()) {
                Message::whereIn('chatId', $chatIds)->delete();
                Chat::whereIn('id', $chatIds)->delete();
            }

            $auction->followers()->detach();
            $auction->additionalImages()->detach();
            $auction->delete();
        });

        return redirect()
            ->route('admin.auctions.index')
            ->with('success', 'Aukcja została usunięta.');
    }

    // Pozostali administratorzy systemu
    public function administrators(): View
    {
        $administrators = User::query()
            ->where('isAdmin', true)
            ->where('id', '!=', Auth::id())
            ->orderBy('lastName')
            ->orderBy('firstName')
            ->get();

        return view('admin.administrators', [
            'administrators' => $administrators,
            'isMainAdmin' => (bool) Auth::user()->isMainAdmin,
        ]);
    }

    // Zmiana uprawnień administratora — tylko główny administrator
    public function updatePermissions(AdminUpdatePermissionsRequest $request, User $user): RedirectResponse
    {
        if ($user->isMainAdmin) {
            return redirect()
                ->route('admin.administrators.index')
                ->withErrors(['permissions' => 'Nie można zmienić uprawnień głównego administratora.']);
        }

        if ((int) $user->id === (int) Auth::id()) {
            abort(403);
        }

        $user->update([
            'isAdmin' => $request->boolean('isAdmin'),
        ]);

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', 'Uprawnienia użytkownika zostały zaktualizowane.');
    }
}
