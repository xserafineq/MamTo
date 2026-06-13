<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/', function () {
    $categories = Category::with('image')
        ->whereNull('parentId')
        ->get();

    return view('home', compact('categories'));
});

Route::get('/auctions', function () {
    return view('auctions');
});


Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});



Route::get('/auction-page', function () {
    return view('auction-page');
});
