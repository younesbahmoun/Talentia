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
            'Développeur Full Stack',
            'Designer UX',
            'Designer UI',
            'Designer graphique',
            'Chef de projet',
            'Marketing digital',
            'Data Scientist',
            'Data Analyst',
            'DevOps Engineer',
            'Ingénieur Cloud',
            'Architecte logiciel',
            'Product Manager',
            'Scrum Master',
            'Consultant IT',
            'Administrateur Système',
            'Ingénieur QA',
        ];

        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'name' => $lastName,
            'prenom' => $firstName,
            'role' => fake()->randomElement(['candidat', 'recruteur']),
            'specialite' => fake()->randomElement($specialites),
            'photo' => 'https://ui-avatars.com/api/?name=' . urlencode($firstName . ' ' . $lastName) . '&background=' . fake()->randomElement(['0D8ABC', '6610f2', '198754', 'dc3545', 'fd7e14', '0d6efd']) . '&color=fff&size=128',
            'bio' => fake()->paragraph(2),
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
