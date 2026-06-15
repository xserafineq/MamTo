@extends('layout')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@push('scripts')
    @vite(['resources/js/home-latest-auctions.js'])
@endpush

@section('content')
    <header class="home-header">
        <div class="home-header__stage">
            <div id="header-text-box" class="home-header__copy">
                <div id="header-text">
                    <p class="header-text__headline">
                        Utwórz nową<br>
                        aukcje całkowicie<br>
                        <span class="header-text__accent">za darmo</span>
                    </p>
                    <a href="{{ route('auctions.create') }}" id="create-auction-btn">Utwórz aukcje</a>
                </div>
            </div>

            <div class="home-header__visual">
                <div id="car-img">
                    <img src="{{ asset('assets/car.svg') }}" alt="Samochód"/>
                </div>
            </div>

            <div class="header-hexagon header-hexagon--top-right">
                <img src="{{ asset('assets/hexagon-halftop.png') }}" alt=""/>
            </div>
            <div class="header-hexagon header-hexagon--center center-hexagon">
                <img src="{{ asset('assets/hexagon.svg') }}" alt=""/>
            </div>
            <div class="header-hexagon header-hexagon--bottom">
                <img src="{{ asset('assets/hexagon-half.svg') }}" alt=""/>
            </div>

            <div id="clouds">
                <img src="{{ asset('assets/clouds.svg') }}" alt=""/>
            </div>
        </div>
    </header>
    <section id="categories">
        <div class="section-text-title">Kategorie <span style="color: #0066FF">Główne</span></div>

        <div id="categories-box">
            @forelse($categories as $category)
                <a href="{{ route('auctions.index', ['category' => $category->id]) }}" class="category-card">
                    <img
                        src="{{ $category->image?->file_url ?? asset('assets/default-category.png') }}"
                        alt="{{ $category->name }}"
                        style="max-width: 130px"
                    />
                    <div class="category-title">{{ $category->name }}</div>
                </a>
            @empty
                <p>Brak kategorii.</p>
            @endforelse
        </div>
    </section>
    <section id="newest-auctions">
        <div class="section-text-title">Najnowsze <span style="color: #0066FF">Aukcje</span></div>
        <div
            id="auctions-box"
            data-placeholder-url="{{ asset('assets/placeholder.png') }}"
        >
            <p class="home-auctions-loading">Ładowanie aukcji…</p>
        </div>
    </section>
@endsection
