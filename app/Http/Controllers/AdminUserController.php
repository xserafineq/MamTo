<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdatePermissionsRequest;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Models\Auction;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->withCount('auctions');

        if ($request->filled('q')) {
            $query->matchingSearch($request->input('q'));
        }

        $users = $query
            ->orderBy('lastName')
            ->orderBy('firstName')
            ->paginate(12)
            ->withQueryString();

        $totalUsers = User::count();
        $adminCount = User::where('isAdmin', true)->count();

        return view('admin.users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'regularCount' => $totalUsers - $adminCount,
            'isMainAdmin' => (bool) Auth::user()->isMainAdmin,
        ]);
    }

    public function edit(User $user): View
    {
        $this->ensureCanEditUser($user);

        return view('admin.users-edit', [
            'user' => $user,
            'returnQuery' => request()->only('q', 'page'),
        ]);
    }

    public function update(AdminUpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureCanEditUser($user);

        $user->update($request->validated());

        return redirect()
            ->route('admin.users.index', $request->only('q', 'page'))
            ->with('success', 'Dane użytkownika zostały zaktualizowane.');
    }

    public function updatePermissions(AdminUpdatePermissionsRequest $request, User $user): RedirectResponse
    {
        if ($user->isMainAdmin) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['permissions' => 'Nie można zmienić uprawnień głównego administratora.']);
        }

        if ((int) $user->id === (int) Auth::id()) {
            abort(403);
        }

        $user->update([
            'isAdmin' => $request->boolean('isAdmin'),
        ]);

        return redirect()
            ->route('admin.users.index', $request->only('q', 'page'))
            ->with('success', 'Uprawnienia użytkownika zostały zaktualizowane.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->isMainAdmin) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['user' => 'Nie można usunąć głównego administratora.']);
        }

        if ((int) $user->id === (int) Auth::id()) {
            abort(403);
        }

        if ($user->isAdmin && ! Auth::user()->isMainAdmin) {
            abort(403);
        }

        DB::transaction(function () use ($user) {
            foreach ($user->auctions as $auction) {
                $this->deleteAuction($auction);
            }

            $chatIds = Chat::query()
                ->where('buyerId', $user->id)
                ->orWhere('sellerId', $user->id)
                ->pluck('id');

            if ($chatIds->isNotEmpty()) {
                Message::whereIn('chatId', $chatIds)->delete();
                Chat::whereIn('id', $chatIds)->delete();
            }

            $user->followedAuctions()->detach();
            Rating::where('userId', $user->id)->orWhere('sellerId', $user->id)->delete();
            $user->tokens()->delete();
            $user->delete();
        });

        return redirect()
            ->route('admin.users.index', $request->only('q', 'page'))
            ->with('success', 'Użytkownik został usunięty.');
    }

    private function deleteAuction(Auction $auction): void
    {
        $chatIds = Chat::where('auctionId', $auction->id)->pluck('id');

        if ($chatIds->isNotEmpty()) {
            Message::whereIn('chatId', $chatIds)->delete();
            Chat::whereIn('id', $chatIds)->delete();
        }

        $auction->followers()->detach();
        $auction->additionalImages()->detach();
        $auction->delete();
    }

    private function ensureCanEditUser(User $user): void
    {
        if ($user->isMainAdmin && (int) $user->id !== (int) Auth::id()) {
            abort(403, 'Nie można edytować głównego administratora.');
        }

        if ($user->isAdmin && ! Auth::user()->isMainAdmin && (int) $user->id !== (int) Auth::id()) {
            abort(403, 'Brak uprawnień do edycji tego użytkownika.');
        }
    }
}
