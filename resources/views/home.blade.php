<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/css/home.css'])
</head>
<body>
@extends('layout')

@section('content')
    <header>
        <div id="header-text-box">
            <div id="header-text">
                <div>Utwórz nową</div>
                <div>aukcje całkowicie</div>
                <div>za darmo</div>
                <button id="create-auction-btn">Utwórz aukcje</button>
            </div>
        </div>
        <div style="bottom: 0" class="header-hexagon">
            <img src="{{asset('assets/hexagon-half.svg')}}" alt="hexagon"/>
        </div>
        <div style="right: 0" class="header-hexagon">
            <img src="{{asset('assets/hexagon-halftop.png')}}" alt="hexagon"/>
        </div>
        <div style="right: 400px; top: 180px" class="header-hexagon">
            <img src="{{asset('assets/hexagon.svg')}}" alt="hexagon"/>
        </div>
        <div id="car-img">
            <img src="{{asset('assets/car.svg')}}" alt="car"/>
        </div>
        <div style="position: absolute; bottom: 0; right: 0; z-index: 3;">
            <img src="{{asset('assets/cloud.png')}}" alt="clouds"/>
        </div>
    </header>
    <section id="categories">
        <div class="section-text-title">Kategorie <span style="color: #0066FF">Główne</span></div>

        <div id="categories-box">
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
            <div class="category-card">
                <img src="{{asset('assets/category-card-test-img.png')}}" alt="category-img"/>
                <div class="category-title">Rowery</div>
            </div>
        </div>
    </section>
    <section id="newest-auctions">
        <div class="section-text-title">Najnowsze <span style="color: #0066FF">Aukcje</span></div>
        <div id="auctions-box">
            <div class="newest-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="newest-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="newest-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="newest-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="newest-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
        </div>
    </section>
@endsection
</body>
</html>
