@extends('layout')

@push('styles')
    @vite(['resources/css/auction-page.css'])
@endpush

@section('content')
    @if($auction->status === 'zakończona')
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                <strong>To ogłoszenie jest zamknięte.</strong>
                Nie można już kontaktować się ze sprzedawcą w sprawie tej oferty.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
            </div>
        </div>
    @endif

    <div id="auction-container">
        <div id="main-data">
            <div id="carousel-container">
                <div id="auctionCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($images as $image)
                            <div class="carousel-item @if($loop->first) active @endif">
                                <img
                                    src="{{ $image->file_url }}"
                                    class="d-block w-100 auction-carousel-image"
                                    alt="{{ $auction->name }}"
                                    data-slide-index="{{ $loop->index }}"
                                    style="object-fit: cover;"
                                >
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img
                                    src="{{ asset('assets/placeholder.png') }}"
                                    class="d-block w-100 auction-carousel-image"
                                    alt="{{ $auction->name }}"
                                    data-slide-index="0"
                                >
                            </div>
                        @endforelse
                    </div>
                    @if($images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#auctionCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Poprzedni</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#auctionCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Następny</span>
                        </button>
                    @endif
                </div>
            </div>
            <div id="information">
                <div id="title">
                    {{ $auction->name }}
                    @include('partials.own-auction-badge', ['auction' => $auction])
                </div>
                <div id="price">
                    @if($isJobOffer)
                        @if($auction->salaryType === 'do uzgodnienia' || ($auction->negotiable && ! $auction->salaryType))
                            Wynagrodzenie do uzgodnienia
                        @else
                            {{ number_format($auction->price, 0, ',', ' ') }} zł {{ $auction->salaryType }}
                        @endif
                    @else
                        {{ number_format($auction->price, 0, ',', ' ') }} zł
                    @endif
                </div>
                @if(!$isJobOffer && $auction->negotiable)
                    <div id="finalPrice">
                        do negocjacji
                    </div>
                @endif
                <div id="seller">
                    <div class="seller-header">{{ $isJobOffer ? 'Pracodawca' : 'Sprzedający' }}</div>
                    <div id="seller-data">
                        <a href="{{ route('users.show', $auction->user) }}" class="seller-profile-link" aria-label="Profil użytkownika">
                            <img id="seller-avatar" src="{{ asset('assets/seller.png') }}" alt="{{ $auction->user->firstName }} {{ $auction->user->lastName }}"/>
                        </a>
                        <div id="seller-main-data">
                            <a href="{{ route('users.show', $auction->user) }}" class="seller-profile-link seller-name">
                                {{ $auction->user->firstName }} {{ $auction->user->lastName }}
                            </a>
                            <div id="phoneNumber">
                                @if($isOwner)
                                    <a href="{{ $auction->user->telHref() }}">{{ $displayPhone }}</a>
                                @else
                                    {{ $displayPhone }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div id="extra-info">
                    <div id="extra-info-location-date">
                        <div class="info-item location">
                            {{ $auction->location }}
                        </div>
                        <div class="info-item date">
                            {{ $auction->createdAt->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
                <div id="btns-box">
                    @if($auction->status === 'aktywna')
                        @auth
                            @if(! $isOwner)
                                <button
                                    type="button"
                                    id="follow-btn"
                                    class="btn-like @if($isFollowed) is-followed @endif"
                                    data-follow-url="{{ route('auctions.follow', $auction) }}"
                                    data-unfollow-url="{{ route('auctions.unfollow', $auction) }}"
                                    data-csrf="{{ csrf_token() }}"
                                    data-followed="{{ $isFollowed ? '1' : '0' }}"
                                    aria-label="{{ $isFollowed ? 'Usuń z obserwowanych' : 'Dodaj do obserwowanych' }}"
                                    aria-pressed="{{ $isFollowed ? 'true' : 'false' }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0066ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </button>
                            @endif
                        @endauth
                    @endif
                    @if($auction->status === 'aktywna')
                        @auth
                            @if((int) auth()->id() !== (int) $auction->user->id)
                                <a href="{{ route('chats.start', $auction) }}" class="btn-message">napisz wiadomość</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-message">napisz wiadomość</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
        <div id="description-container">
            <div id="description">
                <div id="description-head">Opis</div>
                @if(blank(trim($auction->description ?? '')))
                    <p class="auction-description__empty">Brak opisu</p>
                @else
                    <div class="auction-description__body">{{ $auction->description }}</div>
                @endif
            </div>
        </div>
    </div>

    @if($otherAuctions->isNotEmpty())
        <section id="other-auctions">
            <div class="section-text-title">Inne <span style="color: #0066FF">Aukcje od tego sprzedawcy</span></div>
            <div id="other-auctions-box">
                @foreach($otherAuctions as $otherAuction)
                    <a href="{{ route('auctions.show', $otherAuction) }}" class="other-auction-card">
                        <div class="other-auction-card__image">
                            <img
                                src="{{ $otherAuction->image?->file_url ?? asset('assets/placeholder.png') }}"
                                alt="{{ $otherAuction->name }}"
                            />
                        </div>
                        <div class="other-auction-card__body">
                            <div class="other-auction-card__title">{{ $otherAuction->name }}</div>
                            <div class="other-auction-card__footer">
                                <div class="other-auction-card__price">{{ number_format($otherAuction->price, 0, ',', ' ') }} zł</div>
                                <span class="other-auction-card__action">Sprawdź</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="modal fade" id="imageLightbox" tabindex="-1" aria-labelledby="imageLightboxLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <button type="button" class="btn-close image-lightbox__close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                <div id="lightboxCarousel" class="carousel slide image-lightbox__carousel" data-bs-ride="false">
                    <div class="carousel-inner">
                        @forelse($images as $image)
                            <div class="carousel-item @if($loop->first) active @endif">
                                <div class="image-lightbox__frame">
                                    <img
                                        src="{{ $image->file_url }}"
                                        class="d-block image-lightbox__image"
                                        alt="{{ $auction->name }}"
                                    >
                                </div>
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <div class="image-lightbox__frame">
                                    <img
                                        src="{{ asset('assets/placeholder.png') }}"
                                        class="d-block image-lightbox__image"
                                        alt="{{ $auction->name }}"
                                    >
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if($images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Poprzedni</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Następny</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/auctions/auction-page.js'])
@endpush
