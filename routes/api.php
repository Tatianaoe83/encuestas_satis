<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TwilioWebhookController;

// Rutas de webhook (sin middleware CSRF)
Route::post('/webhook-test-clean', [TwilioWebhookController::class, 'webhookTestClean']);

Route::post('/webhook-twilio-clean', function(Request $request) {
    \Log::info('=== WEBHOOK LIMPIO RECIBIDO ===', [
        'timestamp' => now(),
        'method' => $request->method(),
        'url' => $request->url(),
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);
    
    return response()->json(['success' => true, 'message' => 'Webhook limpio recibido']);
});

Route::post('/webhook-twilio', [TwilioWebhookController::class, 'webhook']);

Route::post('/webhook-test', function(Request $request) {
    try {
        // Log inicial
        \Log::info('=== WEBHOOK TEST RECIBIDO ===', [
            'timestamp' => now(),
            'data' => $request->all()
        ]);
        
        // Guardar respuesta directamente
        $respuesta = \App\Models\ChatRespuesta::create([
            'message_sid' => $request->input('MessageSid', 'TEST_' . time()),
            'from_number' => $request->input('From', 'unknown'),
            'to_number' => $request->input('To', 'unknown'),
            'body' => $request->input('Body', 'test'),
            'status' => 'received',
            'twilio_data' => $request->all()
        ]);
        
        \Log::info('Respuesta guardada', ['id' => $respuesta->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Respuesta recibida y guardada',
            'data' => $respuesta
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error en webhook test', [
            'error' => $e->getMessage(),
            'data' => $request->all()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Ruta para obtener estado de un envío
Route::get('/envio/estado', [TwilioWebhookController::class, 'getEstadoEnvio']);

// Ruta para ejecutar comando de timers
Route::post('/ejecutar-comando-timers', function() {
    try {
        \Artisan::call('timers:verificar');
        $output = \Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Comando ejecutado exitosamente',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error ejecutando comando: ' . $e->getMessage()
        ], 500);
    }
});
