--
-- PostgreSQL database dump
--

\restrict YTLnjkHJiKeJMfVlu4FuMpxK7VE7Nmy4oHuTCK5uOM4dUOF51l3HeU1VRkyz2kI

-- Dumped from database version 16.14
-- Dumped by pg_dump version 16.13

-- Started on 2026-06-15 19:24:28 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3510 (class 0 OID 18151)
-- Dependencies: 229
-- Data for Name: Images; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Images" VALUES (2, 'praca-obraz.webp', '2026-06-15 17:32:56', 'b838a253-67ba-4cc3-9db7-f36370b5deec');
INSERT INTO public."Images" VALUES (3, 'Electronic-circuit.webp', '2026-06-15 17:34:14', 'a3af19c6-8c66-4230-8027-5af974c59f52');
INSERT INTO public."Images" VALUES (4, 'parasol.webp', '2026-06-15 17:35:16', '27063563-cf5e-48db-86ec-c7d3bb765ec3');
INSERT INTO public."Images" VALUES (5, 'moda.webp', '2026-06-15 17:38:47', '3e8cd6c4-5df3-41a7-bc89-bc8db1e6bd0b');
INSERT INTO public."Images" VALUES (6, 'kids.png', '2026-06-15 17:38:56', 'e04a7514-c9f7-47a5-a4ba-9d78188a0ca9');
INSERT INTO public."Images" VALUES (7, 'Hobbies.png', '2026-06-15 17:41:05', '4aeffd6c-ba1c-4ce6-a04e-cfef11a66b23');
INSERT INTO public."Images" VALUES (8, 'Peugeot-307-facelift-2005.jpg', '2026-06-15 17:44:47', 'fbb98a06-4a46-4ca9-8bd6-3f1f51d4b16e');
INSERT INTO public."Images" VALUES (9, 'Peugeot-307-facelift-2005.jpg', '2026-06-15 17:50:24', '9e4e4ed6-c76c-4af3-bc49-3f78fe8c6679');
INSERT INTO public."Images" VALUES (10, 'Peugeot-307-facelift-2005.jpg', '2026-06-15 17:50:24', '60700faf-e54a-46d1-b374-5e69ca8a3841');
INSERT INTO public."Images" VALUES (11, 'peugeot_307_2.jpg', '2026-06-15 17:50:24', 'd4cc3980-f345-47d9-a03f-bbaef23f67a8');
INSERT INTO public."Images" VALUES (12, 'Peugeot-307-facelift-2005.jpg', '2026-06-15 17:50:24', '63e69d02-9dbc-4f36-80ee-6b59f799055e');
INSERT INTO public."Images" VALUES (13, 'peugeot_307_2.jpg', '2026-06-15 17:50:24', '9af85093-842e-49c6-878c-8c30d511f718');
INSERT INTO public."Images" VALUES (14, 'image;s=1000x700.webp', '2026-06-15 17:52:36', 'a1c04c7e-642b-4081-a47f-fdc2ad21d5a4');
INSERT INTO public."Images" VALUES (15, 'image;s=1000x7001.webp', '2026-06-15 17:52:36', 'f0e7f2c3-6711-4b10-871c-d58d41f6495a');
INSERT INTO public."Images" VALUES (16, 'image;s=1000x700.webp', '2026-06-15 17:56:28', 'a7b9f397-9f65-4e3a-9e20-84c1551cd07b');
INSERT INTO public."Images" VALUES (17, 'image;s=1000x7004.webp', '2026-06-15 17:56:28', 'aac37b47-7037-47f3-aa0b-996eba39c468');
INSERT INTO public."Images" VALUES (18, 'image;s=1000x7003.webp', '2026-06-15 17:56:28', '73a724d2-987a-41b5-825b-3ff49c104b08');
INSERT INTO public."Images" VALUES (19, 'image;s=1000x701.webp', '2026-06-15 17:56:28', '81ae1ecf-79ed-4dfe-9f1f-ee899281d7b3');
INSERT INTO public."Images" VALUES (20, 'image;s=1000x7002.webp', '2026-06-15 17:56:28', '30f1000d-3dc5-4b35-a605-754c0b7e3fcd');
INSERT INTO public."Images" VALUES (21, 'image;s=1000x700.webp', '2026-06-15 17:59:09', '7bec574c-fcf7-48bf-bbad-cf2680263033');
INSERT INTO public."Images" VALUES (22, 'image;s=1000x703.webp', '2026-06-15 17:59:09', 'c998004b-3c96-4647-84a4-d15946c33cbe');
INSERT INTO public."Images" VALUES (23, 'image;s=1000x702.webp', '2026-06-15 17:59:09', '334dd3fa-6bb9-45bc-b3c5-ec453ca38367');
INSERT INTO public."Images" VALUES (24, 'image;s=1000x701.webp', '2026-06-15 17:59:09', '8d5070a5-34f4-4ee2-b155-24ad3fa26345');
INSERT INTO public."Images" VALUES (25, '1.webp', '2026-06-15 18:04:27', '1205d16b-5731-4293-ae67-f3ef5ac80010');
INSERT INTO public."Images" VALUES (26, '2.webp', '2026-06-15 18:04:27', '38a97579-79e3-4f29-832c-620c70fad2f7');
INSERT INTO public."Images" VALUES (27, '3.webp', '2026-06-15 18:04:27', 'c4c8228a-2279-4730-86b8-275ca748b39f');
INSERT INTO public."Images" VALUES (28, '4.webp', '2026-06-15 18:04:27', 'edfa821a-f7e8-47eb-8e0a-3f39ef4a1238');
INSERT INTO public."Images" VALUES (29, 'kids.png', '2026-06-15 18:09:58', 'e0c28982-50ad-410f-b0de-a4bcab9ed7ac');
INSERT INTO public."Images" VALUES (30, '1.webp', '2026-06-15 18:12:52', '23e479b3-6bc9-40c0-a904-a19977a5964b');
INSERT INTO public."Images" VALUES (31, '2.webp', '2026-06-15 18:12:52', 'e4715bd9-0250-4bd7-a967-d346aa55c532');
INSERT INTO public."Images" VALUES (32, '3.webp', '2026-06-15 18:12:52', '93c11b0b-cb5c-459d-a30b-58d1569e050c');
INSERT INTO public."Images" VALUES (33, '1.webp', '2026-06-15 18:17:38', 'ece5012f-8932-49b0-adda-458c4fa7c11a');
INSERT INTO public."Images" VALUES (34, '2.webp', '2026-06-15 18:17:38', '78bc9d7f-3af5-4ddf-a21f-a0a690ee4850');
INSERT INTO public."Images" VALUES (35, '1.webp', '2026-06-15 18:22:31', '4a371f66-788b-4a83-b7b2-f2146575c112');
INSERT INTO public."Images" VALUES (36, '2.webp', '2026-06-15 18:22:31', '8d75267c-68b5-49ae-a77f-411478362ae9');
INSERT INTO public."Images" VALUES (37, '3.webp', '2026-06-15 18:22:31', '2194827a-f4df-479e-a05e-be639783ff11');
INSERT INTO public."Images" VALUES (38, '1.webp', '2026-06-15 18:31:05', '6ffeee8e-5dc7-4b1b-82fc-259809076727');
INSERT INTO public."Images" VALUES (39, '2.webp', '2026-06-15 18:31:05', '58309390-28f2-4943-bd5a-d83d7998d49e');
INSERT INTO public."Images" VALUES (40, '3.webp', '2026-06-15 18:31:05', 'ff4f4c81-9b78-47c2-9de7-2a37a651cde8');
INSERT INTO public."Images" VALUES (41, '4.webp', '2026-06-15 18:31:05', 'fcc24286-9a19-4c38-a6dd-b50c43515907');
INSERT INTO public."Images" VALUES (42, '1.webp', '2026-06-15 18:36:03', '7bec9f31-fb49-452d-8f28-2f6cbedbc44a');
INSERT INTO public."Images" VALUES (43, '2.webp', '2026-06-15 18:36:03', 'd89309cb-ef37-4677-b98f-a420eaeb950e');
INSERT INTO public."Images" VALUES (44, '3.webp', '2026-06-15 18:36:03', 'd8b5f31a-5875-44a3-88ef-5ff8463ee5b1');
INSERT INTO public."Images" VALUES (45, '4.webp', '2026-06-15 18:36:03', '16225374-8336-4898-945d-c5e97635f78c');
INSERT INTO public."Images" VALUES (46, '5.webp', '2026-06-15 18:36:03', '1e86a598-f969-4cc7-b30b-d0adc3a4fd1a');
INSERT INTO public."Images" VALUES (47, '1.webp', '2026-06-15 18:37:31', '1f06c02d-17dd-4996-bf9a-22332bf2b0b9');
INSERT INTO public."Images" VALUES (48, '2.webp', '2026-06-15 18:37:31', 'f19f2825-e76a-484f-82ea-23540146944b');
INSERT INTO public."Images" VALUES (49, '2.webp', '2026-06-15 18:59:42', 'c2e5c84e-5d90-42e7-97ff-1d790c180564');
INSERT INTO public."Images" VALUES (50, '1.webp', '2026-06-15 19:04:25', '9b28b752-743e-4b03-935e-8758d8a14667');
INSERT INTO public."Images" VALUES (1, 'notfound.png', '2026-06-15 17:32:10', '11111111-1111-4111-8111-111111111111');


