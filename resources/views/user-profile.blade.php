@extends('layout')

@push('styles')
    @vite(['resources/css/user-profile.css', 'resources/css/my-auctions.css', 'resources/css/auctions.css'])
@endpush

@section('content')
    <section class="user-profile-page">
        <div class="user-profile-header">
            <img
                class="user-profile-avatar"
                src="{{ asset('assets/seller.png') }}"
                alt="{{ $user->firstName }} {{ $user->lastName }}"
            >
            <div class="user-profile-info">
                <h1>{{ $user->firstName }} {{ $user->lastName }}</h1>
                <p class="user-profile-joined">
                    Dołączył: {{ $user->joinedAt?->format('d.m.Y') ?? '—' }}
                </p>
                @if($recommendationPercent !== null)
                    <div id="rating" data-recommendation-percent="{{ $recommendationPercent }}">
                        {{ $recommendationPercent }}% oceniających poleca
                    </div>
                @else
                    <div id="rating" data-recommendation-percent="" hidden></div>
                @endif

                @auth
                    @if(! $isOwner)
                        <div
                            id="seller-rating-actions"
                            class="seller-rating-actions"
                            data-rate-url="{{ route('users.rating.store', $user) }}"
                            data-csrf="{{ csrf_token() }}"
                            data-user-rating="{{ $userRating ?? '' }}"
                            data-can-rate="{{ $canRateSeller ? '1' : '0' }}"
                        >
                            <button
                                type="button"
                                class="btn-rate btn-rate--recommend @if($userRating === 1) is-selected @endif"
                                data-rating="1"
                                @if(! $canRateSeller) disabled @endif
                            >
                                polecam
                            </button>
                            <button
                                type="button"
                                class="btn-rate btn-rate--not-recommend @if($userRating === 0) is-selected @endif"
                                data-rating="0"
                                @if(! $canRateSeller) disabled @endif
                            >
                                nie polecam
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <h2 class="user-profile-auctions-title">
            Ogłoszenia <span>({{ $auctions->total() }})</span>
        </h2>

        @forelse($auctions as $auction)
            <article class="my-auctions-card">
                <a href="{{ route('auctions.show', $auction) }}" class="my-auctions-card__link">
                    <img
                        src="{{ $auction->image?->file_url ?? asset('assets/placeholder.png') }}"
                        alt="{{ $auction->name }}"
                    >
                    <div class="my-auctions-card__info">
                        <div class="my-auctions-card__title">{{ $auction->name }}</div>
                        <div class="my-auctions-card__price">{{ number_format($auction->price, 0, ',', ' ') }} zł</div>
                        <div class="my-auctions-card__meta">
                            {{ $auction->location }} · {{ $auction->createdAt->format('d.m.Y') }}
                        </div>
                    </div>
                </a>
            </article>
        @empty
            <p class="user-profile-empty">Ten użytkownik nie ma aktywnych ogłoszeń.</p>
        @endforelse

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
    </section>
@endsection

@push('scripts')
    @vite(['resources/js/user-profile.js'])
@endpush
