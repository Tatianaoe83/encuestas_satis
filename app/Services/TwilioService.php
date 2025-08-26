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
        Log::info("Enviando encuesta por WhatsApp", [
            'envio_id' => $envio->idenvio,
            'cliente_id' => $envio->cliente_id
        ]);
        
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);

            Log::info("Número de WhatsApp", [
                'numeroWhatsApp' => $numeroWhatsApp
            ]);

            // Enviar solo la primera pregunta
            $mensaje = $this->construirPrimeraPregunta($envio);
            Log::info("Primera pregunta enviada", [
                'mensaje' => $mensaje
            ]);

            // Verificar si estamos en modo de prueba
            if (app()->environment('local') || config('app.debug')) {
                Log::info("MODO PRUEBA: Simulando envío de WhatsApp");
                
                // Actualizar el envío con la información simulada
                $envio->update([
                    'whatsapp_number' => $numeroWhatsApp,
                    'twilio_message_sid' => 'SIM_' . time(),
                    'whatsapp_message' => $mensaje,
                    'estado' => 'enviado',
                    'fecha_envio' => now(),
                    'whatsapp_sent_at' => now(),
                    'pregunta_actual' => 1, // Marcar que estamos en la primera pregunta
                ]);

                Log::info("Primera pregunta simulada exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'cliente' => $cliente->nombre_completo,
                    'numero' => $numeroWhatsApp,
                    'message_sid' => 'SIM_' . time(),
                    'pregunta_actual' => 1
                ]);

                return true;
            }

            // Envío real a Twilio
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
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
                'pregunta_actual' => 1, // Marcar que estamos en la primera pregunta
            ]);

            Log::info("Primera pregunta enviada exitosamente", [
                'envio_id' => $envio->idenvio,
                'cliente' => $cliente->nombre_completo,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid,
                'pregunta_actual' => 1
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error enviando encuesta por WhatsApp", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Actualizar el estado del envío como fallido
            $envio->update([
                'estado' => 'error',
                'whatsapp_error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Enviar siguiente pregunta basada en la respuesta anterior
     */
    public function enviarSiguientePregunta(Envio $envio, $respuestaAnterior)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            
            // Determinar qué pregunta enviar
            $preguntaActual = $envio->pregunta_actual ?? 1;
            $siguientePregunta = $preguntaActual + 1;
            
            // Si ya respondió la pregunta actual, guardar la respuesta
            if ($preguntaActual <= 4) {
                $campoRespuesta = "respuesta_{$preguntaActual}";
                $envio->update([
                    $campoRespuesta => $respuestaAnterior
                ]);
            }
            
            // Si es la última pregunta o ya se completó
            if ($siguientePregunta > 4) {
                // Enviar mensaje de agradecimiento
                $mensaje = $this->construirMensajeAgradecimiento($envio);
                
                // Verificar si estamos en modo de prueba
                if (app()->environment('local') || config('app.debug')) {
                    Log::info("MODO PRUEBA: Simulando envío de mensaje de agradecimiento");
                } else {
                    $message = $this->client->messages->create(
                        "whatsapp:{$numeroWhatsApp}",
                        [
                            'from' => "whatsapp:{$this->fromNumber}",
                            'body' => $mensaje,
                        ]
                    );
                }
                
                // Marcar como completado
                $envio->update([
                    'estado' => 'completado',
                    'fecha_respuesta' => now(),
                    'whatsapp_responded_at' => now(),
                    'pregunta_actual' => 4
                ]);
                
                Log::info("Encuesta completada", [
                    'envio_id' => $envio->idenvio,
                    'numero' => $numeroWhatsApp
                ]);
                
                return true;
            }
            
            // Enviar siguiente pregunta
            $mensaje = $this->construirPregunta($envio, $siguientePregunta);
            
            // Verificar si estamos en modo de prueba
            if (app()->environment('local') || config('app.debug')) {
                Log::info("MODO PRUEBA: Simulando envío de siguiente pregunta");
            } else {
                $message = $this->client->messages->create(
                    "whatsapp:{$numeroWhatsApp}",
                    [
                        'from' => "whatsapp:{$this->fromNumber}",
                        'body' => $mensaje,
                    ]
                );
            }
            
            // Actualizar pregunta actual y estado
            $envio->update([
                'pregunta_actual' => $siguientePregunta,
                'whatsapp_message' => $mensaje,
                'estado' => 'en_proceso' // Marcar como en proceso mientras se contesta
            ]);
            
            Log::info("Siguiente pregunta enviada", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $siguientePregunta,
                'numero' => $numeroWhatsApp
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Error enviando siguiente pregunta", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Construir la primera pregunta
     */
    protected function construirPrimeraPregunta(Envio $envio)
    {
        $cliente = $envio->cliente;
        
        $mensaje = "🏗️ *Encuesta de Satisfacción - Proser*\n\n";
        $mensaje .= "Hola {$cliente->nombre_completo},\n\n";
        $mensaje .= "Gracias por confiar en Proser. Nos gustaría conocer tu opinión sobre nuestro servicio.\n\n";
        $mensaje .= "Te enviaré 4 preguntas una por una para facilitar tu respuesta.\n\n";
        $mensaje .= "📝 *Pregunta 1 de 4:*\n";
        $mensaje .= "En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?\n\n";
        $mensaje .= "Responde solo con un número del 0 al 10.";

        return $mensaje;
    }

    /**
     * Construir pregunta específica
     */
    protected function construirPregunta(Envio $envio, $numeroPregunta)
    {
        $cliente = $envio->cliente;
        
        switch ($numeroPregunta) {
            case 2:
                $mensaje = "📝 *Pregunta 2 de 4:*\n";
                $mensaje .= "¿Cuál es la razón principal de tu calificación?\n\n";
                $mensaje .= "Responde con tu razón.";
                break;
                
            case 3:
                $mensaje = "📝 *Pregunta 3 de 4:*\n";
                $mensaje .= "¿A qué tipo de obra se destinó este concreto?\n\n";
                $mensaje .= "Opciones:\n";
                $mensaje .= "• Vivienda unifamiliar\n";
                $mensaje .= "• Edificio vertical\n";
                $mensaje .= "• Obra vial\n";
                $mensaje .= "• Obra industrial\n";
                $mensaje .= "• Otro\n\n";
                $mensaje .= "Responde con una de las opciones o describe tu caso.";
                break;
                
            case 4:
                $mensaje = "📝 *Pregunta 4 de 4:*\n";
                $mensaje .= "¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?\n\n";
                $mensaje .= "Responde con tu sugerencia o escribe 'N/A' si no tienes sugerencias.";
                break;
                
            default:
                $mensaje = "❓ Pregunta no válida";
        }

        return $mensaje;
    }

    /**
     * Construir mensaje de agradecimiento
     */
    protected function construirMensajeAgradecimiento(Envio $envio)
    {
        $mensaje = "✅ *¡Gracias por completar nuestra encuesta!*\n\n";
        $mensaje .= "Hemos recibido todas tus respuestas y las tendremos en cuenta para mejorar nuestros servicios.\n\n";
        $mensaje .= "Si tienes alguna consulta adicional, no dudes en contactarnos.\n\n";
        $mensaje .= "¡Que tengas un excelente día! 🏗️";

        return $mensaje;
    }

    /**
     * Procesar respuesta recibida por WhatsApp
     */
    public function procesarRespuesta($from, $body, $messageSid)
    {
        try {
            // Buscar el envío por el número de WhatsApp o por número de celular del cliente
            $envio = Envio::where(function($query) use ($from) {
                $query->where('whatsapp_number', $from)
                      ->orWhereHas('cliente', function($q) use ($from) {
                          $q->where('celular', 'LIKE', '%' . $from . '%');
                      });
            })
            ->whereIn('estado', ['enviado', 'en_proceso'])
            ->latest()
            ->first();

            if (!$envio) {
                Log::warning("No se encontró envío para el número: {$from}");
                return false;
            }

            // Procesar la respuesta y enviar siguiente pregunta
            $resultado = $this->enviarSiguientePregunta($envio, $body);

            if ($resultado) {
                Log::info("Respuesta procesada exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'numero' => $from,
                    'respuesta' => $body,
                    'pregunta_actual' => $envio->pregunta_actual
                ]);
            }

            return $resultado;

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
            $numero = '521' . $numero;
        }
        
        return $numero;
    }

    /**
     * Método de prueba para verificar la conexión con Twilio
     */
    public function probarConexion($numeroPrueba = null)
    {
        try {
            // Si no se proporciona número de prueba, usar uno por defecto
            if (!$numeroPrueba) {
                $numeroPrueba = '5219993778529'; // Número del ejemplo
            }

            $numeroWhatsApp = $this->formatearNumeroWhatsApp($numeroPrueba);

            Log::info("Probando conexión con Twilio", [
                'numero_original' => $numeroPrueba,
                'numero_formateado' => $numeroWhatsApp,
                'from_number' => $this->fromNumber
            ]);

            // Enviar mensaje de prueba
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => "🧪 *Prueba de conexión*\n\nEste es un mensaje de prueba para verificar que la integración con Twilio funciona correctamente.\n\nFecha: " . now()->format('d/m/Y H:i:s') . "\n\n✅ Si recibes este mensaje, la configuración está correcta."
                ]
            );

            Log::info("Prueba de conexión exitosa", [
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status,
                'numero_enviado' => $numeroWhatsApp
            ];

        } catch (\Exception $e) {
            Log::error("Error en prueba de conexión", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'numero_intentado' => $numeroWhatsApp ?? $numeroPrueba
            ];
        }
    }

    /**
     * Verificar configuración de Twilio
     */
    public function verificarConfiguracion()
    {
        $config = [
            'account_sid' => config('services.twilio.account_sid'),
            'auth_token' => config('services.twilio.auth_token'),
            'whatsapp_from' => config('services.twilio.whatsapp_from')
        ];

        $errores = [];
        
        if (empty($config['account_sid'])) {
            $errores[] = 'TWILIO_ACCOUNT_SID no está configurado';
        }
        
        if (empty($config['auth_token'])) {
            $errores[] = 'TWILIO_AUTH_TOKEN no está configurado';
        }
        
        if (empty($config['whatsapp_from'])) {
            $errores[] = 'TWILIO_WHATSAPP_FROM no está configurado';
        }

        return [
            'configuracion_completa' => empty($errores),
            'errores' => $errores,
            'config' => array_map(function($value) {
                return $value ? 'Configurado' : 'No configurado';
            }, $config)
        ];
    }
} 