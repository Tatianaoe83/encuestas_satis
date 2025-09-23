<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;

class EnviarRecordatoriosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encuestas:enviar-recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de encuestas a los 15 minutos';

    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        parent::__construct();
        $this->twilioService = $twilioService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando envío de recordatorios...');

        try {
            $resultado = $this->twilioService->verificarRecordatorios();

            if ($resultado['success']) {
                $this->info("Recordatorios enviados: {$resultado['recordatorios_enviados']}");
            } else {
                $this->error("Error enviando recordatorios: {$resultado['error']}");
            }

        } catch (\Exception $e) {
            $this->error("Error ejecutando recordatorios: {$e->getMessage()}");
        }
    }
}
