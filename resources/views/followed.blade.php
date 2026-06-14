@extends('layout')

@push('styles')
    @vite(['resources/css/auctions.css'])
@endpush

@section('content')
    <h1 id="followed-header">Obserwowane</h1>

    <section id="searched-auctions">
        @forelse($auctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}" class="searched-auction-card">
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
                        {{ Str::limit(Str::words($auction->description, 30, ''), 200, '...') }}
                    </div>
                    <div class="date-location">
                        <div class="date">{{ $auction->createdAt->format('d.m.Y') }}</div>
                        <div class="date">{{ $auction->location }}</div>
                    </div>
                </div>
            </a>
        @empty
            <p class="followed-empty">Nie obserwujesz jeszcze żadnych aktywnych aukcji.</p>
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
