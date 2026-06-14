@extends('layout')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@push('scripts')
    @vite(['resources/js/home-latest-auctions.js'])
@endpush

@section('content')
    <header>
        <div id="header-text-box">
            <div id="header-text">
                <div>Utwórz nową</div>
                <div>aukcje całkowicie</div>
                <div>za darmo</div>
                <a href="{{ route('auctions.create') }}" id="create-auction-btn">Utwórz aukcje</a>
            </div>
        </div>
        <div style="bottom: 0; z-index: 4 !important;" class="header-hexagon">
            <img src="{{asset('assets/hexagon-half.svg')}}" alt="hexagon"/>
        </div>
        <div style="right: 0;" class="header-hexagon">
            <img src="{{asset('assets/hexagon-halftop.png')}}" alt="hexagon"/>
        </div>
        <div style="right: 400px; top: 180px;" class="header-hexagon center-hexagon">
            <img src="{{asset('assets/hexagon.svg')}}" alt="hexagon"/>
        </div>
        <div id="car-img">
            <img style="object-fit: cover;" src="{{asset('assets/car.svg')}}" alt="car"/>
        </div>
        <div id="clouds" style="display: flex; width: 100%; padding: 0; position: absolute; z-index: 3; bottom: -30px; height: 180px;">
            <img style="object-fit: cover;" src="{{asset('assets/clouds.svg')}}" width="100%" alt="clouds"/>
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
