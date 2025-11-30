<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\clientes>
 */
class clientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'telefono' => fake()->phoneNumber(),
            'direccion' =>fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'latitud' => fake()->randomFloat(5, 13.43000,13.44160),
            'longitud' => fake()->randomFloat(5, -88.15600,-88.16500),
        ];
    }
}
