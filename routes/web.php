<?php

use App\Http\Controllers\FollowedAuctionController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Auction;
use App\Models\Category;



Route::get('/', function () {
    $categories = Category::with('image')
        ->whereNull('parentId')
        ->get();

    return view('home', compact('categories'));
});


Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

Route::get('/users/{user}', [UserController::class, 'show'])
    ->whereNumber('user')
    ->name('users.show');

Route::middleware('auth')->group(function () {
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions/create', [AuctionController::class, 'store'])->name('auctions.store');
    Route::get('/my-auctions', [AuctionController::class, 'mine'])->name('auctions.mine');
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit')->whereNumber('auction');
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update')->whereNumber('auction');
    Route::post('/auctions/{auction}/close', [AuctionController::class, 'close'])->name('auctions.close')->whereNumber('auction');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/followed', [UserController::class, 'followed'])->name('followed.index');
    Route::post('/auctions/{auction}/follow', [FollowedAuctionController::class, 'store'])->name('auctions.follow')->whereNumber('auction');
    Route::delete('/auctions/{auction}/follow', [FollowedAuctionController::class, 'destroy'])->name('auctions.unfollow')->whereNumber('auction');

    Route::get('/messages', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/messages/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/messages/{chat}', [ChatController::class, 'storeMessage'])->name('chats.messages.store');
    Route::get('/auctions/{auction}/chat', [ChatController::class, 'start'])->name('chats.start');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('panel');
        Route::get('/auctions', [AdminController::class, 'auctions'])->name('auctions.index');
        Route::get('/approvals', [AdminController::class, 'approvals'])->name('approvals.index');
        Route::post('/approvals/{auction}/approve', [AdminController::class, 'approve'])->name('approvals.approve')->whereNumber('auction');
        Route::get('/administrators', [AdminController::class, 'administrators'])->name('administrators.index');
        Route::put('/administrators/{user}/permissions', [AdminController::class, 'updatePermissions'])->name('administrators.permissions.update')->whereNumber('user');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit')->whereNumber('user');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update')->whereNumber('user');
        Route::put('/users/{user}/permissions', [AdminUserController::class, 'updatePermissions'])->name('users.permissions.update')->whereNumber('user');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy')->whereNumber('user');
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update')->whereNumber('category');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy')->whereNumber('category');
        Route::get('/auctions/{auction}/edit', [AuctionController::class, 'adminEdit'])->name('auctions.edit')->whereNumber('auction');
        Route::put('/auctions/{auction}', [AuctionController::class, 'adminUpdate'])->name('auctions.update')->whereNumber('auction');
        Route::delete('/auctions/{auction}', [AdminController::class, 'destroy'])->name('auctions.destroy')->whereNumber('auction');
    });
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
