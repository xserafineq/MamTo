<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    // Profil zalogowanego użytkownika
    public function profile(): View
    {
        return view('profile', [
            'user' => Auth::user(),
        ]);
    }

    // Aktualizacja danych profilu
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        Auth::user()->update($request->validated());

        return redirect()
            ->route('profile')
            ->with('success', 'Dane profilu zostały zaktualizowane.');
    }

    // Zmiana hasła użytkownika
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        Auth::user()->update([
            'password' => $request->validated('password'),
        ]);

        return redirect()
            ->route('profile')
            ->with('success', 'Hasło zostało zmienione.');
    }

    // Obserwowane aukcje użytkownika
    public function followed(): View
    {
        $auctions = Auth::user()
            ->followedAuctions()
            ->publiclyVisible()
            ->with('image')
            ->orderByDesc('Auctions.createdAt')
            ->paginate(10);

        return view('followed', compact('auctions'));
    }
}
