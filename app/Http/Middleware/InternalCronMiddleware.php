<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InternalCronMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ejecutar cron interno solo si han pasado al menos 5 minutos desde la última ejecución
        $this->runInternalCron();
        
        return $next($request);
    }

    /**
     * Ejecutar cron interno
     */
    protected function runInternalCron(): void
    {
        $cacheKey = 'internal_cron_last_run';
        $lastRun = Cache::get($cacheKey);
        $now = now();
        
        // Ejecutar solo si han pasado al menos 5 minutos desde la última ejecución
        if (!$lastRun || $now->diffInMinutes($lastRun) >= 5) {
            try {
                Log::info('Ejecutando cron interno automáticamente', [
                    'timestamp' => $now,
                    'last_run' => $lastRun
                ]);
                
                // Ejecutar comando de timers
                \Artisan::call('timers:verificar');
                
                // Actualizar timestamp de última ejecución
                Cache::put($cacheKey, $now, now()->addHours(1));
                
                Log::info('Cron interno ejecutado exitosamente', [
                    'timestamp' => $now,
                    'output' => \Artisan::output()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error ejecutando cron interno', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'timestamp' => $now
                ]);
            }
        }
    }
}
