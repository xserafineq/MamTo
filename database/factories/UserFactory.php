<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phoneNumber' => fake()->numerify('5########'),
            'password' => static::$password ??= Hash::make('password'),
            'joinedAt' => fake()->dateTimeBetween('-2 years', 'now'),
            'lastOnline' => fake()->dateTimeBetween('-1 month', 'now'),
            'isAdmin' => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'isAdmin' => true,
        ]);
    }
}
