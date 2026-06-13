<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTree([
            [
                'name' => 'Motoryzacja',
                'children' => [
                    [
                        'name' => 'Samochody',
                        'children' => [
                            ['name' => 'Osobowe'],
                            ['name' => 'Ciężarowe'],
                            ['name' => 'Dostawcze'],
                        ],
                    ],
                    [
                        'name' => 'Motocykle',
                        'children' => [
                            ['name' => 'Sportowe'],
                            ['name' => 'Turystyczne'],
                            ['name' => 'Skutery'],
                        ],
                    ],
                    ['name' => 'Części samochodowe'],
                    ['name' => 'Opony i felgi'],
                ],
            ],
            [
                'name' => 'Elektronika',
                'children' => [
                    [
                        'name' => 'Telefony',
                        'children' => [
                            ['name' => 'Smartfony'],
                            ['name' => 'Telefony komórkowe'],
                            ['name' => 'Akcesoria'],
                        ],
                    ],
                    [
                        'name' => 'Komputery',
                        'children' => [
                            ['name' => 'Laptopy'],
                            ['name' => 'Komputery stacjonarne'],
                            ['name' => 'Tablety'],
                        ],
                    ],
                    ['name' => 'TV i audio'],
                    ['name' => 'Gaming'],
                ],
            ],
            [
                'name' => 'Dom i Ogród',
                'children' => [
                    ['name' => 'Meble'],
                    ['name' => 'Wyposażenie wnętrz'],
                    ['name' => 'Narzędzia'],
                    [
                        'name' => 'Ogród',
                        'children' => [
                            ['name' => 'Rośliny'],
                            ['name' => 'Meble ogrodowe'],
                            ['name' => 'Grille'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Moda',
                'children' => [
                    ['name' => 'Odzież damska'],
                    ['name' => 'Odzież męska'],
                    ['name' => 'Obuwie'],
                    ['name' => 'Biżuteria i zegarki'],
                ],
            ],
            [
                'name' => 'Sport i Hobby',
                'children' => [
                    ['name' => 'Rowery'],
                    ['name' => 'Fitness'],
                    ['name' => 'Sporty zespołowe'],
                    [
                        'name' => 'Turystyka',
                        'children' => [
                            ['name' => 'Namioty'],
                            ['name' => 'Plecaki'],
                            ['name' => 'Sprzęt wspinaczkowy'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Dla dzieci',
                'children' => [
                    ['name' => 'Zabawki'],
                    ['name' => 'Wózki i foteliki'],
                    ['name' => 'Ubranka dziecięce'],
                    ['name' => 'Rowery i hulajnogi'],
                ],
            ],
            [
                'name' => 'Praca',
                'children' => [
                    ['name' => 'IT i technologie'],
                    ['name' => 'Handel i sprzedaż'],
                    ['name' => 'Produkcja'],
                    ['name' => 'Biuro i administracja'],
                    ['name' => 'Inne oferty pracy'],
                ],
            ],
            [
                'name' => 'Usługi',
                'children' => [
                    ['name' => 'Budowlane'],
                    ['name' => 'Transport i przeprowadzki'],
                    ['name' => 'IT i programowanie'],
                    ['name' => 'Korepetycje'],
                ],
            ],
            [
                'name' => 'Nieruchomości',
                'children' => [
                    ['name' => 'Mieszkania'],
                    ['name' => 'Domy'],
                    ['name' => 'Działki'],
                    ['name' => 'Lokale użytkowe'],
                ],
            ],
            [
                'name' => 'Zwierzęta',
                'children' => [
                    ['name' => 'Psy'],
                    ['name' => 'Koty'],
                    ['name' => 'Akcesoria dla zwierząt'],
                    ['name' => 'Akwarystyka'],
                ],
            ],
        ]);
    }

    private function seedTree(array $nodes, ?int $parentId = null): void
    {
        foreach ($nodes as $node) {
            $category = Category::create([
                'name' => $node['name'],
                'parentId' => $parentId,
                'imageId' => null,
            ]);

            if (!empty($node['children'])) {
                $this->seedTree($node['children'], $category->id);
            }
        }
    }
}
