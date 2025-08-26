<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class VerificarEnvio extends Command
{
    protected $signature = 'envio:verificar {numero}';
    protected $description = 'Verificar el estado de un envío por número de WhatsApp';

    public function handle()
    {
        $numero = $this->argument('numero');
        
        $this->info("🔍 Buscando envíos para el número: {$numero}");
        
        $envios = Envio::whereHas('cliente', function($q) use ($numero) {
            $q->where('celular', 'LIKE', '%' . $numero . '%');
        })->get();
        
        if ($envios->isEmpty()) {
            $this->warn("❌ No se encontraron envíos para el número: {$numero}");
            return;
        }
        
        $this->info("✅ Se encontraron " . $envios->count() . " envíos:");
        
        foreach ($envios as $envio) {
            $this->line("📋 ID: {$envio->idenvio}");
            $this->line("   Estado: {$envio->estado}");
            $this->line("   Pregunta actual: " . ($envio->pregunta_actual ?? 'NULL'));
            $this->line("   Respuesta 1: " . ($envio->respuesta_1 ?? 'NULL'));
            $this->line("   Respuesta 2: " . ($envio->respuesta_2 ?? 'NULL'));
            $this->line("   Respuesta 3: " . ($envio->respuesta_3 ?? 'NULL'));
            $this->line("   Respuesta 4: " . ($envio->respuesta_4 ?? 'NULL'));
            $this->line("   WhatsApp number: " . ($envio->whatsapp_number ?? 'NULL'));
            $this->line("   Cliente: " . ($envio->cliente->nombre_completo ?? 'N/A'));
            $this->line("   Celular: " . ($envio->cliente->celular ?? 'N/A'));
            $this->line("---");
        }
    }
}
