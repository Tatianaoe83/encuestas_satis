<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Envio;

class EnvioSecuencialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar un cliente existente o crear uno de prueba
        $cliente = Cliente::first();
        
        if (!$cliente) {
            $cliente = Cliente::create([
                'nombre' => 'Cliente Prueba Secuencial',
                'celular' => '9993778529',
                'email' => 'prueba@secuencial.com',
                'empresa' => 'Empresa Prueba'
            ]);
        }

        // Crear envío de prueba
        $envio = Envio::create([
            'cliente_id' => $cliente->idcliente,
            'estado' => 'pendiente',
            'pregunta_actual' => 1,
            'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?',
            'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
            'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
            'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?'
        ]);

        $this->command->info("✅ Envío de prueba creado:");
        $this->command->info("   - ID: {$envio->idenvio}");
        $this->command->info("   - Cliente: {$cliente->nombre}");
        $this->command->info("   - Celular: {$cliente->celular}");
        $this->command->info("   - Estado: {$envio->estado}");
        $this->command->info("   - Pregunta actual: {$envio->pregunta_actual}");
        
        $this->command->info("\n🎯 Para probar el sistema:");
        $this->command->info("1. php artisan test:envio-secuencial {$envio->idenvio}");
        $this->command->info("2. php artisan simular:respuesta {$envio->idenvio} \"8\"");
        $this->command->info("3. php artisan simular:respuesta {$envio->idenvio} \"Excelente servicio\"");
        $this->command->info("4. php artisan simular:respuesta {$envio->idenvio} \"Vivienda unifamiliar\"");
        $this->command->info("5. php artisan simular:respuesta {$envio->idenvio} \"Más horarios de entrega\"");
    }
}
