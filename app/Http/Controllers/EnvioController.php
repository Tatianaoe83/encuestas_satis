<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnvioController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $envios = Envio::with('cliente')->latest()->get();
        $saldoTwilio = $this->twilioService->obtenerSaldo();
        
        return view('envios.index', compact('envios', 'saldoTwilio'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('envios.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,idcliente',
        ]);

        // Crear el envío con las preguntas por defecto
        $envio = Envio::create([
            'cliente_id' => $request->cliente_id,
            'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende Konkret a un colega o contacto del sector construcción?',
            'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
            'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
            'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?',
            'estado' => 'pendiente',
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        $envio->load('cliente');
        return view('envios.show', compact('envio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        $clientes = Cliente::all();
        return view('envios.edit', compact('envio', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|in:pendiente,enviado,respondido,cancelado',
            'respuesta_1' => 'nullable|string|max:1000',
            'respuesta_2' => 'nullable|string|max:1000',
            'respuesta_3' => 'nullable|string|max:1000',
            'respuesta_4' => 'nullable|string|max:1000',
        ]);

        $envio->update($request->all());

        return redirect()->route('envios.index')
            ->with('success', 'Envío actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        $envio->delete();

        return redirect()->route('envios.index')
            ->with('success', 'Envío eliminado exitosamente.');
    }

        /**
     * Enviar encuesta por WhatsApp usando Twilio.
     */
    public function enviarPorWhatsApp($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        
        
        try {
            // Verificar que el cliente tenga número de celular
            if (empty($envio->cliente->celular)) {
                return redirect()->route('envios.index')
                    ->with('error', 'El cliente no tiene número de celular registrado.');
            }

            // Enviar la encuesta por WhatsApp
            $resultado = $this->twilioService->enviarEncuesta($envio);

            if ($resultado) {
                return redirect()->route('envios.index')
                    ->with('success', 'Encuesta enviada exitosamente por WhatsApp.');
            } else {
                return redirect()->route('envios.index')
                    ->with('error', 'Error al enviar la encuesta por WhatsApp.');
            }

        } catch (\Exception $e) {
            return redirect()->route('envios.index')
                    ->with('error', 'Error al enviar la encuesta: ' . $e->getMessage());
        }
    }

 
  
    /**
     * Marcar envío como enviado.
     */
    public function marcarEnviado($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        $envio->update([
            'estado' => 'enviado',
            'fecha_envio' => now(),
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío marcado como enviado.');
    }

    /**
     * Marcar envío como respondido.
     */
    public function marcarRespondido($idenvio)
    {
        $envio = Envio::findOrFail($idenvio);
        $envio->update([
            'estado' => 'respondido',
            'fecha_respuesta' => now(),
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío marcado como respondido.');
    }

    public function respuestas(Request $request) {
        Log::info('Encuesta Konkret recibida:', $request->all());
        
        try {
            // Buscar el envío por número de WhatsApp o crear uno nuevo
            $whatsappNumber = $request->input('From') ?? $request->input('whatsapp_number');
            
            if (!$whatsappNumber) {
                return response()->json(['error' => 'Número de WhatsApp no proporcionado'], 400);
            }
            
            // Limpiar el número de WhatsApp (remover prefijos)
            $cleanNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
            if (strlen($cleanNumber) > 10) {
                $cleanNumber = substr($cleanNumber, -10);
            }
            
            // Buscar cliente por número de celular
            $cliente = Cliente::where('celular', 'LIKE', '%' . $cleanNumber . '%')->first();
            
            if (!$cliente) {
                // Crear cliente temporal si no existe
                $cliente = Cliente::create([
                    'nombre' => 'Cliente WhatsApp',
                    'celular' => $cleanNumber,
                    'email' => 'whatsapp@temp.com',
                    'empresa' => 'Cliente Temporal'
                ]);
            }
            
            // Buscar envío existente o crear uno nuevo
            $envio = Envio::where('cliente_id', $cliente->idcliente)
                         ->where('estado', '!=', 'respondido')
                         ->latest()
                         ->first();
            
            if (!$envio) {
                // Crear nuevo envío
                $envio = Envio::create([
                    'cliente_id' => $cliente->idcliente,
                    'whatsapp_number' => $cleanNumber,
                    'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende Konkret a un colega o contacto del sector construcción?',
                    'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
                    'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
                    'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?',
                    'estado' => 'pendiente'
                ]);
            }
            
            // Procesar respuestas según el formato de Twilio
            $respuestas = [];
            $mensaje = $request->input('Body') ?? $request->input('message', '');
            
            // Mapear respuestas según el flujo de la conversación
            if (strpos($mensaje, '0') !== false || strpos($mensaje, '1') !== false || 
                strpos($mensaje, '2') !== false || strpos($mensaje, '3') !== false || 
                strpos($mensaje, '4') !== false || strpos($mensaje, '5') !== false || 
                strpos($mensaje, '6') !== false || strpos($mensaje, '7') !== false || 
                strpos($mensaje, '8') !== false || strpos($mensaje, '9') !== false || 
                strpos($mensaje, '10') !== false) {
                
                // Es una calificación numérica
                $envio->respuesta_1 = $mensaje;
                $envio->estado = 'respondido';
                $envio->fecha_respuesta = now();
                $envio->whatsapp_responded_at = now();
                
            } else {
                // Es una respuesta de texto
                if (empty($envio->respuesta_1)) {
                    $envio->respuesta_1 = $mensaje;
                } elseif (empty($envio->respuesta_2)) {
                    $envio->respuesta_2 = $mensaje;
                } elseif (empty($envio->respuesta_3)) {
                    $envio->respuesta_3 = $mensaje;
                } elseif (empty($envio->respuesta_4)) {
                    $envio->respuesta_4 = $mensaje;
                    $envio->estado = 'respondido';
                    $envio->fecha_respuesta = now();
                    $envio->whatsapp_responded_at = now();
                }
            }
            
            // Guardar respuestas adicionales en el campo JSON
            $respuestasAdicionales = $envio->whatsapp_responses ?? [];
            $respuestasAdicionales[] = [
                'mensaje' => $mensaje,
                'timestamp' => now()->toISOString(),
                'from' => $whatsappNumber
            ];
            $envio->whatsapp_responses = $respuestasAdicionales;
            
            $envio->save();
            
            Log::info('Respuesta guardada exitosamente para envío ID: ' . $envio->idenvio);
            
            return response()->json([
                'message' => 'Encuesta recibida correctamente',
                'envio_id' => $envio->idenvio,
                'cliente' => $cliente->nombre,
                'estado' => $envio->estado
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al procesar respuesta: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la respuesta'], 500);
        }
    }
} 