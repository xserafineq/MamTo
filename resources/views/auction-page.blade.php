@extends('layout')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auction-page.css'])
@endpush

@section('content')
    @php
        $seller = $auction->user;
        $phone = $seller->phoneNumber;
        $maskedPhone = strlen($phone) > 7
            ? substr($phone, 0, 4) . ' *** ' . substr($phone, -3)
            : $phone;
    @endphp

    <div id="auction-container">
        <div id="main-data">
            <div id="carousel-container">
                <div id="auctionCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($images as $image)
                            <div class="carousel-item @if($loop->first) active @endif">
                                <img
                                    src="{{ $image->file_url }}"
                                    class="d-block w-100"
                                    alt="{{ $auction->name }}"
                                    style="object-fit: cover;"
                                >
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img
                                    src="{{ asset('assets/placeholder.png') }}"
                                    class="d-block w-100"
                                    alt="{{ $auction->name }}"
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
                </div>
                <div id="price">
                    {{ number_format($auction->price, 0, ',', ' ') }} zł
                </div>
                @if($auction->negotiable)
                    <div id="finalPrice">
                        do negocjacji
                    </div>
                @endif
                <div id="seller">
                    <div class="seller-header">Sprzedający</div>
                    <div id="seller-data">
                        <img id="seller-avatar" src="{{ asset('assets/seller.png') }}" alt="avatar"/>
                        <div id="seller-main-data">
                            <div id="email">{{ $seller->email }}</div>
                            <div id="phoneNumber">{{ $maskedPhone }}</div>
                            @if($sellerRating)
                                <div id="rating">Ocena {{ number_format($sellerRating, 1, ',', ' ') }} / 5</div>
                            @endif
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
                    <button class="btn-like" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0066ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                    @auth
                        @if((int) auth()->id() !== (int) $seller->id)
                            <a href="{{ route('chats.start', $auction) }}" class="btn-message">napisz wiadomość</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-message">napisz wiadomość</a>
                    @endauth
                </div>
            </div>
        </div>
        <div id="description">
            <div id="description-head">Opis</div>
            {{ $auction->description }}
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
