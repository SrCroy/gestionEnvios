<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Motoristas adicionales
        $motoristas = [
            ['name' => 'Juan Pérez', 'email' => 'juan.perez@uesfmo.com'],
            ['name' => 'María López', 'email' => 'maria.lopez@uesfmo.com'],
            ['name' => 'Carlos Gómez', 'email' => 'carlos.gomez@uesfmo.com'],
        ];
        foreach ($motoristas as $m) {
            User::firstOrCreate(
                ['email' => $m['email']],
                ['name' => $m['name'], 'password' => bcrypt('motorista1234')]
            );
        }

        $hoy = Carbon::today();

        // Clientes (ajustado a: nombre, telefono, direccion, email UNIQUE, password, latitud/longitud opcional)
        if (DB::getSchemaBuilder()->hasTable('clientes')) {
            DB::table('clientes')->upsert([
                ['id' => 1, 'nombre' => 'Empresa ME&B', 'telefono' => '2222-1111', 'direccion' => 'Av. Central 123, San Salvador', 'email' => 'contacto@meb.com.sv', 'password' => bcrypt('cliente123'), 'latitud' => 13.69294, 'longitud' => -89.21819, 'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'nombre' => 'Comercial Atlas', 'telefono' => '2222-2222', 'direccion' => 'Calle El Mirador 456, Santa Tecla', 'email' => 'ventas@atlas.com.sv', 'password' => bcrypt('cliente123'), 'latitud' => 13.67409, 'longitud' => -89.28799, 'created_at' => now(), 'updated_at' => now()],
                ['id' => 3, 'nombre' => 'Distribuidora Sol', 'telefono' => '2222-3333', 'direccion' => 'Boulevard Venezuela 789, Soyapango', 'email' => 'info@sol.com.sv', 'password' => bcrypt('cliente123'), 'latitud' => 13.71020, 'longitud' => -89.15106, 'created_at' => now(), 'updated_at' => now()],
            ], ['id'], ['nombre', 'telefono', 'direccion', 'email', 'password', 'latitud', 'longitud', 'updated_at']);
        }

        // Vehículos (ajustado a: marca, modelo, pesoMaximo, volumenMaximo, estado)
        if (DB::getSchemaBuilder()->hasTable('vehiculos')) {
            DB::table('vehiculos')->upsert([
                ['id' => 1, 'marca' => 'Toyota', 'modelo' => 'Hilux', 'pesoMaximo' => 1000, 'volumenMaximo' => 8.5, 'estado' => 'Disponible', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'marca' => 'Nissan', 'modelo' => 'Frontier', 'pesoMaximo' => 900, 'volumenMaximo' => 7.8, 'estado' => 'Disponible', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 3, 'marca' => 'Ford', 'modelo' => 'Transit', 'pesoMaximo' => 1200, 'volumenMaximo' => 10.2, 'estado' => 'Disponible', 'created_at' => now(), 'updated_at' => now()],
            ], ['id'], ['marca', 'modelo', 'pesoMaximo', 'volumenMaximo', 'estado', 'updated_at']);
        }

        // Paquetes (ajustado a: idDestinatario, idRemitente, idVehiculo, estadoActual)
        if (DB::getSchemaBuilder()->hasTable('paquetes')) {
            DB::table('paquetes')->upsert([
                ['id' => 101, 'idDestinatario' => 1, 'idRemitente' => 2, 'idVehiculo' => 1, 'descripcion' => 'ME&B - Pedido #A1001', 'peso' => 25, 'altura' => 60, 'fechaRegistro' => $hoy->copy()->subDays(1), 'fechaEstimadaEntrega' => $hoy->copy()->addDays(1), 'estadoActual' => 'En Ruta', 'created_at' => $hoy->copy()->subDays(1), 'updated_at' => now()],
                ['id' => 102, 'idDestinatario' => 2, 'idRemitente' => 3, 'idVehiculo' => 2, 'descripcion' => 'Atlas - Pedido #B2002', 'peso' => 12, 'altura' => 40, 'fechaRegistro' => $hoy, 'fechaEstimadaEntrega' => $hoy->copy()->addDays(2), 'estadoActual' => 'Pendiente', 'created_at' => $hoy, 'updated_at' => now()],
                ['id' => 103, 'idDestinatario' => 3, 'idRemitente' => 1, 'idVehiculo' => 2, 'descripcion' => 'Sol - Pedido #C3003', 'peso' => 30, 'altura' => 70, 'fechaRegistro' => $hoy, 'fechaEstimadaEntrega' => $hoy->copy()->addDays(1), 'estadoActual' => 'En Ruta', 'created_at' => $hoy, 'updated_at' => now()],
            ], ['id'], ['idDestinatario', 'idRemitente', 'idVehiculo', 'descripcion', 'peso', 'altura', 'fechaRegistro', 'fechaEstimadaEntrega', 'estadoActual', 'updated_at']);
        }

        // Asignaciones (ajustado a: idPaquete, idMotorista, idVehiculo, fechaAsignacion)
        if (DB::getSchemaBuilder()->hasTable('asignaciones')) {
            $juan = User::where('email', 'juan.perez@uesfmo.com')->first();
            $maria = User::where('email', 'maria.lopez@uesfmo.com')->first();

            DB::table('asignaciones')->upsert([
                ['id' => 5001, 'idPaquete' => 101, 'idMotorista' => $juan?->id, 'idVehiculo' => 1, 'fechaAsignacion' => $hoy->copy()->subDays(1), 'created_at' => $hoy->copy()->subDays(1), 'updated_at' => now()],
                ['id' => 5002, 'idPaquete' => 102, 'idMotorista' => $maria?->id, 'idVehiculo' => 2, 'fechaAsignacion' => $hoy, 'created_at' => $hoy, 'updated_at' => now()],
                ['id' => 5003, 'idPaquete' => 103, 'idMotorista' => $juan?->id, 'idVehiculo' => 2, 'fechaAsignacion' => $hoy, 'created_at' => $hoy, 'updated_at' => now()],
            ], ['id'], ['idPaquete', 'idMotorista', 'idVehiculo', 'fechaAsignacion', 'updated_at']);
        }

        // Puntos de ruta: sin columna 'fecha', se derivará de created_at
        if (DB::getSchemaBuilder()->hasTable('rutas_puntos')) {
            DB::table('rutas_puntos')->upsert([
                ['id' => 9001, 'idPaquete' => 101, 'tipo' => 'recoger',  'latitud' => 13.69294, 'longitud' => -89.21819, 'created_at' => $hoy->copy()->subDays(1), 'updated_at' => now()],
                ['id' => 9002, 'idPaquete' => 101, 'tipo' => 'entregar', 'latitud' => 13.70844, 'longitud' => -89.21373, 'created_at' => $hoy->copy()->subDays(1), 'updated_at' => now()],
                ['id' => 9003, 'idPaquete' => 102, 'tipo' => 'recoger',  'latitud' => 13.67409, 'longitud' => -89.28799, 'created_at' => $hoy, 'updated_at' => now()],
                ['id' => 9004, 'idPaquete' => 102, 'tipo' => 'entregar', 'latitud' => 13.67163, 'longitud' => -89.28611, 'created_at' => $hoy, 'updated_at' => now()],
                ['id' => 9005, 'idPaquete' => 103, 'tipo' => 'recoger',  'latitud' => 13.71020, 'longitud' => -89.15106, 'created_at' => $hoy, 'updated_at' => now()],
                ['id' => 9006, 'idPaquete' => 103, 'tipo' => 'entregar', 'latitud' => 13.71180, 'longitud' => -89.15470, 'created_at' => $hoy, 'updated_at' => now()],
            ], ['id'], ['idPaquete', 'tipo', 'latitud', 'longitud', 'updated_at']);
        }

        // Nota: ajusta nombres de columnas si tu esquema usa otros (ej. motorista_id vs id_motorista, vehiculo_id vs id_vehiculo).

        $this->call(PaquetesSeeder::class);
    }
}