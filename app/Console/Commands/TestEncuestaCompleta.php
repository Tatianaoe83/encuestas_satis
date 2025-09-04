<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;

class TestEncuestaCompleta extends Command
{
    protected $signature = 'test:encuesta-completa {envio_id}';
    protected $description = 'Probar el flujo completo de la encuesta paso a paso';

    public function handle(TwilioService $twilioService)
    {
        $envioId = $this->argument('envio_id');
        
        $this->info("Probando flujo completo de encuesta para envío ID: {$envioId}");
        
        try {
            // Buscar el envío
            $envio = Envio::find($envioId);
            
            if (!$envio) {
                $this->error("No se encontró el envío con ID: {$envioId}");
                return 1;
            }
            
            $this->info("Envío encontrado:");
            $this->info("- Cliente: " . ($envio->cliente->nombre_completo ?? 'N/A'));
            $this->info("- Número: " . ($envio->cliente->celular ?? 'N/A'));
            $this->info("- Estado actual: " . ($envio->estado ?? 'N/A'));
            $this->info("- Pregunta actual: " . ($envio->pregunta_actual ?? 'N/A'));
            
            // Simular respuestas paso a paso
            $respuestas = [
                1.1 => '8',  // Calidad del producto
                1.2 => '9',  // Puntualidad de entrega
                1.3 => '7',  // Trato del asesor comercial
                1.4 => '8',  // Precio
                1.5 => '9',  // Rapidez en programación
                2 => 'Si',   // ¿Recomendarías a Konkret?
            ];
            
            foreach ($respuestas as $pregunta => $respuesta) {
                $this->info("\n--- Procesando pregunta {$pregunta}: {$respuesta} ---");
                
                // Simular el procesamiento de la respuesta
                $resultado = $twilioService->procesarRespuesta(
                    $envio->cliente->celular,
                    $respuesta,
                    'TEST_' . time() . '_' . $pregunta
                );
                
                if ($resultado) {
                    $envio->refresh();
                    $this->info("✅ Respuesta procesada exitosamente");
                    $this->info("Estado: " . $envio->estado);
                    $this->info("Pregunta actual: " . $envio->pregunta_actual);
                    
                    // Mostrar respuestas guardadas
                    $this->info("Respuestas guardadas:");
                    $this->info("- respuesta_1_1: " . ($envio->respuesta_1_1 ?? 'N/A'));
                    $this->info("- respuesta_1_2: " . ($envio->respuesta_1_2 ?? 'N/A'));
                    $this->info("- respuesta_1_3: " . ($envio->respuesta_1_3 ?? 'N/A'));
                    $this->info("- respuesta_1_4: " . ($envio->respuesta_1_4 ?? 'N/A'));
                    $this->info("- respuesta_1_5: " . ($envio->respuesta_1_5 ?? 'N/A'));
                    $this->info("- respuesta_2: " . ($envio->respuesta_2 ?? 'N/A'));
                    $this->info("- promedio_respuesta_1: " . ($envio->promedio_respuesta_1 ?? 'N/A'));
                    
                    if ($envio->estado === 'completado') {
                        $this->info("\n🎉 ¡Encuesta completada!");
                        break;
                    }
                } else {
                    $this->error("❌ Error al procesar respuesta");
                    break;
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
