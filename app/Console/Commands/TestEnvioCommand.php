<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Services\TwilioService;

class TestEnvioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:envio {idenvio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar envío de encuesta';

    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        parent::__construct();
        $this->twilioService = $twilioService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idenvio = $this->argument('idenvio');
        
        $this->info("Probando envío de encuesta ID: {$idenvio}");
        
        try {
            $envio = Envio::find($idenvio);
            
            if (!$envio) {
                $this->error("Envío no encontrado");
                return;
            }
            
            $this->info("Envío encontrado: {$envio->idenvio}");
            $this->info("Cliente: {$envio->cliente->nombre_completo}");
            $this->info("Estado actual: {$envio->estado}");
            
            $resultado = $this->twilioService->enviarEncuesta($envio);
            
            if ($resultado) {
                $this->info("✅ Envío exitoso");
            } else {
                $this->error("❌ Error en el envío");
            }
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
