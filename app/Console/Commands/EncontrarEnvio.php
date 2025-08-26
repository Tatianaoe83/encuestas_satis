<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class EncontrarEnvio extends Command
{
    protected $signature = 'envio:encontrar {id}';
    protected $description = 'Encontrar en qué página está un envío específico';

    public function handle()
    {
        $id = $this->argument('id');
        
        $this->info("🔍 Buscando envío ID: {$id}");
        
        // Obtener todos los envíos ordenados
        $envios = Envio::with('cliente')->latest()->get();
        
        // Encontrar la posición del envío
        $posicion = $envios->search(function($envio) use ($id) {
            return $envio->idenvio == $id;
        });
        
        if ($posicion === false) {
            $this->error("Envío no encontrado");
            return 1;
        }
        
        $pagina = floor($posicion / 10) + 1;
        $posicionEnPagina = ($posicion % 10) + 1;
        
        $this->info("\n📋 Información del Envío:");
        $this->line("   ID: {$id}");
        $this->line("   Posición total: " . ($posicion + 1));
        $this->line("   Página: {$pagina}");
        $this->line("   Posición en página: {$posicionEnPagina}");
        
        $envio = $envios[$posicion];
        $cliente = $envio->cliente;
        
        $this->info("\n👤 Información del Cliente:");
        $this->line("   ID: {$cliente->idcliente}");
        $this->line("   Nombre: {$cliente->nombre_completo}");
        $this->line("   Celular: '{$cliente->celular}'");
        $this->line("   Estado: {$envio->estado}");
        
        $this->info("\n🔍 Condiciones para WhatsApp:");
        $this->line("   Estado === 'pendiente': " . ($envio->estado === 'pendiente' ? 'SÍ' : 'NO'));
        $this->line("   Cliente tiene celular: " . (!empty($cliente->celular) ? 'SÍ' : 'NO'));
        $this->line("   Ambas condiciones: " . (($envio->estado === 'pendiente' && !empty($cliente->celular)) ? 'SÍ' : 'NO'));
        
        return 0;
    }
}
