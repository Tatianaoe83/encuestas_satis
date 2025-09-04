<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\TwilioService;

echo "Simulando respuesta 'Si' al contenido aprobado:\n";

$twilioService = new TwilioService();

// Simular los datos que vendrían del webhook
$from = '+5219993778529';
$body = 'Si';
$messageSid = 'SM4ca519084b24009195c0a52d032adf51';

echo "From: $from\n";
echo "Body: $body\n";
echo "MessageSid: $messageSid\n\n";

// Procesar la respuesta
$resultado = $twilioService->procesarRespuestaContenidoAprobado($from, $body, $messageSid);

echo "Resultado: " . ($resultado ? 'Éxito' : 'Falló') . "\n";
