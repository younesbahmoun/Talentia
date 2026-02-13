<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les rôles
        $candidat = Role::create(['name' => 'candidat']);
        $recruteur = Role::create(['name' => 'recruteur']);

        $premium = Role::create(['name' => 'premium']);

        // Optionnel : Créer des permissions spécifiques
        $permissions = [
            'postuler à une offre',
            'créer une offre',
            'modifier une offre',
            'supprimer une offre',
            'voir les candidatures',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assigner les permissions aux rôles
        $candidat->givePermissionTo('postuler à une offre');
        
        $recruteur->givePermissionTo([
            'créer une offre',
            'modifier une offre',
            'supprimer une offre',
            'voir les candidatures',
        ]);
    }
}