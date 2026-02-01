<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $this->call([
        //     UserSeeder::class,
        // ]);
        User::factory(100)->create();
        // \App\Models\User::factory(10)->create();
        // $users = [
        //     ['name' => 'Dupont', 'prenom' => 'Jean', 'email' => 'jean.dupont@test.com', 'specialite' => 'Développeur PHP'],
        //     ['name' => 'Dupont', 'prenom' => 'Marie', 'email' => 'marie.dupont@test.com', 'specialite' => 'Designer UX'],
        //     ['name' => 'Martin', 'prenom' => 'Pierre', 'email' => 'pierre.martin@test.com', 'specialite' => 'Développeur web'],
        //     ['name' => 'Martin', 'prenom' => 'Sophie', 'email' => 'sophie.martin@test.com', 'specialite' => 'Marketing digital'],
        //     ['name' => 'Bernard', 'prenom' => 'Lucas', 'email' => 'lucas.bernard@test.com', 'specialite' => 'Développeur Frontend'],
        //     ['name' => 'Laurent', 'prenom' => 'Emma', 'email' => 'emma.laurent@test.com', 'specialite' => 'Designer graphique'],
        //     ['name' => 'Simon', 'prenom' => 'Thomas', 'email' => 'thomas.simon@test.com', 'specialite' => 'Chef de projet'],
        //     ['name' => 'Michel', 'prenom' => 'Julie', 'email' => 'julie.michel@test.com', 'specialite' => 'Data Scientist'],
        //     ['name' => 'Lefebvre', 'prenom' => 'Alexandre', 'email' => 'alex.lefebvre@test.com', 'specialite' => 'DevOps'],
        //     ['name' => 'Roux', 'prenom' => 'Clara', 'email' => 'clara.roux@test.com', 'specialite' => 'Développeur Backend'],
        // ];
        // \App\Models\User::factory()->create([
        //     'name' => 'Dupont',
        //     'prenom' => 'Jean', 
        //     'email' => 'jean.dupont@test.com', 
        //     'specialite' => 'Développeur PHP',
        // ]);
        // foreach ($users as $data) {
        //     // \App\Models\User::factory()->create([
        //     User::updateOrCreate([
        //         'email' => $data['email'],
        //         'name' => $data['name'],
        //         'prenom' => $data['prenom'],
        //         'email' => $data['email'],
        //         'specialite' => $data['specialite'],
        //         'role' => 'user',
        //         'photo' => '',
        //         'bio' => 'Bio de ' . $data['prenom'] . ' ' . $data['name'],
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]);
        // }
    }
}
