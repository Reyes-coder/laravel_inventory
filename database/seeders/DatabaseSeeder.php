<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        // Admin: Samuel Reyes Castro
        User::factory()->withPersonalTeam()->create([
            'name' => 'Samuel Reyes Castro',
            'email' => 'samuelreyescastro456@gmail.com',
            'password' => Hash::make('Admin@2026!'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // User 1: Juan Pérez
        User::factory()->withPersonalTeam()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => Hash::make('Juan@Perez123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User 2: María García
        User::factory()->withPersonalTeam()->create([
            'name' => 'María García',
            'email' => 'maria.garcia@example.com',
            'password' => Hash::make('Maria@Garcia456'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Llamar otros seeders
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
