<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;

class TestPrimeraPregunta extends Command
{
    protected $signature = 'test:primera-pregunta {envio_id} {--reset}';
    protected $description = 'Probar el envío de la primera pregunta después del contenido aprobado';

    public function handle(TwilioService $twilioService)
    {
        $envioId = $this->argument('envio_id');
        $reset = $this->option('reset');
        
        $this->info("Probando envío de primera pregunta para envío ID: {$envioId}");
        
        try {
            // Buscar el envío
            $envio = Envio::find($envioId);
            
            if (!$envio) {
                $this->error("No se encontró el envío con ID: {$envioId}");
                return 1;
            }
            
            if ($reset) {
                $this->info("Reseteando envío...");
                $envio->update([
                    'estado' => 'esperando_respuesta',
                    'pregunta_actual' => null,
                    'timer_activo' => true,
                    'estado_timer' => 'activo',
                    'respuesta_1_1' => null,
                    'respuesta_1_2' => null,
                    'respuesta_1_3' => null,
                    'respuesta_1_4' => null,
                    'respuesta_1_5' => null,
                    'respuesta_2' => null,
                    'respuesta_3' => null,
                    'promedio_respuesta_1' => null
                ]);
                $this->info("✅ Envío reseteado");
            }
            
            $this->info("Envío encontrado:");
            $this->info("- Cliente: " . ($envio->cliente->nombre_completo ?? 'N/A'));
            $this->info("- Número: " . ($envio->cliente->celular ?? 'N/A'));
            $this->info("- Estado actual: " . ($envio->estado ?? 'N/A'));
            $this->info("- Pregunta actual: " . ($envio->pregunta_actual ?? 'N/A'));
            $this->info("- Timer activo: " . ($envio->timer_activo ? 'true' : 'false'));
            $this->info("- Estado timer: " . ($envio->estado_timer ?? 'N/A'));
            $this->info("- WhatsApp number: " . ($envio->whatsapp_number ?? 'N/A'));
            
            // Simular el envío de la primera pregunta
            $this->info("\nSimulando envío de primera pregunta...");
            $resultado = $twilioService->enviarPrimeraPreguntaNuevaEncuesta($envio);
            
            if ($resultado) {
                $this->info("✅ Primera pregunta enviada exitosamente");
                $this->info("Estado actualizado: " . $envio->fresh()->estado);
                $this->info("Pregunta actual: " . $envio->fresh()->pregunta_actual);
            } else {
                $this->error("❌ Error al enviar la primera pregunta");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
