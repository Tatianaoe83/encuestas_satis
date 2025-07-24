<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Manejar webhook de mensajes entrantes de WhatsApp
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Verificar que la solicitud viene de Twilio
            Log::info("Webhook recibido de Twilio", [
                'request' => $request->all()
            ]);
            
            if (!$this->verificarFirmaTwilio($request)) {
                Log::warning("Solicitud webhook no verificada como de Twilio");
                return response('Unauthorized', 401);
            }

            // Obtener datos del mensaje
            $from = $request->input('From');
            $body = $request->input('Body');
            $messageSid = $request->input('MessageSid');
            $messageStatus = $request->input('MessageStatus');

            Log::info("Webhook recibido de Twilio", [
                'from' => $from,
                'body' => $body,
                'message_sid' => $messageSid,
                'status' => $messageStatus
            ]);

            // Solo procesar mensajes entrantes (no de estado)
            if ($messageStatus === 'received') {
                // Remover el prefijo "whatsapp:" del número
                $numero = str_replace('whatsapp:', '', $from);
                
                // Procesar la respuesta
                $resultado = $this->twilioService->procesarRespuesta($numero, $body, $messageSid);
                
                if ($resultado) {
                    return response('OK', 200);
                } else {
                    return response('Error processing message', 500);
                }
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error("Error procesando webhook de Twilio", [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Manejar webhook de estado de mensajes
     */
    public function handleStatusWebhook(Request $request)
    {
        try {
            // Verificar que la solicitud viene de Twilio
            if (!$this->verificarFirmaTwilio($request)) {
                Log::warning("Solicitud de estado webhook no verificada como de Twilio");
                return response('Unauthorized', 401);
            }

            $messageSid = $request->input('MessageSid');
            $messageStatus = $request->input('MessageStatus');
            $errorCode = $request->input('ErrorCode');
            $errorMessage = $request->input('ErrorMessage');

            Log::info("Estado de mensaje actualizado", [
                'message_sid' => $messageSid,
                'status' => $messageStatus,
                'error_code' => $errorCode,
                'error_message' => $errorMessage
            ]);

            // Aquí podrías actualizar el estado del mensaje en tu base de datos
            // si necesitas hacer seguimiento del estado de entrega

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error("Error procesando webhook de estado", [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Verificar la firma de Twilio para autenticar el webhook
     */
    protected function verificarFirmaTwilio(Request $request)
    {
        $twilioSignature = $request->header('X-Twilio-Signature');
        $url = $request->fullUrl();
        $params = $request->all();
        
        // Ordenar los parámetros alfabéticamente
        ksort($params);
        
        // Construir la cadena de parámetros
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= $key . $value;
        }
        
        // Construir la cadena a firmar
        $stringToSign = $url . $paramString;
        
        // Generar la firma esperada
        $expectedSignature = base64_encode(hash_hmac('sha1', $stringToSign, config('services.twilio.auth_token'), true));
        
        return hash_equals($expectedSignature, $twilioSignature);
    }

    /**
     * Endpoint de prueba para verificar que el webhook funciona
     */
    public function test()
    {
        return response()->json([
            'message' => 'Webhook endpoint is working',
            'timestamp' => now()
        ]);
    }
} 