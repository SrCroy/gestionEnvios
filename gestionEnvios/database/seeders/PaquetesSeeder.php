<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaquetesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paquetes')->insert([
            [
                'idDestinatario' => 1,
                'idRemitente' => 2,
                'idVehiculo' => null, // Dejar nulo para asignar después
                'descripcion' => 'ME&B - Pedido #A1001',
                'peso' => 25.00,
                'altura' => 60.00,
                'fechaRegistro' => '2025-12-02',
                'fechaEstimadaEntrega' => '2025-12-04',
                'estadoActual' => 'Recoger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idDestinatario' => 2,
                'idRemitente' => 3,
                'idVehiculo' => null, // Dejar nulo para asignar después
                'descripcion' => 'Atlas - Pedido #B2002',
                'peso' => 12.00,
                'altura' => 40.00,
                'fechaRegistro' => '2025-12-03',
                'fechaEstimadaEntrega' => '2025-12-05',
                'estadoActual' => 'Recoger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idDestinatario' => 3,
                'idRemitente' => 1,
                'idVehiculo' => null, // Dejar nulo para asignar después
                'descripcion' => 'Sol - Pedido #C3003',
                'peso' => 30.00,
                'altura' => 70.00,
                'fechaRegistro' => '2025-12-03',
                'fechaEstimadaEntrega' => '2025-12-04',
                'estadoActual' => 'Recoger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
