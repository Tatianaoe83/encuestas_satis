<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;

class CorregirPreguntaActualCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'envios:corregir-pregunta-actual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir pregunta_actual de string a numérico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Corrigiendo pregunta_actual...');
        
        // Buscar envíos que tienen 'encuesta' como string en pregunta_actual
        $enviosIncorrectos = Envio::where('pregunta_actual', 'encuesta')
            ->where('estado', '!=', 'completado')
            ->get();
        
        $this->info("Encontrados {$enviosIncorrectos->count()} envíos con pregunta_actual incorrecta");
        
        $corregidos = 0;
        
        foreach ($enviosIncorrectos as $envio) {
            $this->info("Corrigiendo envío ID: {$envio->idenvio} - pregunta_actual: {$envio->pregunta_actual}");
            
            $envio->update([
                'pregunta_actual' => 1.1
            ]);
            
            $corregidos++;
        }
        
        $this->info("✅ Corregidos {$corregidos} envíos");
        
        // También corregir envíos que tienen 'completado' como string
        $enviosCompletados = Envio::where('pregunta_actual', 'completado')
            ->where('estado', 'completado')
            ->get();
        
        $this->info("Encontrados {$enviosCompletados->count()} envíos completados con pregunta_actual incorrecta");
        
        $completadosCorregidos = 0;
        
        foreach ($enviosCompletados as $envio) {
            $this->info("Corrigiendo envío completado ID: {$envio->idenvio}");
            
            $envio->update([
                'pregunta_actual' => 4.0
            ]);
            
            $completadosCorregidos++;
        }
        
        $this->info("✅ Corregidos {$completadosCorregidos} envíos completados");
        $this->info("Corrección completada");
    }
}
