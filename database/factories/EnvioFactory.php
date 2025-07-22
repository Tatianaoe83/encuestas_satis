<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Envio>
 */
class EnvioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?',
            'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
            'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
            'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?',
            'respuesta_1' => fake()->optional()->numberBetween(0, 10),
            'respuesta_2' => fake()->optional()->sentence(),
            'respuesta_3' => fake()->optional()->randomElement(['Vivienda unifamiliar', 'Edificio o proyecto vertical', 'Obra vial o infraestructura', 'Obra industrial', 'Otro']),
            'respuesta_4' => fake()->optional()->sentence(),
            'estado' => fake()->randomElement(['pendiente', 'enviado', 'respondido', 'cancelado']),
            'fecha_envio' => fake()->optional()->dateTime(),
            'fecha_respuesta' => fake()->optional()->dateTime(),
        ];
    }
} 