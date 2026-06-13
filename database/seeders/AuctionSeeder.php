<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::whereDoesntHave('children')->get();
        $image = Image::first();

        if ($users->isEmpty() || $categories->isEmpty() || !$image) {
            $this->command->warn('Uruchom najpierw UserSeeder, CategorySeeder i ImageSeeder.');

            return;
        }

        $locations = ['Warszawa', 'Kraków', 'Gdańsk', 'Wrocław', 'Poznań', 'Tarnobrzeg'];
        $statuses = ['aktywna', 'zakończona'];

        for ($i = 0; $i < 30; $i++) {
            $createdAt = fake()->dateTimeBetween('-7 days', 'now');

            Auction::forceCreate([
                'name' => fake()->sentence(4),
                'description' => fake()->paragraph(100),
                'price' => fake()->randomFloat(2, 50, 50000),
                'negotiable' => fake()->boolean(),
                'location' => fake()->randomElement($locations),
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
}
