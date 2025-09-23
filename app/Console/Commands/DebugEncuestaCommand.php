<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Http\Controllers\EncuestaController;

class DebugEncuestaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:encuesta {idenvio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug encuesta para ver qué pregunta se está mostrando';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idenvio = $this->argument('idenvio');
        
        $this->info("Debug encuesta ID: {$idenvio}");
        
        try {
            $envio = Envio::with('cliente')->find($idenvio);
            
            if (!$envio) {
                $this->error("Envío no encontrado");
                return;
            }
            
            $this->info("=== INFORMACIÓN DEL ENVÍO ===");
            $this->info("ID: {$envio->idenvio}");
            $this->info("Cliente: {$envio->cliente->nombre_completo}");
            $this->info("Estado: {$envio->estado}");
            $this->info("Pregunta actual: {$envio->pregunta_actual}");
            $this->info("Respuesta 3: " . ($envio->respuesta_3 ? 'Sí' : 'No'));
            $this->info("Timer activo: " . ($envio->timer_activo ? 'Sí' : 'No'));
            
            // Simular la lógica del controlador
            $controller = new EncuestaController();
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('determinarPreguntaActual');
            $method->setAccessible(true);
            
            $preguntaActual = $method->invoke($controller, $envio);
            
            $this->info("=== RESULTADO ===");
            $this->info("Pregunta determinada: {$preguntaActual}");
            
            if ($preguntaActual === 'encuesta') {
                $this->info("✅ Debería mostrar el contenido simple");
            } elseif ($preguntaActual === 'completado') {
                $this->info("✅ Debería mostrar la página de completado");
            } elseif ($preguntaActual === '1.1') {
                $this->info("✅ Debería mostrar la pregunta 1.1 con estrellas");
            } else {
                $this->warn("⚠️ Pregunta no reconocida: {$preguntaActual}");
            }
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
