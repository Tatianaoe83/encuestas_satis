<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'chat/webhook-respuesta',
        'envios/respuestas',
        'webhook-twilio',
        'webhook-twilio-clean',
        'webhook-test',
        'webhook-test-clean',
        'webhook*'  // Excluir todas las rutas que empiecen con webhook
    ];
}
