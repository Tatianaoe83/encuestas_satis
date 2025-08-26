<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class SimularRespuesta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simular:respuesta {envio_id} {respuesta}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simular una respuesta para probar el flujo de preguntas secuenciales';

    /**
     * Execute the console command.
     */
    public function handle(TwilioService $twilioService)
    {
        $envioId = $this->argument('envio_id');
        $respuesta = $this->argument('respuesta');
        
        $this->info("Simulando respuesta para envío ID: {$envioId}");
        $this->info("Respuesta: {$respuesta}");
        
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
            
            // Simular la respuesta
            $this->info("\nSimulando respuesta...");
            $resultado = $twilioService->procesarRespuesta(
                $envio->cliente->celular, 
                $respuesta, 
                'SIM_' . time()
            );
            
            if ($resultado) {
                $this->info("✅ Respuesta procesada exitosamente");
                $this->info("Estado actualizado: " . $envio->fresh()->estado);
                $this->info("Pregunta actual: " . $envio->fresh()->pregunta_actual);
                
                // Mostrar respuestas guardadas
                $envio = $envio->fresh();
                $this->info("\nRespuestas guardadas:");
                $this->info("- Respuesta 1: " . ($envio->respuesta_1 ?? 'N/A'));
                $this->info("- Respuesta 2: " . ($envio->respuesta_2 ?? 'N/A'));
                $this->info("- Respuesta 3: " . ($envio->respuesta_3 ?? 'N/A'));
                $this->info("- Respuesta 4: " . ($envio->respuesta_4 ?? 'N/A'));
                
                if ($envio->estado === 'completado') {
                    $this->info("\n🎉 ¡Encuesta completada!");
                } else {
                    $this->info("\n📝 Siguiente pregunta enviada automáticamente");
                }
            } else {
                $this->error("❌ Error procesando respuesta");
                return 1;
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error("Error en comando simular:respuesta", [
                'envio_id' => $envioId,
                'respuesta' => $respuesta,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
}
