<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatController;

class ProbarChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:probar {--numero=+529961100930} {--mensaje=Hola, este es un mensaje de prueba}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar el envío de mensajes del chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $numero = $this->option('numero');
        $mensaje = $this->option('mensaje');
        
        $this->info("🧪 Probando envío de mensaje...");
        $this->info("   Número: {$numero}");
        $this->info("   Mensaje: {$mensaje}");
        $this->info("");
        
        // Crear una request simulada
        $request = new Request();
        $request->merge([
            'to' => $numero,
            'mensaje' => $mensaje,
            'nombre' => 'Usuario de Prueba',
            'codigo' => 'TEST'
        ]);
        
        // Crear instancia del controlador
        $chatController = new ChatController();
        
        try {
            $this->info("📤 Enviando mensaje...");
            $response = $chatController->enviarMensaje($request);
            
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getContent(), true);
                
                if ($data['success']) {
                    $this->info("✅ Mensaje enviado exitosamente");
                    $this->info("   Message SID: {$data['data']['message_sid']}");
                    $this->info("   Status: {$data['data']['status']}");
                    $this->info("   Timestamp: {$data['data']['timestamp']}");
                } else {
                    $this->error("❌ Error en el envío: {$data['message']}");
                }
            } else {
                $this->error("❌ Error HTTP: {$response->getStatusCode()}");
                $this->error("   Contenido: {$response->getContent()}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Excepción: {$e->getMessage()}");
            $this->error("   Clase: " . get_class($e));
        }
        
        return 0;
    }
}
