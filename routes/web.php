<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Auction;

Route::get('/', function () {
    $categories = Category::with('image')
        ->whereNull('parentId')
        ->get();

    return view('home', compact('categories'));
});

Route::get('/auctions', function () {
    $auctions = Auction::with('image')
        ->latest('createdAt')
        ->paginate(10);

    return view('auctions', compact('auctions'));
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
