<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'firstName' => 'Admin',
            'lastName' => 'Testowy',
            'email' => 'admin@mamto.test',
            'phoneNumber' => '500100200',
            'password' => 'password',
            'joinedAt' => now(),
            'lastOnline' => now(),
            'isAdmin' => true,
        ]);

        User::create([
            'firstName' => 'Jan',
            'lastName' => 'Kowalski',
            'email' => 'jan.kowalski@example.com',
            'phoneNumber' => '600200300',
            'password' => 'password',
            'joinedAt' => now()->subMonths(6),
            'lastOnline' => now()->subHours(2),
            'isAdmin' => false,
        ]);

        User::create([
            'firstName' => 'Anna',
            'lastName' => 'Nowak',
            'email' => 'anna.nowak@example.com',
            'phoneNumber' => '700300400',
            'password' => 'password',
            'joinedAt' => now()->subMonths(3),
            'lastOnline' => now()->subDay(),
            'isAdmin' => false,
        ]);

        User::factory(12)->create();
    }
}
