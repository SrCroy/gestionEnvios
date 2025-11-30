<?php

namespace Database\Seeders;

use App\Models\clientes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class clientesSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        clientes::factory()->count(5)->create();

        clientes::factory()->create([
            'nombre' => 'Adán Omar Quevedo Argueta',
            'telefono' => '25252525',
            'direccion' => 'UES FMO',
            'email' => 'quevedo@kb2.net',
            'latitud' => 13.440102542012788, 
            'longitud' => -88.15816860967003,
        ]);
    }
}
