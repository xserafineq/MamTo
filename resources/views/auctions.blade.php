@extends('layout')

@push('styles')
    @vite(['resources/css/auctions.css'])
@endpush

@section('content')
    <section id="filters">
        <div id="sortby">
            <div>Sortuj</div>
            <select>
                <option selected>Popularne</option>
                <option>Rosnąco</option>
                <option>Malejąco</option>
                <option>Najnowsze</option>
            </select>
        </div>
        <div id="price">
            <div>Cena</div>
            <div id="price-inputs">
                <input type="number" placeholder="od">
                <input type="number" placeholder="do">
            </div>
        </div>
    </section>
    <section id="searched-auctions">
        @forelse($auctions as $auction)
            <div class="searched-auction-card">
                <img
                    src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                    class="searched-auction-img"
                    alt="{{ $auction->name }}"
                    style="object-fit: cover;"
                />
                <div class="content">
                    <div class="title-price">
                        <div class="title">{{ $auction->name }}</div>
                        <div class="price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                    </div>
                    <div class="description">
                        {{ $auction->description }}
                    </div>
                    <div class="date-location">
                        <div class="date">{{ $auction->createdAt->format('d.m.Y') }}</div>
                        <div class="date">{{ $auction->location }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p>Brak aukcji.</p>
        @endforelse
    </section>
    @if ($auctions->hasPages())
        <div id="pagination">
            @for ($page = 1; $page <= $auctions->lastPage(); $page++)
                @if ($page === $auctions->currentPage())
                    <span class="pagination-btn is-current">{{ $page }}</span>
                @else
                    <a href="{{ $auctions->url($page) }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endfor
        </div>
    @endif
@endsection
