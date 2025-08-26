<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Envio;
use App\Models\Cliente;

class CrearEnvioPrueba extends Command
{
    protected $signature = 'envio:crear-prueba {numero}';
    protected $description = 'Crear un envío de prueba para un número específico';

    public function handle()
    {
        $numero = $this->argument('numero');
        
        $this->info("🔧 Creando envío de prueba para el número: {$numero}");
        
        // Buscar o crear cliente
        $cliente = Cliente::where('celular', 'LIKE', '%' . $numero . '%')->first();
        
        if (!$cliente) {
            $this->warn("⚠️  No se encontró cliente con ese número, creando uno nuevo...");
            
            $cliente = Cliente::create([
                'nombre_completo' => 'Cliente WhatsApp Test',
                'celular' => $numero,
                'email' => 'test@whatsapp.com',
                'empresa' => 'Test'
            ]);
            
            $this->info("✅ Cliente creado con ID: {$cliente->idcliente}");
        } else {
            $this->info("✅ Cliente encontrado: {$cliente->nombre_completo}");
        }
        
        // Crear envío
        $envio = Envio::create([
            'cliente_id' => $cliente->idcliente,
            'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende Proser a un colega o contacto del sector construcción?',
            'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
            'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
            'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?',
            'estado' => 'enviado',
            'pregunta_actual' => 1,
            'whatsapp_number' => 'whatsapp:' . $numero,
            'fecha_envio' => now()
        ]);
        
        $this->info("✅ Envío creado exitosamente:");
        $this->line("   ID: {$envio->idenvio}");
        $this->line("   Estado: {$envio->estado}");
        $this->line("   Pregunta actual: {$envio->pregunta_actual}");
        $this->line("   WhatsApp number: {$envio->whatsapp_number}");
        
        $this->info("🎯 Ahora cuando envíes un mensaje a este número, debería procesarse correctamente.");
    }
}
