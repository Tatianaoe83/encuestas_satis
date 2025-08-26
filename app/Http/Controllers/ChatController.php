<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Models\ChatRespuesta;

class ChatController extends Controller
{
    /**
     * Enviar mensaje de chat y registrar respuesta
     */
    public function enviarMensaje(Request $request)
    {
        try {
            $request->validate([
                'to' => 'required|string',
                'mensaje' => 'required|string',
                'nombre' => 'nullable|string',
                'codigo' => 'nullable|string'
            ]);

            $to = $request->input('to');
            $mensaje = $request->input('mensaje');
            $nombre = $request->input('nombre', 'Usuario');
            $codigo = $request->input('codigo', 'CHAT');

            // Formatear número de WhatsApp
            if (!str_starts_with($to, 'whatsapp:')) {
                $to = 'whatsapp:' . $to;
            }

            // Crear cliente de Twilio
            $client = new Client(
                config('services.twilio.account_sid'),
                config('services.twilio.auth_token')
            );

            // Enviar mensaje
            $message = $client->messages->create(
                $to,
                [
                    'from' => config('services.twilio.whatsapp_from'),
                    'body' => $mensaje
                ]
            );

            // Registrar envío exitoso
            Log::info('Mensaje enviado exitosamente', [
                'message_sid' => $message->sid,
                'to' => $to,
                'mensaje' => $mensaje,
                'nombre' => $nombre,
                'codigo' => $codigo,
                'status' => $message->status,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado exitosamente',
                'data' => [
                    'message_sid' => $message->sid,
                    'to' => $to,
                    'status' => $message->status,
                    'timestamp' => now()
                ]
            ]);

        } catch (\Exception $e) {
            // Registrar error
            Log::error('Error enviando mensaje', [
                'error' => $e->getMessage(),
                'to' => $to ?? 'N/A',
                'mensaje' => $mensaje ?? 'N/A',
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error enviando mensaje: ' . $e->getMessage()
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
