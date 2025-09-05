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
     * Ejecutar cron interno automáticamente
     */
    public function ejecutarCronInterno(Request $request)
    {
        $cacheKey = 'internal_cron_last_run';
        $lastRun = Cache::get($cacheKey);
        $now = now();
        
        // Verificar si han pasado al menos 5 minutos desde la última ejecución
        if ($lastRun && $now->diffInMinutes($lastRun) < 5) {
            return response()->json([
                'success' => true,
                'message' => 'Cron ya ejecutado recientemente',
                'last_run' => $lastRun,
                'next_run' => $lastRun->addMinutes(5),
                'wait_minutes' => 5 - $now->diffInMinutes($lastRun)
            ]);
        }
        
        try {
            /*Log::info('Ejecutando cron interno automáticamente', [
                'timestamp' => $now,
                'last_run' => $lastRun,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);*/
            
            // Ejecutar verificación de timers
            $resultado = $this->twilioService->verificarTimersExpirados();
            
            // Actualizar timestamp de última ejecución
            Cache::put($cacheKey, $now, now()->addHours(1));
            
            Log::info('Cron interno ejecutado exitosamente', [
                'timestamp' => $now,
                'resultado' => $resultado
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Cron interno ejecutado exitosamente',
                'data' => [
                    'timestamp' => $now,
                    'timers_cancelados' => $resultado['timers_cancelados'] ?? 0,
                    'last_run' => $now,
                    'next_run' => $now->addMinutes(5)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error ejecutando cron interno', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => $now
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error ejecutando cron interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado del cron interno
     */
    public function verificarEstadoCron()
    {
        $cacheKey = 'internal_cron_last_run';
        $lastRun = Cache::get($cacheKey);
        $now = now();
        
        $estado = [
            'ultima_ejecucion' => $lastRun,
            'tiempo_transcurrido_minutos' => $lastRun ? $now->diffInMinutes($lastRun) : null,
            'proxima_ejecucion' => $lastRun ? $lastRun->addMinutes(5) : $now,
            'puede_ejecutar' => !$lastRun || $now->diffInMinutes($lastRun) >= 5,
            'timers_activos' => \App\Models\Envio::where('timer_activo', true)
                ->where('estado', 'esperando_respuesta')
                ->where('tiempo_expiracion', '>', now())
                ->count(),
            'timers_expirados' => \App\Models\Envio::where('timer_activo', true)
                ->where('estado', 'esperando_respuesta')
                ->where('tiempo_expiracion', '<', now())
                ->count()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }

    /**
     * Forzar ejecución del cron (para testing)
     */
    public function forzarEjecucion(Request $request)
    {
        try {
            Log::info('Forzando ejecución del cron interno', [
                'timestamp' => now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Ejecutar verificación de timers
            $resultado = $this->twilioService->verificarTimersExpirados();
            
            return response()->json([
                'success' => true,
                'message' => 'Cron forzado ejecutado exitosamente',
                'data' => [
                    'timestamp' => now(),
                    'timers_cancelados' => $resultado['timers_cancelados'] ?? 0
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
