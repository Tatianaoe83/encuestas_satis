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

            // Comentado temporalmente para forzar envío real
            // if (app()->environment('local') || config('app.debug')) {
            //     Log::info("MODO PRUEBA: Simulando envío de WhatsApp");
            //     
            //     // Actualizar el envío con la información simulada
            //     $envio->update([
            //         'whatsapp_number' => $numeroWhatsApp,
            //         'twilio_message_sid' => 'SIM_' . time(),
            //         'whatsapp_message' => $mensaje,
            //         'estado' => 'enviado',
            //         'fecha_envio' => now(),
            //         'whatsapp_sent_at' => now(),
            //         'pregunta_actual' => 1, // Marcar que estamos en la primera pregunta
            //     ]);
            //
            //     Log::info("Primera pregunta simulada exitosamente", [
            //         'envio_id' => $envio->idenvio,
            //         'cliente' => $cliente->nombre_completo,
            //         'numero' => $numeroWhatsApp,
            //         'message_sid' => 'SIM_' . time(),
            //         'pregunta_actual' => 1
            //     ]);
            //
            //     return true;
            // }

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
                'whatsapp_number' => 'whatsapp:'.$numeroWhatsApp,
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
            
            Log::info("Procesando siguiente pregunta", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $preguntaActual,
                'siguiente_pregunta' => $siguientePregunta,
                'respuesta_anterior' => $respuestaAnterior
            ]);
            
            // Si es la última pregunta o ya se completó
            if ($siguientePregunta > 4) {
                // Enviar mensaje de agradecimiento
                $mensaje = $this->construirMensajeAgradecimiento($envio);
                
                Log::info("Enviando mensaje de agradecimiento", [
                    'envio_id' => $envio->idenvio,
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
                
                // Marcar como completado
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
            
            // Enviar siguiente pregunta
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
            
            // Actualizar pregunta actual y estado
            $envio->update([
                'pregunta_actual' => $siguientePregunta,
                'whatsapp_message' => $mensaje,
                'estado' => 'en_proceso' // Marcar como en proceso mientras se contesta
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
     * Construir la primera pregunta
     */
    protected function construirPrimeraPregunta(Envio $envio)
    {
        $cliente = $envio->cliente;
        
        // Debug: verificar que el ID esté disponible
        Log::info("Construyendo primera pregunta", [
            'envio_id' => $envio->idenvio,
            'envio_attributes' => $envio->getAttributes(),
            'cliente_nombre' => $cliente->nombre_completo ?? 'N/A',
            'cliente_celular' => $cliente->celular ?? 'N/A'
        ]);
        
        $mensaje = "🏗️ *Encuesta de Satisfacción - Konkret*\n\n";
        $mensaje .= "Hola {$cliente->nombre_completo},\n\n";
        $mensaje .= "Gracias por confiar en nosotros. Nos gustaría conocer tu opinión sobre nuestro servicio.\n\n";
        $mensaje .= "Te enviaré 4 preguntas una por una para facilitar tu respuesta.\n\n";
        $mensaje .= "📝 *Pregunta 1 de 4:*\n";
        $mensaje .= "En una escala del 1 al 10, ¿qué probabilidad hay de que recomiende Konkret a un colega o contacto del sector construcción?\n\n";
        $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
        $mensaje .= "---\n";
        $mensaje .= "🆔 *# de Encuesta: " . ($envio->idenvio ?? 'N/A') . "*\n";
        $mensaje .= "📱 *Tu número: " . ($cliente->celular ?? 'N/A') . "*";
        // Agregar información oculta usando caracteres invisibles
        //$mensaje .= "\n\n" . "\u{200B}" . ($envio->idenvio ?? 'N/A') . "|" . ($cliente->celular ?? 'N/A') . "\u{200B}";

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
            case 2:
                $mensaje = "📝 *Pregunta 2 de 4:*\n";
                $mensaje .= "¿Cuál es la razón principal de tu calificación?\n\n";
                $mensaje .= "Responde con tu razón.\n\n";
                $mensaje .= "---\n";
                $mensaje .= "🆔 *# de Encuesta: " . ($envio->idenvio ?? 'N/A') . "*\n";
                $mensaje .= "🔑 *# de Respuesta: {$identificador}*";
                // Agregar información oculta usando caracteres invisibles
               
                break;
                
            case 3:
                
                $mensaje = "📝 *Pregunta 3 de 4:*\n";
                $mensaje .= "¿A qué tipo de obra se destinó este concreto?\n\n";
                $mensaje .= "Opciones:\n";
                $mensaje .= "1️⃣. Vivienda unifamiliar\n";
                $mensaje .= "2️⃣. Edificio vertical\n";
                $mensaje .= "3️⃣. Obra vial\n";
                $mensaje .= "4️⃣. Obra industrial\n";
                $mensaje .= "5️⃣. Otro\n\n";
                $mensaje .= "Responde del 1 al 5 con una de las opciones.\n\n";
                $mensaje .= "---\n";
                $mensaje .= "🆔 *# de Encuesta: " . ($envio->idenvio ?? 'N/A') . "*\n";
                $mensaje .= "🔑 *# de Respuesta: {$identificador}*";
               
               
                break;
                
            case 4:
                $mensaje = "📝 *Pregunta 4 de 4:*\n";
                $mensaje .= "¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?\n\n";
                $mensaje .= "Responde con tu sugerencia o escribe 'N/A' si no tienes sugerencias.\n\n";
                $mensaje .= "---\n";
                $mensaje .= "🆔 *# de Encuesta: " . ($envio->idenvio ?? 'N/A') . "*\n";
                $mensaje .= "🔑 *# de Respuesta: {$identificador}*";
                // Agregar información oculta usando caracteres invisibles
               
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
            if (preg_match('/🆔 \*# de Encuesta: (\d+)\*/', $body, $matches)) {
                $envioId = $matches[1];
                Log::info("ID de encuesta extraído del mensaje", ['envio_id' => $envioId]);
            }
            
            if (preg_match('/🔑 \*# de Respuesta: ([A-Za-z0-9]+)\*/', $body, $matches)) {
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
                    ->whereIn('estado', ['enviado', 'en_proceso'])
                    ->first();
                
                if ($envio) {
                    Log::info("Envío encontrado por message_sid", [
                        'envio_id' => $envio->idenvio,
                        'message_sid' => $messageSid,
                        'whatsapp_number' => $envio->whatsapp_number
                    ]);
                } else {
                    Log::info("No se encontró envío por message_sid", ['message_sid_buscado' => $messageSid]);
                }
            }
            
            if (!$envio && $envioId) {
                // SEGUNDA PRIORIDAD: Buscar por ID de la encuesta
                $envio = Envio::where('idenvio', $envioId)
                    ->whereIn('estado', ['enviado', 'en_proceso'])
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
                    ->whereIn('estado', ['enviado', 'en_proceso'])
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
                    ->whereIn('estado', ['enviado', 'en_proceso'])
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
                    ->whereIn('estado', ['enviado', 'en_proceso'])
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
                ->whereIn('estado', ['enviado', 'en_proceso'])
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
                ->whereIn('estado', ['enviado', 'en_proceso'])
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

            // Validar la respuesta antes de procesarla
            $validacion = $this->validarRespuesta($envio, $body);
            
            if (!$validacion['valida']) {
                // Enviar mensaje de error y solicitar respuesta válida
                $this->enviarMensajeError($envio, $validacion['mensaje']);
                return false;
            }
            
            // Guardar la respuesta recibida
            $this->guardarRespuesta($envio, $body, $respuestaId);

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
        $preguntaActual = $envio->pregunta_actual ?? 1;
        
        Log::info("Validando respuesta", [
            'envio_id' => $envio->idenvio,
            'pregunta_actual' => $preguntaActual,
            'respuesta' => $respuesta
        ]);
        
        switch ($preguntaActual) {
            case 1:
                // Validar que sea un número del 1 al 10
                $respuestaLimpia = trim($respuesta);
                
                // Verificar si es un número
                if (!is_numeric($respuestaLimpia)) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta no válida*\n\nPara la pregunta 1, debes responder con un número del 1 al 10.\n\nEjemplos válidos: 5, 8, 10\n\nPor favor, responde solo con un número."
                    ];
                }
                
                $numero = (int) $respuestaLimpia;
                
                // Verificar rango del 1 al 10
                if ($numero < 1 || $numero > 10) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Número fuera de rango*\n\nPara la pregunta 1, debes responder con un número del 1 al 10.\n\nTu respuesta: {$numero}\n\nPor favor, responde con un número entre 1 y 10."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            case 2:
                // Validar que no esté vacía y tenga al menos 3 caracteres
                $respuestaLimpia = trim($respuesta);
                
                if (strlen($respuestaLimpia) < 3) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta muy corta*\n\nPara la pregunta 2, por favor explica tu razón con más detalle (mínimo 3 caracteres).\n\nTu respuesta actual: '{$respuestaLimpia}'"
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            case 3:
                // Validar que sea un número del 1 al 5
                $respuestaLimpia = trim($respuesta);
                
                // Verificar si es un número
                if (!is_numeric($respuestaLimpia)) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta no válida*\n\nPara la pregunta 3, debes responder con un número del 1 al 5.\n\nOpciones disponibles:\n1️⃣. Vivienda unifamiliar\n2️⃣. Edificio vertical\n3️⃣. Obra vial\n4️⃣. Obra industrial\n5️⃣. Otro\n\nPor favor, responde solo con un número."
                    ];
                }
                
                $numero = (int) $respuestaLimpia;
                
                // Verificar rango del 1 al 5
                if ($numero < 1 || $numero > 5) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Número fuera de rango*\n\nPara la pregunta 3, debes responder con un número del 1 al 5.\n\nTu respuesta: {$numero}\n\nOpciones disponibles:\n1️⃣. Vivienda unifamiliar\n2️⃣. Edificio vertical\n3️⃣. Obra vial\n4️⃣. Obra industrial\n5️⃣. Otro\n\nPor favor, responde con un número entre 1 y 5."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            case 4:
                // Validar que no esté vacía
                $respuestaLimpia = trim($respuesta);
                
                if (empty($respuestaLimpia)) {
                    return [
                        'valida' => false,
                        'mensaje' => "❌ *Respuesta vacía*\n\nPara la pregunta 4, por favor escribe tu sugerencia o 'N/A' si no tienes sugerencias."
                    ];
                }
                
                return ['valida' => true, 'mensaje' => ''];
                
            default:
                return ['valida' => true, 'mensaje' => ''];
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
            // Agregar información oculta usando caracteres invisibles
            $mensajeCompleto .= "\n\n" . "\u{200B}" . ($envio->idenvio ?? 'N/A') . "|" . ($envio->cliente->celular ?? 'N/A') . "\u{200B}";
            
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
     * Construir instrucciones para reenviar la respuesta
     */
    protected function construirInstruccionesReenvio(Envio $envio)
    {
        $preguntaActual = $envio->pregunta_actual ?? 1;
        
        switch ($preguntaActual) {
            case 1:
                return "📝 *Reenvía tu respuesta:*\nResponde solo con un número del 1 al 10.";
            case 2:
                return "📝 *Reenvía tu respuesta:*\nExplica tu razón con más detalle.";
            case 3:
                return "📝 *Reenvía tu respuesta:*\nResponde solo con un número del 1 al 5.";
            case 4:
                return "📝 *Reenvía tu respuesta:*\nEscribe tu sugerencia o 'N/A'.";
            default:
                return "📝 *Reenvía tu respuesta:*\nPor favor, responde de nuevo.";
        }
    }

    /**
     * Guardar respuesta del cliente en el envío
     */
    protected function guardarRespuesta(Envio $envio, $respuesta, $respuestaId = null)
    {
        try {
            $preguntaActual = $envio->pregunta_actual ?? 1;
            
            Log::info("Intentando guardar respuesta", [
                'envio_id' => $envio->idenvio,
                'pregunta_actual' => $preguntaActual,
                'respuesta' => $respuesta,
                'respuesta_id' => $respuestaId,
                'estado_actual' => $envio->estado
            ]);
            
            if ($preguntaActual <= 4) {
                $campoRespuesta = "respuesta_{$preguntaActual}";
                
                // Verificar que el campo existe antes de actualizar
                if (in_array($campoRespuesta, ['respuesta_1', 'respuesta_2', 'respuesta_3', 'respuesta_4'])) {
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
                        'campo_solicitado' => $campoRespuesta,
                        'campos_validos' => ['respuesta_1', 'respuesta_2', 'respuesta_3', 'respuesta_4']
                    ]);
                }
            } else {
                Log::warning("Pregunta actual fuera de rango", [
                    'envio_id' => $envio->idenvio,
                    'pregunta_actual' => $preguntaActual,
                    'max_preguntas' => 4
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
     * Construir el mensaje de la encuesta
     */
    protected function construirMensajeEncuesta(Envio $envio)
    {
        $cliente = $envio->cliente;
        
        $mensaje = "🏗️ *Encuesta de Satisfacción - Proser*\n\n";
        $mensaje .= "Hola {$cliente->nombre_completo},\n\n";
        $mensaje .= "Gracias por confiar en Proser. Nos gustaría conocer tu opinión sobre nuestro servicio.\n\n";
        $mensaje .= "*Por favor responde las siguientes preguntas:*\n\n";
        
        $mensaje .= "1️⃣ *Pregunta 1 (Escala 1-10):*\n";
        $mensaje .= "En una escala del 1 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?\n";
        $mensaje .= "Responde solo con un número del 1 al 10.\n\n";
        
        $mensaje .= "2️⃣ *Pregunta 2:*\n";
        $mensaje .= "¿Cuál es la razón principal de tu calificación?\n\n";
        
        $mensaje .= "3️⃣ *Pregunta 3 (Opcional):*\n";
        $mensaje .= "¿A qué tipo de obra se destinó este concreto?\n";
        $mensaje .= "Opciones: Vivienda unifamiliar, Edificio vertical, Obra vial, Obra industrial, Otro\n\n";
        
        $mensaje .= "4️⃣ *Pregunta 4 (Opcional):*\n";
        $mensaje .= "¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?\n\n";
        
        $mensaje .= "*Formato de respuesta:*\n";
        $mensaje .= "1. [número del 1 al 10]\n";
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

            // Comentado temporalmente para forzar envío real
            // if (app()->environment('local') || config('app.debug')) {
            //     Log::info("MODO PRUEBA: Simulando envío de WhatsApp");
            //     
            //     return [
            //         'success' => true,
            //         'message_sid' => 'SIM_' . time(),
            //         'status' => 'sent',
            //         'numero_enviado' => $numeroWhatsApp
            //     ];
            // }

            // Envío real a Twilio
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