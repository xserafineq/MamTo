<?php

use App\Http\Controllers\Api\Admin\AdministratorController;
use App\Http\Controllers\Api\Admin\ApprovalController;
use App\Http\Controllers\Api\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FollowedAuctionController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('optional.sanctum')->group(function () {
    Route::get('/auctions', [AuctionController::class, 'index']);
    Route::get('/auctions/latest', [AuctionController::class, 'latest']);
    Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->whereNumber('auction');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    Route::get('/my-auctions', [AuctionController::class, 'mine']);
    Route::post('/auctions', [AuctionController::class, 'store']);
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->whereNumber('auction');
    Route::post('/auctions/{auction}/close', [AuctionController::class, 'close'])->whereNumber('auction');

    Route::get('/followed-auctions', [FollowedAuctionController::class, 'index']);
    Route::post('/auctions/{auction}/follow', [FollowedAuctionController::class, 'store'])->whereNumber('auction');
    Route::delete('/auctions/{auction}/follow', [FollowedAuctionController::class, 'destroy'])->whereNumber('auction');

    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->whereNumber('chat');
    Route::post('/auctions/{auction}/chat', [ChatController::class, 'start'])->whereNumber('auction');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'storeMessage'])->whereNumber('chat');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/auctions', [AdminAuctionController::class, 'index']);
        Route::put('/auctions/{auction}', [AuctionController::class, 'adminUpdate'])->whereNumber('auction');

        Route::get('/approvals', [ApprovalController::class, 'index']);
        Route::post('/approvals/{auction}/approve', [ApprovalController::class, 'approve'])->whereNumber('auction');

        Route::get('/administrators', [AdministratorController::class, 'index']);
        Route::put('/administrators/{user}/permissions', [AdministratorController::class, 'updatePermissions'])->whereNumber('user');
    });
});
