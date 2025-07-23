<?php

namespace App\Console\Commands;

use App\Models\Envio;
use App\Services\TwilioService;
use Illuminate\Console\Command;

class TestTwilioWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio:test-whatsapp {envio_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar el envío de encuesta por WhatsApp usando Twilio';

    /**
     * Execute the console command.
     */
    public function handle(TwilioService $twilioService)
    {
        $envioId = $this->argument('envio_id');
        
        $this->info("🔍 Buscando envío con ID: {$envioId}");
        
        $envio = Envio::with('cliente')->find($envioId);
        
        if (!$envio) {
            $this->error("❌ No se encontró el envío con ID: {$envioId}");
            return 1;
        }
        
        $this->info("✅ Envío encontrado:");
        $this->line("   Cliente: {$envio->cliente->nombre_completo}");
        $this->line("   Celular: {$envio->cliente->celular}");
        $this->line("   Estado: {$envio->estado}");
        
        if (empty($envio->cliente->celular)) {
            $this->error("❌ El cliente no tiene número de celular registrado");
            return 1;
        }
        
        $this->info("\n📱 Enviando encuesta por WhatsApp...");
        
        try {
            $resultado = $twilioService->enviarEncuesta($envio);
            
            if ($resultado) {
                $this->info("✅ Encuesta enviada exitosamente");
                $this->line("   Message SID: {$envio->twilio_message_sid}");
                $this->line("   Número: {$envio->whatsapp_number}");
                $this->line("   Estado actualizado: {$envio->estado}");
            } else {
                $this->error("❌ Error al enviar la encuesta");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Excepción: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
} 