<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EncuestaController extends Controller
{
    /**
     * Generar token corto para la encuesta
     */
    public static function generarTokenCorto($idenvio)
    {
        // Crear un token único de 16 caracteres usando el ID + salt fijo
        $salt = 'encuestas_satis_2024';
        $hash = hash('sha256', $idenvio . $salt);
        $token = substr($hash, 0, 16);
        
        return $token . '_' . $idenvio;
    }

    /**
     * Extraer ID del token corto
     */
    public static function extraerIdDelToken($token)
    {
        // El formato es: token_id
        $partes = explode('_', $token);
        if (count($partes) === 2) {
            return $partes[1];
        }
        throw new \Exception('Token inválido');
    }

    /**
     * Verificar si el token es válido
     */
    public static function verificarToken($token, $idenvio)
    {
        $tokenGenerado = self::generarTokenCorto($idenvio);
        return hash_equals($token, $tokenGenerado);
    }

    /**
     * Generar URL corta para la encuesta
     */
    public static function generarUrlCorta($idenvio)
    {
        $token = self::generarTokenCorto($idenvio);
        return route('encuesta.mostrar', ['idencrypted' => $token]);
    }
    /**
     * Mostrar la encuesta en el navegador
     */
    public function mostrar($idencrypted)
    {
        //dd(self::extraerIdDelToken($idencrypted));
        try {
            // Extraer el ID del token corto
            $idenvio = self::extraerIdDelToken($idencrypted);
            Log::info('Token recibido: ' . $idencrypted . ' | ID extraído: ' . $idenvio);
            
            $envio = Envio::with('cliente')->findOrFail($idenvio);
            Log::info('Envío encontrado - ID: ' . $envio->id . ' | Estado: ' . $envio->estado);
            
            // Verificar que el token es válido
            if (!self::verificarToken($idencrypted, $idenvio)) {
                Log::error('Token inválido - Token recibido: ' . $idencrypted . ' | Token esperado: ' . self::generarTokenCorto($idenvio));
                return view('encuesta.error', [
                    'mensaje' => 'Enlace de encuesta no válido.'
                ]);
            }
            
            Log::info('Token válido');
            
            // Verificar que el envío existe y tiene un cliente asociado
            if (!$envio->cliente) {
                Log::error('No se encontró cliente asociado al envío ID: ' . $envio->id);
                return view('encuesta.error', [
                    'mensaje' => 'No se encontró información del cliente para esta encuesta.'
                ]);
            }
            
            Log::info('Cliente asociado encontrado - ID: ' . $envio->cliente->id);

            // Verificar el estado del envío y mostrar la vista correspondiente
            switch ($envio->estado) {
                case 'cancelado':
                    Log::warning('Encuesta cancelada - Envío ID: ' . $envio->id);
                    return view('encuesta.error', [
                        'mensaje' => $envio->estado === 'cancelado' 
                            ? 'Esta encuesta ha sido cancelada.' 
                            : 'Ha ocurrido un error con esta encuesta.'
                    ]);
                case 'error':
                    Log::error('Encuesta con error - Envío ID: ' . $envio->id);
                    return view('encuesta.error', [
                        'mensaje' => $envio->estado === 'error' 
                            ? 'Ha ocurrido un error con esta encuesta.'
                            : 'Ha ocurrido un error con esta encuesta.'
                    ]);
               
                case 'completado':
                    Log::info('Mostrando encuesta completada - Envío ID: ' . $envio->id);
                    return view('encuesta.completada', [
                        'envio' => $envio,
                        'cliente' => $envio->cliente
                    ]);
                
                default:
                    // Para estados como 'pendiente', 'enviado', 'en_proceso', etc.
                    // Determinar qué pregunta mostrar
                    $preguntaActual = $this->determinarPreguntaActual($envio);
                    Log::info('Mostrando encuesta - Envío ID: ' . $envio->id . ' | Estado: ' . $envio->estado . ' | Pregunta actual: ' . $preguntaActual);
                    
                    return view('encuesta.mostrar', [
                        'envio' => $envio,
                        'cliente' => $envio->cliente,
                        'preguntaActual' => $preguntaActual,
                        'idencrypted' => $idencrypted
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Excepción al mostrar encuesta: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return view('encuesta.error', [
                'mensaje' => 'No se pudo cargar la encuesta. Verifique que el enlace sea correcto.'
            ]);
        }
    }

    /**
     * Procesar respuesta de la encuesta
     */
    public function responder(Request $request, $idencrypted)
    {
     
        try {
            // Extraer el ID del token corto
            $idenvio = self::extraerIdDelToken($idencrypted);
            $envio = Envio::with('cliente')->findOrFail($idenvio);
            
            // Verificar que el token es válido
            if (!self::verificarToken($idencrypted, $idenvio)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enlace de encuesta no válido.'
                ], 400);
            }
            
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

            if (strlen(trim($respuesta)) < 5) {
                return [
                    'valida' => false,
                    'mensaje' => 'Por favor, escriba al menos 5 caracteres.'
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

        // Si es la primera pregunta (cualquiera de las 1.1 a 1.5), cambiar estado a en_proceso
        if (in_array($pregunta, ['1.1', '1.2', '1.3', '1.4', '1.5']) && $envio->estado !== 'en_proceso') {
            $datosActualizacion['estado'] = 'en_proceso';
        }

        // Si es la pregunta 1.5, calcular el promedio de las respuestas 1_1 a 1_5
        if ($pregunta === '1.5') {
            $promedio = $this->calcularPromedioRespuestas1($envio, (int) $respuesta);
            $datosActualizacion['promedio_respuesta_1'] = $promedio;
        }

        // Si es la última pregunta, marcar como completado
        if ($pregunta === '3' || ($pregunta === '2' && trim(strtolower($respuesta)) === 'si')) {
            $datosActualizacion['estado'] = 'completado';
            $datosActualizacion['fecha_respuesta'] = \Carbon\Carbon::now();
            // Desactivar timer cuando se completa la encuesta
            $datosActualizacion['timer_activo'] = false;
            $datosActualizacion['estado_timer'] = 'completado';
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

    /**
     * Calcular el promedio de las respuestas 1_1 a 1_5
     */
    private function calcularPromedioRespuestas1($envio, $respuesta1_5)
    {
        $respuestas = [
            $envio->respuesta_1_1,
            $envio->respuesta_1_2,
            $envio->respuesta_1_3,
            $envio->respuesta_1_4,
            $respuesta1_5
        ];

        // Filtrar valores nulos y calcular promedio
        $respuestasValidas = array_filter($respuestas, function($valor) {
            return $valor !== null && $valor !== '';
        });

        if (empty($respuestasValidas)) {
            return 0;
        }

        $suma = array_sum($respuestasValidas);
        $cantidad = count($respuestasValidas);
        
        return round($suma / $cantidad, 2);
    }
}
