<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Envio;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crear algunos clientes de ejemplo
        Cliente::factory(10)->create();

        // Crear algunos envíos de ejemplo
        Envio::factory(15)->create();

        // Ejecutar seeder de resultados para datos de prueba
        $this->call(ResultadosSeeder::class);
    }
}
