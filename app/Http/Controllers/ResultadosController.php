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


        // Top 5 asesores comerciales por envíos
        $topAsesores = Cliente::select('asesor_comercial', DB::raw('count(envios.idenvio) as total_envios'))
            ->join('envios', 'clientes.idcliente', '=', 'envios.cliente_id')
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

        // Respuestas por pregunta usando los campos correctos
        // Pregunta 1: Calidad del producto (promedio de 1.1 a 1.5)
        $respuestasPregunta1 = Envio::select('promedio_respuesta_1', DB::raw('count(*) as total'))
            ->whereNotNull('promedio_respuesta_1')
            ->where('estado', 'completado')
            ->groupBy('promedio_respuesta_1')
            ->orderBy('promedio_respuesta_1')
            ->get();

        // Pregunta 2: ¿Recomendarías a Konkret?
        $respuestasPregunta2 = Envio::select('respuesta_2', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_2')
            ->where('respuesta_2', '!=', '')
            ->where('estado', 'completado')
            ->groupBy('respuesta_2')
            ->get();

        // Pregunta 3: ¿Qué podríamos hacer para mejorar tu experiencia?
        $respuestasPregunta3 = Envio::select('respuesta_3', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_3')
            ->where('respuesta_3', '!=', '')
            ->where('estado', 'completado')
            ->groupBy('respuesta_3')
            ->get();

        // Detalle de respuestas 1.1 a 1.5 para análisis individual
        $respuestasDetalle1 = $this->obtenerRespuestasDetalle1();

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
            'topAsesores',
            'enviosPorDia',
            'respuestasPregunta1',
            'respuestasPregunta2',
            'respuestasPregunta3',
            'respuestasDetalle1',
            'npsData'
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

    public function detalle()
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

        // Envíos recientes
        $enviosRecientes = Envio::with('cliente')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('resultados.detalle', compact('estadisticasAsesores', 'enviosRecientes'));
    }

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
        $respuestas1_1 = Envio::select('respuesta_1_1', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1_1')
            ->where('estado', 'completado')
            ->groupBy('respuesta_1_1')
            ->orderBy('respuesta_1_1')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.1 - Calidad del producto',
                    'respuesta' => $item->respuesta_1_1,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.2
        $respuestas1_2 = Envio::select('respuesta_1_2', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1_2')
            ->where('estado', 'completado')
            ->groupBy('respuesta_1_2')
            ->orderBy('respuesta_1_2')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.2 - Puntualidad de entrega',
                    'respuesta' => $item->respuesta_1_2,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.3
        $respuestas1_3 = Envio::select('respuesta_1_3', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1_3')
            ->where('estado', 'completado')
            ->groupBy('respuesta_1_3')
            ->orderBy('respuesta_1_3')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.3 - Trato del asesor comercial',
                    'respuesta' => $item->respuesta_1_3,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.4
        $respuestas1_4 = Envio::select('respuesta_1_4', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1_4')
            ->where('estado', 'completado')
            ->groupBy('respuesta_1_4')
            ->orderBy('respuesta_1_4')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.4 - Precio',
                    'respuesta' => $item->respuesta_1_4,
                    'total' => $item->total
                ];
            });

        // Respuestas 1.5
        $respuestas1_5 = Envio::select('respuesta_1_5', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1_5')
            ->where('estado', 'completado')
            ->groupBy('respuesta_1_5')
            ->orderBy('respuesta_1_5')
            ->get()
            ->map(function ($item) {
                return [
                    'pregunta' => '1.5 - Rapidez en programación',
                    'respuesta' => $item->respuesta_1_5,
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
}