--
-- TOC entry 3512 (class 0 OID 18160)
-- Dependencies: 231
-- Data for Name: Categories; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Categories" VALUES (2, 'IT i technologie', NULL, 1);
INSERT INTO public."Categories" VALUES (3, 'Handel i sprzedaż', NULL, 1);
INSERT INTO public."Categories" VALUES (4, 'Produkcja', NULL, 1);
INSERT INTO public."Categories" VALUES (5, 'Biuro i administracja', NULL, 1);
INSERT INTO public."Categories" VALUES (6, 'Inne oferty pracy', NULL, 1);
INSERT INTO public."Categories" VALUES (1, 'Praca', 2, NULL);
INSERT INTO public."Categories" VALUES (9, 'Dom i ogród', 4, NULL);
INSERT INTO public."Categories" VALUES (10, 'Moda', 5, NULL);
INSERT INTO public."Categories" VALUES (11, 'Dla dzieci', 6, NULL);
INSERT INTO public."Categories" VALUES (12, 'Sport i Hobby', 7, NULL);
INSERT INTO public."Categories" VALUES (13, 'Samochody', 8, 7);
INSERT INTO public."Categories" VALUES (14, 'Skutery', NULL, 7);
INSERT INTO public."Categories" VALUES (15, 'Rowery', NULL, 12);
INSERT INTO public."Categories" VALUES (16, 'Fitness', NULL, 12);
INSERT INTO public."Categories" VALUES (8, 'Elektronika', 3, NULL);
INSERT INTO public."Categories" VALUES (17, 'Telefony', NULL, 8);
INSERT INTO public."Categories" VALUES (18, 'Komputery', NULL, 8);
INSERT INTO public."Categories" VALUES (19, 'Odzież damska', NULL, 10);
INSERT INTO public."Categories" VALUES (20, 'Odzież męska', NULL, 10);
INSERT INTO public."Categories" VALUES (7, 'Motoryzacja', 50, NULL);


