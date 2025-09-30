<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\TwilioWebhookController;
use App\Http\Controllers\TwilioController;
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

// Ruta adicional para reactivar clientes
Route::post('clientes/{id}/restore', [ClienteController::class, 'restore'])
    ->name('clientes.restore')
    ->middleware(['auth']);

// Rutas para el módulo de envíos
Route::resource('envios', EnvioController::class)
    ->parameters(['envios' => 'idenvio'])
    ->middleware(['auth']);

// Rutas adicionales para envíos


// ruta para visualizar la encuesta en web (con idencriptado para seguridad)
Route::get('encuesta/{idencrypted}', [App\Http\Controllers\EncuestaController::class, 'mostrar'])
    ->name('encuesta.mostrar');

Route::post('encuesta/{idencrypted}/responder', [App\Http\Controllers\EncuestaController::class, 'responder'])
    ->name('encuesta.responder');


Route::post('envios/{idenvio}/enviar-por-whatsapp', [EnvioController::class, 'enviarPorWhatsApp'])
    ->name('envios.enviar-por-whatsapp')
    ->middleware(['auth']);

Route::post('envios/{idenvio}/marcar-enviado', [EnvioController::class, 'marcarEnviado'])
    ->name('envios.marcar-enviado')
    ->middleware(['auth']);

    Route::post('envios/{idenvio}/marcar-respondido', [EnvioController::class, 'marcarRespondido'])
    ->name('envios.marcar-respondido')
    ->middleware(['auth']);

Route::get('envios/{idenvio}/url-encriptada', [EnvioController::class, 'generarUrlEncriptada'])
    ->name('envios.url-encriptada')
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

// Rutas para contenido aprobado y timers
Route::prefix('contenido-aprobado')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('contenido-aprobado.index');
    })->name('contenido-aprobado.index');
    
    Route::post('/enviar', [App\Http\Controllers\ContenidoAprobadoController::class, 'enviarContenidoAprobado'])
        ->name('contenido-aprobado.enviar');
    
    Route::get('/timers-activos', [App\Http\Controllers\ContenidoAprobadoController::class, 'obtenerTimersActivos'])
        ->name('contenido-aprobado.timers-activos');
    
    Route::post('/cancelar-timer', [App\Http\Controllers\ContenidoAprobadoController::class, 'cancelarTimer'])
        ->name('contenido-aprobado.cancelar-timer');
    
    Route::post('/verificar-timers', [App\Http\Controllers\ContenidoAprobadoController::class, 'verificarTimersExpirados'])
        ->name('contenido-aprobado.verificar-timers');
    
    Route::get('/estadisticas', [App\Http\Controllers\ContenidoAprobadoController::class, 'obtenerEstadisticasTimers'])
        ->name('contenido-aprobado.estadisticas');
});

// Rutas para cron interno (sin autenticación para que funcione automáticamente)
Route::prefix('cron-interno')->group(function () {
    Route::get('/ejecutar', [App\Http\Controllers\CronInternoController::class, 'ejecutarCronInterno'])
        ->name('cron-interno.ejecutar');
    
    Route::get('/estado', [App\Http\Controllers\CronInternoController::class, 'verificarEstadoCron'])
        ->name('cron-interno.estado');
    
    Route::post('/forzar', [App\Http\Controllers\CronInternoController::class, 'forzarEjecucion'])
        ->name('cron-interno.forzar');
});

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

// Rutas para información de Twilio
Route::prefix('twilio')->middleware(['auth'])->group(function () {
    Route::get('/', [TwilioController::class, 'index'])
        ->name('twilio.index');
    
    Route::get('/saldo', [TwilioController::class, 'obtenerSaldo'])
        ->name('twilio.saldo');
    
    Route::post('/probar', [TwilioController::class, 'probarConexion'])
        ->name('twilio.probar');
});

// Ruta de prueba para diagnosticar encuestas (con token corto)
Route::get('/test-encuesta/{idencrypted}', function($idencrypted) {
    try {
        // Extraer el ID del token corto
        $idenvio = \App\Http\Controllers\EncuestaController::extraerIdDelToken($idencrypted);
        
        // Verificar que el token es válido
        if (!\App\Http\Controllers\EncuestaController::verificarToken($idencrypted, $idenvio)) {
            return response()->json([
                'success' => false,
                'error' => 'Token inválido'
            ]);
        }
        
        $envio = \App\Models\Envio::with('cliente')->findOrFail($idenvio);
        return response()->json([
            'success' => true,
            'envio' => $envio,
            'cliente' => $envio->cliente,
            'estado' => $envio->estado,
            'pregunta_actual' => $envio->pregunta_actual
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.encuesta');


