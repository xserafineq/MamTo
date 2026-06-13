<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Models\Auction;
use App\Models\Category;

Route::get('/', function () {
    $categories = Category::with('image')
        ->whereNull('parentId')
        ->get();

    $newestAuctions = Auction::with('image')
        ->latest('createdAt')
        ->limit(6)
        ->get();

    return view('home', compact('categories', 'newestAuctions'));
});

Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

Route::middleware('auth')->group(function () {
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');

    Route::get('/messages', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/messages/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/messages/{chat}', [ChatController::class, 'storeMessage'])->name('chats.messages.store');
    Route::get('/auctions/{auction}/chat', [ChatController::class, 'start'])->name('chats.start');
});

Route::get('/auctions/{auction}', [AuctionController::class, 'show'])
    ->whereNumber('auction')
    ->name('auctions.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
