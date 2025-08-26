<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class DebugEnvio extends Command
{
    protected $signature = 'debug:envio {id}';
    protected $description = 'Debuggear un envío específico';

    public function handle()
    {
        $id = $this->argument('id');
        
        $this->info("🔍 Debuggeando envío ID: {$id}");
        
        $envio = Envio::with('cliente')->find($id);
        
        if (!$envio) {
            $this->error("Envío no encontrado");
            return 1;
        }
        
        $cliente = $envio->cliente;
        
        $this->info("\n📋 Información del Envío:");
        $this->line("   ID: {$envio->idenvio}");
        $this->line("   Estado: {$envio->estado}");
        $this->line("   Cliente ID: {$envio->cliente_id}");
        
        $this->info("\n👤 Información del Cliente:");
        $this->line("   ID: {$cliente->idcliente}");
        $this->line("   Nombre: {$cliente->nombre_completo}");
        $this->line("   Celular: '{$cliente->celular}'");
        $this->line("   Celular vacío: " . (empty($cliente->celular) ? 'SÍ' : 'NO'));
        $this->line("   Celular NULL: " . (is_null($cliente->celular) ? 'SÍ' : 'NO'));
        
        $this->info("\n🔍 Condiciones para WhatsApp:");
        $this->line("   Estado === 'pendiente': " . ($envio->estado === 'pendiente' ? 'SÍ' : 'NO'));
        $this->line("   Cliente tiene celular: " . (!empty($cliente->celular) ? 'SÍ' : 'NO'));
        $this->line("   Ambas condiciones: " . (($envio->estado === 'pendiente' && !empty($cliente->celular)) ? 'SÍ' : 'NO'));
        
        return 0;
    }
}
