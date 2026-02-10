<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialites = [
            'Développeur web',
            'Développeur PHP',
            'Développeur Frontend',
            'Développeur Backend',
            'Designer UX',
            'Designer graphique',
            'Chef de projet',
            'Marketing digital',
            'Data Scientist',
            'DevOps',
        ];

        return [
            'name' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'role' => fake()->randomElement(['admin', 'user', 'candidat']),
            'specialite' => fake()->randomElement($specialites),
            'photo' => '',
            'bio' => fake()->sentence(10),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
