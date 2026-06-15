
## Diagram ERD

### Tabela `Users`

Konta użytkowników platformy (zwykli użytkownicy i administratorzy).

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator użytkownika |
| `firstName` | VARCHAR(100) | Imię |
| `lastName` | VARCHAR(100) | Nazwisko |
| `email` | VARCHAR(200), UNIQUE | Adres e-mail (login) |
| `phoneNumber` | VARCHAR(12) | Numer telefonu |
| `password` | VARCHAR(255) | Hasło (hash bcrypt) |
| `joinedAt` | TIMESTAMP | Data rejestracji |
| `lastOnline` | TIMESTAMP | Ostatnia aktywność |
| `isAdmin` | BOOLEAN | Czy użytkownik ma dostęp do panelu administratora |
| `isMainAdmin` | BOOLEAN, default `false` | Czy to główny administrator (nieusuwalny) |

**Relacje:**
- 1 → N `Auctions` (właściciel ogłoszeń, kolumna `userId`)
- 1 → N `Chats` jako sprzedawca (`sellerId`) lub kupujący (`buyerId`)
- 1 → N `Messages` (nadawca, kolumna `senderId`)
- 1 → N `FollowedAuctions` (obserwowane ogłoszenia)
- 1 → N `Ratings` jako oceniany (`sellerId`) lub oceniający (`userId`)

---

### Tabela `Images`

Metadane przechowywanych plików graficznych (zdjęcia ogłoszeń i kategorii).

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator obrazu |
| `filename` | TEXT | Nazwa / ścieżka pliku w storage |
| `uploadedAt` | TIMESTAMP | Data wgrania |
| `uuid` | UUID, nullable | Opcjonalny identyfikator pliku |

**Relacje:**
- 1 → N `Auctions` (miniatura ogłoszenia, kolumna `imageId`)
- 1 → N `AuctionsImages` (zdjęcia w galerii ogłoszenia)
- 1 → N `Categories` (ikona kategorii, kolumna `imageId`)

---

### Tabela `Categories`

Drzewo kategorii ogłoszeń (np. Motoryzacja → Samochody osobowe).

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator kategorii |
| `name` | VARCHAR(150) | Nazwa kategorii |
| `imageId` | BIGINT (FK → `Images`), nullable | Zdjęcie kategorii; przy usunięciu obrazu → `NULL` |
| `parentId` | BIGINT (FK → `Categories`), nullable | Kategoria nadrzędna; `NULL` = kategoria główna |

**Relacje:**
- N → 1 `Categories` (self-reference przez `parentId` — struktura drzewiasta)
- N → 1 `Images` (ikona kategorii)
- 1 → N `Auctions` (ogłoszenia w danej kategorii)

**Uwaga biznesowa:** kategoria **Praca** i wszystkie jej podkategorie wymagają akceptacji administratora przed publikacją ogłoszenia (`approved = false`).

---

### Tabela `Auctions`

Główna encja systemu — ogłoszenia / aukcje.

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator ogłoszenia |
| `name` | TEXT | Tytuł ogłoszenia |
| `description` | TEXT, nullable | Opis oferty |
| `price` | NUMERIC(10,2) | Cena lub wynagrodzenie |
| `negotiable` | BOOLEAN | Czy cena do negocjacji / „do uzgodnienia” |
| `location` | VARCHAR(200), nullable | Opis lokalizacji (miasto, adres tekstowy) |
| `latitude` | NUMERIC(10,8), nullable | Szerokość geograficzna (mapa) |
| `longitude` | NUMERIC(11,8), nullable | Długość geograficzna (mapa) |
| `status` | VARCHAR(100) | Status ogłoszenia: `aktywna` lub `zakończona` |
| `approved` | BOOLEAN, default `true` | Czy ogłoszenie zaakceptowane przez admina |
| `salaryType` | VARCHAR(50), nullable | Rodzaj wynagrodzenia (tylko kategoria Praca): `brutto/h`, `brutto/mies.`, `netto/h`, `do uzgodnienia` |
| `createdAt` | TIMESTAMP | Data utworzenia |
| `updatedAt` | TIMESTAMP | Data ostatniej modyfikacji |
| `userId` | BIGINT (FK → `Users`) | Właściciel ogłoszenia |
| `categoryId` | BIGINT (FK → `Categories`) | Kategoria ogłoszenia |
| `imageId` | BIGINT (FK → `Images`) | Miniatura (zdjęcie główne) |

