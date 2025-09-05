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
            $contentSid = config('services.twilio.content_sid');

            Log::info("Número de WhatsApp", [
                'numeroWhatsApp' => $numeroWhatsApp,
                'content_sid' => $contentSid
            ]);

            if (!$contentSid) {
                throw new \Exception('Content SID no está configurado');
            }

            Log::info("Enviando contenido aprobado", [
                'envio_id' => $envio->idenvio,
                'content_sid' => $contentSid
            ]);

            // Preparar variables de contenido
            $contentVariables = [
                'nombre' => $cliente->nombre_completo ?? 'Cliente',
                'encuesta' => (string) ($envio->idenvio ?? '0')
            ];
            
            Log::info("Variables de contenido preparadas", [
                'content_variables' => $contentVariables
            ]);

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'contentSid' => $contentSid,
                    'contentVariables' => json_encode($contentVariables)
                ]
            );

            $tiempoExpiracion = now()->addMinutes(30);

            // Actualizar el envío
            $envio->update([
                'whatsapp_number' => 'whatsapp:'.$numeroWhatsApp,
                'twilio_message_sid' => $message->sid,
                'content_sid' => $contentSid,
                'estado' => 'esperando_respuesta',
                'fecha_envio' => now(),
                'whatsapp_sent_at' => now(),
                'esperando_respuesta_desde' => now(),
                'tiempo_espera_minutos' => 30,
                'tiempo_expiracion' => $tiempoExpiracion,
                'timer_activo' => true,
                'estado_timer' => 'activo'
            ]);

            Log::info("Contenido aprobado enviado y timer configurado", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => null,
                'estado' => 'esperando_respuesta'
            ]);

            Log::info("Contenido aprobado enviado exitosamente", [
                'envio_id' => $envio->idenvio,
                'cliente' => $cliente->nombre_completo,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid,
                'tiempo_expiracion' => $tiempoExpiracion,
                'estado' => 'esperando_respuesta'
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error enviando encuesta por WhatsApp", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Actualizar estado como fallido
            $envio->update([
                'estado' => 'error',
                'whatsapp_error' => $e->getMessage(),
                'timer_activo' => false,
                'estado_timer' => 'error'
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
            
            $preguntaActual = $envio->pregunta_actual ?? 1.1;
            
            // Determinar siguiente pregunta
            $siguientePregunta = $this->determinarSiguientePregunta($preguntaActual, $respuestaAnterior);
            
            Log::info("Procesando siguiente pregunta", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $preguntaActual,
                'siguiente_pregunta' => $siguientePregunta,
                'respuesta_anterior' => $respuestaAnterior
            ]);
            
            if ($siguientePregunta === 'completado') {
                // Enviar mensaje de agradecimiento
                $mensaje = $this->construirMensajeAgradecimiento($envio);
                
                Log::info("Enviando mensaje de agradecimiento", [
                    'envio_id' => $envio->idenvio,
                    'numero' => $numeroWhatsApp
                ]);
                
                $message = $this->client->messages->create(
                    "whatsapp:{$numeroWhatsApp}",
                    [
                        'from' => "whatsapp:{$this->fromNumber}",
                        'body' => $mensaje,
                    ]
                );
                
                $envio->update([
                    'estado' => 'completado',
                    'fecha_respuesta' => now(),
                    'whatsapp_responded_at' => now(),
                    'pregunta_actual' => 4
                ]);
                
                Log::info("Encuesta completada exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'numero' => $numeroWhatsApp,
                    'message_sid' => $message->sid
                ]);
                
                return true;
            }
            
            $mensaje = $this->construirPregunta($envio, $siguientePregunta);
            
            Log::info("Enviando siguiente pregunta", [
                'envio_id' => $envio->idenvio,
                'pregunta' => $siguientePregunta,
                'numero' => $numeroWhatsApp
            ]);
            
            // Envío real a Twilio
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );
            
            // Si se está pasando de pregunta 1.5 a pregunta 2, calcular el promedio
            if ($preguntaActual == 1.5 && $siguientePregunta == 2) {
                Log::info("Calculando promedio al completar pregunta 1.5", [
                    'envio_id' => $envio->idenvio,
                    'pregunta_actual' => $preguntaActual,
                    'siguiente_pregunta' => $siguientePregunta
                ]);
                $this->calcularPromedioPregunta1($envio);
            }
            
            $envio->update([
                'pregunta_actual' => $siguientePregunta,
                'whatsapp_message' => $mensaje,
                'estado' => 'en_proceso'
            ]);
            
            Log::info("Siguiente pregunta enviada exitosamente", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $siguientePregunta,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Error enviando siguiente pregunta", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Determinar la siguiente pregunta basada en la estructura nueva
     */
    protected function determinarSiguientePregunta($preguntaActual, $respuestaAnterior = null)
    {
        // Convertir a string para manejar valores decimales correctamente
        $preguntaStr = (string) $preguntaActual;
        
        switch ($preguntaStr) {
            case '1.1':
            case '1.0':
                return '1.2'; // Puntualidad de entrega
            case '1.2':
                return '1.3'; // Trato del asesor comercial
            case '1.3':
                return '1.4'; // Precio
            case '1.4':
                return '1.5'; // Rapidez en programación
            case '1.5':
                return '2'; // ¿Recomendarías a Konkret?
            case '2':
                // Si la respuesta anterior fue "no", ir a pregunta 3
                // Si fue "si", completar encuesta
                if ($respuestaAnterior) {
                    $respuestaLimpia = trim(strtolower($respuestaAnterior));
                    if ($respuestaLimpia === 'no') {
                        return '3'; // Ir a pregunta 3
                    } else {
                        return 'completado'; // Completar encuesta
                    }
                }
                return 'completado'; // Por defecto completar
            case '3':
                return 'completado'; // Última pregunta
            default:
                return 'completado';
        }
    }

    /**
     * Construir la primera pregunta
     */
   

    /**
     * Enviar primera pregunta de la nueva encuesta después del contenido aprobado
     */
    public function enviarPrimeraPreguntaNuevaEncuesta(Envio $envio)
    {
        Log::info("Enviando primera pregunta después del contenido aprobado", [
            'envio_id' => $envio->idenvio,
            'cliente_id' => $envio->cliente_id
        ]);
        
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);

            Log::info("Número de WhatsApp", [
                'numeroWhatsApp' => $numeroWhatsApp
            ]);

            // Construir la primera pregunta de la nueva encuesta
            $mensaje = $this->construirPregunta1NuevaEncuesta($envio);
            Log::info("Primera pregunta de nueva encuesta", [
                'mensaje' => $mensaje
            ]);

            Log::info("Enviando mensaje a Twilio", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'from_number' => $this->fromNumber
            ]);

            // Envío real a Twilio
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );

            Log::info("Mensaje enviado a Twilio exitosamente", [
                'envio_id' => $envio->idenvio,
                'message_sid' => $message->sid,
                'status' => $message->status ?? 'N/A'
            ]);

            // Actualizar el envío con la información de Twilio
            $envio->update([
                'whatsapp_number' => 'whatsapp:'.$numeroWhatsApp,
                'twilio_message_sid' => $message->sid,
                'whatsapp_message' => $mensaje,
                'estado' => 'enviado',
                'fecha_envio' => now(),
                'whatsapp_sent_at' => now(),
                'pregunta_actual' => 1.1, // Marcar que estamos en la primera subpregunta
            ]);

            Log::info("Primera pregunta de nueva encuesta enviada exitosamente", [
                'envio_id' => $envio->idenvio,
                'cliente' => $cliente->nombre_completo,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid,
                'pregunta_actual' => 1.1
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error enviando primera pregunta después de contenido aprobado", [
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
     * Construir la primera pregunta de la nueva encuesta
     */
    protected function construirPregunta1NuevaEncuesta(Envio $envio)
    {
        $cliente = $envio->cliente;
        $identificador = $this->generarIdentificadorRespuesta($envio, 1.1);
        
        $mensaje  = "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
        $mensaje .= "*1. En una escala del 1-10, ¿Cómo calificarías nuestro servicio con base en los siguientes puntos?*\n\n";
        $mensaje .= "*1* mala calificación y *10* es la mejor calificación.\n\n";
        $mensaje .= "---\n";
        $mensaje .= "*📝 Pregunta 1.1 de 5:*\n";
        $mensaje .= "_Calidad del producto_\n\n";
        $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
        $mensaje .= "---\n";
        // Identificador de respuesta discreto
        $mensaje .= "Ref: " . $identificador;
        
        return $mensaje;
    }

    /**
     * Construir pregunta específica
     */
    protected function construirPregunta(Envio $envio, $numeroPregunta)
    {
        $cliente = $envio->cliente;
        $identificador = $this->generarIdentificadorRespuesta($envio, $numeroPregunta);
        
        switch ($numeroPregunta) {
            case 1.2:
                $mensaje = "📝 *Pregunta 1.2 de 5:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "_Puntualidad de entrega_\n\n";
                $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
                break;
                
            case 1.3:
                $mensaje = "📝 *Pregunta 1.3 de 5:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "_Trato del asesor comercial_\n\n";
                $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
                break;
                
            case 1.4:
                $mensaje = "📝 *Pregunta 1.4 de 5:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "_Precio_\n\n";
                $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
                break;
                
            case 1.5:
                $mensaje = "📝 *Pregunta 1.5 de 5:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "_Rapidez en programación_\n\n";
                $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
                break;
                
            case 2:
                $mensaje = "📝 *Pregunta 2:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "¿Recomendarías a Konkret?\n\n";
                $mensaje .= "Responde solo con 'Si' o 'No'.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
                break;
                
            case 3:
                $mensaje = "📝 *Pregunta 3:*\n";
                $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n\n";
                $mensaje .= "¿Qué podríamos hacer para mejorar tu experiencia?\n\n";
                $mensaje .= "Responde con tu sugerencia.\n\n";
                $mensaje .= "---\n";
                // Identificador de respuesta discreto
                $mensaje .= "Ref: " . $identificador;
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
        // Agregar información oculta usando caracteres invisibles
        //$mensaje .= "\n\n" . "\u{200B}" . ($envio->idenvio ?? 'N/A') . "|" . ($envio->cliente->celular ?? 'N/A') . "\u{200B}";

        return $mensaje;
    }

    /**
     * Procesar respuesta recibida por WhatsApp
     */
    public function procesarRespuesta($from, $body, $messageSid)
    {
        Log::info("Procesando respuesta de WhatsApp procesarRespuesta", [
            'from' => $from,
            'body' => $body,
            'message_sid' => $messageSid,
        ]);

        try {
            // Declarar variables al inicio
            $envioId = null;
            $respuestaId = null;
            
            Log::info("Procesando respuesta de WhatsApp", [
                'from' => $from,
                'body' => $body,
                'message_sid' => $messageSid,
                'envio_id_extraido' => $envioId,
                'respuesta_id_extraido' => $respuestaId
            ]);

            // Intentar extraer el ID de la encuesta del mensaje si está disponible
            if (preg_match('/# de encuesta: (\d+)/', $body, $matches)) {
                $envioId = $matches[1];
                Log::info("ID de encuesta extraído del mensaje", ['envio_id' => $envioId]);
            }
            
            if (preg_match('/Ref: ([A-Za-z0-9]+)/', $body, $matches)) {
                $respuestaId = $matches[1];
                Log::info("ID de respuesta extraído del mensaje", ['respuesta_id' => $respuestaId]);
            }

            // Buscar el envío por múltiples criterios
            $envio = null;
            
            Log::info("Iniciando búsqueda de envío", [
                'from' => $from,
                'envio_id_extraido' => $envioId,
                'respuesta_id_extraido' => $respuestaId,
                'message_sid' => $messageSid
            ]);
            
            // PRIMERA PRIORIDAD: Buscar por message_sid (más específico)
            if ($messageSid) {
                $envio = Envio::where('twilio_message_sid', $messageSid)
                    ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por message_sid", [
                        'envio_id' => $envio->idenvio,
                        'message_sid_buscado' => $messageSid,
                        'message_sid_encontrado' => $envio->twilio_message_sid
                    ]);
                } else {
                    Log::info("No se encontró envío por message_sid", [
                        'message_sid_buscado' => $messageSid
                    ]);
                }
            }
            
            if (!$envio && $envioId) {
                // SEGUNDA PRIORIDAD: Buscar por ID de la encuesta
                $envio = Envio::where('idenvio', $envioId)
                    ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por ID de encuesta", ['envio_id' => $envio->idenvio]);
                } else {
                    Log::info("No se encontró envío por ID de encuesta", ['envio_id_buscado' => $envioId]);
                }
            }
            
            if (!$envio) {
                // Si no se encontró por ID, buscar por número de WhatsApp (formato completo)
                $whatsappNumber = "whatsapp:{$from}";
                $envio = Envio::where('whatsapp_number', $whatsappNumber)
                    ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                    ->latest()
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por número de WhatsApp completo", [
                        'envio_id' => $envio->idenvio,
                        'whatsapp_number_buscado' => $whatsappNumber,
                        'whatsapp_number_encontrado' => $envio->whatsapp_number
                    ]);
                } else {
                    Log::info("No se encontró envío por número de WhatsApp completo", [
                        'whatsapp_number_buscado' => $whatsappNumber
                    ]);
                }
            }
            
            if (!$envio) {
                // Buscar por número de WhatsApp sin prefijo
                $envio = Envio::where('whatsapp_number', $from)
                    ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                    ->latest()
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por número de WhatsApp sin prefijo", [
                        'envio_id' => $envio->idenvio,
                        'numero_buscado' => $from,
                        'whatsapp_number_encontrado' => $envio->whatsapp_number
                    ]);
                } else {
                    Log::info("No se encontró envío por número de WhatsApp sin prefijo", [
                        'numero_buscado' => $from
                    ]);
                }
            }
            
            if (!$envio) {
                // Buscar por número de WhatsApp con formato alternativo (sin el prefijo whatsapp:)
                $numeroSinPrefijo = str_replace('whatsapp:', '', $from);
                $envio = Envio::where('whatsapp_number', $numeroSinPrefijo)
                    ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                    ->latest()
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por número de WhatsApp sin prefijo whatsapp:", [
                        'envio_id' => $envio->idenvio,
                        'numero_buscado' => $numeroSinPrefijo,
                        'whatsapp_number_encontrado' => $envio->whatsapp_number
                    ]);
                } else {
                    Log::info("No se encontró envío por número de WhatsApp sin prefijo whatsapp:", [
                        'numero_buscado' => $numeroSinPrefijo
                    ]);
                }
            }
            
            if (!$envio) {
                // Buscar por número de celular del cliente (con y sin prefijo)
                $cleanFrom = str_replace(['+', '52'], '', $from);
                $cleanFromWhatsApp = str_replace(['whatsapp:', '+', '52'], '', $from);
                
                $envio = Envio::whereHas('cliente', function($query) use ($from, $cleanFrom, $cleanFromWhatsApp) {
                    $query->where('celular', $from)
                          ->orWhere('celular', $cleanFrom)
                          ->orWhere('celular', $cleanFromWhatsApp)
                          ->orWhere('celular', '+' . $cleanFrom)
                          ->orWhere('celular', '+' . $cleanFromWhatsApp)
                          ->orWhere('celular', '52' . $cleanFrom)
                          ->orWhere('celular', '52' . $cleanFromWhatsApp)
                          ->orWhere('celular', '521' . $cleanFrom)
                          ->orWhere('celular', '521' . $cleanFromWhatsApp);
                })
                ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                ->latest()
                ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por número de celular del cliente", [
                        'envio_id' => $envio->idenvio,
                        'numero_original' => $from,
                        'numero_limpio' => $cleanFrom,
                        'numero_limpio_whatsapp' => $cleanFromWhatsApp,
                        'celular_cliente' => $envio->cliente->celular ?? 'N/A'
                    ]);
                } else {
                    Log::info("No se encontró envío por número de celular del cliente", [
                        'numero_original' => $from,
                        'numero_limpio' => $cleanFrom,
                        'numero_limpio_whatsapp' => $cleanFromWhatsApp
                    ]);
                }
            }
            
            if (!$envio) {
                // Búsqueda más flexible por whatsapp_number con diferentes formatos
                $numeroLimpio = preg_replace('/[^0-9]/', '', $from);
                $numeroConPrefijo = 'whatsapp:' . $numeroLimpio;
                $numeroSinPrefijo = $numeroLimpio;
                
                $envio = Envio::where(function($query) use ($numeroConPrefijo, $numeroSinPrefijo, $numeroLimpio) {
                    $query->where('whatsapp_number', $numeroConPrefijo)
                          ->orWhere('whatsapp_number', $numeroSinPrefijo)
                          ->orWhere('whatsapp_number', 'LIKE', '%' . $numeroLimpio . '%');
                })
                ->whereIn('estado', ['enviado', 'en_proceso', 'esperando_respuesta'])
                ->latest()
                ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por búsqueda flexible de whatsapp_number", [
                        'envio_id' => $envio->idenvio,
                        'numero_limpio' => $numeroLimpio,
                        'numero_con_prefijo' => $numeroConPrefijo,
                        'numero_sin_prefijo' => $numeroSinPrefijo,
                        'whatsapp_number_encontrado' => $envio->whatsapp_number
                    ]);
                } else {
                    Log::info("No se encontró envío por búsqueda flexible de whatsapp_number", [
                        'numero_limpio' => $numeroLimpio,
                        'numero_con_prefijo' => $numeroConPrefijo,
                        'numero_sin_prefijo' => $numeroSinPrefijo
                    ]);
                }
            }

            if (!$envio) {
                Log::warning("No se encontró envío para el número: {$from}", [
                    'from' => $from,
                    'body' => $body,
                    'envio_id_extraido' => $envioId,
                    'message_sid' => $messageSid,
                    'numero_limpio' => str_replace(['+', '52'], '', $from)
                ]);
                return false;
            }
            
            // VALIDACIÓN CRÍTICA: Verificar que el envío encontrado corresponda al número correcto
            $numeroEnvio = $envio->whatsapp_number;
            $numeroRespuesta = $from;
            
            // Limpiar ambos números para comparación
            $numeroEnvioLimpio = preg_replace('/[^0-9]/', '', $numeroEnvio);
            $numeroRespuestaLimpio = preg_replace('/[^0-9]/', '', $numeroRespuesta);
            
            if ($numeroEnvioLimpio !== $numeroRespuestaLimpio) {
                Log::warning("Número de respuesta no coincide con el envío", [
                    'envio_id' => $envio->idenvio,
                    'numero_envio' => $numeroEnvio,
                    'numero_envio_limpio' => $numeroEnvioLimpio,
                    'numero_respuesta' => $numeroRespuesta,
                    'numero_respuesta_limpio' => $numeroRespuestaLimpio,
                    'message_sid' => $messageSid
                ]);
                return false;
            }
            
            Log::info("Validación de número exitosa", [
                'envio_id' => $envio->idenvio,
                'numero_envio' => $numeroEnvio,
                'numero_respuesta' => $numeroRespuesta,
                'coinciden' => true
            ]);

            Log::info("Envío encontrado exitosamente", [
                'envio_id' => $envio->idenvio,
                'idenvio' => $envio->idenvio,
                'estado' => $envio->estado,
                'pregunta_actual' => $envio->pregunta_actual ?? 1,
                'cliente_celular' => $envio->cliente->celular ?? 'N/A',
                'respuesta_id_extraido' => $respuestaId,
                'whatsapp_number' => $envio->whatsapp_number ?? 'N/A'
            ]);

            // Verificar si es una respuesta de contenido aprobado
            if ($envio->estado === 'esperando_respuesta' && $envio->timer_activo) {
                return $this->procesarRespuestaContenidoAprobado($from, $body, $messageSid);
            }

            // Validar la respuesta antes de procesarla
            $validacion = $this->validarRespuesta($envio, $body);
            
            if (!$validacion['valida']) {
                // Enviar mensaje de error y solicitar respuesta válida
                $this->enviarMensajeError($envio, $validacion['mensaje']);
                return false;
            }
            
            // Guardar la respuesta recibida
            $this->guardarRespuesta($envio, $body, $respuestaId);

            $envio->refresh();

            // Procesar la respuesta y enviar siguiente pregunta
            $resultado = $this->enviarSiguientePregunta($envio, $body);

            if ($resultado) {
                Log::info("Respuesta procesada exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'idenvio' => $envio->idenvio,
                    'numero' => $from,
                    'respuesta' => $body,
                    'pregunta_actual' => $envio->pregunta_actual,
                    'respuesta_id_extraido' => $respuestaId
                ]);
            }

            return $resultado;

        } catch (\Exception $e) {
            Log::error("Error procesando respuesta de WhatsApp", [
                'from' => $from,
                'body' => $body,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Validar la respuesta del cliente según la pregunta actual
     */
    protected function validarRespuesta(Envio $envio, $respuesta)
    {
        $preguntaActual = $envio->pregunta_actual ?? 1.1;
        
        Log::info("Validando respuesta", [
            'envio_id' => $envio->idenvio,
            'pregunta_actual' => $preguntaActual,
            'respuesta' => $respuesta
        ]);
        
        switch ($preguntaActual) {
            case 1.1:
            case 1.2:
            case 1.3:
            case 1.4:
            case 1.5:
                // Validar que sea un número del 1 al 10
                $respuestaLimpia = trim($respuesta);
                
                // Verificar si es un número
                if (!is_numeric($respuestaLimpia)) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta no válida*\n\nPara la pregunta {$preguntaActual}, debes responder con un número del 1 al 10.\n\nEjemplos válidos: 5, 8, 10\n\nPor favor, responde solo con un número."
                    ];
                }
                
                $numero = (int) $respuestaLimpia;
                
                // Verificar rango del 1 al 10
                if ($numero < 1 || $numero > 10) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Número fuera de rango*\n\nPara la pregunta {$preguntaActual}, debes responder con un número del 1 al 10.\n\nTu respuesta: {$numero}\n\nPor favor, responde con un número entre 1 y 10."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            case 2:
                // Validar que sea "si" o "no"
                $respuestaLimpia = trim(strtolower($respuesta));
                
                if ($respuestaLimpia !== 'si' && $respuestaLimpia !== 'sí' && $respuestaLimpia !== 'no') {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta no válida*\n\nPara la pregunta 2, debes responder solo con 'Si' o 'No'.\n\nTu respuesta: '{$respuesta}'\n\nPor favor, responde solo con 'Si' o 'No'."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            case 3:
                // Validar que no esté vacía
                $respuestaLimpia = trim($respuesta);
                
                if (empty($respuestaLimpia)) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta vacía*\n\nPara la pregunta 3, por favor escribe tu sugerencia."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            default:
                return ['valida' => true, 'mensaje' => ''];
        }
    }

    /**
     * Construir instrucciones para reenvío de respuesta
     */
    protected function construirInstruccionesReenvio(Envio $envio)
    {
        $preguntaActual = $envio->pregunta_actual ?? 1.1;
        
        switch ($preguntaActual) {
            case 1.1:
            case 1.2:
            case 1.3:
            case 1.4:
            case 1.5:
                return "Por favor, responde solo con un número del 1 al 10.";
            case 2:
                return "Por favor, responde solo con 'Si' o 'No'.";
            case 3:
                return "Por favor, escribe tu sugerencia.";
            default:
                return "Por favor, responde según las instrucciones.";
        }
    }

    /**
     * Enviar mensaje de error al cliente
     */
    protected function enviarMensajeError(Envio $envio, $mensajeError)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            
            // Agregar instrucciones para reenviar la respuesta
            $mensajeCompleto = $mensajeError . "\n\n" . $this->construirInstruccionesReenvio($envio);
            // Agregar información discreta
            $mensajeCompleto .= "\n\n# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n";
            $mensajeCompleto .= "Ref: " . $this->generarIdentificadorRespuesta($envio, $envio->pregunta_actual ?? 1.1);
            
            Log::info("Enviando mensaje de error", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'mensaje_error' => $mensajeError
            ]);
            
            // Envío real a Twilio
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensajeCompleto,
                ]
            );
            
            Log::info("Mensaje de error enviado exitosamente", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error enviando mensaje de error", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }



    /**
     * Guardar respuesta del cliente en el envío
     */
    protected function guardarRespuesta(Envio $envio, $respuesta, $respuestaId = null)
    {
        try {
            $preguntaActual = $envio->pregunta_actual ?? 1.1;
            
            Log::info("Intentando guardar respuesta", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $preguntaActual,
                'respuesta' => $respuesta,
                'respuesta_id' => $respuestaId,
                'estado_actual' => $envio->estado
            ]);
            
            // Mapear preguntas a campos de la base de datos
            $campoRespuesta = $this->mapearPreguntaACampo($preguntaActual);
            
            if ($campoRespuesta) {
                $envio->update([
                    $campoRespuesta => $respuesta
                ]);
                
                // Recargar el modelo para obtener los datos actualizados
                $envio->refresh();
                
                Log::info("Respuesta guardada exitosamente", [
                    'envio_id' => $envio->idenvio,
                    'idenvio' => $envio->idenvio,
                    'pregunta' => $preguntaActual,
                    'campo' => $campoRespuesta,
                    'respuesta' => $respuesta,
                    'cliente' => $envio->cliente->nombre_completo ?? 'N/A',
                    'celular' => $envio->cliente->celular ?? 'N/A',
                    'respuesta_id' => $respuestaId,
                    'campo_actualizado' => $envio->$campoRespuesta
                ]);
            } else {
                Log::error("Campo de respuesta no válido", [
                    'envio_id' => $envio->idenvio,
                    'pregunta_actual' => $preguntaActual
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error guardando respuesta", [
                'envio_id' => $envio->idenvio,
                'idenvio' => $envio->idenvio,
                'pregunta_actual' => $envio->pregunta_actual ?? 'N/A',
                'error' => $e->getMessage(),
                'respuesta_id' => $respuestaId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Calcular el promedio de las 5 respuestas de la pregunta 1
     */
    public function calcularPromedioPregunta1(Envio $envio)
    {
        try {
            // Obtener las 5 respuestas
            $respuestas = [
                $envio->respuesta_1_1,
                $envio->respuesta_1_2,
                $envio->respuesta_1_3,
                $envio->respuesta_1_4,
                $envio->respuesta_1_5
            ];
            
            // Filtrar solo valores numéricos válidos
            $respuestasValidas = array_filter($respuestas, function($valor) {
                return is_numeric($valor) && $valor >= 1 && $valor <= 10;
            });
            
            if (count($respuestasValidas) > 0) {
                $promedio = round(array_sum($respuestasValidas) / count($respuestasValidas), 2);
                
                $envio->update([
                    'promedio_respuesta_1' => $promedio
                ]);
                
                Log::info("Promedio de pregunta 1 calculado", [
                    'envio_id' => $envio->idenvio,
                    'respuestas' => $respuestas,
                    'respuestas_validas' => $respuestasValidas,
                    'promedio' => $promedio
                ]);
            } else {
                Log::warning("No se pudieron calcular el promedio de pregunta 1", [
                    'envio_id' => $envio->idenvio,
                    'respuestas' => $respuestas
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error calculando promedio de pregunta 1", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Mapear pregunta actual a campo de la base de datos
     */
    protected function mapearPreguntaACampo($preguntaActual)
    {
        // Convertir a string para manejar valores decimales correctamente
        $preguntaStr = (string) $preguntaActual;
        
        switch ($preguntaStr) {
            case '1.1':
            case '1.0':
                return 'respuesta_1_1'; // Calidad del producto
            case '1.2':
                return 'respuesta_1_2'; // Puntualidad de entrega
            case '1.3':
                return 'respuesta_1_3'; // Trato del asesor comercial
            case '1.4':
                return 'respuesta_1_4'; // Precio
            case '1.5':
                return 'respuesta_1_5'; // Rapidez en programación
            case '2':
                return 'respuesta_2'; // ¿Recomendarías a Konkret?
            case '3':
                return 'respuesta_3'; // ¿Qué podríamos hacer para mejorar tu experiencia?
            default:
                return null;
        }
    }

    /**
     * Generar identificador único para la respuesta
     */
    protected function generarIdentificadorRespuesta(Envio $envio, $preguntaActual)
    {
        $envioId = $envio->idenvio ?? '0';
        $timestamp = now()->format('YmdHis');
        $hash = substr(md5($envioId . $preguntaActual . $timestamp), 0, 8);
        return "R{$envioId}P{$preguntaActual}{$hash}";
    }







    /**
     * Formatear número para WhatsApp
     */
    protected function formatearNumeroWhatsApp($numero)
    {
        $numero = preg_replace('/[^0-9]/', '', $numero);
        
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
            if (!$numeroPrueba) {
                $numeroPrueba = '5219993778529';
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
     * Enviar mensaje directo por WhatsApp
     */
    public function enviarMensajeDirecto($numero, $mensaje, $nombre = null, $codigo = null)
    {
        try {
            Log::info("Enviando mensaje directo por WhatsApp", [
                'numero' => $numero,
                'mensaje' => $mensaje,
                'nombre' => $nombre,
                'codigo' => $codigo
            ]);

            // Formatear número para WhatsApp
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($numero);

            Log::info("Número formateado para WhatsApp", [
                'numero_original' => $numero,
                'numero_formateado' => $numeroWhatsApp
            ]);

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );

            Log::info("Mensaje enviado exitosamente", [
                'message_sid' => $message->sid,
                'status' => $message->status,
                'numero_enviado' => $numeroWhatsApp
            ]);

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status,
                'numero_enviado' => $numeroWhatsApp
            ];

        } catch (\Exception $e) {
            Log::error("Error enviando mensaje directo por WhatsApp", [
                'numero' => $numero,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Enviar contenido aprobado y esperar respuesta
     */
    public function enviarContenidoAprobado(Envio $envio, $tiempoEsperaMinutos = 30)
    {
        Log::info("Enviando contenido aprobado y configurando timer", [
            'envio_id' => $envio->idenvio,
            'cliente_id' => $envio->cliente_id,
            'tiempo_espera' => $tiempoEsperaMinutos
        ]);
        
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            $contentSid = config('services.twilio.content_sid');

            Log::info("Configuración para envío de contenido aprobado", [
                'numeroWhatsApp' => $numeroWhatsApp,
                'content_sid' => $contentSid
            ]);

            if (!$contentSid) {
                throw new \Exception('Content SID no está configurado');
            }

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'contentSid' => $contentSid,
                    'contentVariables' => json_encode([
                        'nombre' => $cliente->nombre_completo,
                        'encuesta' => $envio->idenvio
                    ])
                ]
            );

            $tiempoExpiracion = now()->addMinutes($tiempoEsperaMinutos);

            // Actualizar el envío
            $envio->update([
                'whatsapp_number' => 'whatsapp:'.$numeroWhatsApp,
                'twilio_message_sid' => $message->sid,
                'content_sid' => $contentSid,
                'estado' => 'esperando_respuesta',
                'fecha_envio' => now(),
                'whatsapp_sent_at' => now(),
                'esperando_respuesta_desde' => now(),
                'tiempo_espera_minutos' => $tiempoEsperaMinutos,
                'tiempo_expiracion' => $tiempoExpiracion,
                'timer_activo' => true,
                'estado_timer' => 'activo'
            ]);

            Log::info("Contenido aprobado enviado y timer configurado exitosamente", [
                'envio_id' => $envio->idenvio,
                'cliente' => $cliente->nombre_completo,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid,
                'tiempo_expiracion' => $tiempoExpiracion,
                'estado' => 'esperando_respuesta'
            ]);

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'tiempo_expiracion' => $tiempoExpiracion,
                'estado' => 'esperando_respuesta'
            ];

        } catch (\Exception $e) {
            Log::error("Error enviando contenido aprobado", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Actualizar el estado del envío como fallido
            $envio->update([
                'estado' => 'error',
                'whatsapp_error' => $e->getMessage(),
                'timer_activo' => false,
                'estado_timer' => 'error'
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Procesar respuesta cuando se envía contenido aprobado
     */
    public function procesarRespuestaContenidoAprobado($from, $body, $messageSid)
    {
        Log::info("Procesando respuesta de contenido aprobado", [
            'from' => $from,
            'body' => $body,
            'message_sid' => $messageSid,
        ]);

        try {
            // Buscar el envío que está esperando respuesta con múltiples criterios
            $envio = null;
            
            // PRIMERA PRIORIDAD: Buscar por message_sid
            if ($messageSid) {
                $envio = Envio::where('twilio_message_sid', $messageSid)
                    ->where('estado', 'esperando_respuesta')
                    ->where('timer_activo', true)
                    ->where('tiempo_expiracion', '>', now())
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por message_sid en contenido aprobado", [
                        'envio_id' => $envio->idenvio,
                        'message_sid_buscado' => $messageSid
                    ]);
                } else {
                    Log::info("No se encontró envío por message_sid en contenido aprobado", [
                        'message_sid_buscado' => $messageSid
                    ]);
                }
            }
            
            // SEGUNDA PRIORIDAD: Buscar por número de WhatsApp
            if (!$envio) {
                $envio = Envio::where('whatsapp_number', "whatsapp:{$from}")
                    ->where('estado', 'esperando_respuesta')
                    ->where('timer_activo', true)
                    ->where('tiempo_expiracion', '>', now())
                    ->latest()
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por número WhatsApp en contenido aprobado", [
                        'envio_id' => $envio->idenvio,
                        'whatsapp_number_buscado' => "whatsapp:{$from}"
                    ]);
                } else {
                    Log::info("No se encontró envío por número WhatsApp en contenido aprobado", [
                        'whatsapp_number_buscado' => "whatsapp:{$from}"
                    ]);
                }
            }
            
            // TERCERA PRIORIDAD: Buscar por número sin prefijo
            if (!$envio) {
                $envio = Envio::where('whatsapp_number', $from)
                    ->where('estado', 'esperando_respuesta')
                    ->where('timer_activo', true)
                    ->where('tiempo_expiracion', '>', now())
                    ->latest()
                    ->first();
            }
            
            // CUARTA PRIORIDAD: Búsqueda flexible por número de celular del cliente
            if (!$envio) {
                $cleanFrom = str_replace(['+', '52'], '', $from);
                $cleanFromWhatsApp = str_replace(['whatsapp:', '+', '52'], '', $from);
                
                $envio = Envio::whereHas('cliente', function($query) use ($from, $cleanFrom, $cleanFromWhatsApp) {
                    $query->where('celular', $from)
                          ->orWhere('celular', $cleanFrom)
                          ->orWhere('celular', $cleanFromWhatsApp)
                          ->orWhere('celular', '+' . $cleanFrom)
                          ->orWhere('celular', '+' . $cleanFromWhatsApp)
                          ->orWhere('celular', '52' . $cleanFrom)
                          ->orWhere('celular', '52' . $cleanFromWhatsApp)
                          ->orWhere('celular', '521' . $cleanFrom)
                          ->orWhere('celular', '521' . $cleanFromWhatsApp);
                })
                ->where('estado', 'esperando_respuesta')
                ->where('timer_activo', true)
                ->where('tiempo_expiracion', '>', now())
                ->latest()
                ->first();
            }
            
            // QUINTA PRIORIDAD: Búsqueda más flexible por whatsapp_number con diferentes formatos
            if (!$envio) {
                $numeroLimpio = preg_replace('/[^0-9]/', '', $from);
                $numeroConPrefijo = 'whatsapp:' . $numeroLimpio;
                $numeroSinPrefijo = $numeroLimpio;
                
                $envio = Envio::where(function($query) use ($numeroConPrefijo, $numeroSinPrefijo, $numeroLimpio) {
                    $query->where('whatsapp_number', $numeroConPrefijo)
                          ->orWhere('whatsapp_number', $numeroSinPrefijo)
                          ->orWhere('whatsapp_number', 'LIKE', '%' . $numeroLimpio . '%');
                })
                ->where('estado', 'esperando_respuesta')
                ->where('timer_activo', true)
                ->where('tiempo_expiracion', '>', now())
                ->latest()
                ->first();
            }

            if (!$envio) {
                Log::warning("No se encontró envío esperando respuesta o timer expirado", [
                    'from' => $from,
                    'message_sid' => $messageSid,
                    'body' => $body
                ]);
                return false;
            }

            Log::info("Envío encontrado para contenido aprobado", [
                'envio_id' => $envio->idenvio,
                'estado' => $envio->estado,
                'timer_activo' => $envio->timer_activo,
                'tiempo_expiracion' => $envio->tiempo_expiracion,
                'from' => $from,
                'body' => $body
            ]);

            // Validar si la respuesta es "Si" (para continuar)
            $respuestaLimpia = trim(strtolower($body));
            
            Log::info("Validando respuesta de contenido aprobado", [
                'respuesta_original' => $body,
                'respuesta_limpia' => $respuestaLimpia,
                'es_si' => in_array($respuestaLimpia, ['si', 'sí', 'yes', 'ok', 'okay', 'vale', 'bueno'])
            ]);
            
            if (in_array($respuestaLimpia, ['si', 'sí', 'yes', 'ok', 'okay', 'vale', 'bueno'])) {
                // Desactivar timer y continuar con la encuesta
                $envio->update([
                    'timer_activo' => false,
                    'estado_timer' => 'respondido',
                    'estado' => 'enviado',
                    'pregunta_actual' => 1.1 // Iniciar con la primera subpregunta
                ]);

                Log::info("Respuesta positiva recibida, enviando primera pregunta", [
                    'envio_id' => $envio->idenvio,
                    'respuesta' => $body
                ]);

                // Enviar la primera pregunta de la nueva encuesta
                return $this->enviarPrimeraPreguntaNuevaEncuesta($envio);
            } else {
                // Enviar mensaje de error y mantener timer activo
                Log::info("Respuesta negativa o inválida, enviando mensaje de error", [
                    'envio_id' => $envio->idenvio,
                    'respuesta' => $body
                ]);
                
                $this->enviarMensajeErrorContenidoAprobado($envio, $body);
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Error procesando respuesta de contenido aprobado", [
                'from' => $from,
                'body' => $body,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Enviar mensaje de error para contenido aprobado
     */
    protected function enviarMensajeErrorContenidoAprobado(Envio $envio, $respuestaRecibida)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            
            $mensaje = "❌ *Respuesta no válida*\n\n";
            $mensaje .= "Para continuar con la encuesta, responde con:\n";
            $mensaje .= "• \"Si\" o \"Sí\"\n";
            $mensaje .= "• \"Ok\" o \"Okay\"\n";
            $mensaje .= "• \"Vale\" o \"Bueno\"\n\n";
            $mensaje .= "Tu respuesta: \"{$respuestaRecibida}\"\n\n";
            $mensaje .= "⏰ *Tiempo restante:* " . $this->calcularTiempoRestante($envio) . "\n\n";
            $mensaje .= "Responde con \"Si\" para continuar.\n\n";
            $mensaje .= "---\n";
            $mensaje .= "# de encuesta: " . ($envio->idenvio ?? 'N/A') . "\n";
            $mensaje .= "Ref: " . $this->generarIdentificadorRespuesta($envio, 'contenido_aprobado');
            
            Log::info("Enviando mensaje de error para contenido aprobado", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'respuesta_recibida' => $respuestaRecibida
            ]);
            
            // Envío real a Twilio
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );
            
            Log::info("Mensaje de error enviado exitosamente", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error enviando mensaje de error para contenido aprobado", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Calcular tiempo restante del timer
     */
    protected function calcularTiempoRestante(Envio $envio)
    {
        if (!$envio->tiempo_expiracion) {
            return 'No configurado';
        }

        $tiempoRestante = $envio->tiempo_expiracion->diffInMinutes(now());
        
        if ($tiempoRestante <= 0) {
            return 'Expirado';
        }

        return $tiempoRestante . ' minutos';
    }

    /**
     * Verificar y cancelar timers expirados
     */
    public function verificarTimersExpirados()
    {
        Log::info("Verificando timers expirados");

        try {
            $enviosExpirados = Envio::where('timer_activo', true)
                ->where('tiempo_expiracion', '<', now())
                ->where('estado', 'esperando_respuesta')
                ->get();

            Log::info("Encontrados " . $enviosExpirados->count() . " timers expirados");

            foreach ($enviosExpirados as $envio) {
                $this->cancelarTimerExpirado($envio);
            }

            return [
                'success' => true,
                'timers_cancelados' => $enviosExpirados->count()
            ];

        } catch (\Exception $e) {
            Log::error("Error verificando timers expirados", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancelar timer expirado
     */
    protected function cancelarTimerExpirado(Envio $envio)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            
            $mensaje = "⏰ *Tiempo de espera agotado*\n\n";
            $mensaje .= "No recibimos tu respuesta a tiempo.\n\n";
            $mensaje .= "La encuesta ha sido cancelada automáticamente.\n\n";
            $mensaje .= "Si deseas participar en el futuro, no dudes en contactarnos.\n\n";
            $mensaje .= "¡Gracias por tu interés! 🏗️";
            
            Log::info("Cancelando timer expirado", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'tiempo_expiracion' => $envio->tiempo_expiracion
            ]);
            
            // Enviar mensaje de cancelación
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );
            
            // Actualizar estado del envío
            $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'expirado',
                'whatsapp_message' => $mensaje
            ]);
            
            Log::info("Timer cancelado exitosamente", [
                'envio_id' => $envio->idenvio,
                'numero' => $numeroWhatsApp,
                'message_sid' => $message->sid,
                'estado_final' => 'cancelado'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error cancelando timer expirado", [
                'envio_id' => $envio->idenvio,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Al menos actualizar el estado aunque falle el envío
            $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'error'
            ]);
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
            'whatsapp_from' => config('services.twilio.whatsapp_from'),
            'content_sid' => config('services.twilio.content_sid')
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

        if (empty($config['content_sid'])) {
            $errores[] = 'TWILIO_CONTENT_SID no está configurado';
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