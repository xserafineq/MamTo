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

    public function run(): void
    {
        $users = User::all();
        $categories = Category::whereDoesntHave('children')->get();
        $image = Image::first();

        if ($users->isEmpty() || $categories->isEmpty() || !$image) {
            $this->command->warn('Uruchom najpierw UserSeeder, CategorySeeder i ImageSeeder.');

            return;
        }

        $statuses = ['aktywna', 'zakończona'];

        for ($i = 0; $i < 30; $i++) {
            $city = fake()->randomElement(self::CITIES);
            $createdAt = fake()->dateTimeBetween('-7 days', 'now');

            Auction::forceCreate([
                'name' => fake()->sentence(4),
                'description' => fake()->paragraph(100),
                'price' => fake()->randomFloat(2, 50, 50000),
                'negotiable' => fake()->boolean(),
                'location' => $city['name'],
                'latitude' => $this->jitterCoordinate($city['latitude'], 0.06),
                'longitude' => $this->jitterCoordinate($city['longitude'], 0.09),
                'status' => fake()->randomElement($statuses),
                'approved' => true,
                'userId' => $users->random()->id,
                'categoryId' => $categories->random()->id,
                'imageId' => $image->id,
                'createdAt' => $createdAt,
                'updatedAt' => $createdAt,
            ]);
        }
    }

    private function jitterCoordinate(float $value, float $maxOffset): float
    {
        return round($value + fake()->randomFloat(6, -$maxOffset, $maxOffset), 6);
    }
}
