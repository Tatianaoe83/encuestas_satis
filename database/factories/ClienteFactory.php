<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asesor_comercial' => fake()->name(),
            'razon_social' => fake()->company(),
            'nombre_completo' => fake()->name(),
            'puesto' => fake()->jobTitle(),
            'celular' => fake()->phoneNumber(),
            'correo' => fake()->unique()->safeEmail(),
        ];
    }
} 