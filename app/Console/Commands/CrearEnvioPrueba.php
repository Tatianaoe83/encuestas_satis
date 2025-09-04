<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Models\Cliente;

class CrearEnvioPrueba extends Command
{
    protected $signature = 'envio:crear-prueba';
    protected $description = 'Crear un envío de prueba para testing';

    public function handle()
    {
        try {
            // Buscar o crear cliente de prueba
            $cliente = Cliente::where('celular', '9993778529')->first();
            
            if (!$cliente) {
                $cliente = Cliente::create([
                    'nombre_completo' => 'Cliente Prueba',
                    'razon_social' => 'Empresa Prueba',
                    'asesor_comercial' => 'Asesor Prueba',
                    'puesto' => 'Gerente',
                    'celular' => '9993778529',
                    'correo' => 'prueba@test.com'
                ]);
            }
            
            // Crear envío de prueba
            $envio = Envio::create([
                'cliente_id' => $cliente->idcliente,
                'whatsapp_number' => 'whatsapp:5219993778529',
                'estado' => 'esperando_respuesta',
                'pregunta_actual' => null,
                'timer_activo' => true,
                'estado_timer' => 'activo',
                'tiempo_espera_minutos' => 30,
                'tiempo_expiracion' => now()->addMinutes(30),
                'pregunta_1' => 'En una escala del 1-10, ¿Cómo calificarías nuestro servicio con base en los siguientes puntos?',
                'pregunta_2' => '¿Recomendarías a Konkret?',
                'pregunta_3' => '¿Qué podríamos hacer para mejorar tu experiencia?'
            ]);
            
            $this->info("✅ Envío de prueba creado exitosamente");
            $this->info("ID: " . $envio->idenvio);
            $this->info("Cliente: " . $cliente->nombre_completo);
            $this->info("Estado: " . $envio->estado);
            $this->info("WhatsApp: " . $envio->whatsapp_number);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
