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
        // User::factory(10)->create();

        // Crear usuario de administrador por defecto
        User::factory()->create([
            'name' => 'Administrador UES',
            'email' => 'admin@uesfmo.com',
            'password' => bcrypt('admin1234'),
        ]);

        User::factory()->create([
            'name' => 'Motorista',
            'email' => 'motorista@uesfmo.com',
            'password' => bcrypt('motorista1234'),
        ]);

        // Usuario de prueba
        User::factory()->create([
            'name' => 'Usuario Prueba',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
