<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Envio;

echo "Verificando envío 286:\n";

$envio = Envio::find(286);

if ($envio) {
    echo "ID: " . $envio->idenvio . "\n";
    echo "Estado: " . $envio->estado . "\n";
    echo "Timer activo: " . ($envio->timer_activo ? 'Sí' : 'No') . "\n";
    echo "WhatsApp number: " . $envio->whatsapp_number . "\n";
    echo "Tiempo expiración: " . $envio->tiempo_expiracion . "\n";
    echo "Pregunta actual: " . ($envio->pregunta_actual ?? 'null') . "\n";
} else {
    echo "Envío no encontrado\n";
}
