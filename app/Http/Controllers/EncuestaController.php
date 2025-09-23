<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EncuestaController extends Controller
{
    /**
     * Mostrar la encuesta en el navegador
     */
    public function mostrar($idenvio)
    {
        try {
            $envio = Envio::with('cliente')->findOrFail($idenvio);
            
            // Verificar que el envío existe y tiene un cliente asociado
            if (!$envio->cliente) {
                return view('encuesta.error', [
                    'mensaje' => 'No se encontró información del cliente para esta encuesta.'
                ]);
            }

            // Verificar si la encuesta ya fue completada
            if ($envio->estado === 'completado') {
                return view('encuesta.completada', [
                    'envio' => $envio,
                    'cliente' => $envio->cliente
                ]);
            }

            // Determinar qué pregunta mostrar
            $preguntaActual = $this->determinarPreguntaActual($envio);
            
            return view('encuesta.mostrar', [
                'envio' => $envio,
                'cliente' => $envio->cliente,
                'preguntaActual' => $preguntaActual
            ]);

        } catch (\Exception $e) {
            return view('encuesta.error', [
                'mensaje' => 'No se pudo cargar la encuesta. Verifique que el enlace sea correcto.'
            ]);
        }
    }

    /**
     * Procesar respuesta de la encuesta
     */
    public function responder(Request $request, $idenvio)
    {
        try {
            $envio = Envio::with('cliente')->findOrFail($idenvio);
            
            // Verificar que el envío existe y no está completado
            if ($envio->estado === 'completado') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta encuesta ya fue completada.'
                ], 400);
            }

            $preguntaActual = $this->determinarPreguntaActual($envio);
            $respuesta = $request->input('respuesta');

            // Validar respuesta según el tipo de pregunta
            $validacion = $this->validarRespuesta($preguntaActual, $respuesta);
            if (!$validacion['valida']) {
                return response()->json([
                    'success' => false,
                    'message' => $validacion['mensaje']
                ], 400);
            }

            // Guardar respuesta
            $this->guardarRespuesta($envio, $preguntaActual, $respuesta);

            // Determinar siguiente pregunta
            $siguientePregunta = $this->determinarSiguientePregunta($preguntaActual, $respuesta);
            
            // Actualizar estado del envío
            $this->actualizarEstadoEnvio($envio, $siguientePregunta);

            return response()->json([
                'success' => true,
                'siguientePregunta' => $siguientePregunta,
                'esCompletada' => $siguientePregunta === 'completado',
                'message' => $siguientePregunta === 'completado' 
                    ? '¡Gracias por completar la encuesta!' 
                    : 'Respuesta guardada correctamente.'
            ]);

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en EncuestaController::responder', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'envio_id' => $idenvio
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la respuesta. Intente nuevamente.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Determinar qué pregunta mostrar actualmente
     */
    private function determinarPreguntaActual($envio)
    {
        // Si ya completó la encuesta
        if ($envio->estado === 'completado') {
            return 'completado';
        }

        // Si ya tiene respuesta_3, está completado
        if ($envio->respuesta_3) {
            return 'completado';
        }

        // Determinar pregunta actual basada en las respuestas existentes
        $preguntaActual = $envio->pregunta_actual;

        // Si no tiene respuesta_1_1, mostrar pregunta 1.1
        if (!$envio->respuesta_1_1) {
            return '1.1';
        }

        // Si no tiene respuesta_1_2, mostrar pregunta 1.2
        if (!$envio->respuesta_1_2) {
            return '1.2';
        }

        // Si no tiene respuesta_1_3, mostrar pregunta 1.3
        if (!$envio->respuesta_1_3) {
            return '1.3';
        }

        // Si no tiene respuesta_1_4, mostrar pregunta 1.4
        if (!$envio->respuesta_1_4) {
            return '1.4';
        }

        // Si no tiene respuesta_1_5, mostrar pregunta 1.5
        if (!$envio->respuesta_1_5) {
            return '1.5';
        }

        // Si no tiene respuesta_2, mostrar pregunta 2
        if (!$envio->respuesta_2) {
            return '2';
        }

        // Si tiene respuesta_2 = 'no' y no tiene respuesta_3, mostrar pregunta 3
        if ($envio->respuesta_2 === 'no' && !$envio->respuesta_3) {
            return '3';
        }

        // Si llegó aquí, está completado
        return 'completado';
    }

    /**
     * Validar respuesta según el tipo de pregunta
     */
    private function validarRespuesta($pregunta, $respuesta)
    {
        // Si es pregunta 1.1 a 1.5 (estrellas), validar rango numérico
        if (in_array($pregunta, ['1.1', '1.2', '1.3', '1.4', '1.5'])) {
            if (!is_numeric($respuesta) || $respuesta < 1 || $respuesta > 10) {
                return [
                    'valida' => false,
                    'mensaje' => 'Por favor, seleccione una calificación del 1 al 10.'
                ];
            }
            return ['valida' => true];
        }

        // Si es pregunta 2 (Sí/No), validar opciones
        if ($pregunta === '2') {
            $respuestaLimpia = trim(strtolower($respuesta));
            if (!in_array($respuestaLimpia, ['si', 'sí', 'no'])) {
                return [
                    'valida' => false,
                    'mensaje' => 'Por favor, responda con "Sí" o "No".'
                ];
            }
            return ['valida' => true];
        }

        // Si es pregunta 3 (abierta), validar texto
        if ($pregunta === '3') {
            if (empty(trim($respuesta))) {
                return [
                    'valida' => false,
                    'mensaje' => 'Por favor, escriba su respuesta.'
                ];
            }

            if (strlen(trim($respuesta)) < 10) {
                return [
                    'valida' => false,
                    'mensaje' => 'Por favor, escriba al menos 10 caracteres.'
                ];
            }
            return ['valida' => true];
        }

        return ['valida' => true];
    }

    /**
     * Guardar respuesta en la base de datos
     */
    private function guardarRespuesta($envio, $pregunta, $respuesta)
    {
        $datosActualizacion = [];

        // Mapear preguntas a campos de la base de datos
        switch ($pregunta) {
            case '1.1':
                $datosActualizacion = ['respuesta_1_1' => (int) $respuesta];
                break;
            case '1.2':
                $datosActualizacion = ['respuesta_1_2' => (int) $respuesta];
                break;
            case '1.3':
                $datosActualizacion = ['respuesta_1_3' => (int) $respuesta];
                break;
            case '1.4':
                $datosActualizacion = ['respuesta_1_4' => (int) $respuesta];
                break;
            case '1.5':
                $datosActualizacion = ['respuesta_1_5' => (int) $respuesta];
                break;
            case '2':
                $datosActualizacion = ['respuesta_2' => trim(strtolower($respuesta))];
                break;
            case '3':
                $datosActualizacion = ['respuesta_3' => trim($respuesta)];
                break;
        }

        // Actualizar pregunta_actual
        $datosActualizacion['pregunta_actual'] = $pregunta;

        // Si es la última pregunta, marcar como completado
        if ($pregunta === '3' || ($pregunta === '2' && trim(strtolower($respuesta)) === 'si')) {
            $datosActualizacion['estado'] = 'completado';
            $datosActualizacion['fecha_respuesta'] = now();
        }

        try {
            $envio->update($datosActualizacion);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar envío', [
                'envio_id' => $envio->idenvio,
                'datos' => $datosActualizacion,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Determinar siguiente pregunta
     */
    private function determinarSiguientePregunta($preguntaActual, $respuesta)
    {
        switch ($preguntaActual) {
            case '1.1':
                return '1.2';
            case '1.2':
                return '1.3';
            case '1.3':
                return '1.4';
            case '1.4':
                return '1.5';
            case '1.5':
                return '2';
            case '2':
                // Si responde "no", ir a pregunta 3, si "si", completar
                $respuestaLimpia = trim(strtolower($respuesta));
                return $respuestaLimpia === 'no' ? '3' : 'completado';
            case '3':
                return 'completado';
            default:
                return 'completado';
        }
    }

    /**
     * Actualizar estado del envío
     */
    private function actualizarEstadoEnvio($envio, $siguientePregunta)
    {
        // El estado ya se actualiza en guardarRespuesta
        // Solo actualizar pregunta_actual si es necesario
        if ($siguientePregunta !== 'completado') {
            $envio->update(['pregunta_actual' => $siguientePregunta]);
        }
    }
}
