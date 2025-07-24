<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\TwilioWebhookController;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rutas para el módulo de clientes
Route::resource('clientes', ClienteController::class)
    ->middleware(['auth']);

// Rutas para el módulo de envíos
Route::resource('envios', EnvioController::class)
    ->parameters(['envios' => 'idenvio'])
    ->middleware(['auth']);

// Rutas adicionales para envíos
Route::post('envios/{idenvio}/marcar-enviado', [EnvioController::class, 'marcarEnviado'])
    ->name('envios.marcar-enviado')
    ->middleware(['auth']);

Route::post('envios/{idenvio}/marcar-respondido', [EnvioController::class, 'marcarRespondido'])
    ->name('envios.marcar-respondido')
    ->middleware(['auth']);

// Ruta para enviar encuesta por WhatsApp
Route::post('envios/{idenvio}/enviar-whatsapp', [EnvioController::class, 'enviarPorWhatsApp'])
    ->name('envios.enviar-whatsapp')
    ->middleware(['auth']);

// Rutas para webhooks de Twilio (sin autenticación)
Route::post('webhook/twilio', [TwilioWebhookController::class, 'handleWebhook'])
    ->name('webhook.twilio');

Route::post('webhook/twilio/status', [TwilioWebhookController::class, 'handleStatusWebhook'])
    ->name('webhook.twilio.status');

Route::get('webhook/twilio/test', [TwilioWebhookController::class, 'test'])
    ->name('webhook.twilio.test');

// Ruta para visualización de resultados
Route::get('resultados', [ResultadosController::class, 'index'])
    ->name('resultados.index')
    ->middleware(['auth']);

Route::get('resultados/exportar', [ResultadosController::class, 'exportar'])
    ->name('resultados.exportar')
    ->middleware(['auth']);

Route::get('resultados/detalle', [ResultadosController::class, 'detalle'])
    ->name('resultados.detalle')
    ->middleware(['auth']);

require __DIR__.'/auth.php';