**Relacje:**
- N → 1 `Users` (właściciel)
- N → 1 `Categories`
- N → 1 `Images` (miniatura)
- 1 → N `AuctionsImages` (dodatkowe zdjęcia w galerii)
- 1 → N `Chats` (rozmowy przy ogłoszeniu)
- 1 → N `FollowedAuctions` (użytkownicy obserwujący ogłoszenie)

**Widoczność publiczna:** ogłoszenie jest widoczne na liście, gdy `status = 'aktywna'` **oraz** `approved = true`.

---

### Tabela `AuctionsImages`

Tabela łącząca ogłoszenia z dodatkowymi zdjęciami (galeria poza miniaturą).

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator wpisu |
| `imageId` | BIGINT (FK → `Images`), nullable | Zdjęcie w galerii |
| `auctionId` | BIGINT (FK → `Auctions`), nullable | Powiązane ogłoszenie |
| `order` | INTEGER, nullable | Kolejność wyświetlania zdjęcia |

**Relacja:** N ↔ N między `Auctions` a `Images` (implementacja relacji many-to-many z atrybutem `order`).

---

### Tabela `Chats`

Wątki rozmów między kupującym a sprzedawcą w kontekście konkretnego ogłoszenia.

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator czatu |
| `auctionId` | BIGINT (FK → `Auctions`), nullable | Ogłoszenie, którego dotyczy rozmowa |
| `sellerId` | BIGINT (FK → `Users`) | Sprzedawca (właściciel ogłoszenia) |
| `buyerId` | BIGINT (FK → `Users`) | Kupujący / osoba zainteresowana |

**Relacje:**
- N → 1 `Auctions`
- N → 1 `Users` (sprzedawca)
- N → 1 `Users` (kupujący)
- 1 → N `Messages`

---

### Tabela `Messages`

Pojedyncze wiadomości w ramach czatu.

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator wiadomości |
| `chatId` | BIGINT (FK → `Chats`), nullable | Czat, do którego należy wiadomość |
| `text` | TEXT | Treść wiadomości |
| `sentAt` | TIMESTAMP | Data i godzina wysłania |
| `senderId` | BIGINT (FK → `Users`) | Nadawca wiadomości |

**Relacje:**
- N → 1 `Chats`
- N → 1 `Users` (nadawca)

---

### Tabela `FollowedAuctions`

Obserwowane ogłoszenia — użytkownik może dodać cudze ogłoszenie do listy ulubionych.

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator wpisu |
| `userId` | BIGINT (FK → `Users`) | Użytkownik obserwujący |
| `auctionId` | BIGINT (FK → `Auctions`) | Obserwowane ogłoszenie |

**Relacja:** N ↔ N między `Users` a `Auctions`.

---

### Tabela `Ratings`

Oceny sprzedawców wystawiane przez innych użytkowników.

| Kolumna | Typ | Opis |
|---------|-----|------|
| `id` | BIGINT (PK) | Identyfikator oceny |
| `sellerId` | BIGINT (FK → `Users`) | Oceniany sprzedawca |
| `rating` | INTEGER, nullable | Wartość oceny (np. 1–5) |
| `userId` | BIGINT (FK → `Users`), nullable | Użytkownik wystawiający ocenę |

### Podsumowanie relacji

| Relacja | Typ | Opis |
|---------|-----|------|
| `Users` → `Auctions` | 1:N | Jeden użytkownik ma wiele ogłoszeń |
| `Categories` → `Categories` | 1:N (self) | Drzewo kategorii (rodzic → dzieci) |
| `Categories` → `Auctions` | 1:N | Kategoria grupuje ogłoszenia |
| `Images` → `Auctions` | 1:N | Obraz jako miniatura ogłoszenia |
| `Auctions` ↔ `Images` | N:M | Galeria zdjęć przez `AuctionsImages` |
| `Images` → `Categories` | 1:N | Ikona kategorii |
| `Auctions` → `Chats` | 1:N | Przy ogłoszeniu powstają rozmowy |
| `Users` → `Chats` | 1:N | Użytkownik uczestniczy jako kupujący lub sprzedawca |
| `Chats` → `Messages` | 1:N | Czat składa się z wiadomości |
| `Users` → `Messages` | 1:N | Nadawca wiadomości |
| `Users` ↔ `Auctions` | N:M | Obserwowane ogłoszenia przez `FollowedAuctions` |
| `Users` → `Ratings` | 1:N | Sprzedawca otrzymuje oceny; użytkownik je wystawia |
