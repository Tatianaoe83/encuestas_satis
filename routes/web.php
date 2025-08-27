<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\TwilioWebhookController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;

Route::redirect('/', '/login');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('dashboard/estadisticas-tiempo-real', [App\Http\Controllers\DashboardController::class, 'estadisticasTiempoReal'])
    ->middleware(['auth'])
    ->name('dashboard.estadisticas-tiempo-real');

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

Route::post('envios/{idenvio}/enviar-por-whatsapp', [EnvioController::class, 'enviarPorWhatsApp'])
    ->name('envios.enviar-por-whatsapp')
    ->middleware(['auth']);

Route::post('envios/{idenvio}/marcar-enviado', [EnvioController::class, 'marcarEnviado'])
    ->name('envios.marcar-enviado')
    ->middleware(['auth']);

    Route::post('envios/{idenvio}/marcar-respondido', [EnvioController::class, 'marcarRespondido'])
    ->name('envios.marcar-respondido')
    ->middleware(['auth']);

// Ruta para visualización de resultados
Route::get('resultados', [ResultadosController::class, 'index'])
    ->name('resultados.index')
    ->middleware(['auth']);

Route::get('resultados/exportar', [ResultadosController::class, 'exportar'])
    ->name('resultados.exportar')
    ->middleware(['auth']);

Route::get('resultados/exportar-nps', [ResultadosController::class, 'exportarNPS'])
    ->name('resultados.exportar-nps')
    ->middleware(['auth']);

Route::get('resultados/exportar-estadisticas', [ResultadosController::class, 'exportarEstadisticas'])
    ->name('resultados.exportar-estadisticas')
    ->middleware(['auth']);

Route::get('resultados/detalle', [ResultadosController::class, 'detalle'])
    ->name('resultados.detalle')
    ->middleware(['auth']);

    
require __DIR__.'/auth.php';

// Incluir rutas de webhook
require __DIR__.'/webhook.php';

// Rutas para el chat y mensajería
Route::prefix('chat')->group(function () {
    // Enviar mensaje de chat
    Route::post('/enviar', [ChatController::class, 'enviarMensaje'])
        ->name('chat.enviar');
    
    // Obtener historial de mensajes
    Route::get('/historial', [ChatController::class, 'obtenerHistorial'])
        ->name('chat.historial');
    
    // Obtener solo las respuestas recibidas
    Route::get('/respuestas', [ChatController::class, 'obtenerRespuestas'])
        ->name('chat.respuestas');
    
    // Verificar configuración de Twilio
    Route::get('/verificar-config', [ChatController::class, 'verificarConfiguracion'])
        ->name('chat.verificar-config');
    
    // Webhook para recibir respuestas de Twilio
    Route::post('/webhook-respuesta', [ChatController::class, 'webhookRespuesta'])
        ->name('chat.webhook-respuesta');
});

// Ruta para mostrar la interfaz de chat
Route::get('/chat', function () {
    return view('chat.index');
})->name('chat.index');


