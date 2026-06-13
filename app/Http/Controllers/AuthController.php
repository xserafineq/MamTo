<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // Formularz logowania
    public function showLoginForm(): View
    {
        return view('login');
    }

    // Logowanie użytkownika
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Podane dane logowania są nieprawidłowe.']);
        }

        $request->session()->regenerate();

        User::whereKey(Auth::id())->update(['lastOnline' => now()]);

        return redirect()->intended('/');
    }

    // Formularz rejestracji
    public function showRegisterForm(): View
    {
        return view('register');
    }

    // Rejestracja nowego użytkownika
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'firstName' => $request->validated('firstName'),
            'lastName' => $request->validated('lastName'),
            'email' => $request->validated('email'),
            'phoneNumber' => $request->validated('phoneNumber'),
            'password' => $request->validated('password'),
            'joinedAt' => now(),
            'lastOnline' => now(),
            'isAdmin' => false,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect('/')->with('success', 'Konto zostało utworzone. Witaj!');
    }

    // Wylogowanie i unieważnienie sesji
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