--
-- TOC entry 3508 (class 0 OID 18140)
-- Dependencies: 227
-- Data for Name: Users; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Users" VALUES (4, 'Dobry', 'Wystawiający', 'scam@u.com', '111111111', '$2y$12$we0eglmfLrI1K5oOurng2O1lfeuqjEobd/f9r3/annmyu6xmmI9XO', '2026-06-15 17:29:39', '2026-06-15 18:00:08', false, false);
INSERT INTO public."Users" VALUES (2, 'Adam', 'Kowalski', 'adam@user.com', '123456789', '$2y$12$8Z0FuS1nbzZOm8nJbLqC4eJURtd3GYCRF6wPMBB9dQEHVizaS8ryG', '2026-02-15 17:21:17', '2026-06-15 19:13:16', false, false);
INSERT INTO public."Users" VALUES (3, 'Anna', 'Nowak', 'anna@user.com', '+48123123123', '$2y$12$oWF/5twhApIpdiBOp4b81umh27PBE2DhDr9BXEGdmSPQMcL0cnlxe', '2026-03-15 17:21:51', '2026-06-15 19:14:54', true, false);
INSERT INTO public."Users" VALUES (1, 'admin', 'admin', 'ja@admin.com', '000000000', '$2y$12$CeGkL4miS3gSE8oyXbufhe5UdRORVZDZNNSLka55sMd/M3wsQwchC', '2026-01-15 17:19:53', '2026-06-15 19:15:57', true, true);


