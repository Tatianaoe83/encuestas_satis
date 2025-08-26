<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class TestBusquedaEnvio extends Command
{
    protected $signature = 'test:busqueda-envio {numero}';
    protected $description = 'Probar la búsqueda de envíos por diferentes formatos de números';

    public function handle()
    {
        $numero = $this->argument('numero');
        
        $this->info("🔍 Probando búsqueda de envíos para el número: {$numero}");
        
        // Simular el proceso de búsqueda del TwilioService
        $envio = $this->buscarEnvio($numero);
        
        if ($envio) {
            $this->info("✅ Envío encontrado:");
            $this->line("   ID: {$envio->idenvio}");
            $this->line("   Estado: {$envio->estado}");
            $this->line("   Pregunta actual: " . ($envio->pregunta_actual ?? 'NULL'));
            $this->line("   WhatsApp number: " . ($envio->whatsapp_number ?? 'NULL'));
            $this->line("   Cliente: " . ($envio->cliente->nombre_completo ?? 'N/A'));
            $this->line("   Celular cliente: " . ($envio->cliente->celular ?? 'N/A'));
        } else {
            $this->warn("❌ No se encontró ningún envío");
        }
        
        // Mostrar todos los envíos en la base de datos para debugging
        $this->info("\n📋 Todos los envíos en la base de datos:");
        $envios = Envio::with('cliente')->get();
        
        if ($envios->isEmpty()) {
            $this->line("   No hay envíos en la base de datos");
        } else {
            foreach ($envios as $env) {
                $this->line("   ID: {$env->idenvio} | Estado: {$env->estado} | WhatsApp: " . ($env->whatsapp_number ?? 'NULL') . " | Cliente: " . ($env->cliente->celular ?? 'N/A'));
            }
        }
    }
    
    protected function buscarEnvio($from)
    {
        $envio = null;
        
        // Búsqueda por ID de encuesta (si está en el mensaje)
        if (preg_match('/🆔 \*ID Encuesta: (\d+)\*/', $from, $matches)) {
            $envioId = $matches[1];
            $envio = Envio::where('idenvio', $envioId)
                ->whereIn('estado', ['enviado', 'en_proceso'])
                ->first();
            
            if ($envio) {
                $this->info("Envío encontrado por ID de encuesta: {$envioId}");
                return $envio;
            }
        }
        
        // Búsqueda por número de WhatsApp (formato completo)
        $whatsappNumber = "whatsapp:{$from}";
        $envio = Envio::where('whatsapp_number', $whatsappNumber)
            ->whereIn('estado', ['enviado', 'en_proceso'])
            ->latest()
            ->first();
        
        if ($envio) {
            $this->info("Envío encontrado por número de WhatsApp completo");
            return $envio;
        }
        
        // Búsqueda por número de WhatsApp sin prefijo
        $envio = Envio::where('whatsapp_number', $from)
            ->whereIn('estado', ['enviado', 'en_proceso'])
            ->latest()
            ->first();
        
        if ($envio) {
            $this->info("Envío encontrado por número de WhatsApp sin prefijo");
            return $envio;
        }
        
        // Búsqueda por número de WhatsApp con formato alternativo (sin el prefijo whatsapp:)
        $numeroSinPrefijo = str_replace('whatsapp:', '', $from);
        $envio = Envio::where('whatsapp_number', $numeroSinPrefijo)
            ->whereIn('estado', ['enviado', 'en_proceso'])
            ->latest()
            ->first();
        
        if ($envio) {
            $this->info("Envío encontrado por número de WhatsApp sin prefijo whatsapp:");
            return $envio;
        }
        
        // Búsqueda por número de celular del cliente
        $cleanFrom = str_replace(['+', '52'], '', $from);
        $cleanFromWhatsApp = str_replace(['whatsapp:', '+', '52'], '', $from);
        
        $envio = Envio::whereHas('cliente', function($query) use ($from, $cleanFrom, $cleanFromWhatsApp) {
            $query->where('celular', $from)
                  ->orWhere('celular', $cleanFrom)
                  ->orWhere('celular', $cleanFromWhatsApp)
                  ->orWhere('celular', '+' . $cleanFrom)
                  ->orWhere('celular', '+' . $cleanFromWhatsApp)
                  ->orWhere('celular', '52' . $cleanFrom)
                  ->orWhere('celular', '52' . $cleanFromWhatsApp)
                  ->orWhere('celular', '521' . $cleanFrom)
                  ->orWhere('celular', '521' . $cleanFromWhatsApp);
        })
        ->whereIn('estado', ['enviado', 'en_proceso'])
        ->latest()
        ->first();
        
        if ($envio) {
            $this->info("Envío encontrado por número de celular del cliente");
            return $envio;
        }
        
        // Búsqueda más flexible por whatsapp_number
        $numeroLimpio = preg_replace('/[^0-9]/', '', $from);
        $numeroConPrefijo = 'whatsapp:' . $numeroLimpio;
        $numeroSinPrefijo = $numeroLimpio;
        
        $envio = Envio::where(function($query) use ($numeroConPrefijo, $numeroSinPrefijo, $numeroLimpio) {
            $query->where('whatsapp_number', $numeroConPrefijo)
                  ->orWhere('whatsapp_number', $numeroSinPrefijo)
                  ->orWhere('whatsapp_number', 'LIKE', '%' . $numeroLimpio . '%');
        })
        ->whereIn('estado', ['enviado', 'en_proceso'])
        ->latest()
        ->first();
        
        if ($envio) {
            $this->info("Envío encontrado por búsqueda flexible de whatsapp_number");
            return $envio;
        }
        
        return null;
    }
}
