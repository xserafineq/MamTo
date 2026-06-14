# MamTo REST API

Interfejs REST API działa obok aplikacji webowej (Blade). Wszystkie endpointy mają prefix `/api`.

## Autentykacja

Chronione endpointy wymagają nagłówka:

```
Authorization: Bearer {token}
Accept: application/json
```

Token otrzymujesz po `POST /api/register` lub `POST /api/login`.

## Endpointy publiczne

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/categories` | Kategorie główne, drzewo z licznikami, lista do formularza |
| GET | `/api/auctions` | Lista ogłoszeń (`category`, `q`, `price_min`, `price_max`, `sort`, `per_page`) |
| GET | `/api/auctions/latest` | Najnowsze ogłoszenia (`limit`, domyślnie 6) |
| GET | `/api/auctions/{id}` | Szczegóły ogłoszenia |
| POST | `/api/register` | Rejestracja |
| POST | `/api/login` | Logowanie |

### Przykład: lista aukcji

```
GET /api/auctions?category=2&q=iphone&sort=newest&per_page=10
```

## Endpointy chronione (wymagany token)

### Użytkownik

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/user` | Zalogowany użytkownik |
| POST | `/api/logout` | Wylogowanie (usuwa bieżący token) |
| GET | `/api/profile` | Profil |
| PUT | `/api/profile` | Aktualizacja profilu |
| PUT | `/api/profile/password` | Zmiana hasła |

### Ogłoszenia

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/my-auctions` | Moje ogłoszenia + liczniki |
| POST | `/api/auctions` | Utworzenie (`multipart/form-data`) |
| PUT | `/api/auctions/{id}` | Edycja własnego ogłoszenia |
| POST | `/api/auctions/{id}/close` | Zamknięcie ogłoszenia |

Pola formularza aukcji: `name`, `description`, `categoryId`, `price`, `negotiable`, `salaryType` (dla Praca), `location`, `thumbnail`, `images[]`.

### Obserwowane

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/followed-auctions` | Lista obserwowanych |
| POST | `/api/auctions/{id}/follow` | Dodaj do obserwowanych |
| DELETE | `/api/auctions/{id}/follow` | Usuń z obserwowanych |

### Czat

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/chats` | Lista rozmów |
| GET | `/api/chats/{id}` | Szczegóły czatu z wiadomościami |
| POST | `/api/auctions/{id}/chat` | Rozpocznij czat przy ogłoszeniu |
| POST | `/api/chats/{id}/messages` | Wyślij wiadomość (`text`) |

## Endpointy administratora (token + `isAdmin`)

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/admin/auctions` | Wszystkie aukcje |
| PUT | `/api/admin/auctions/{id}` | Edycja aukcji (+ pole `status`) |
| GET | `/api/admin/approvals` | Ogłoszenia oczekujące na akceptację |
| POST | `/api/admin/approvals/{id}/approve` | Akceptacja ogłoszenia |
| GET | `/api/admin/administrators` | Lista administratorów |
| PUT | `/api/admin/administrators/{id}/permissions` | Zmiana uprawnień (`isAdmin`) — tylko główny admin |

### Kategorie

| Metoda | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/admin/categories` | Drzewo kategorii + płaska lista (`tree`, `data`) |
| POST | `/api/admin/categories` | Dodaj kategorię (`multipart/form-data`) |
| PUT | `/api/admin/categories/{id}` | Edytuj nazwę i/lub zdjęcie kategorii |
| DELETE | `/api/admin/categories/{id}` | Usuń kategorię |

Pola przy tworzeniu (`POST`):

- `name` — wymagane, max 150 znaków
- `parentId` — opcjonalne; ID kategorii nadrzędnej (brak = kategoria główna)
- `image` — opcjonalne; JPG, PNG lub WEBP, max 5 MB

Pola przy edycji (`PUT`):

- `name` — wymagane
- `image` — opcjonalne; nowe zdjęcie zastępuje poprzednie

Usunięcie zwraca `422`, gdy kategoria ma podkategorie lub przypisane ogłoszenia.


