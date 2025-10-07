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
            $now = Carbon::now();
            Log::info('Verificando recordatorios', ['timestamp' => $now]);
            
            $enviosParaRecordatorio = Envio::where('timer_activo', true)
                ->where('recordatorio_enviado', false)
                ->where('tiempo_recordatorio', '<=', $now)
                ->where('tiempo_expiracion', '>', $now)
                ->whereIn('estado', ['enviado'])
                ->get();

            Log::info('Envios para recordatorio encontrados', [
                'count' => $enviosParaRecordatorio->count(),
                'envios' => $enviosParaRecordatorio->pluck('idenvio')->toArray()
            ]);

            $recordatoriosEnviados = 0;

            foreach ($enviosParaRecordatorio as $envio) {
                Log::info('Enviando recordatorio', [
                    'idenvio' => $envio->idenvio,
                    'tiempo_recordatorio' => $envio->tiempo_recordatorio,
                    'tiempo_expiracion' => $envio->tiempo_expiracion
                ]);
                if ($this->enviarRecordatorio($envio)) {
                    $recordatoriosEnviados++;
                }
            }

            return [
                'success' => true,
                'recordatorios_enviados' => $recordatoriosEnviados
            ];
            
        } catch (\Exception $e) {
            Log::error('Error verificando recordatorios', ['error' => $e->getMessage()]);
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
            $contentSidRecordatorio = config('services.twilio.content_sid_recordatorio');

            if (!$contentSidRecordatorio) {
                Log::warning('Content SID para recordatorio no configurado', [
                    'envio_id' => $envio->id,
                    'cliente_id' => $cliente->id
                ]);
                throw new \Exception('Content SID para recordatorio no está configurado');
            }

            // Preparar variables de contenido para el recordatorio
            $contentVariables = [
                'nombre' => $cliente->nombre_completo ?? 'Cliente',
            ];

            Log::info('Enviando recordatorio', [
                'envio_id' => $envio->id,
                'cliente_id' => $cliente->id,
                'numero_whatsapp' => $numeroWhatsApp,
                'content_sid' => $contentSidRecordatorio
            ]);

            $message = $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'contentSid' => $contentSidRecordatorio,
                    'contentVariables' => json_encode($contentVariables)
                ]
            );

            // Marcar recordatorio como enviado
            $envio->update([
                'recordatorio_enviado' => true,
                'recordatorio_enviado_at' => Carbon::now(),
                'estado' => 'recordatorio_enviado'
            ]);

            Log::info('Recordatorio enviado exitosamente', [
                'envio_id' => $envio->id,
                'message_sid' => $message->sid,
                'status' => $message->status
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando recordatorio', [
                'envio_id' => $envio->id,
                'cliente_id' => $cliente->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
            $now = Carbon::now();
            Log::info('Verificando timers expirados', ['timestamp' => $now]);
            
            $enviosExpirados = Envio::where('timer_activo', true)
                ->where('tiempo_expiracion', '<', $now)
                ->whereIn('estado', ['enviado', 'en_proceso', 'recordatorio_enviado'])
                ->get();

            Log::info('Envios expirados encontrados', [
                'count' => $enviosExpirados->count(),
                'envios' => $enviosExpirados->pluck('idenvio')->toArray()
            ]);

            foreach ($enviosExpirados as $envio) {
                Log::info('Cancelando timer expirado', [
                    'idenvio' => $envio->idenvio,
                    'estado' => $envio->estado,
                    'tiempo_expiracion' => $envio->tiempo_expiracion
                ]);
                $this->cancelarTimerExpirado($envio);
            }

            return [
                'success' => true,
                'timers_cancelados' => $enviosExpirados->count()
            ];
                
        } catch (\Exception $e) {
            Log::error('Error verificando timers expirados', ['error' => $e->getMessage()]);
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
            $contentSidVencimiento = config('services.twilio.content_sid_vencimiento');
            
            // Si hay content SID configurado para vencimiento, usarlo
            
        $this->client->messages->create(
                "whatsapp:{$numeroWhatsApp}",
                [
                    'from' => "whatsapp:{$this->fromNumber}",
                    'contentSid' => $contentSidVencimiento,
                ]   
            );  
                
            // Actualizar estado del envío
            $envio->update([
                'estado' => 'cancelado',
                'timer_activo' => false,
                'estado_timer' => 'expirado'
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
            'content_sid' => config('services.twilio.content_sid'),
            'content_sid_recordatorio' => config('services.twilio.content_sid_recordatorio'),
            'content_sid_vencimiento' => config('services.twilio.content_sid_vencimiento')
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

        // Los content SIDs opcionales, solo mostrar advertencias
        if (empty($config['content_sid_recordatorio'])) {
            $errores[] = 'TWILIO_CONTENT_SID_RECORDATORIO no está configurado (opcional - usará mensaje por defecto)';
        }

        if (empty($config['content_sid_vencimiento'])) {
            $errores[] = 'TWILIO_CONTENT_SID_VENCIMIENTO no está configurado (opcional - usará mensaje por defecto)';
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