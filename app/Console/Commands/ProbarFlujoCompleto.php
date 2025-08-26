<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Http;

class ProbarFlujoCompleto extends Command
{
    protected $signature = 'flujo:probar {envio_id}';
    protected $description = 'Probar el flujo completo de la encuesta';

    public function handle()
    {
        $envioId = $this->argument('envio_id');
        
        $this->info("🔄 Probando flujo completo para envío ID: {$envioId}");
        
        // Buscar el envío
        $envio = Envio::with('cliente')->find($envioId);
        
        if (!$envio) {
            $this->error("❌ No se encontró el envío con ID: {$envioId}");
            return 1;
        }
        
        $this->info("✅ Envío encontrado:");
        $this->info("   - Cliente: {$envio->cliente->nombre_completo}");
        $this->info("   - Celular: {$envio->cliente->celular}");
        $this->info("   - Estado: {$envio->estado}");
        $this->info("   - Pregunta actual: " . ($envio->pregunta_actual ?? 'No definida'));
        
        // Verificar que esté en estado correcto
        if ($envio->estado !== 'pendiente') {
            $this->warn("⚠️  El envío no está en estado 'pendiente'. Estado actual: {$envio->estado}");
            
            if (!$this->confirm('¿Quieres cambiar el estado a "pendiente"?')) {
                return 1;
            }
            
            $envio->update(['estado' => 'pendiente']);
            $this->info("✅ Estado cambiado a 'pendiente'");
        }
        
        // Simular primera pregunta
        $this->info("\n📤 Simulando envío de primera pregunta...");
        
        $twilioService = new TwilioService();
        $resultado = $twilioService->enviarEncuesta($envio);
        
        if ($resultado) {
            $this->info("✅ Primera pregunta enviada exitosamente");
            
            // Actualizar estado
            $envio->update([
                'estado' => 'enviado',
                'pregunta_actual' => 1,
                'fecha_envio' => now()
            ]);
            
            $this->info("✅ Estado actualizado: enviado, pregunta_actual: 1");
            
        } else {
            $this->error("❌ Error enviando primera pregunta");
            return 1;
        }
        
        // Simular respuestas secuenciales
        $respuestas = [
            "9", // Pregunta 1: Escala 0-10
            "Excelente calidad del concreto y servicio rápido", // Pregunta 2: Razón
            "Vivienda unifamiliar", // Pregunta 3: Tipo de obra
            "Mantener la misma calidad y tiempos de entrega" // Pregunta 4: Sugerencia
        ];
        
        $this->info("\n📝 Simulando respuestas del cliente...");
        
        foreach ($respuestas as $index => $respuesta) {
            $preguntaNum = $index + 1;
            $this->info("   Pregunta {$preguntaNum}: {$respuesta}");
            
            // Simular webhook
            $webhookData = [
                'From' => 'whatsapp:' . $envio->cliente->celular,
                'To' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
                'Body' => $respuesta,
                'MessageSid' => 'TEST_' . time() . '_' . $preguntaNum
            ];
            
            $this->info("   📡 Enviando webhook a /api/webhook-test-clean...");
            
            try {
                // Usar URL local para pruebas
                $webhookUrl = 'http://localhost/encuestas_satis/public/api/webhook-test-clean';
                $response = Http::post($webhookUrl, $webhookData);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $this->info("   ✅ Webhook procesado: " . $data['message']);
                    
                    if ($data['data']['procesado']) {
                        $this->info("   ✅ Respuesta procesada correctamente");
                    } else {
                        $this->warn("   ⚠️  Respuesta no procesada");
                    }
                    
                } else {
                    $this->error("   ❌ Error en webhook: " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ Error enviando webhook: " . $e->getMessage());
            }
            
            // Pausa entre respuestas
            if ($preguntaNum < 4) {
                $this->info("   ⏳ Esperando 2 segundos...");
                sleep(2);
            }
        }
        
        // Verificar estado final
        $envio->refresh();
        
        $this->info("\n📊 Estado final del envío:");
        $this->info("   - Estado: {$envio->estado}");
        $this->info("   - Pregunta actual: " . ($envio->pregunta_actual ?? 'No definida'));
        $this->info("   - Respuesta 1: " . ($envio->respuesta_1 ?? 'No respondida'));
        $this->info("   - Respuesta 2: " . ($envio->respuesta_2 ?? 'No respondida'));
        $this->info("   - Respuesta 3: " . ($envio->respuesta_3 ?? 'No respondida'));
        $this->info("   - Respuesta 4: " . ($envio->respuesta_4 ?? 'No respondida'));
        $this->info("   - Fecha respuesta: " . ($envio->fecha_respuesta ?? 'No respondida'));
        
        if ($envio->estado === 'completado') {
            $this->info("\n🎉 ¡Flujo completado exitosamente!");
        } else {
            $this->warn("\n⚠️  El flujo no se completó completamente");
        }
        
        return 0;
    }
}
