@extends('layout')

@section('content')
    <section class="user-list-page">
        <h1>Obserwowane</h1>

        @forelse($auctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}" class="user-list-page__item">
                <img src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}" alt="{{ $auction->name }}">
                <div>
                    <div class="user-list-page__title">{{ $auction->name }}</div>
                    <div class="user-list-page__price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                </div>
            </a>
        @empty
            <p>Nie obserwujesz jeszcze żadnych aukcji.</p>
        @endforelse
    </section>
@endsection
