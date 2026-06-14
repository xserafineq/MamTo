<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    private const CITIES = [
        ['name' => 'Warszawa', 'latitude' => 52.2297000, 'longitude' => 21.0122000],
        ['name' => 'Kraków', 'latitude' => 50.0647000, 'longitude' => 19.9450000],
        ['name' => 'Gdańsk', 'latitude' => 54.3520000, 'longitude' => 18.6466000],
        ['name' => 'Wrocław', 'latitude' => 51.1079000, 'longitude' => 17.0385000],
        ['name' => 'Poznań', 'latitude' => 52.4064000, 'longitude' => 16.9252000],
        ['name' => 'Łódź', 'latitude' => 51.7592000, 'longitude' => 19.4560000],
        ['name' => 'Szczecin', 'latitude' => 53.4285000, 'longitude' => 14.5528000],
        ['name' => 'Lublin', 'latitude' => 51.2465000, 'longitude' => 22.5684000],
        ['name' => 'Katowice', 'latitude' => 50.2649000, 'longitude' => 19.0238000],
        ['name' => 'Białystok', 'latitude' => 53.1325000, 'longitude' => 23.1688000],
        ['name' => 'Rzeszów', 'latitude' => 50.0412000, 'longitude' => 21.9991000],
        ['name' => 'Tarnobrzeg', 'latitude' => 50.5730400, 'longitude' => 21.6793700],
    ];

    private const CONTENT = [
        [
            'titl' => 'Smartfon Samsung 128GB Czarny | Stan Idealny + Etui Gratis!',
            'desc' => 'Telefon od nowości noszony w etui i z profesjonalnym szkłem hartowanym na ekranie, dzięki czemu wygląda jak nowy. W pełni sprawny, z oryginalnym pudełkiem i fabrycznym zestawem akcesoriów w komplecie.',
            'category' => 'Smartfony',
        ],
        [
            'titl' => 'Skórzana Kurtka',
            'desc' => 'Ponadczasowa ramoneska wykonana z wysokiej jakości, miękkiej skóry naturalnej, która świetnie dopasowuje się do sylwetki. Stan oceniam na bardzo dobry – nie posiada żadnych przetarć ani uszkodzeń podszewki.',
            'category' => 'Odzież męska',
        ],
        [
            'titl' => 'Designerski Fotel Uszak',
            'desc' => 'Niezwykle wygodny i stylowy fotel, który idealnie dopełni wnętrze każdego salonu lub kącika do czytania. Tapicerka jest czysta, zadbana i całkowicie wolna od śladów użytkowania przez zwierzęta.',
            'category' => 'Meble',
        ],
        [
            'titl' => 'Bestseller "Shuggie Bain" - Douglas Stuar',
            'desc' => 'Wciągająca powieść, która zdobyła uznanie czytelników na całym świecie, teraz dostępna w twardej oprawie. Egzemplarz jest zupełnie nowy, nigdy nieczytany i nie posiada żadnych zagięć ani zarysowań.',
            'category' => 'Wyposażenie wnętrz',
        ],
        [
            'titl' => 'Rower Górski MTB Kross Hexagon 5.0 Koła 29"',
            'desc' => 'Solidny rower górski wyposażony w niezawodny osprzęt Shimano, idealny zarówno na leśne ścieżki, jak i miejskie trasy. Sprzęt przeszedł kompleksowy przegląd przedsezonowy, ma wymieniony łańcuch i wyregulowane hamulce.',
            'category' => 'Rowery',
        ],
        [
            'titl' => 'Konsola Sony PlayStation 5 Slim 1TB | 2 Pady + Gra w Zestawie',
            'desc' => 'Sprzęt w stanie idealnym, używany sporadycznie przez dorosłego użytkownika, w 100% sprawny technicznie. Konsola pracuje cicho, nie przegrzewa się, a w komplecie znajduje się pełne fabryczne okablowanie.',
            'category' => 'Gaming',
        ],
        [
            'titl' => 'Ekspres Ciśnieniowy DeLonghi Magnifica S | Po Przeglądzie, Super Stan',
            'desc' => 'Niezawodny ekspres automatyczny, który parzy doskonałą, aromatyczną kawę czarną oraz mleczną za jednym dotknięciem. Urządzenie było regularnie czyszczone i odkamieniane wyłącznie oryginalnymi środkami producenta.',
            'category' => 'Wyposażenie wnętrz',
        ],
        [
            'titl' => 'Zegarek Męski Casio G-Shock Classic | Wstrząsoodporny, Box, Sklep',
            'desc' => 'Legendarny, pancerny zegarek sportowy, który idealnie sprawdzi się w każdych, nawet najtrudniejszych warunkach. Posiada minimalne ślady użytkowania, a bateria oraz wszystkie funkcje działają bez zarzutu.',
            'category' => 'Biżuteria i zegarki',
        ],
        [
            'titl' => 'Słuchawki Bezprzewodowe JBL Tune 510BT Czane | Bluetooth, Mocny Bas',
            'desc' => 'Lekkie i wygodne słuchawki nauszne, które zachwycają czystym brzmieniem oraz niezwykle wydajną baterią działającą do 40 godzin. Stan wizualny oraz techniczny jest perfekcyjny, wyglądają jak prosto z salonu.',
            'category' => 'TV i audio',
        ],
        [
            'titl' => 'Klocki LEGO Technic 42151 Bugatti Bolide | 100% Kompletny, Instrukcja',
            'desc' => 'Fantastyczny model kolekcjonerski, który został złożony tylko raz i służył wyłącznie jako ozdoba na półce. Zestaw zawiera wszystkie oryginalne elementy, zapasowe klocki, instrukcję składania oraz fabryczne pudełko.',
            'category' => 'Zabawki',
        ],
    ];

    public function run(): void
    {
        $users = User::all();
        $leafCategories = Category::whereDoesntHave('children')->get()->keyBy('name');
        $image = Image::first();

        if ($users->isEmpty() || $leafCategories->isEmpty() || ! $image) {
            $this->command->warn('Uruchom najpierw UserSeeder, CategorySeeder i ImageSeeder.');

            return;
        }

        $missingCategories = collect(self::CONTENT)
            ->pluck('category')
            ->unique()
            ->diff($leafCategories->keys());

        if ($missingCategories->isNotEmpty()) {
            $this->command->error('Brak kategorii liści: ' . $missingCategories->implode(', '));

            return;
        }

        $statuses = ['aktywna', 'zakończona'];

        for ($i = 0; $i < 30; $i++) {
            $city = fake()->randomElement(self::CITIES);
            $createdAt = fake()->dateTimeBetween('-21 days', 'now');
            $content = fake()->randomElement(self::CONTENT);
            $category = $leafCategories->get($content['category']);

            Auction::forceCreate([
                'name' => $content['titl'],
                'description' => $content['desc'],
                'price' => fake()->randomFloat(2, 50, 50000),
                'negotiable' => fake()->boolean(),
                'location' => $city['name'],
                'latitude' => $city['latitude'],
                'longitude' => $city['longitude'],
                'status' => fake()->randomElement($statuses),
                'approved' => true,
                'userId' => $users->random()->id,
                'categoryId' => $category->id,
                'imageId' => $image->id,
                'createdAt' => $createdAt,
                'updatedAt' => $createdAt,
            ]);
        }
    }
}
