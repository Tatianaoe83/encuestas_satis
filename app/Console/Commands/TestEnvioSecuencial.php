<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class TestEnvioSecuencial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:envio-secuencial {envio_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar el envío secuencial de preguntas para una encuesta';

    /**
     * Execute the console command.
     */
    public function handle(TwilioService $twilioService)
    {
        $envioId = $this->argument('envio_id');
        
        $this->info("Probando envío secuencial para envío ID: {$envioId}");
        
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
            
            // Enviar primera pregunta
            $this->info("\nEnviando primera pregunta...");
            $resultado = $twilioService->enviarEncuesta($envio);
            
            if ($resultado) {
                $this->info("✅ Primera pregunta enviada exitosamente");
                $this->info("Estado actualizado: " . $envio->fresh()->estado);
                $this->info("Pregunta actual: " . $envio->fresh()->pregunta_actual);
            } else {
                $this->error("❌ Error enviando primera pregunta");
                return 1;
            }
            
            $this->info("\n🎯 Para probar el flujo completo:");
            $this->info("1. Responde la primera pregunta desde WhatsApp");
            $this->info("2. El sistema enviará automáticamente la siguiente pregunta");
            $this->info("3. Repite hasta completar las 4 preguntas");
            $this->info("4. Usa la ruta /api/webhook-test-clean para simular respuestas");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error("Error en comando test:envio-secuencial", [
                'envio_id' => $envioId,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
}
