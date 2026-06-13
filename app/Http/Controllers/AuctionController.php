<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AuctionController extends Controller
{
    // Formularz tworzenia aukcji
    public function create(): View
    {
        return view('create-auction');
    }
}
