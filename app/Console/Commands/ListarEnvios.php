<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class ListarEnvios extends Command
{
    protected $signature = 'envios:listar {estado?} {--all}';
    protected $description = 'Listar envíos por estado';

    public function handle()
    {
        $estado = $this->argument('estado') ?? 'esperando_respuesta';
        $all = $this->option('all');
        
        if ($all) {
            $this->info("Todos los envíos:");
            $envios = Envio::with('cliente')
                ->get(['idenvio', 'cliente_id', 'estado', 'pregunta_actual', 'whatsapp_number']);
        } else {
            $this->info("Envíos en estado: {$estado}");
            $envios = Envio::where('estado', $estado)
                ->with('cliente')
                ->get(['idenvio', 'cliente_id', 'estado', 'pregunta_actual', 'whatsapp_number']);
        }
        
        if ($envios->isEmpty()) {
            $this->warn("No se encontraron envíos" . ($all ? "" : " en estado: {$estado}"));
            return;
        }
        
        $this->table(
            ['ID', 'Cliente', 'Estado', 'Pregunta', 'WhatsApp'],
            $envios->map(function($envio) {
                return [
                    $envio->idenvio,
                    $envio->cliente->nombre_completo ?? 'N/A',
                    $envio->estado,
                    $envio->pregunta_actual ?? 'N/A',
                    $envio->whatsapp_number ?? 'N/A'
                ];
            })
        );
    }
}
