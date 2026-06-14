@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css', 'resources/css/my-auctions.css'])
@endpush

@section('content')
    <section class="admin-panel">

        <h1>Aukcje</h1>

        <div class="my-auctions-stats">
            <span class="my-auctions-stats__active">Aktywne: {{ $activeCount }}</span>
            <span class="my-auctions-stats__closed">Zamknięte: {{ $closedCount }}</span>
            <span class="admin-panel-total">Razem: {{ $activeCount + $closedCount }}</span>
        </div>

        @include('admin.partials.nav')

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.auctions.index') }}" class="admin-panel-users-search">
            <input
                type="search"
                name="q"
                class="form-control"
                placeholder="Szukaj po tytule, lokalizacji, imieniu, nazwisku lub e-mailu sprzedawcy…"
                value="{{ request('q') }}"
            >
            <button type="submit" class="btn btn-primary">Szukaj</button>
            @if (request('q'))
                <a href="{{ route('admin.auctions.index') }}" class="btn btn-outline-secondary">Wyczyść</a>
            @endif
        </form>

        @forelse($auctions as $auction)
            <article class="my-auctions-card {{ $auction->status !== 'aktywna' ? 'my-auctions-card--closed' : '' }}">
                <a href="{{ route('auctions.show', $auction) }}" class="my-auctions-card__link">
                    <img
                        src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                        alt="{{ $auction->name }}"
                    >
                    <div class="my-auctions-card__info">
                        <div class="my-auctions-card__title">{{ $auction->name }}</div>
                        <div class="my-auctions-card__price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                        <div class="my-auctions-card__meta">
                            {{ $auction->user->email }} · {{ $auction->location }} · {{ $auction->createdAt->format('d.m.Y') }}
                        </div>
                        @if($auction->status === 'aktywna' && $auction->approved)
                            <span class="my-auctions-card__badge my-auctions-card__badge--active">Aktywna</span>
                        @elseif($auction->status === 'aktywna')
                            <span class="my-auctions-card__badge my-auctions-card__badge--pending">Oczekuje na akceptację</span>
                        @else
                            <span class="my-auctions-card__badge">Zamknięta</span>
                        @endif
                    </div>
                </a>

                <div class="my-auctions-card__actions">
                    <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline-secondary btn-sm">Podgląd</a>
                    <a href="{{ route('admin.auctions.edit', $auction) }}" class="btn btn-outline-primary btn-sm">Edytuj</a>
                    <form
                        action="{{ route('admin.auctions.destroy', $auction) }}"
                        method="POST"
                        onsubmit="return confirm('Czy na pewno chcesz usunąć to ogłoszenie? Tej operacji nie można cofnąć.');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">Usuń</button>
                    </form>
                </div>
            </article>
        @empty
            <p>Brak aukcji w systemie.</p>
        @endforelse

        @include('admin.partials.pagination', ['paginator' => $auctions])
    </section>
@endsection
