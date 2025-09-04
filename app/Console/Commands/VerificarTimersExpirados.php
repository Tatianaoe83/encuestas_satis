<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class VerificarTimersExpirados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timers:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar y cancelar timers expirados de encuestas';

    protected $twilioService;

    /**
     * Create a new command instance.
     */
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
        $this->info('Iniciando verificación de timers expirados...');
        
        try {
            $resultado = $this->twilioService->verificarTimersExpirados();
            
            if ($resultado['success']) {
                $this->info("Verificación completada exitosamente.");
                $this->info("Timers cancelados: " . $resultado['timers_cancelados']);
                
                Log::info("Comando de verificación de timers ejecutado", [
                    'timers_cancelados' => $resultado['timers_cancelados'],
                    'timestamp' => now()
                ]);
            } else {
                $this->error("Error en la verificación: " . $resultado['error']);
                
                Log::error("Error en comando de verificación de timers", [
                    'error' => $resultado['error'],
                    'timestamp' => now()
                ]);
            }
            
        } catch (\Exception $e) {
            $this->error("Error ejecutando comando: " . $e->getMessage());
            
            Log::error("Error en comando de verificación de timers", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);
        }
        
        $this->info('Verificación de timers finalizada.');
    }
}
