<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Models\Cliente;

class CrearEnvioPruebaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:crear-envio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear envío de prueba para testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creando envío de prueba...');
        
        try {
            // Buscar un cliente existente o crear uno
            $cliente = Cliente::first();
            
            if (!$cliente) {
                $cliente = Cliente::create([
                    'nombre_completo' => 'Cliente Prueba',
                    'celular' => '5219993778529',
                    'email' => 'prueba@test.com'
                ]);
                $this->info("Cliente creado: {$cliente->nombre_completo}");
            } else {
                $this->info("Usando cliente existente: {$cliente->nombre_completo}");
            }
            
            // Crear envío de prueba
            $envio = Envio::create([
                'cliente_id' => $cliente->id,
                'estado' => 'enviado',
                'pregunta_actual' => 'encuesta',
                'timer_activo' => true,
                'estado_timer' => 'activo',
                'tiempo_expiracion' => now()->addMinutes(30),
                'tiempo_recordatorio' => now()->addMinutes(15),
                'recordatorio_enviado' => false
            ]);
            
            $this->info("✅ Envío creado exitosamente");
            $this->info("ID: {$envio->idenvio}");
            $this->info("Cliente: {$cliente->nombre_completo}");
            $this->info("Estado: {$envio->estado}");
            $this->info("Pregunta actual: {$envio->pregunta_actual}");
            $this->info("URL: /encuesta/{$envio->idenvio}");
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
