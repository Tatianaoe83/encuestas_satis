<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\EncuestasExport;
use App\Exports\NPSExport;
use App\Exports\EstadisticasExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ResultadosController extends Controller
{
    public function index()
    {
        // Headers para evitar caché en móviles
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        // Estadísticas generales
        $totalEnvios = Envio::count();
        $enviosCompletados = Envio::where('estado', 'completado')->count();
        $enviosCancelados = Envio::where('estado', 'cancelado')->count();
        $enviosPendientes = Envio::whereIn('estado', ['enviado', 'en_proceso'])->count();
        //dd($enviosPendientes);

        // Tasa de respuesta
        $tasaRespuesta = $totalEnvios > 0 ? round(($enviosCancelados / $totalEnvios) * 100, 2) : 0;

        // Envíos por estado (para gráfica de dona)
        $enviosPorEstado = Envio::select(
            DB::raw("
        CASE 
            WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
            WHEN estado = 'completado' THEN 'completado'
            WHEN estado = 'cancelado' THEN 'cancelado'
        END AS estado
    "),
            DB::raw('COUNT(*) AS total')
        )
            ->whereIn('estado', ['enviado', 'en_proceso', 'completado', 'cancelado'])
            ->groupBy(DB::raw("
    CASE 
        WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
        WHEN estado = 'completado' THEN 'completado'
        WHEN estado = 'cancelado' THEN 'cancelado'
    END
"))
            ->get();

        // Envíos por mes (para gráfica de línea)
        $enviosPorMes = Envio::select(
            DB::raw('MONTH(fecha_envio) as mes'),
            DB::raw('YEAR(fecha_envio) as año'),
            DB::raw('count(*) as total')
        )
            ->whereNotNull('fecha_envio')
            ->where('estado', 'completado')
            ->groupBy('mes', 'año')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();

        // Envíos por estado por mes (para gráfica de barras apiladas)
        $enviosPorEstadoPorMes = Envio::select(
            DB::raw('MONTH(fecha_envio) as mes'),
            DB::raw('YEAR(fecha_envio) as año'),
            DB::raw("
                CASE 
                    WHEN estado = 'completado' THEN 'completado'
                    WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
                    WHEN estado = 'cancelado' THEN 'sin_respuesta'
                END AS estado_grupo
            "),
            DB::raw('count(*) as total')
        )
            ->whereNotNull('fecha_envio')
            ->whereIn('estado', ['completado', 'enviado', 'en_proceso', 'cancelado'])
            ->groupBy('mes', 'año', DB::raw("
                CASE 
                    WHEN estado = 'completado' THEN 'completado'
                    WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
                    WHEN estado = 'cancelado' THEN 'sin_respuesta'
                END
            "))
            ->orderBy('año')
            ->orderBy('mes')
            ->get();


        // Detalles de asesores con NPS y promedio de servicio
        $topAsesores = Cliente::select(
            'clientes.asesor_comercial',
            DB::raw('COUNT(envios.idenvio) AS total_envios'),

            DB::raw('ROUND(AVG(envios.respuesta_1_3), 2) AS promedio_servicio'),

            DB::raw('
            ROUND(
                (
                    (SUM(CASE WHEN envios.promedio_respuesta_1 >= 9 THEN 1 ELSE 0 END) * 100.0 / COUNT(envios.idenvio))
                    -
                    (SUM(CASE WHEN envios.promedio_respuesta_1 <= 6 THEN 1 ELSE 0 END) * 100.0 / COUNT(envios.idenvio))
                ), 1
            ) AS nps_score
            ')
        )
            ->join('envios', 'clientes.idcliente', '=', 'envios.cliente_id')
            ->whereNull('clientes.deleted_at')
            ->whereNull('envios.deleted_at')
            ->where('envios.estado', 'completado')
            ->groupBy('clientes.asesor_comercial')
            ->orderByDesc('nps_score')
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

        // Envíos por estado por día de la semana (para gráfica de barras apiladas)
        $enviosPorEstadoPorDia = Envio::select(
            DB::raw('DAYOFWEEK(fecha_envio) as dia_semana'),
            DB::raw("
                CASE 
                    WHEN estado = 'completado' THEN 'completado'
                    WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
                    WHEN estado = 'cancelado' THEN 'sin_respuesta'
                END AS estado_grupo
            "),
            DB::raw('count(*) as total')
        )
            ->whereNotNull('fecha_envio')
            ->whereIn('estado', ['completado', 'enviado', 'en_proceso', 'cancelado'])
            ->groupBy('dia_semana', DB::raw("
                CASE 
                    WHEN estado = 'completado' THEN 'completado'
                    WHEN estado IN ('enviado', 'en_proceso') THEN 'pendiente'
                    WHEN estado = 'cancelado' THEN 'sin_respuesta'
                END
            "))
            ->orderBy('dia_semana')
            ->get();

        // Respuestas por pregunta usando los campos correctos
        // Pregunta 1: Calidad del producto (promedio de 1.1 a 1.5)
        $respuestasPregunta1 = Envio::select('promedio_respuesta_1', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('promedio_respuesta_1')
            ->where('envios.estado', 'completado')
            ->groupBy('promedio_respuesta_1', 'clientes.asesor_comercial')
            ->orderBy('promedio_respuesta_1')
            ->get();

        // Pregunta 2: ¿Recomendarías a Konkret?
        $respuestasPregunta2 = Envio::select('respuesta_2', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_2')
            ->where('respuesta_2', '!=', '')
            ->where('envios.estado', 'Completado')
            ->whereNull('envios.deleted_at')
            ->groupBy('respuesta_2', 'clientes.asesor_comercial')
            ->get();

        // Pregunta 3: ¿Qué podríamos hacer para mejorar tu experiencia?
        $respuestasPregunta3 = Envio::select('respuesta_3', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_3')
            ->where('respuesta_3', '!=', '')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_3', 'clientes.asesor_comercial')
            ->get();

        // Detalle de respuestas 1.1 a 1.5 para análisis individual
        $respuestasDetalle1 = $this->obtenerRespuestasDetalle1();

        //Calculo de recomendaciones
        $recomendacion = DB::selectOne("
            SELECT 
                SUM(CASE WHEN LOWER(TRIM(envios.respuesta_2)) = 'si' THEN 1 ELSE 0 END) AS positivos,
                SUM(CASE WHEN LOWER(TRIM(envios.respuesta_2)) = 'no' THEN 1 ELSE 0 END) AS negativos,
                COUNT(envios.respuesta_2) AS total,
                ROUND(
                    (SUM(CASE WHEN LOWER(TRIM(envios.respuesta_2)) = 'si' THEN 1 ELSE 0 END) / 
                    NULLIF(COUNT(envios.respuesta_2), 0)) * 100, 1
                ) AS porcentaje_recomendacion
            FROM envios
            WHERE TRIM(LOWER(envios.estado)) = 'completado'
            AND envios.respuesta_2 IS NOT NULL
        ");

        // Calcular NPS (Net Promoter Score) basado en promedio_respuesta_1
        $npsData = $this->calcularNPS();

        return view('resultados.index', compact(
            'totalEnvios',
            'enviosCompletados',
            'enviosPendientes',
            'enviosCancelados',
            'tasaRespuesta',
            'enviosPorEstado',
            'enviosPorMes',
            'enviosPorEstadoPorMes',
            'topAsesores',
            'enviosPorDia',
            'enviosPorEstadoPorDia',
            'respuestasPregunta1',
            'respuestasPregunta2',
            'respuestasPregunta3',
            'respuestasDetalle1',
            'npsData',
        ));
    }

    public function exportar(Request $request)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "encuestas_satisfaccion_{$timestamp}.xlsx";

        return Excel::download(new EncuestasExport(), $filename);
    }

    /**
     * Exporta estadísticas resumidas en formato Excel
     */
    public function exportarEstadisticas(Request $request)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "estadisticas_generales_{$timestamp}.xlsx";

        return Excel::download(new EstadisticasExport(), $filename);
    }

    /**
     * Exporta solo los datos del NPS en formato Excel
     */
    public function exportarNPS(Request $request)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "nps_encuestas_{$timestamp}.xlsx";

        return Excel::download(new NPSExport(), $filename);
    }

    public function detalle(Request $request)
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $estadisticasAsesores = Cliente::select('asesor_comercial')
            ->distinct()
            ->whereNotNull('asesor_comercial')
            ->get()
            ->map(function ($asesor) {
                $asesor->total_envios = Envio::join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
                    ->where('clientes.asesor_comercial', $asesor->asesor_comercial)
                    ->whereNull('envios.deleted_at')
                    ->count();
                
                $asesor->completados = Envio::join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
                    ->where('clientes.asesor_comercial', $asesor->asesor_comercial)
                    ->where('envios.estado', 'completado')
                    ->whereNull('envios.deleted_at')
                    ->count();
                
                $asesor->cancelados = Envio::join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
                    ->where('clientes.asesor_comercial', $asesor->asesor_comercial)
                    ->where('envios.estado', 'cancelado')
                    ->whereNull('envios.deleted_at')
                    ->count();
                
                $asesor->pendientes = Envio::join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
                    ->where('clientes.asesor_comercial', $asesor->asesor_comercial)
                    ->whereIn('envios.estado', ['enviado', 'en_proceso'])
                    ->whereNull('envios.deleted_at')
                    ->count();
                
                $asesor->tasa_respuesta = $asesor->total_envios > 0 ?
                    round(($asesor->cancelados / $asesor->total_envios) * 100, 2) : 0;
                
                return $asesor;
            })
            ->filter(function ($asesor) {
                return $asesor->total_envios > 0;
            })
            ->sortByDesc('total_envios')
            ->values();

        $enviosRecientes = Envio::with('cliente')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $estadosDisponibles = [
            'todos' => 'Todos',
            'pendiente' => 'Pendientes',
            'completado' => 'Completados',
            'cancelado' => 'Sin Respuesta'
        ];

        return view('resultados.detalle', compact('estadisticasAsesores', 'enviosRecientes', 'estadosDisponibles'));
    }


    /* public function detalle(Request $request)
    {
        // Headers para evitar caché en móviles
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        // Estadísticas detalladas por asesor
        $estadisticasAsesores = Cliente::select(
            'asesor_comercial',
            DB::raw('count(envios.idenvio) as total_envios'),
            DB::raw('sum(case when envios.estado = "completado" then 1 else 0 end) as completados'),
            DB::raw('sum(case when envios.estado = "cancelado" then 1 else 0 end) as cancelados'),
            DB::raw('sum(case when envios.estado = "pendiente" then 1 else 0 end) as pendientes')
        )
            ->join('envios', 'clientes.idcliente', '=', 'envios.cliente_id')
            ->groupBy('asesor_comercial')
            ->orderByDesc('total_envios')
            ->get()
            ->map(function ($asesor) {
                $asesor->tasa_respuesta = $asesor->total_envios > 0 ?
                    round(($asesor->cancelados / $asesor->total_envios) * 100, 2) : 0;
                return $asesor;
            });

        // Filtrados de envios
        $estado = $request->input('estado');
        dd($estado);

        $query = Envio::with('cliente')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');

        if (!empty($estado) && $estado !== 'todos') {
            $query->where('estado', $estado);
        }

        $enviosRecientes = $query->paginate(10)->withQueryString();

        return view('resultados.detalle', compact('estadisticasAsesores', 'enviosRecientes'));
    } */

    /**
     * Calcula el NPS (Net Promoter Score) basado en promedio_respuesta_1
     */
    private function calcularNPS()
    {
        $enviosCompletados = Envio::where('estado', 'completado')
            ->whereNotNull('promedio_respuesta_1')
            ->where('fecha_envio', '>=', Carbon::now()->subMonths(6))
            ->where('fecha_envio', '<=', Carbon::now())
            ->get();

        if ($enviosCompletados->count() === 0) {
            return [
                'nps_score' => 0,
                'promotores' => 0,
                'pasivos' => 0,
                'detractores' => 0,
                'total' => 0,
                'porcentaje_promotores' => 0,
                'porcentaje_pasivos' => 0,
                'porcentaje_detractores' => 0
            ];
        }

        $total = $enviosCompletados->count();
        $promotores = $enviosCompletados->where('promedio_respuesta_1', '>=', 9)->count();
        $pasivos = $enviosCompletados->where('promedio_respuesta_1', '>=', 7)->where('promedio_respuesta_1', '<', 9)->count();
        $detractores = $enviosCompletados->where('promedio_respuesta_1', '<', 7)->count();

        // Calcular NPS correctamente: % Promotores - % Detractores
        $porcentajePromotores = ($promotores / $total) * 100;
        $porcentajeDetractores = ($detractores / $total) * 100;
        $npsScore = round($porcentajePromotores - $porcentajeDetractores, 1);

        return [
            'nps_score' => $npsScore,
            'promotores' => $promotores,
            'pasivos' => $pasivos,
            'detractores' => $detractores,
            'total' => $total,
            'porcentaje_promotores' => round($porcentajePromotores, 1),
            'porcentaje_pasivos' => round(($pasivos / $total) * 100, 1),
            'porcentaje_detractores' => round($porcentajeDetractores, 1)
        ];
    }

    /**
     * Obtiene el detalle de las respuestas 1.1 a 1.5 para análisis individual
     */
    private function obtenerRespuestasDetalle1()
    {
        $detalle = [];

        // Respuestas 1.1
        $respuestas1_1 = Envio::select('respuesta_1_1', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_1_1')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_1_1', 'clientes.asesor_comercial')
            ->orderBy('respuesta_1_1')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.1 - Calidad del producto',
                    'respuesta' => $item->respuesta_1_1,
                    'asesor_comercial' => $item->asesor_comercial,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.2
        $respuestas1_2 = Envio::select('respuesta_1_2', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_1_2')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_1_2', 'clientes.asesor_comercial')
            ->orderBy('respuesta_1_2')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.2 - Puntualidad de entrega',
                    'respuesta' => $item->respuesta_1_2,
                    'asesor_comercial' => $item->asesor_comercial,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.3
        $respuestas1_3 = Envio::select('respuesta_1_3', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_1_3')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_1_3', 'clientes.asesor_comercial')
            ->orderBy('respuesta_1_3')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.3 - Trato del asesor comercial',
                    'respuesta' => $item->respuesta_1_3,
                    'asesor_comercial' => $item->asesor_comercial,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.4
        $respuestas1_4 = Envio::select('respuesta_1_4', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_1_4')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_1_4', 'clientes.asesor_comercial')
            ->orderBy('respuesta_1_4')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.4 - Precio',
                    'respuesta' => $item->respuesta_1_4,
                    'asesor_comercial' => $item->asesor_comercial,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.5
        $respuestas1_5 = Envio::select('respuesta_1_5', 'clientes.asesor_comercial', DB::raw('count(*) as total'))
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->whereNotNull('respuesta_1_5')
            ->where('envios.estado', 'completado')
            ->groupBy('respuesta_1_5', 'clientes.asesor_comercial')
            ->orderBy('respuesta_1_5')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.5 - Rapidez en programación',
                    'respuesta' => $item->respuesta_1_5,
                    'asesor_comercial' => $item->asesor_comercial,
                    'total' => $item->total
                ];
            });

        return [
            '1_1' => $respuestas1_1,
            '1_2' => $respuestas1_2,
            '1_3' => $respuestas1_3,
            '1_4' => $respuestas1_4,
            '1_5' => $respuestas1_5
        ];
    }

    /**
     * Obtiene los últimos envíos de calificaciones de calidad del producto organizados por asesor
     */
    public function obtenerUltimosEnviosCalidad(Request $request)
    {
        $asesor = $request->get('asesor', '');

        $query = Envio::select(
            'envios.idenvio',
            'envios.fecha_envio',
            'envios.fecha_respuesta',
            'envios.respuesta_1_1',
            'envios.respuesta_1_2',
            'envios.respuesta_1_3',
            'envios.respuesta_1_4',
            'envios.respuesta_1_5',
            'envios.promedio_respuesta_1',
            'clientes.asesor_comercial',
            'clientes.razon_social',
            'clientes.nombre_completo',
            'clientes.puesto'
        )
            ->join('clientes', 'envios.cliente_id', '=', 'clientes.idcliente')
            ->where('envios.estado', 'completado')
            ->whereNotNull('envios.promedio_respuesta_1');

        // Si se especifica un asesor, filtrar por ese asesor
        if (!empty($asesor)) {
            $query->where('clientes.asesor_comercial', $asesor);
        }

        $ultimosEnvios = $query->orderBy('envios.fecha_respuesta', 'desc')
            ->limit(50)
            ->get();

        // Si hay un asesor específico, devolver directamente los envíos
        if (!empty($asesor)) {
            return response()->json([
                'asesor' => $asesor,
                'envios' => $ultimosEnvios,
                'total' => $ultimosEnvios->count()
            ]);
        }

        // Si no hay asesor específico, agrupar por asesor
        return response()->json($ultimosEnvios->groupBy('asesor_comercial'));
    }
}
