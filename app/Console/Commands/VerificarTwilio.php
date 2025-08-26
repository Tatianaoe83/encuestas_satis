<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;

class VerificarTwilio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar la configuración de Twilio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando configuración de Twilio...');
        
        $twilioService = new TwilioService();
        $configuracion = $twilioService->verificarConfiguracion();
        
        if ($configuracion['configuracion_completa']) {
            $this->info('✅ Configuración completa');
        } else {
            $this->error('❌ Configuración incompleta');
            foreach ($configuracion['errores'] as $error) {
                $this->error("   - {$error}");
            }
        }
        
        $this->info("\n📋 Estado de la configuración:");
        foreach ($configuracion['config'] as $key => $value) {
            $status = $value === 'Configurado' ? '✅' : '❌';
            $this->line("   {$status} {$key}: {$value}");
        }
        
        if ($configuracion['configuracion_completa']) {
            $this->info("\n🧪 Probando conexión con Twilio...");
            
            try {
                $resultado = $twilioService->probarConexion();
                
                if ($resultado['success']) {
                    $this->info("✅ Conexión exitosa");
                    $this->info("   Message SID: {$resultado['message_sid']}");
                    $this->info("   Status: {$resultado['status']}");
                    $this->info("   Número enviado: {$resultado['numero_enviado']}");
                } else {
                    $this->error("❌ Error en la conexión: {$resultado['error']}");
                }
            } catch (\Exception $e) {
                $this->error("❌ Excepción: {$e->getMessage()}");
            }
        }
        
        return 0;
    }
}
