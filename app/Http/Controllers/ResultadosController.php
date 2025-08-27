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

class ResultadosController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalEnvios = Envio::count();
        $enviosCompletados = Envio::where('estado', 'completado')->count();
        $enviosCancelados = Envio::where('estado', 'cancelado')->count();
        $enviosPendientes = Envio::where('estado', 'pendiente')->count();

        // Tasa de respuesta
        $tasaRespuesta = $totalEnvios > 0 ? round(($enviosCancelados / $totalEnvios) * 100, 2) : 0;

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

        // Respuestas por pregunta (si las respuestas son numéricas)
        $respuestasPregunta1 = Envio::select('respuesta_1', DB::raw('count(*) as total'))
            ->whereNotNull('respuesta_1')
            ->where('respuesta_1', '!=', '')
            ->where('estado', 'completado')
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

        // Calcular NPS (Net Promoter Score) basado en respuesta_1
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
            'respuestasPregunta4',
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
     * Calcula el NPS (Net Promoter Score) basado en respuesta_1
     */
    private function calcularNPS()
    {
        $enviosCompletados = Envio::where('estado', 'completado')
            ->whereNotNull('respuesta_1')
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
        $promotores = $enviosCompletados->where('respuesta_1', '>=', 9)->count();
        $pasivos = $enviosCompletados->where('respuesta_1', '>=', 7)->where('respuesta_1', '<=', 8)->count();
        $detractores = $enviosCompletados->where('respuesta_1', '<=', 6)->count();

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
} 