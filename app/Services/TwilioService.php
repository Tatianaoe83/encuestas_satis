<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\Envio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

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
     * Enviar contenido aprobado por WhatsApp (solo contenido, sin preguntas)
     */
    public function enviarEncuesta(Envio $envio)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);
            $contentSid = config('services.twilio.content_sid');

            if (!$contentSid) {
                throw new \Exception('Content SID no está configurado');
            }

            // Preparar variables de contenido
            $contentVariables = [
                'nombre' => $cliente->nombre_completo ?? 'Cliente',
                'idencuesta' => \App\Http\Controllers\EncuestaController::generarTokenCorto($envio->idenvio ?? '0')
            ];

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'contentSid' => $contentSid,
                    'contentVariables' => json_encode($contentVariables)
                ]
            );

            $tiempoExpiracion = Carbon::now()->addMinutes(30);
            $tiempoRecordatorio = Carbon::now()->addMinutes(15);

            // Actualizar el envío - solo contenido, sin preguntas
            $envio->update([
                'whatsapp_number' => 'whatsapp:'.$numeroWhatsApp,
                'twilio_message_sid' => $message->sid,
                'content_sid' => $contentSid,
                'estado' => 'enviado',
                'fecha_envio' => Carbon::now(),
                'whatsapp_sent_at' => Carbon::now(),
                'tiempo_espera_minutos' => 30,
                'tiempo_expiracion' => $tiempoExpiracion,
                'tiempo_recordatorio' => $tiempoRecordatorio,
                'timer_activo' => true,
                'estado_timer' => 'activo',
                'recordatorio_enviado' => false,
                'pregunta_actual' => null  // Usar valor numérico para la primera pregunta
            ]);

            return true;

        } catch (\Exception $e) {
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
     * Verificar y enviar recordatorios
     */
    public function verificarRecordatorios()
    {
        try {
            $enviosParaRecordatorio = Envio::where('timer_activo', true)
                ->where('recordatorio_enviado', false)
                ->where('tiempo_recordatorio', '<=', Carbon::now())
                ->where('tiempo_expiracion', '>', Carbon::now())
                ->whereIn('estado', ['enviado'])
                ->get();

            $recordatoriosEnviados = 0;

            foreach ($enviosParaRecordatorio as $envio) {
                if ($this->enviarRecordatorio($envio)) {
                    $recordatoriosEnviados++;
                }
            }

            return [
                'success' => true,
                'recordatorios_enviados' => $recordatoriosEnviados
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Enviar recordatorio de encuesta
     */
    protected function enviarRecordatorio(Envio $envio)
    {
        try {
            $cliente = $envio->cliente;
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($cliente->celular);

            $mensaje = "🔔 *Recordatorio de encuesta*\n\n";
            $mensaje .= "Hola {$cliente->nombre_completo},\n\n";
            $mensaje .= "Te recordamos que tienes una encuesta pendiente que solo te tomará 1 minuto completar.\n\n";
            $mensaje .= "Tu opinión es muy importante para nosotros y nos ayuda a mejorar nuestros servicios.\n\n";
            $mensaje .= "¡Gracias por tu tiempo! 😊\n\n";
            $mensaje .= "---\n";
            $mensaje .= "Atentamente *KONKRET, UNA EMPRESA DE GRUPO PROSER*";
            
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );

            // Marcar recordatorio como enviado
            $envio->update([
                'recordatorio_enviado' => true,
                'recordatorio_enviado_at' => Carbon::now(),
                'whatsapp_message' => $mensaje,
                'estado' => 'recordatorio_enviado'
            ]);

            return true;

        } catch (\Exception $e) {
            return false;
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

        $tiempoRestante = Carbon::parse($envio->tiempo_expiracion)->diffInMinutes(Carbon::now());
        
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
        try {
            $enviosExpirados = Envio::where('timer_activo', true)
                ->where('tiempo_expiracion', '<', Carbon::now())
                ->whereIn('estado', ['enviado', 'en_proceso', 'recordatorio_enviado'])
                ->get();

            foreach ($enviosExpirados as $envio) {
                $this->cancelarTimerExpirado($envio);
            }

                    return [
                'success' => true,
                'timers_cancelados' => $enviosExpirados->count()
                    ];
                
        } catch (\Exception $e) {
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
            
        } catch (\Exception $e) {
            // Al menos actualizar el estado aunque falle el envío
                $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'error'
            ]);
        }
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

            // Enviar mensaje de prueba
            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => "🧪 *Prueba de conexión*\n\nEste es un mensaje de prueba para verificar que la integración con Twilio funciona correctamente.\n\nFecha: " . Carbon::now()->format('d/m/Y H:i:s') . "\n\n✅ Si recibes este mensaje, la configuración está correcta."
                ]
            );

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status,
                'numero_enviado' => $numeroWhatsApp
            ];

        } catch (\Exception $e) {
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
            // Formatear número para WhatsApp
            $numeroWhatsApp = $this->formatearNumeroWhatsApp($numero);

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'body' => $mensaje,
                ]
            );

            return [
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status,
                'numero_enviado' => $numeroWhatsApp
            ];

        } catch (\Exception $e) {
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

    /**
     * Obtener saldo de la cuenta de Twilio
     */
    public function obtenerSaldo()
    {
        try {
            // Obtener información de la cuenta
            $account = $this->client->api->accounts(config('services.twilio.account_sid'))->fetch();
            
            // Obtener el saldo de la cuenta
            $balance = $this->client->api->accounts(config('services.twilio.account_sid'))->balance->fetch();
            
            return [
                'success' => true,
                'account_sid' => $account->sid,
                'account_name' => $account->friendlyName,
                'account_status' => $account->status,
                'balance' => $balance->balance,
                'currency' => $balance->currency,
                'balance_formatted' => number_format($balance->balance, 2) . ' ' . $balance->currency,
                'fecha_consulta' => Carbon::now()->format('d/m/Y H:i:s')
            ];

        } catch (\Exception $e) {
            Log::error("Error obteniendo saldo de Twilio", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'fecha_consulta' => Carbon::now()->format('d/m/Y H:i:s')
            ];
        }
    }
} 