<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\TwilioService;

class CronInternoController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }


    /**
     * Verificar estado del cron interno
     */
    public function verificarEstadoCron()
    {
        // Asegurar que se use la zona horaria correcta
        date_default_timezone_set(config('app.timezone'));
        
        $cacheKey = 'internal_cron_last_run';
        $lastRun = Cache::get($cacheKey);
        $now = \Carbon\Carbon::now();
        
        $estado = [
            'ultima_ejecucion' => $lastRun,
            'tiempo_transcurrido_minutos' => $lastRun ? $now->diffInMinutes($lastRun) : null,
            'proxima_ejecucion' => $lastRun ? $lastRun->addMinutes(5) : $now,
            'puede_ejecutar' => !$lastRun || $now->diffInMinutes($lastRun) >= 5,
            'timers_activos' => \App\Models\Envio::where('timer_activo', true)
                ->whereIn('estado', ['enviado', 'en_proceso', 'recordatorio_enviado'])
                ->where('tiempo_expiracion', '>', \Carbon\Carbon::now())
                ->count(),
            'timers_expirados' => \App\Models\Envio::where('timer_activo', true)
                ->whereIn('estado', ['enviado', 'en_proceso', 'recordatorio_enviado'])
                ->where('tiempo_expiracion', '<', \Carbon\Carbon::now())
                ->count()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }

    /**
     * Forzar ejecución del cron (para testing - la ejecución automática ahora es via schedule)
     */
    public function forzarEjecucion(Request $request)
    {
        try {
            // Asegurar que se use la zona horaria correcta
            date_default_timezone_set(config('app.timezone'));
            
            Log::info('Forzando ejecución del cron interno', [
                'timestamp' => \Carbon\Carbon::now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Ejecutar verificación de timers y recordatorios
            $resultadoTimers = $this->twilioService->verificarTimersExpirados();
            $resultadoRecordatorios = $this->twilioService->verificarRecordatorios();
            
            $resultado = [
                'timers_cancelados' => $resultadoTimers['timers_cancelados'] ?? 0,
                'recordatorios_enviados' => $resultadoRecordatorios['recordatorios_enviados'] ?? 0
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Cron forzado ejecutado exitosamente',
                'data' => [
                    'timestamp' => \Carbon\Carbon::now(),
                    'timers_cancelados' => $resultado['timers_cancelados'],
                    'recordatorios_enviados' => $resultado['recordatorios_enviados']
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error forzando ejecución del cron', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error forzando ejecución del cron',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
