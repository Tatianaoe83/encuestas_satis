<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class CorregirEstadosEnvioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'envios:corregir-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir estados de envíos que tienen respuesta pero estado incorrecto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Corrigiendo estados de envíos...');
        
        // Buscar envíos que tienen respuesta_3 pero estado no es 'completado'
        $enviosIncorrectos = Envio::whereNotNull('respuesta_3')
            ->where('estado', '!=', 'completado')
            ->get();
        
        $this->info("Encontrados {$enviosIncorrectos->count()} envíos con estado incorrecto");
        
        $corregidos = 0;
        
        foreach ($enviosIncorrectos as $envio) {
            $this->info("Corrigiendo envío ID: {$envio->idenvio} - Estado actual: {$envio->estado}");
            
            $envio->update([
                'estado' => 'completado',
                'fecha_respuesta' => $envio->fecha_respuesta ?: \Carbon\Carbon::now()
            ]);
            
            $corregidos++;
        }
        
        $this->info("✅ Corregidos {$corregidos} envíos");
        
        // También buscar envíos que están en 'enviado' pero no tienen respuesta
        $enviosSinRespuesta = Envio::where('estado', 'enviado')
            ->whereNull('respuesta_3')
            ->where('timer_activo', true)
            ->where('tiempo_expiracion', '<', \Carbon\Carbon::now())
            ->get();
        
        $this->info("Encontrados {$enviosSinRespuesta->count()} envíos expirados sin respuesta");
        
        $expirados = 0;
        
        foreach ($enviosSinRespuesta as $envio) {
            $this->info("Marcando como cancelado envío ID: {$envio->idenvio}");
            
            $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'expirado'
            ]);
            
            $expirados++;
        }
        
        $this->info("✅ Marcados como cancelados {$expirados} envíos expirados");
        $this->info("Corrección completada");
    }
}
