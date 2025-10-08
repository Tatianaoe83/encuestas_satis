<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\TwilioService;

class CronInternoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:interno 
                            {--force : Forzar ejecución sin verificar cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecutar cron interno para verificar timers expirados y recordatorios';

    /**
     * Execute the console command.
     */
    public function handle(TwilioService $twilioService)
    {
        // Asegurar que se use la zona horaria correcta
        date_default_timezone_set(config('app.timezone'));
        
        $cacheKey = 'internal_cron_last_run';
        $lastRun = Cache::get($cacheKey);
        $now = \Carbon\Carbon::now();
        
        // Verificar si han pasado al menos 5 minutos desde la última ejecución (a menos que se fuerce)
        if (!$this->option('force') && $lastRun && $now->diffInMinutes($lastRun) < 1) {
            $this->info('Cron ya ejecutado recientemente. Última ejecución: ' . $lastRun->format('Y-m-d H:i:s'));
            $this->info('Próxima ejecución: ' . $lastRun->addMinutes(1)->format('Y-m-d H:i:s'));
            $this->info('Usa --force para ejecutar de todas formas');
            return 0;
        }
        
        try {
            $this->info('Ejecutando cron interno automáticamente...');
            
            // Ejecutar verificación de timers y recordatorios
            $resultadoTimers = $twilioService->verificarTimersExpirados();
            $resultadoRecordatorios = $twilioService->verificarRecordatorios();
            
            $resultado = [
                'timers_cancelados' => $resultadoTimers['timers_cancelados'] ?? 0,
                'recordatorios_enviados' => $resultadoRecordatorios['recordatorios_enviados'] ?? 0
            ];
            
            // Actualizar timestamp de última ejecución
            Cache::put($cacheKey, $now, \Carbon\Carbon::now()->addHours(1));
            
            Log::info('Cron interno ejecutado exitosamente via schedule', [
                'timestamp' => $now,
                'resultado' => $resultado
            ]);
            
            $this->info('✅ Cron interno ejecutado exitosamente');
            $this->info('🕐 Timers cancelados: ' . $resultado['timers_cancelados']);
            $this->info('📨 Recordatorios enviados: ' . $resultado['recordatorios_enviados']);
            $this->info('⏰ Timestamp: ' . $now->format('Y-m-d H:i:s'));
            
            return 0;
            
        } catch (\Exception $e) {
            Log::error('Error ejecutando cron interno via schedule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => $now
            ]);
            
            $this->error('❌ Error ejecutando cron interno: ' . $e->getMessage());
            return 1;
        }
    }
}
