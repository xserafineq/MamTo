<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/auction-page.css'])
</head>
<body>
@extends('layout')

@section('content')
    <div id="auction-container">
        <div id="main-data">
            <div id="carousel-container">
                <div id="auctionCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://agroprofil.pl/cms/wp-content/uploads/2019/12/7r330_lemken_plow_dsc2933-e1576754071505.jpg" class="d-block w-100" alt="slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="https://agroprofil.pl/cms/wp-content/uploads/2019/12/7r330_lemken_plow_dsc2933-e1576754071505.jpg" class="d-block w-100" alt="slide 2">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#auctionCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Poprzedni</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#auctionCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Następny</span>
                    </button>
                </div>
            </div>
            <div id="information">
                <div id="title">
                    JOHN DEERE 6140M 2020r. 1130h DEMO ISOBUS
                </div>
                <div id="price">
                    350 000 zł
                </div>
                <div id="finalPrice">
                    do negocjacji
                </div>
                <div id="seller">
                    <div class="seller-header">Sprzedający</div>
                    <div id="seller-data">
                        <img id="seller-avatar" src="{{asset("assets/seller.png")}}" alt="avatar"/>
                        <div id="seller-main-data">
                            <div id="email">@arturkowalski@gmail.com</div>
                            <div id="phoneNumber">+48 123 *** ***</div>
                            <div id="rating">Ocena 4.5 / 5</div>
                        </div>
                    </div>
                </div>
                <div id="extra-info">
                    <div id="extra-info-location-date">
                        <div class="info-item location">
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>--}}
                            Tarnobrzeg, podkarpackie
                        </div>
                        <div class="info-item date">
                            27 kwietnia 2025
                        </div>
                    </div>
                </div>
                <div id="btns-box">
                    <button class="btn-like">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0066ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                    <button class="btn-message">napisz wiadomość</button>
                </div>
            </div>
        </div>
        <div id="description">
            <div id="description-head">Opis</div>
            Do sprzedania ciągnik rolniczy  JOHN DEERE 6140M 2020r 1130h 140km Skrzynia Autoquad 40km/h Isobus Amortyzacja przedniej osi Amortyzacja kabiny Koła 460/85R38 - 420/85R24 Przedni TUZ + ciężarek 600kg Pełne oświetlenie LED Układ hamulcowy dwuobwodowy pneumatyczny 2+1 Jak nowy Cena + VAT  Więcej informacji pod numerem +48********91  John Deere 6120, 6130, 6140, 6145, 6150, 6155, 6170, 6175, 6195, 6210, 6215, 6220, 6320, 6520, 6530, 6630, 6830, 7430, 7530, Fendt, deutz fahr, Massey Ferguson, new Holland
        </div>
    </div>
    <section id="other-auctions">
        <div class="section-text-title">Inne <span style="color: #0066FF">Aukcje od tego sprzedawcy</span></div>
        <div id="auctions-box">
            <div class="other-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="other-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
            <div class="other-auction-card">
                <img src="{{asset('assets/car.svg')}}" alt="auction-img"/>
                <div class="auction-title">Tytuł aukcji</div>
                <button class="check-auction-btn">Sprawdź</button>
                <div class="auction-price">2000 zł</div>
            </div>
        </div>
    </section>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
