<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/css/auctions.css'])
</head>
<body>
@extends('layout')

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
        <div class="searched-auction-card">
            <img src="https://agroprofil.pl/cms/wp-content/uploads/2019/12/7r330_lemken_plow_dsc2933-e1576754071505.jpg" class="searched-auction-img" alt="auction-img"/>
            <div class="content">
                <div class="title-price">
                    <div class="title">
                        JOHN DEERE 250KM 4X4 2020 280MTH
                    </div>
                    <div class="price">
                        2500 zł
                    </div>
                </div>
                <div class="description">
                    JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4
                    2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH
                </div>
                <div class="date-location">
                    <div class="date">24 kwietnia 2026</div>
                    <div class="date">Tarnobrzeg</div>
                </div>
            </div>
        </div>
        <div class="searched-auction-card">
            <img src="https://agroprofil.pl/cms/wp-content/uploads/2019/12/7r330_lemken_plow_dsc2933-e1576754071505.jpg" class="searched-auction-img" alt="auction-img"/>
            <div class="content">
                <div class="title-price">
                    <div class="title">
                        JOHN DEERE 250KM 4X4 2020 280MTH
                    </div>
                    <div class="price">
                        2500 zł
                    </div>
                </div>
                <div class="description">
                    JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4
                    2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH
                </div>
                <div class="date-location">
                    <div class="date">24 kwietnia 2026</div>
                    <div class="date">Tarnobrzeg</div>
                </div>
            </div>
        </div>
        <div class="searched-auction-card">
            <img src="https://agroprofil.pl/cms/wp-content/uploads/2019/12/7r330_lemken_plow_dsc2933-e1576754071505.jpg" class="searched-auction-img" alt="auction-img"/>
            <div class="content">
                <div class="title-price">
                    <div class="title">
                        JOHN DEERE 250KM 4X4 2020 280MTH
                    </div>
                    <div class="price">
                        2500 zł
                    </div>
                </div>
                <div class="description">
                    JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4
                    2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH JOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTHJOHN DEERE 250KM 4X4 2020 280MTH
                </div>
                <div class="date-location">
                    <div class="date">24 kwietnia 2026</div>
                    <div class="date">Tarnobrzeg</div>
                </div>
            </div>
        </div>
    </section>
    <div id="pagination">
        <btn class="pagination-btn">1</btn>
        <btn class="pagination-btn">2</btn>
        <btn class="pagination-btn">3</btn>
        <btn class="pagination-btn">...</btn>
    </div>
@endsection
</body>
</html>
