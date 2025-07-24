<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;

class TestTwilioWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio:test {--numero= : Número de teléfono para la prueba}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar la conexión con Twilio WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Probando conexión con Twilio WhatsApp...');
        
        $twilioService = new TwilioService();
        
        // Verificar configuración primero
        $this->info('📋 Verificando configuración...');
        $config = $twilioService->verificarConfiguracion();
        
        if (!$config['configuracion_completa']) {
            $this->error('❌ Configuración incompleta:');
            foreach ($config['errores'] as $error) {
                $this->error("   - {$error}");
            }
            $this->info('');
            $this->info('💡 Asegúrate de configurar las siguientes variables en tu archivo .env:');
            $this->info('   TWILIO_ACCOUNT_SID=tu_account_sid');
            $this->info('   TWILIO_AUTH_TOKEN=tu_auth_token');
            $this->info('   TWILIO_WHATSAPP_FROM=+14155238886');
            return 1;
        }
        
        $this->info('✅ Configuración correcta');
        $this->info('');
        
        // Obtener número de prueba
        $numeroPrueba = $this->option('numero');
        if ($numeroPrueba) {
            $this->info("📱 Usando número de prueba: {$numeroPrueba}");
        } else {
            $this->info('📱 Usando número de prueba por defecto: 5219993778529');
        }
        
        $this->info('');
        $this->info('🚀 Enviando mensaje de prueba...');
        
        // Probar conexión
        $resultado = $twilioService->probarConexion($numeroPrueba);
        
        if ($resultado['success']) {
            $this->info('✅ Mensaje enviado exitosamente!');
            $this->info("📨 Message SID: {$resultado['message_sid']}");
            $this->info("📊 Status: {$resultado['status']}");
            $this->info("📱 Número: {$resultado['numero_enviado']}");
            $this->info('');
            $this->info('🎉 ¡La integración con Twilio funciona correctamente!');
        } else {
            $this->error('❌ Error al enviar mensaje:');
            $this->error("   {$resultado['error']}");
            $this->info('');
            $this->info('🔍 Posibles causas:');
            $this->info('   - Credenciales de Twilio incorrectas');
            $this->info('   - Número de WhatsApp no válido');
            $this->info('   - Cuenta de Twilio sin saldo');
            $this->info('   - Número no registrado en WhatsApp Business API');
            return 1;
        }
        
        return 0;
    }
} 