--
-- TOC entry 3514 (class 0 OID 18177)
-- Dependencies: 233
-- Data for Name: Auctions; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Auctions" VALUES (1, 'Peugeot 307', 'Na sprzedaż oferuję Peugeota 307SW z silnikiem 1.6 HDI o mocy 109KM.
Rok produkcji: 2007
Data pierwszej rejestracji: 13.08.2007r.
Samochód sprowadzony został do Polski we wrześniu 2019r. i od tego czasu użytkowany był w jednych rękach.
Stan ogólny Peugeota oceniam na dobry.
Silnik odpala bez problemu, pracuje równo, przyspiesza dynamicznie, nie dymi, nie szarpie i nie przerywa.
Sprzęgło, oraz skrzynia biegów działają prawidłowo, biegi wchodzą lekko a przy ich zmianie nie występują żadne szarpnięcia.
W zawieszeniu, a także w układzie kierowniczym nie słychać żadnych niepokojących odgłosów.
Hamulce działają prawidłowo.
Auto prowadzi się pewnie, nie ściąga podczas jazdy ani przy, nie występują również żadne drgania na kierownicy.
Wizualny stan jak na zdjęciach.
Wnętrze jest czyste i zadbane, fotele nie są porwane ani poprzecierane.
Karoseria, progi, oraz podwozie bez większych śladów zużycia i korozji.
Rozrząd wymieniony był przy przebiegu 302730km
Olej wymieniony przy przebiegu 338940km
Świeżo zrobione badanie techniczne, które ważne jest do 08.06.2027r.
Ubezpieczenie OC aktualne do 03.09.2026r.
Wyposażenie:
> Klimatyzacja Automatyczna Dwustrefowa-Sprawna
> Elektrycznie sterowane szyby
> Elektrycznie regulowane i podgrzewane lusterka
> Panoramiczny dach z elektrycznie rozsuwaną roletą
> Kontrola trakcji
> Czujnik deszczu
> Czujnik zmierzchu
> Poduszki powietrzne
> Komputer pokładowy
> ABS
> Immobilizer
> Centralny zamek na pilota
> Autoalarm
> Regulacja kierownicy
> Elektrycznie regulowana wysokość świateł
> Regulacja wysokości foteli
> Aluminiowe felgi
> Fabryczne Radio na CD sterowane przy kierownicy
> Isofixy
> Fotochromatyczne lusterko wsteczne
> Stoliki tylnych foteli
> Halogeny
> Hak wbity w dowód
Samochód do obejrzenia w Lublinie.
Zapraszam do kontaktu.', 6400.00, false, 'Stroma 30, Bydgoszcz', 'aktywna', '2026-06-15 17:50:24', '2026-06-15 17:50:24', 1, 13, 9, true, 53.11513031, 17.98690799, NULL);
INSERT INTO public."Auctions" VALUES (2, 'Rower Kellys CityBike ambiente', 'Rower nieużywany od kilku lat. Wymaga przeglądu. Uszkodzona przednią lampa. Mam do niego dodatkowo dętkę i komplet błotników. Odbiór osobisty w Piasecznie. Cena do negocjacji', 400.00, true, 'Leśna, Józefówek', 'aktywna', '2026-06-15 17:52:36', '2026-06-15 17:52:36', 1, 15, 14, true, 51.48959257, 21.14685070, NULL);
INSERT INTO public."Auctions" VALUES (3, 'Valco Baby Snap 4, stan bardzo dobry', 'Sprzedam wózek spacerowy Valco Baby Snap 4 – lekki, wygodny i bardzo praktyczny

Wózek jest używany, w pełni sprawny, normalne ślady użytkowania widoczne na stelażu i kołach. Bardzo lekki i świetnie się prowadzi, idealny zarówno do miasta, jak i na spacery po nierównym terenie.

- szybkie składanie jedną ręką
- duża budka z filtrem UV
- regulowane oparcie – możliwość rozłożenia do pozycji leżącej
- pojemny kosz na zakupy
- skrętne przednie koła z możliwością blokady
- lekka konstrukcja – wygodny do przenoszenia i transportu

W zestawie: folia przeciwdeszczowa

Stan oceniam na bardzo dobry
Możliwy odbiór osobisty na Mokotowie.

Zapraszam do kontaktu :)', 390.00, false, 'Warszawa', 'aktywna', '2026-06-15 17:56:28', '2026-06-15 17:56:28', 2, 11, 16, true, 52.19367250, 21.04442820, NULL);
INSERT INTO public."Auctions" VALUES (4, '3 Figurki LEGO Marvel X-Men 6866 Deadpool Magneto Wolverine | Stan Kolekcjonerski', 'Na sprzedaż komplet trzech oryginalnych i niezwykle poszukiwanych figurek LEGO z serii Marvel Super Heroes / X-Men, pochodzących z kultowego zestawu 6866 Wolverine''s Chopper Showdown.

​Wszystkie figurki są w stanie kolekcjonerskim – brak jakichkolwiek pęknięć, przetarć czy uszkodzeń. Każda z nich sprzedawana jest z kompletnym, oryginalnym wyposażeniem i akcesoriami widocznymi w katalogu Bricklinka.

​W skład zestawu wchodzą:
​Deadpool (sh0032) – z pełnym wyposażeniem broń/katany
​Magneto - Red Outfit, Cloth Cape (sh0031) – stan peleryny idealny
​Wolverine - Hair, Dark Blue Hands (sh0017) – w komplecie z włosami i szponami
​
Figurki pochodzą z mojej prywatnej kolekcji. Zainteresowanym mogę przesłać dokładne zdjęcia makro.', 757.00, false, 'Aleja Wyzwolenia, Rzeszów', 'aktywna', '2026-06-15 17:59:09', '2026-06-15 17:59:09', 2, 11, 21, true, 50.05643338, 21.98043826, NULL);
INSERT INTO public."Auctions" VALUES (5, 'Yamaha xj900', 'Na sprzedaż Yamaha XJ900
Rok 1988
Napęd kardan
2włascicieli w De
Motocykl na chodzie,wymaga wymiany płynów eksploatacyjnych.
Jak wyjęty z garażu tak wyglada/ do dopieszczenia.
Dokumenty do rejestracji w Pl, faktura zwalniająca z podatku.
2xbrief i stare sprzed 2lat zaswiadczenie tuv.Innych dok brak
Mogę wysłać inne zdjęcia lub film.
Zapraszam do kontaktu telefonicznego.
Proszę nie pisać smsow
Możliwy transport.
Nie interesuje mnie zmiana.
Negocjacja tylko na miejscu.', 3200.00, false, 'Mokra Prawa', 'aktywna', '2026-06-15 18:04:27', '2026-06-15 18:04:27', 4, 14, 25, true, 52.01734587, 20.18646246, NULL);
INSERT INTO public."Auctions" VALUES (7, 'Osoba na stanowisko nauczyciel wychowania przedszkolnego', 'Niepubliczne Przedszkole poszukuje osoby na stanowisko nauczyciela z pasją i powołaniem, otwartego na nowe wyzwania, elastycznego i empatycznego.

Oferujemy stałą, stabilną pracę w miłym zespole (nauczycie, PS i pomoc), benefity pracownicze, pracę max 6-7 h dziennie (plus przerwy na przygotowanie do zajęć, praca własna), bez pracy w domu. Dodatkowo otrzymasz wsparcie zespołu specjalistów i spokojny onboarding do zespołu i pracy w grupie, mamy budżet na szkolenia, które potrzebujesz aby się rozwijać.

Szukamy osoby z potwierdzonymi kwalifikacjami (ukończone studia lub ostatni rok: Pedagogika przedszkolna i wczesnoszkolna)', 0.00, true, 'Łanowa, Rzeszów', 'aktywna', '2026-06-15 18:09:58', '2026-06-15 18:11:04', 4, 6, 29, true, 50.06524960, 21.94908143, 'do uzgodnienia');
INSERT INTO public."Auctions" VALUES (6, 'specjalista ds. dotacji', 'Zakres obowiązków:

    przygotowywanie i rozliczanie wniosków o dofinansowanie oraz wniosków o płatność,

    obsługa programów dotacyjnych, w szczególności „Czyste Powietrze”, „Mój Prąd” oraz „Moje Ciepło”,

    przygotowywanie korekt, uzupełnień, aneksów oraz pism do instytucji finansujących,

    kontakt telefoniczny i mailowy z klientami,

    wyjaśnianie klientom procedur oraz wymaganej dokumentacji,

    kompletowanie i weryfikacja dokumentów niezbędnych do uzyskania dofinansowania,

    monitorowanie terminów i prowadzenie dokumentacji projektowej,

    współpraca z innymi działami firmy w zakresie realizowanych inwestycji,

    obsługa korespondencji oraz wsparcie administracyjne procesów dotacyjnych.

Wymagania:

    doświadczenie na podobnym stanowisku,

    dobra organizacja pracy oraz samodzielność w działaniu,

    umiejętność pracy z dokumentacją i dbałość o szczegóły,

    komunikatywność oraz wysoka kultura osobista,

    znajomość pakietu MS Office,

    mile widziana znajomość programów dotacyjnych związanych z termomodernizacją i odnawialnymi źródłami energii.', 4000.00, false, 'Hetmańska, Rzeszów', 'aktywna', '2026-06-15 18:07:12', '2026-06-15 18:11:10', 4, 5, 1, true, 50.01647591, 21.99302675, 'brutto/mies.');
INSERT INTO public."Auctions" VALUES (8, 'Nowoczesnym fotel uszak', 'Stylowy, nowoczesny fotel uszak. Wygodny i elegancki mebel, który będzie pięknie prezentował się w salonie lub sypialni. Wykonany z wysokiej jakości materiałów, zapewniających długotrwałe użytkowanie.
•Prosto od producenta.
•Szybki termin realizacji.
•Wysyłka na terenie całego kraju (+100zł)
Więcej informacji w wiadomości prywatnej.', 600.00, false, 'Chłopska 20, Częstochowa', 'aktywna', '2026-06-15 18:12:52', '2026-06-15 18:12:52', 1, 9, 30, true, 50.76703904, 19.03747570, NULL);
INSERT INTO public."Auctions" VALUES (9, 'Zabawka jaszczurka 3D', 'Jaszczurka drukowana 3D – ruchoma, elastyczna zabawka

Sprzedam ruchomą jaszczurkę wykonaną metodą druku 3D. Model składa się z połączonych segmentów, dzięki czemu wygina się i porusza w bardzo ciekawy sposób.

Wydruk 3D wysokiej jakości
Ruchome segmenty na całej długości
Świetna jakość wykonania Idealna jako zabawka, dekoracja lub prezent
Kolor: żółty , czerwony , fioletowo- różowy, zielony , biały, niebieski ,czarny, szary, czerwono - czarny
- długość 24 cm
Stan: nowy', 30.00, false, 'Białostocka 27A, Radom', 'aktywna', '2026-06-15 18:17:38', '2026-06-15 18:17:38', 1, 11, 33, true, 51.39115119, 21.19182587, NULL);
INSERT INTO public."Auctions" VALUES (10, 'Jeździk Little Tikes biedronka', 'Dodatkowa wkładka pod nogi dla malutkich dzieci.

Tylko odbiór osobisty.

Jeździk Little Tikes w stanie używanym. Idealny dla maluchów, które lubią aktywnie spędzać czas na świeżym powietrzu. Solidna konstrukcja zapewnia bezpieczną zabawę. Produkt godny polecenia dla najmłodszych miłośników pojazdów.', 100.00, false, 'Kielecka 117, Radom', 'aktywna', '2026-06-15 18:22:31', '2026-06-15 18:22:31', 1, 11, 35, true, 51.39372200, 21.10290539, NULL);
INSERT INTO public."Auctions" VALUES (11, 'Plecak Nike elite', 'Plecak Nike elite, nowy z metką, kolor czarny, bardzo ładny, zainteresowanych zapraszam do kontaktu', 160.00, false, 'Błędowo', 'aktywna', '2026-06-15 18:31:05', '2026-06-15 18:31:05', 3, 10, 38, true, 53.23892064, 21.21276867, NULL);
INSERT INTO public."Auctions" VALUES (12, 'Okazja !!! Skoda OCTAVIA III VRS 2.0 TDI', 'Witam,
Na sprzedaż z racji kupna nowego oferuje moje prywatne auto skoda octavia VRS , wersja challenge najbogatsza, ma praktycznie wszystko.
Auto jeżdżące w ciągłej eksploatacji
Jak już wspomniałem wcześniej kupiłam nowe
Stąd okazyjna cena
Zapraszam do kontaktu i na oględziny', 49900.00, false, 'Maliniec', 'aktywna', '2026-06-15 18:36:03', '2026-06-15 18:36:03', 3, 13, 42, true, 50.69750165, 22.20153820, NULL);
INSERT INTO public."Auctions" VALUES (13, 'Złoty naszyjnik z ametystami i perłami (7351)', 'Ekskluzywna zawieszka wykonana z żółtego złota próby 585 oraz elementów z żółtego złota próby 375, o łącznej masie 8,32 g. Centralną ozdobę stanowi efektowny naturalny ametyst o masie 9,56 ct, zachwycający głęboką fioletową barwą i wyjątkowym blaskiem.
Kompozycję uzupełniają dodatkowe ametysty o łącznej masie 1,91 ct oraz perły, nadające biżuterii elegancji i ponadczasowego charakteru. Starannie wykonany projekt harmonijnie łączy szlachetne kamienie z ciepłym odcieniem złota, tworząc wyjątkową ozdobę o luksusowym wyglądzie.
Do zawieszki dołączony jest certyfikat potwierdzający autentyczność wyrobu oraz jego parametry. To wyjątkowy dodatek dla osób ceniących wysoką jakość wykonania, klasyczne piękno i unikatowy design', 4500.00, false, 'Dębina', 'aktywna', '2026-06-15 18:37:31', '2026-06-15 18:37:31', 3, 19, 47, true, 51.05796970, 17.43347179, NULL);
INSERT INTO public."Auctions" VALUES (14, 'Buty męskie adidas rozmiar 43,5', 'Bardzo wygodne buty męskie marki adidas, w rozmiarze 43,5. Kolor niebieski, długość wkładki: 27,5 cm', 60.00, true, 'Piastów 25b, Łojki', 'aktywna', '2026-06-15 18:59:42', '2026-06-15 18:59:42', 2, 20, 49, true, 50.78649093, 18.99353039, NULL);
INSERT INTO public."Auctions" VALUES (15, 'Magazynier', 'Szukam magazyniera', 0.00, true, '74, Zosin', 'aktywna', '2026-06-15 19:05:24', '2026-06-15 19:05:24', 1, 1, 1, false, 50.85941517, 24.12901640, 'do uzgodnienia');


--
-- TOC entry 3524 (class 0 OID 18276)
-- Dependencies: 243
-- Data for Name: AuctionsImages; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."AuctionsImages" VALUES (1, 10, 1, 1);
INSERT INTO public."AuctionsImages" VALUES (2, 11, 1, 2);
INSERT INTO public."AuctionsImages" VALUES (3, 12, 1, 3);
INSERT INTO public."AuctionsImages" VALUES (4, 13, 1, 4);
INSERT INTO public."AuctionsImages" VALUES (5, 15, 2, 1);
INSERT INTO public."AuctionsImages" VALUES (6, 17, 3, 1);
INSERT INTO public."AuctionsImages" VALUES (7, 18, 3, 2);
INSERT INTO public."AuctionsImages" VALUES (8, 19, 3, 3);
INSERT INTO public."AuctionsImages" VALUES (9, 20, 3, 4);
INSERT INTO public."AuctionsImages" VALUES (10, 22, 4, 1);
INSERT INTO public."AuctionsImages" VALUES (11, 23, 4, 2);
INSERT INTO public."AuctionsImages" VALUES (12, 24, 4, 3);
INSERT INTO public."AuctionsImages" VALUES (13, 26, 5, 1);
INSERT INTO public."AuctionsImages" VALUES (14, 27, 5, 2);
INSERT INTO public."AuctionsImages" VALUES (15, 28, 5, 3);
INSERT INTO public."AuctionsImages" VALUES (16, 31, 8, 1);
INSERT INTO public."AuctionsImages" VALUES (17, 32, 8, 2);
INSERT INTO public."AuctionsImages" VALUES (18, 34, 9, 1);
INSERT INTO public."AuctionsImages" VALUES (19, 36, 10, 1);
INSERT INTO public."AuctionsImages" VALUES (20, 37, 10, 2);
INSERT INTO public."AuctionsImages" VALUES (21, 39, 11, 1);
INSERT INTO public."AuctionsImages" VALUES (22, 40, 11, 2);
INSERT INTO public."AuctionsImages" VALUES (23, 41, 11, 3);
INSERT INTO public."AuctionsImages" VALUES (24, 43, 12, 1);
INSERT INTO public."AuctionsImages" VALUES (25, 44, 12, 2);
INSERT INTO public."AuctionsImages" VALUES (26, 45, 12, 3);
INSERT INTO public."AuctionsImages" VALUES (27, 46, 12, 4);
INSERT INTO public."AuctionsImages" VALUES (28, 48, 13, 1);


--
-- TOC entry 3518 (class 0 OID 18218)
-- Dependencies: 237
-- Data for Name: Chats; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Chats" VALUES (1, 1, 1, 2, '2026-06-15 17:54:04', NULL, false);
INSERT INTO public."Chats" VALUES (2, 4, 2, 4, '2026-06-15 18:01:32', NULL, false);
INSERT INTO public."Chats" VALUES (3, 6, 4, 1, '2026-06-15 18:28:52', NULL, false);
INSERT INTO public."Chats" VALUES (4, 5, 4, 1, '2026-06-15 18:28:56', NULL, false);
INSERT INTO public."Chats" VALUES (5, 10, 1, 3, '2026-06-15 18:32:33', NULL, false);


--
-- TOC entry 3516 (class 0 OID 18201)
-- Dependencies: 235
-- Data for Name: FollowedAuctions; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."FollowedAuctions" VALUES (1, 2, 1);
INSERT INTO public."FollowedAuctions" VALUES (2, 4, 4);
INSERT INTO public."FollowedAuctions" VALUES (3, 1, 6);
INSERT INTO public."FollowedAuctions" VALUES (4, 1, 5);
INSERT INTO public."FollowedAuctions" VALUES (5, 3, 10);
INSERT INTO public."FollowedAuctions" VALUES (6, 3, 7);
INSERT INTO public."FollowedAuctions" VALUES (7, 3, 9);
INSERT INTO public."FollowedAuctions" VALUES (8, 3, 8);


--
-- TOC entry 3520 (class 0 OID 18240)
-- Dependencies: 239
-- Data for Name: Messages; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Messages" VALUES (1, 1, 'Czy jest możliwość opuszczenia ceny?', '2026-06-15 17:53:38', 2, false);
INSERT INTO public."Messages" VALUES (2, 1, 'Proszę', '2026-06-15 17:54:04', 2, false);
INSERT INTO public."Messages" VALUES (3, 2, 'Sprzedawać zabawki dla dzieci za taką cenę? Chyba cię coś boli. To nie jest warte nawet 10 zł.', '2026-06-15 18:01:01', 4, false);
INSERT INTO public."Messages" VALUES (4, 2, 'Oszukiwać ludzi', '2026-06-15 18:01:25', 4, false);
INSERT INTO public."Messages" VALUES (5, 2, 'Wstydziłbyś się', '2026-06-15 18:01:32', 4, false);
INSERT INTO public."Messages" VALUES (6, 3, 'Popraw sobie zdjęcie', '2026-06-15 18:27:23', 1, false);
INSERT INTO public."Messages" VALUES (7, 5, 'Podoba się mojemu dziecku, chętnie kupię', '2026-06-15 18:31:49', 3, false);
INSERT INTO public."Messages" VALUES (8, 5, 'Mogę jutro odebrać na miejscu', '2026-06-15 18:32:33', 3, false);


--
-- TOC entry 3522 (class 0 OID 18259)
-- Dependencies: 241
-- Data for Name: Ratings; Type: TABLE DATA; Schema: public; Owner: laravel_user
--

INSERT INTO public."Ratings" VALUES (1, 3, 1, 1);
INSERT INTO public."Ratings" VALUES (2, 4, 1, 1);
INSERT INTO public."Ratings" VALUES (3, 2, 0, 1);
INSERT INTO public."Ratings" VALUES (4, 4, 0, 2);
INSERT INTO public."Ratings" VALUES (5, 1, 1, 2);
INSERT INTO public."Ratings" VALUES (6, 1, 1, 3);
INSERT INTO public."Ratings" VALUES (7, 2, 1, 3);
INSERT INTO public."Ratings" VALUES (8, 4, 1, 3);


--
-- TOC entry 3530 (class 0 OID 0)
-- Dependencies: 242
-- Name: AuctionsImages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."AuctionsImages_id_seq"', 28, true);


--
-- TOC entry 3531 (class 0 OID 0)
-- Dependencies: 232
-- Name: Auctions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Auctions_id_seq"', 15, true);


--
-- TOC entry 3532 (class 0 OID 0)
-- Dependencies: 230
-- Name: Categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Categories_id_seq"', 20, true);


--
-- TOC entry 3533 (class 0 OID 0)
-- Dependencies: 236
-- Name: Chats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Chats_id_seq"', 5, true);


--
-- TOC entry 3534 (class 0 OID 0)
-- Dependencies: 234
-- Name: FollowedAuctions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."FollowedAuctions_id_seq"', 8, true);


--
-- TOC entry 3535 (class 0 OID 0)
-- Dependencies: 228
-- Name: Images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Images_id_seq"', 50, true);


--
-- TOC entry 3536 (class 0 OID 0)
-- Dependencies: 238
-- Name: Messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Messages_id_seq"', 8, true);


--
-- TOC entry 3537 (class 0 OID 0)
-- Dependencies: 240
-- Name: Ratings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Ratings_id_seq"', 33, true);


--
-- TOC entry 3538 (class 0 OID 0)
-- Dependencies: 226
-- Name: Users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel_user
--

SELECT pg_catalog.setval('public."Users_id_seq"', 4, true);


-- Completed on 2026-06-15 19:24:29 UTC

--
-- PostgreSQL database dump complete
--

\unrestrict YTLnjkHJiKeJMfVlu4FuMpxK7VE7Nmy4oHuTCK5uOM4dUOF51l3HeU1VRkyz2kI

