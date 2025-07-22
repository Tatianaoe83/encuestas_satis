<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\Envio;
use Carbon\Carbon;

class GenerarDatosPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datos:generar-prueba {--cantidad=100 : Cantidad de envíos a generar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera datos de prueba para las gráficas de resultados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cantidad = $this->option('cantidad');
        
        $this->info("Generando {$cantidad} envíos de prueba...");

        // Verificar si hay clientes, si no, crear algunos
        if (Cliente::count() === 0) {
            $this->info('No hay clientes. Creando clientes de prueba...');
            $this->crearClientes();
        }

        // Generar envíos
        $this->generarEnvios($cantidad);

        $this->info('¡Datos de prueba generados exitosamente!');
        $this->info('Puedes acceder a la visualización de resultados en: /resultados');
    }

    private function crearClientes()
    {
        $asesores = [
            'Juan Pérez',
            'María García', 
            'Carlos López',
            'Ana Martínez',
            'Luis Rodríguez',
            'Carmen Silva',
            'Roberto Torres',
            'Patricia Vargas'
        ];

        for ($i = 1; $i <= 30; $i++) {
            Cliente::create([
                'asesor_comercial' => $asesores[array_rand($asesores)],
                'razon_social' => 'Empresa ' . $i,
                'nombre_completo' => 'Contacto ' . $i,
                'puesto' => 'Gerente',
                'celular' => '300' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'correo' => 'contacto' . $i . '@empresa.com',
            ]);
        }
    }

    private function generarEnvios($cantidad)
    {
        $clientes = Cliente::all();
        $estados = ['pendiente', 'enviado', 'respondido', 'cancelado'];
        $respuestas = ['Excelente', 'Muy Bueno', 'Bueno', 'Regular', 'Malo'];
        
        $bar = $this->output->createProgressBar($cantidad);
        $bar->start();

        for ($i = 0; $i < $cantidad; $i++) {
            $cliente = $clientes->random();
            $estado = $estados[array_rand($estados)];
            
            $fechaEnvio = null;
            $fechaRespuesta = null;
            
            if ($estado !== 'pendiente') {
                $fechaEnvio = Carbon::now()->subDays(rand(1, 180));
                
                if ($estado === 'respondido') {
                    $fechaRespuesta = $fechaEnvio->copy()->addDays(rand(1, 14));
                }
            }

            Envio::create([
                'cliente_id' => $cliente->id,
                'pregunta_1' => '¿Cómo calificaría nuestro servicio?',
                'pregunta_2' => '¿Recomendaría nuestros servicios?',
                'pregunta_3' => '¿Está satisfecho con la atención recibida?',
                'pregunta_4' => '¿Volvería a contratar nuestros servicios?',
                'respuesta_1' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                'respuesta_2' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                'respuesta_3' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                'respuesta_4' => $estado === 'respondido' ? $respuestas[array_rand($respuestas)] : null,
                'estado' => $estado,
                'fecha_envio' => $fechaEnvio,
                'fecha_respuesta' => $fechaRespuesta,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
} 