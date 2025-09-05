<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Mostrar información de Twilio incluyendo saldo
     */
    public function index()
    {
        try {
            // Obtener configuración de Twilio
            $configuracion = $this->twilioService->verificarConfiguracion();
            
            // Obtener saldo de Twilio
            $saldo = $this->twilioService->obtenerSaldo();
            
            return view('twilio.index', compact('configuracion', 'saldo'));
            
        } catch (\Exception $e) {
            Log::error("Error en TwilioController::index", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('twilio.index', [
                'configuracion' => ['configuracion_completa' => false, 'errores' => [$e->getMessage()]],
                'saldo' => ['success' => false, 'error' => $e->getMessage()]
            ]);
        }
    }

    /**
     * Obtener solo el saldo de Twilio (para AJAX)
     */
    public function obtenerSaldo()
    {
        try {
            $saldo = $this->twilioService->obtenerSaldo();
            
            return response()->json($saldo);
            
        } catch (\Exception $e) {
            Log::error("Error obteniendo saldo de Twilio", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Probar conexión con Twilio
     */
    public function probarConexion(Request $request)
    {
        try {
            $numeroPrueba = $request->input('numero', '5219993778529');
            $resultado = $this->twilioService->probarConexion($numeroPrueba);
            
            return response()->json($resultado);
            
        } catch (\Exception $e) {
            Log::error("Error probando conexión con Twilio", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
