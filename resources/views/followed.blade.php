@extends('layout')

@push('styles')
    @vite(['resources/css/auctions.css'])
@endpush

@push('scripts')
    @vite(['resources/js/followed-auctions.js'])
@endpush

@section('content')
    <h1 id="followed-header">Obserwowane</h1>

    <section id="searched-auctions">
        @forelse($auctions as $auction)
            <article class="searched-auction-card searched-auction-card--followed" data-auction-id="{{ $auction->id }}">
                <a href="{{ route('auctions.show', $auction) }}" class="searched-auction-card__link">
                    <img
                        src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                        class="searched-auction-img"
                        alt="{{ $auction->name }}"
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
                <button
                    type="button"
                    class="followed-unfollow-btn btn-like is-followed"
                    data-unfollow-url="{{ route('auctions.unfollow', $auction) }}"
                    data-csrf="{{ csrf_token() }}"
                    aria-label="Usuń z obserwowanych"
                    aria-pressed="true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0066ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </button>
            </article>
        @empty
            <p class="followed-empty" id="followed-empty">Nie obserwujesz jeszcze żadnych aktywnych aukcji.</p>
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
