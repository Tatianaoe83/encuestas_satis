<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Envio;
use Carbon\Carbon;

class ResultadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear clientes de prueba con diferentes asesores
        $asesores = ['Juan Pérez', 'María García', 'Carlos López', 'Ana Martínez', 'Luis Rodríguez'];
        
        for ($i = 1; $i <= 50; $i++) {
            Cliente::create([
                'asesor_comercial' => $asesores[array_rand($asesores)],
                'razon_social' => 'Empresa ' . $i,
                'nombre_completo' => 'Contacto ' . $i,
                'puesto' => 'Gerente',
                'celular' => '300' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'correo' => 'contacto' . $i . '@empresa.com',
            ]);
        }

        // Crear envíos de prueba
        $clientes = Cliente::all();
        $estados = ['pendiente', 'enviado', 'respondido', 'cancelado'];
        $respuestas = ['Excelente', 'Muy Bueno', 'Bueno', 'Regular', 'Malo'];

        foreach ($clientes as $cliente) {
            // Crear entre 1 y 5 envíos por cliente
            $numEnvios = rand(1, 5);
            
            for ($j = 0; $j < $numEnvios; $j++) {
                $estado = $estados[array_rand($estados)];
                $fechaEnvio = null;
                $fechaRespuesta = null;
                
                if ($estado !== 'pendiente') {
                    $fechaEnvio = Carbon::now()->subDays(rand(1, 90));
                    
                    if ($estado === 'respondido') {
                        $fechaRespuesta = $fechaEnvio->copy()->addDays(rand(1, 7));
                    }
                }

                $envio = Envio::create([
                    'cliente_id' => $cliente->id,
                    'pregunta_1' => '¿Cómo calificaría nuestro servicio?',
                    'pregunta_2' => '¿Recomendaría nuestros servicios?',
                    'pregunta_3' => '¿Está satisfecho con la atención recibida?',
                    'pregunta_4' => '¿Volvería a contratar nuestros servicios?',
                    'respuesta_1' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                    'respuesta_2' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                    'respuesta_3' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                    'respuesta_4' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                    'estado' => $estado,
                    'fecha_envio' => $fechaEnvio,
                    'fecha_respuesta' => $fechaRespuesta,
                ]);
            }
        }
    }
} 