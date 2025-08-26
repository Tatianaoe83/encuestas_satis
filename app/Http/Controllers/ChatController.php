<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Models\ChatRespuesta;
use App\Services\TwilioService;

class ChatController extends Controller
{
    /**
     * Enviar mensaje de chat y registrar respuesta
     */
    public function enviarMensaje(Request $request)
    {
        
        try {
            // Log de los datos recibidos
            Log::info('Datos recibidos en enviarMensaje', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $request->validate([
                'to' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
                'mensaje' => 'required|string',
                'nombre' => 'nullable|string',
                'codigo' => 'nullable|string'
            ], [
                'to.regex' => 'El número de teléfono debe tener un formato válido (ej: +529961100930)'
            ]);

            $to = $request->input('to');
            $mensaje = $request->input('mensaje');
            $nombre = $request->input('nombre', 'Usuario');
            $codigo = $request->input('codigo', 'CHAT');

            // Log de los datos procesados
            Log::info('Datos procesados', [
                'to' => $to,
                'mensaje' => $mensaje,
                'nombre' => $nombre,
                'codigo' => $codigo
            ]);

            // Formatear número de WhatsApp
            $numeroOriginal = $to;
            
            // Remover caracteres no numéricos excepto el +
            $numeroLimpio = preg_replace('/[^0-9+]/', '', $to);
            
            // Asegurar que tenga el código de país (México: +52)
            if (!str_starts_with($numeroLimpio, '+')) {
                if (strlen($numeroLimpio) == 10) {
                    $numeroLimpio = '+52' . $numeroLimpio;
                } else {
                    $numeroLimpio = '+' . $numeroLimpio;
                }
            }
            
            // Agregar prefijo whatsapp: si no lo tiene
            if (!str_starts_with($numeroLimpio, 'whatsapp:')) {
                $to = 'whatsapp:' . $numeroLimpio;
            } else {
                $to = $numeroLimpio;
            }
            
            Log::info('Número formateado para WhatsApp', [
                'numero_original' => $numeroOriginal,
                'numero_limpio' => $numeroLimpio,
                'numero_final' => $to
            ]);

            // Usar TwilioService para enviar el mensaje
            $twilioService = new TwilioService();
            
            // Enviar mensaje usando TwilioService
            $resultado = $twilioService->enviarMensajeDirecto($numeroLimpio, $mensaje, $nombre, $codigo);
            
            if ($resultado['success']) {
                Log::info('Mensaje enviado exitosamente', [
                    'message_sid' => $resultado['message_sid'],
                    'to' => $numeroLimpio,
                    'mensaje' => $mensaje,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                    'status' => $resultado['status'],
                    'timestamp' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Mensaje enviado exitosamente',
                    'data' => [
                        'message_sid' => $resultado['message_sid'],
                        'to' => $numeroLimpio,
                        'status' => $resultado['status'],
                        'timestamp' => now()
                    ]
                ]);
            } else {
                throw new \Exception($resultado['error']);
            }

        } catch (\Exception $e) {
            // Registrar error con más detalles
            Log::error('Error enviando mensaje', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'to' => $to ?? 'N/A',
                'mensaje' => $mensaje ?? 'N/A',
                'timestamp' => now(),
                'trace' => $e->getTraceAsString()
            ]);

            // Determinar el tipo de error para dar un mensaje más específico
            $errorMessage = 'Error enviando mensaje';
            
            if (strpos($e->getMessage(), 'The to field is required') !== false) {
                $errorMessage = 'El campo "to" (número de teléfono) es requerido y debe tener un formato válido';
            } elseif (strpos($e->getMessage(), 'Unauthorized') !== false) {
                $errorMessage = 'Error de autenticación con Twilio. Verifica las credenciales configuradas';
            } elseif (strpos($e->getMessage(), 'Invalid phone number') !== false) {
                $errorMessage = 'El número de teléfono tiene un formato inválido. Debe incluir el código de país (+52 para México)';
            } elseif (strpos($e->getMessage(), 'whatsapp_from') !== false) {
                $errorMessage = 'Error en la configuración del número de WhatsApp de origen. Verifica TWILIO_WHATSAPP_FROM';
            } else {
                $errorMessage .= ': ' . $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historial de mensajes enviados y respuestas recibidas
     */
    public function obtenerHistorial()
    {
        try {
            // Obtener respuestas de la base de datos
            $respuestas = ChatRespuesta::orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            // Leer logs de mensajes enviados
            $logFile = storage_path('logs/laravel.log');
            $logs = [];
            
            if (file_exists($logFile)) {
                $content = file_get_contents($logFile);
                $lines = explode("\n", $content);
                
                foreach ($lines as $line) {
                    if (strpos($line, 'Mensaje enviado exitosamente') !== false) {
                        $logs[] = $line;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'respuestas' => $respuestas,
                    'logs' => $logs
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo historial: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener solo las respuestas recibidas
     */
    public function obtenerRespuestas()
    {
        try {
            $respuestas = ChatRespuesta::orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $respuestas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo respuestas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar configuración de Twilio
     */
    public function verificarConfiguracion()
    {
        try {
            $twilioService = new \App\Services\TwilioService();
            $configuracion = $twilioService->verificarConfiguracion();
            
            if ($configuracion['configuracion_completa']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuración de Twilio verificada correctamente',
                    'data' => $configuracion
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuración de Twilio incompleta',
                    'errors' => $configuracion['errores'],
                    'data' => $configuracion
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Error verificando configuración de Twilio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error verificando configuración: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook para recibir respuestas de Twilio
     */
    public function webhookRespuesta(Request $request)
    {
        try {
            // Log inicial para debug
            Log::info('=== WEBHOOK RECIBIDO ===', [
                'timestamp' => now(),
                'method' => $request->method(),
                'url' => $request->url(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Validar datos requeridos
            if (!$request->input('From') || !$request->input('Body') || !$request->input('MessageSid')) {
                Log::warning('Webhook recibido sin datos requeridos', [
                    'from' => $request->input('From'),
                    'body' => $request->input('Body'),
                    'message_sid' => $request->input('MessageSid'),
                    'all_data' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ], 400);
            }

            // Registrar respuesta recibida
            Log::info('Respuesta recibida de Twilio', [
                'from' => $request->input('From'),
                'to' => $request->input('To'),
                'body' => $request->input('Body'),
                'message_sid' => $request->input('MessageSid'),
                'timestamp' => now(),
                'all_data' => $request->all()
            ]);

            // Intentar guardar respuesta en la base de datos
            Log::info('Intentando guardar respuesta en BD...');
            
            $respuesta = ChatRespuesta::create([
                'message_sid' => $request->input('MessageSid'),
                'from_number' => $request->input('From'),
                'to_number' => $request->input('To'),
                'body' => $request->input('Body'),
                'status' => 'received',
                'twilio_data' => $request->all()
            ]);

            Log::info('Respuesta guardada exitosamente en BD', [
                'id' => $respuesta->id,
                'message_sid' => $respuesta->message_sid,
                'from_number' => $respuesta->from_number,
                'body' => $respuesta->body
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Respuesta recibida y registrada',
                'data' => $respuesta
            ]);

        } catch (\Exception $e) {
            Log::error('Error procesando respuesta de Twilio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando respuesta: ' . $e->getMessage()
            ], 500);
        }
    }
}
