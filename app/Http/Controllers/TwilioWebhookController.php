<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ChatRespuesta;
use App\Models\Envio;
use App\Services\TwilioService;

class TwilioWebhookController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Webhook principal para recibir respuestas de Twilio
     */
    public function webhook(Request $request)
    {
      
        try {
            // Extraer datos del webhook de Twilio
            $from = $request->input('From');
            $body = $request->input('Body');
            $messageSid = $request->input('MessageSid');
            $to = $request->input('To');

            // Validar datos requeridos
            if (!$from || !$body || !$messageSid) {
                Log::warning('Datos incompletos en webhook', [
                    'from' => $from,
                    'body' => $body,
                    'message_sid' => $messageSid
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ], 400);
            }

            // Limpiar número de WhatsApp (remover prefijo whatsapp:)
            $cleanFrom = str_replace('whatsapp:', '', $from);
            
            // Guardar respuesta en la tabla de chat
            $chatRespuesta = ChatRespuesta::create([
                'message_sid' => $messageSid,
                'from_number' => $cleanFrom,
                'to_number' => str_replace('whatsapp:', '', $to),
                'body' => $body,
                'status' => 'received',
                'twilio_data' => $request->all()
            ]);

            Log::info('Respuesta de chat guardada', [
                'id' => $chatRespuesta->id,
                'from' => $cleanFrom,
                'body' => $body
            ]);

            // Procesar respuesta y enviar siguiente pregunta
            $resultado = $this->twilioService->procesarRespuesta($cleanFrom, $body, $messageSid);

            if ($resultado) {
                Log::info('Respuesta procesada exitosamente', [
                    'from' => $cleanFrom,
                    'message_sid' => $messageSid
                ]);
            } else {
                Log::warning('No se pudo procesar la respuesta', [
                    'from' => $cleanFrom,
                    'message_sid' => $messageSid
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook procesado correctamente',
                'data' => [
                    'chat_respuesta_id' => $chatRespuesta->id,
                    'procesado' => $resultado,
                    'timestamp' => now()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error procesando webhook de Twilio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook de prueba limpio para testing
     */
    public function webhookTestClean(Request $request)
    {
        Log::info('=== WEBHOOK TEST LIMPIO RECIBIDO ===', [
            'timestamp' => now(),
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip()
        ]);

        try {
            // Extraer datos del webhook
            $from = $request->input('From', 'TEST_FROM');
            $body = $request->input('Body', 'TEST_BODY');
            $messageSid = $request->input('MessageSid', 'TEST_' . time());
            $to = $request->input('To', 'TEST_TO');

            // Limpiar número de WhatsApp (remover prefijo whatsapp:)
            $cleanFrom = str_replace('whatsapp:', '', $from);
            
            // Guardar respuesta en la tabla de chat
            $respuesta = ChatRespuesta::create([
                'message_sid' => $messageSid,
                'from_number' => $cleanFrom,
                'to_number' => str_replace('whatsapp:', '', $to),
                'body' => $body,
                'status' => 'received',
                'twilio_data' => $request->all()
            ]);

            Log::info('Respuesta de prueba guardada exitosamente', [
                'id' => $respuesta->id,
                'message_sid' => $messageSid,
                'from' => $cleanFrom,
                'body' => $body
            ]);

            // Procesar respuesta y enviar siguiente pregunta (FLUJO COMPLETO)
            $resultado = $this->twilioService->procesarRespuesta($cleanFrom, $body, $messageSid);

            if ($resultado) {
                Log::info('Respuesta procesada exitosamente en webhook test', [
                    'from' => $cleanFrom,
                    'message_sid' => $messageSid,
                    'procesado' => $resultado
                ]);
            } else {
                Log::warning('No se pudo procesar la respuesta en webhook test', [
                    'from' => $cleanFrom,
                    'message_sid' => $messageSid
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook de prueba recibido y procesado completamente',
                'data' => [
                    'chat_respuesta_id' => $respuesta->id,
                    'procesado' => $resultado,
                    'timestamp' => now()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en webhook test clean', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estado de un envío específico
     */
    public function getEstadoEnvio(Request $request)
    {
        try {
            $whatsappNumber = $request->input('whatsapp_number');
            
            if (!$whatsappNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Número de WhatsApp requerido'
                ], 400);
            }

            $envio = Envio::where('whatsapp_number', $whatsappNumber)
                         ->latest()
                         ->first();

            if (!$envio) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró envío para este número'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'envio_id' => $envio->idenvio,
                    'estado' => $envio->estado,
                    'pregunta_actual' => $envio->pregunta_actual,
                    'fecha_envio' => $envio->fecha_envio,
                    'fecha_respuesta' => $envio->fecha_respuesta,
                    'respuestas' => [
                        'respuesta_1' => $envio->respuesta_1,
                        'respuesta_2' => $envio->respuesta_2,
                        'respuesta_3' => $envio->respuesta_3,
                        'respuesta_4' => $envio->respuesta_4,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo estado del envío', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
} 