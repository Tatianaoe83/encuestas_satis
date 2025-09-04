<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Envio;
use App\Services\TwilioService;

class ContenidoAprobadoController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Enviar contenido aprobado y configurar timer
     */
    public function enviarContenidoAprobado(Request $request)
    {
        try {
            $request->validate([
                'envio_id' => 'required|integer|exists:envios,idenvio',
                'tiempo_espera_minutos' => 'integer|min:1|max:1440' // Máximo 24 horas
            ]);

            $envio = Envio::findOrFail($request->envio_id);
            $tiempoEspera = $request->input('tiempo_espera_minutos', 30);

            // Verificar que el envío esté en estado válido
            if (!in_array($envio->estado, ['pendiente', 'enviado'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'El envío no está en un estado válido para enviar contenido aprobado'
                ], 400);
            }

            // Verificar configuración de Twilio
            $configuracion = $this->twilioService->verificarConfiguracion();
            if (!$configuracion['configuracion_completa']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuración de Twilio incompleta',
                    'errors' => $configuracion['errores']
                ], 400);
            }

            // Enviar contenido aprobado
            $resultado = $this->twilioService->enviarContenidoAprobado($envio, $tiempoEspera);

            if ($resultado['success']) {
                Log::info("Contenido aprobado enviado exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'tiempo_espera' => $tiempoEspera,
                    'message_sid' => $resultado['message_sid']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Contenido aprobado enviado exitosamente',
                    'data' => [
                        'envio_id' => $envio->idenvio,
                        'message_sid' => $resultado['message_sid'],
                        'tiempo_expiracion' => $resultado['tiempo_expiracion'],
                        'estado' => $resultado['estado']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error enviando contenido aprobado',
                    'error' => $resultado['error']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error("Error en envío de contenido aprobado", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estado de timers activos
     */
    public function obtenerTimersActivos()
    {
        try {
            $timersActivos = Envio::where('timer_activo', true)
                ->where('estado', 'esperando_respuesta')
                ->where('tiempo_expiracion', '>', now())
                ->with('cliente')
                ->get()
                ->map(function ($envio) {
                    return [
                        'envio_id' => $envio->idenvio,
                        'cliente' => $envio->cliente->nombre_completo,
                        'numero' => $envio->cliente->celular,
                        'tiempo_expiracion' => $envio->tiempo_expiracion,
                        'tiempo_restante_minutos' => $envio->tiempo_expiracion->diffInMinutes(now()),
                        'estado_timer' => $envio->estado_timer,
                        'esperando_desde' => $envio->esperando_respuesta_desde
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'timers_activos' => $timersActivos,
                    'total' => $timersActivos->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error obteniendo timers activos", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo timers activos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar timer manualmente
     */
    public function cancelarTimer(Request $request)
    {
        try {
            $request->validate([
                'envio_id' => 'required|integer|exists:envios,idenvio'
            ]);

            $envio = Envio::findOrFail($request->envio_id);

            if (!$envio->timer_activo || $envio->estado !== 'esperando_respuesta') {
                return response()->json([
                    'success' => false,
                    'message' => 'El envío no tiene un timer activo'
                ], 400);
            }

            // Cancelar timer
            $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'cancelado_manual'
            ]);

            Log::info("Timer cancelado manualmente", [
                'envio_id' => $envio->idenvio,
                'usuario' => auth()->user()->name ?? 'sistema'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Timer cancelado exitosamente',
                'data' => [
                    'envio_id' => $envio->idenvio,
                    'estado' => 'cancelado'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error cancelando timer", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error cancelando timer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar timers expirados manualmente
     */
    public function verificarTimersExpirados()
    {
        try {
            $resultado = $this->twilioService->verificarTimersExpirados();

            if ($resultado['success']) {
                Log::info("Verificación manual de timers ejecutada", [
                    'timers_cancelados' => $resultado['timers_cancelados'],
                    'usuario' => auth()->user()->name ?? 'sistema'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Verificación completada exitosamente',
                    'data' => [
                        'timers_cancelados' => $resultado['timers_cancelados']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la verificación',
                    'error' => $resultado['error']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error("Error en verificación manual de timers", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en verificación de timers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de timers
     */
    public function obtenerEstadisticasTimers()
    {
        try {
            $estadisticas = [
                'total_timers_activos' => Envio::where('timer_activo', true)
                    ->where('estado', 'esperando_respuesta')
                    ->where('tiempo_expiracion', '>', now())
                    ->count(),
                
                'total_timers_expirados' => Envio::where('timer_activo', true)
                    ->where('estado', 'esperando_respuesta')
                    ->where('tiempo_expiracion', '<', now())
                    ->count(),
                
                'total_respondidos' => Envio::where('estado_timer', 'respondido')->count(),
                
                'total_cancelados' => Envio::where('estado_timer', 'expirado')
                    ->orWhere('estado_timer', 'cancelado_manual')
                    ->count(),
                
                'promedio_tiempo_espera' => Envio::where('tiempo_espera_minutos', '>', 0)
                    ->avg('tiempo_espera_minutos')
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            Log::error("Error obteniendo estadísticas de timers", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
