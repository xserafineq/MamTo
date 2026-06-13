<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/auctions', function () {
    return view('auctions');
});

Route::get('/auction-page', function () {
    return view('auction-page');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
});
