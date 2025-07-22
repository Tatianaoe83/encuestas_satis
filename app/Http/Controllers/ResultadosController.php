<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalEnvios = Envio::count();
        $enviosEnviados = Envio::where('estado', 'enviado')->count();
        $enviosRespondidos = Envio::where('estado', 'respondido')->count();
        $enviosPendientes = Envio::where('estado', 'pendiente')->count();
        $enviosCancelados = Envio::where('estado', 'cancelado')->count();

        // Tasa de respuesta
        $tasaRespuesta = $totalEnvios > 0 ? round(($enviosRespondidos / $totalEnvios) * 100, 2) : 0;

        // Envíos por estado (para gráfica de dona)
        $enviosPorEstado = Envio::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Envíos por mes (para gráfica de línea)
        $enviosPorMes = Envio::select(
                DB::raw('MONTH(fecha_envio) as mes'),
                DB::raw('YEAR(fecha_envio) as año'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('fecha_envio')
            ->groupBy('mes', 'año')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();

        // Top 5 asesores comerciales por envíos
        $topAsesores = Cliente::select('asesor_comercial', DB::raw('count(envios.id) as total_envios'))
            ->join('envios', 'clientes.id', '=', 'envios.cliente_id')
            ->groupBy('asesor_comercial')
            ->orderByDesc('total_envios')
            ->limit(5)
            ->get();

        // Envíos por día de la semana
        $enviosPorDia = Envio::select(
                DB::raw('DAYOFWEEK(fecha_envio) as dia_semana'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('fecha_envio')
            ->groupBy('dia_semana')
            ->orderBy('dia_semana')
            ->get();

        // Respuestas por pregunta (si las respuestas son numéricas)
        $respuestasPregunta1 = Envio::select('respuesta_1', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1')
            ->where('respuesta_1', '!=', '')
            ->groupBy('respuesta_1')
            ->get();

        $respuestasPregunta2 = Envio::select('respuesta_2', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_2')
            ->where('respuesta_2', '!=', '')
            ->groupBy('respuesta_2')
            ->get();

        $respuestasPregunta3 = Envio::select('respuesta_3', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_3')
            ->where('respuesta_3', '!=', '')
            ->groupBy('respuesta_3')
            ->get();

        $respuestasPregunta4 = Envio::select('respuesta_4', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_4')
            ->where('respuesta_4', '!=', '')
            ->groupBy('respuesta_4')
            ->get();

        return view('resultados.index', compact(
            'totalEnvios',
            'enviosEnviados',
            'enviosRespondidos',
            'enviosPendientes',
            'enviosCancelados',
            'tasaRespuesta',
            'enviosPorEstado',
            'enviosPorMes',
            'topAsesores',
            'enviosPorDia',
            'respuestasPregunta1',
            'respuestasPregunta2',
            'respuestasPregunta3',
            'respuestasPregunta4'
        ));
    }

    public function exportar(Request $request)
    {
        $envios = Envio::with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'resultados_envios_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($envios) {
            $file = fopen('php://output', 'w');
            
            // Encabezados del CSV
            fputcsv($file, [
                'ID',
                'Cliente',
                'Asesor Comercial',
                'Pregunta 1',
                'Pregunta 2', 
                'Pregunta 3',
                'Pregunta 4',
                'Respuesta 1',
                'Respuesta 2',
                'Respuesta 3',
                'Respuesta 4',
                'Estado',
                'Fecha Envío',
                'Fecha Respuesta',
                'Fecha Creación'
            ]);

            // Datos
            foreach ($envios as $envio) {
                fputcsv($file, [
                    $envio->id,
                    $envio->cliente->razon_social ?? '',
                    $envio->cliente->asesor_comercial ?? '',
                    $envio->pregunta_1,
                    $envio->pregunta_2,
                    $envio->pregunta_3,
                    $envio->pregunta_4,
                    $envio->respuesta_1,
                    $envio->respuesta_2,
                    $envio->respuesta_3,
                    $envio->respuesta_4,
                    $envio->estado,
                    $envio->fecha_envio ? $envio->fecha_envio->format('Y-m-d H:i:s') : '',
                    $envio->fecha_respuesta ? $envio->fecha_respuesta->format('Y-m-d H:i:s') : '',
                    $envio->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function detalle()
    {
        // Estadísticas detalladas por asesor
        $estadisticasAsesores = Cliente::select(
                'asesor_comercial',
                DB::raw('count(envios.id) as total_envios'),
                DB::raw('sum(case when envios.estado = "enviado" then 1 else 0 end) as enviados'),
                DB::raw('sum(case when envios.estado = "respondido" then 1 else 0 end) as respondidos'),
                DB::raw('sum(case when envios.estado = "pendiente" then 1 else 0 end) as pendientes'),
                DB::raw('sum(case when envios.estado = "cancelado" then 1 else 0 end) as cancelados')
            )
            ->join('envios', 'clientes.id', '=', 'envios.cliente_id')
            ->groupBy('asesor_comercial')
            ->orderByDesc('total_envios')
            ->get()
            ->map(function ($asesor) {
                $asesor->tasa_respuesta = $asesor->total_envios > 0 ? 
                    round(($asesor->respondidos / $asesor->total_envios) * 100, 2) : 0;
                return $asesor;
            });

        // Envíos recientes
        $enviosRecientes = Envio::with('cliente')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('resultados.detalle', compact('estadisticasAsesores', 'enviosRecientes'));
    }
} 