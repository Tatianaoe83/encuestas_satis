<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\Envio;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->fromNumber = config('services.twilio.whatsapp_from');
    }

    /**
     * Enviar encuesta por WhatsApp
     */
    public function enviarEncuesta(Envio $envio)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            
            // Construir el mensaje de la encuesta
            $mensaje = $this->construirMensajeEncuesta($envio);
            
            // Enviar mensaje por WhatsApp
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje
                ]
            );

            // Actualizar el envío con la información de Twilio
            $envio->update([
                'whatsapp_number' => $numeroWhatsApp,
                'twilio_message_sid' => $message->sid,
                'whatsapp_message' => $mensaje,
                'estado' => 'enviado',
                'fecha_envio' => now(),
                'whatsapp_sent_at' => now(),
            ]);

            Log::info("Encuesta enviada exitosamente", [
                'envio_id' => $envio->id,
                'cliente' => $cliente->nombre_completo,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error enviando encuesta por WhatsApp", [
                'envio_id' => $envio->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Procesar respuesta recibida por WhatsApp
     */
    public function procesarRespuesta($from, $body, $messageSid)
    {
        try {
            // Buscar el envío por el número de WhatsApp
            $envio = Envio::where('whatsapp_number', $from)
                         ->where('estado', 'enviado')
                         ->latest()
                         ->first();

            if (!$envio) {
                Log::warning("No se encontró envío para el número: {$from}");
                return false;
            }

            // Procesar la respuesta según el formato esperado
            $respuestas = $this->parsearRespuesta($body);
            
            // Actualizar el envío con las respuestas
            $envio->update([
                'respuesta_1' => $respuestas['pregunta_1'] ?? null,
                'respuesta_2' => $respuestas['pregunta_2'] ?? null,
                'respuesta_3' => $respuestas['pregunta_3'] ?? null,
                'respuesta_4' => $respuestas['pregunta_4'] ?? null,
                'whatsapp_responses' => $respuestas,
                'estado' => 'respondido',
                'fecha_respuesta' => now(),
                'whatsapp_responded_at' => now(),
            ]);

            // Enviar mensaje de confirmación
            $this->enviarConfirmacion($from, $envio);

            Log::info("Respuesta procesada exitosamente", [
                'envio_id' => $envio->id,
                'numero' => $from,
                'respuestas' => $respuestas
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error procesando respuesta de WhatsApp", [
                'from' => $from,
                'body' => $body,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Construir el mensaje de la encuesta
     */
    protected function construirMensajeEncuesta(Envio $envio)
    {
        $cliente = $envio->cliente;
        
        $mensaje = "🏗️ *Encuesta de Satisfacción - Proser*\n\n";
        $mensaje .= "Hola {$cliente->nombre_completo},\n\n";
        $mensaje .= "Gracias por confiar en Proser. Nos gustaría conocer tu opinión sobre nuestro servicio.\n\n";
        $mensaje .= "*Por favor responde las siguientes preguntas:*\n\n";
        
        $mensaje .= "1️⃣ *Pregunta 1 (Escala 0-10):*\n";
        $mensaje .= "En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?\n";
        $mensaje .= "Responde solo con un número del 0 al 10.\n\n";
        
        $mensaje .= "2️⃣ *Pregunta 2:*\n";
        $mensaje .= "¿Cuál es la razón principal de tu calificación?\n\n";
        
        $mensaje .= "3️⃣ *Pregunta 3 (Opcional):*\n";
        $mensaje .= "¿A qué tipo de obra se destinó este concreto?\n";
        $mensaje .= "Opciones: Vivienda unifamiliar, Edificio vertical, Obra vial, Obra industrial, Otro\n\n";
        
        $mensaje .= "4️⃣ *Pregunta 4 (Opcional):*\n";
        $mensaje .= "¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?\n\n";
        
        $mensaje .= "*Formato de respuesta:*\n";
        $mensaje .= "1. [número del 0 al 10]\n";
        $mensaje .= "2. [tu razón]\n";
        $mensaje .= "3. [tipo de obra]\n";
        $mensaje .= "4. [sugerencia de mejora]\n\n";
        
        $mensaje .= "¡Gracias por tu tiempo! 🙏";

        return $mensaje;
    }

    /**
     * Parsear la respuesta del cliente
     */
    protected function parsearRespuesta($body)
    {
        $lineas = explode("\n", trim($body));
        $respuestas = [];
        
        foreach ($lineas as $linea) {
            $linea = trim($linea);
            
            // Buscar patrones como "1.", "2.", etc.
            if (preg_match('/^(\d+)\.\s*(.+)$/', $linea, $matches)) {
                $numeroPregunta = $matches[1];
                $respuesta = trim($matches[2]);
                
                switch ($numeroPregunta) {
                    case '1':
                        $respuestas['pregunta_1'] = $respuesta;
                        break;
                    case '2':
                        $respuestas['pregunta_2'] = $respuesta;
                        break;
                    case '3':
                        $respuestas['pregunta_3'] = $respuesta;
                        break;
                    case '4':
                        $respuestas['pregunta_4'] = $respuesta;
                        break;
                }
            }
        }
        
        return $respuestas;
    }

    /**
     * Enviar mensaje de confirmación
     */
    protected function enviarConfirmacion($to, Envio $envio)
    {
        try {
            $mensaje = "✅ *¡Gracias por completar nuestra encuesta!*\n\n";
            $mensaje .= "Hemos recibido tus respuestas y las tendremos en cuenta para mejorar nuestros servicios.\n\n";
            $mensaje .= "Si tienes alguna consulta adicional, no dudes en contactarnos.\n\n";
            $mensaje .= "¡Que tengas un excelente día! 🏗️";

            $this->client->messages->create(
                "whatsapp:{$to}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje
                ]
            );

        } catch (\Exception $e) {
            Log::error("Error enviando confirmación", [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Formatear número para WhatsApp
     */
    protected function formatearNumeroWhatsApp($numero)
    {
        // Remover caracteres no numéricos
        $numero = preg_replace('/[^0-9]/', '', $numero);
        
        // Asegurar que tenga el código de país (México: 52)
        if (strlen($numero) == 10) {
            $numero = '52' . $numero;
        }
        
        return $numero;
    }
} 