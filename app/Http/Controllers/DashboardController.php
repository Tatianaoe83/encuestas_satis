<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use App\Models\ChatRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales de envíos
        $totalEnvios = Envio::count();
        $enviosCompletados = Envio::where('estado', 'completado')->count();
        $enviosCancelados = Envio::where('estado', 'cancelado')->count();
        $enviosPendientes = Envio::where('estado', 'pendiente')->count();

        // Tasa de respuesta
        $tasaRespuesta = $totalEnvios > 0 ? round(($enviosCompletados / $totalEnvios) * 100, 2) : 0;

        // Estadísticas del mes actual
        $mesActual = Carbon::now()->month;
        $añoActual = Carbon::now()->year;

        $enviosMesActual = Envio::whereMonth('fecha_envio', $mesActual)
            ->whereYear('fecha_envio', $añoActual)
            ->count();

        $enviosMesAnterior = Envio::whereMonth('fecha_envio', $mesActual - 1)
            ->whereYear('fecha_envio', $añoActual)
            ->count();

        // Cálculo de crecimiento mensual
        $crecimientoMensual = 0;
        if ($enviosMesAnterior > 0) {
            $crecimientoMensual = round((($enviosMesActual - $enviosMesAnterior) / $enviosMesAnterior) * 100, 1);
        }

        // Envíos por estado (últimos 30 días)
        $enviosPorEstado = Envio::select('estado', DB::raw('count(*) as total'))
            ->where('fecha_envio', '>=', Carbon::now()->subDays(30))
            ->groupBy('estado')
            ->get();

        // Top 3 asesores comerciales del mes
        $topAsesoresMes = Cliente::select('asesor_comercial', DB::raw('count(envios.idenvio) as total_envios'))
            ->join('envios', 'clientes.idcliente', '=', 'envios.cliente_id')
            ->whereMonth('envios.fecha_envio', $mesActual)
            ->whereYear('envios.fecha_envio', $añoActual)
            ->groupBy('asesor_comercial')
            ->orderByDesc('total_envios')
            ->limit(3)
            ->get();

        // Envíos por día de la semana (últimos 30 días)
        $enviosPorDia = Envio::select(
            DB::raw('DAYOFWEEK(fecha_envio) as dia_semana'),
            DB::raw('count(*) as total')
        )
            ->where('fecha_envio', '>=', Carbon::now()->subDays(30))
            ->groupBy('dia_semana')
            ->orderBy('dia_semana')
            ->get();

        // Envíos por hora del día (últimos 7 días)
        $enviosPorHora = Envio::select(
            DB::raw('HOUR(fecha_envio) as hora'),
            DB::raw('count(*) as total')
        )
            ->where('fecha_envio', '>=', Carbon::now()->subDays(7))
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();

        // Estadísticas de chat y respuestas
        $totalRespuestas = ChatRespuesta::count();
        $respuestasHoy = ChatRespuesta::whereDate('created_at', Carbon::today())->count();
        $respuestasSemana = ChatRespuesta::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Tasa de respuesta por tipo de envío
        $tasaRespuesta = $totalEnvios > 0 ? round(($enviosCancelados / $totalEnvios) * 100, 2) : 0;
        // Métricas de rendimiento
        $promedioRespuesta = 0;
        if ($enviosCompletados > 0) {
            $promedioRespuesta = Envio::where('estado', 'completado')
                ->whereNotNull('fecha_respuesta')
                ->whereNotNull('fecha_envio')
                ->get()
                ->avg(function ($envio) {
                    return Carbon::parse($envio->fecha_envio)
                        ->diffInHours(Carbon::parse($envio->fecha_respuesta));
                });
        }

        // Datos para gráficas
        $datosGraficaMensual = $this->obtenerDatosGraficaMensual();
        $datosGraficaSemanal = $this->obtenerDatosGraficaSemanal();

        // Datos del NPS (Net Promoter Score) usando promedio_respuesta_1
        $datosNPS = $this->obtenerDatosNPS();
        $npsPromedio = $this->calcularNPSPromedio();

        // Estadísticas de calidad del producto (preguntas 1.1 a 1.5)
        $estadisticasCalidad = $this->obtenerEstadisticasCalidad();

        return view('dashboard', compact(
            'totalEnvios',
            'enviosCompletados',
            'enviosCancelados',
            'enviosPendientes',
            'tasaRespuesta',
            'enviosMesActual',
            'crecimientoMensual',
            'enviosPorEstado',
            'topAsesoresMes',
            'enviosPorDia',
            'enviosPorHora',
            'totalRespuestas',
            'respuestasHoy',
            'respuestasSemana',
            'promedioRespuesta',
            'datosGraficaMensual',
            'datosGraficaSemanal',
            'datosNPS',
            'npsPromedio',
            'estadisticasCalidad'
        ));
    }

    /**
     * Obtiene datos para la gráfica mensual
     */
    private function obtenerDatosGraficaMensual()
    {
        return Envio::select(
            DB::raw('MONTH(fecha_envio) as mes'),
            DB::raw('YEAR(fecha_envio) as año'),
            DB::raw('count(*) as total'),
            DB::raw('count(CASE WHEN estado = "completado" THEN 1 END) as completados'),
            DB::raw('count(CASE WHEN estado = "cancelado" THEN 1 END) as cancelados'),
            DB::raw('count(CASE WHEN estado = "pendiente" THEN 1 END) as pendientes')
        )
            ->whereNotNull('fecha_envio')
            ->where('fecha_envio', '>=', Carbon::now()->subMonths(1))
            ->groupBy('mes', 'año')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();
    }

    /**
     * Obtiene datos para la gráfica semanal
     */
    private function obtenerDatosGraficaSemanal()
    {
        return Envio::select(
            DB::raw('WEEK(fecha_envio) as semana'),
            DB::raw('YEAR(fecha_envio) as año'),
            DB::raw('count(*) as total'),
            DB::raw('count(CASE WHEN estado = "completado" THEN 1 END) as completados')
        )
            ->whereNotNull('fecha_envio')
            ->where('fecha_envio', '>=', Carbon::now()->subWeeks(8))
            ->groupBy('semana', 'año')
            ->orderBy('año')
            ->orderBy('semana')
            ->get();
    }

    /**
     * Obtiene datos del NPS (Net Promoter Score) por mes usando promedio_respuesta_1
     */
    private function obtenerDatosNPS()
    {
        return Envio::select(
            DB::raw('MONTH(fecha_envio) as mes'),
            DB::raw('YEAR(fecha_envio) as año'),
            DB::raw('AVG(promedio_respuesta_1) as nps_promedio'),
            DB::raw('count(*) as total_respuestas'),
            DB::raw('count(CASE WHEN promedio_respuesta_1 >= 9 THEN 1 END) as promotores'),
            DB::raw('count(CASE WHEN promedio_respuesta_1 >= 7 AND promedio_respuesta_1 < 9 THEN 1 END) as pasivos'),
            DB::raw('count(CASE WHEN promedio_respuesta_1 < 7 THEN 1 END) as detractores')
        )
            ->whereNotNull('fecha_envio')
            ->whereNotNull('promedio_respuesta_1')
            ->where('estado', 'completado')
            ->where('fecha_envio', '>=', Carbon::now()->subMonths(6))
            ->where('fecha_envio', '<=', Carbon::now())
            ->groupBy('mes', 'año')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();
    }

    /**
     * Calcula el NPS promedio general usando promedio_respuesta_1
     */
    private function calcularNPSPromedio()
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
     * Obtiene estadísticas de calidad del producto (preguntas 1.1 a 1.5)
     */
    private function obtenerEstadisticasCalidad()
    {
        $enviosCompletados = Envio::where('estado', 'completado')
            ->whereNotNull('promedio_respuesta_1')
            ->first();

        if ($enviosCompletados->count() === 0) {
            return [
                'promedio_general' => 0,
                'mejor_aspecto' => 'N/A',
                'peor_aspecto' => 'N/A',
                'aspectos' => [
                    '1_1' => ['nombre' => 'Calidad del Producto', 'promedio' => 0],
                    '1_2' => ['nombre' => 'Puntualidad de Entrega', 'promedio' => 0],
                    '1_3' => ['nombre' => 'Trato del Asesor', 'promedio' => 0],
                    '1_4' => ['nombre' => 'Precio', 'promedio' => 0],
                    '1_5' => ['nombre' => 'Rapidez de Progremación', 'promedio' => 0]
                ]
            ];
        }

        // Calcular promedios por aspecto
        $aspectos = [
            '1_1' => ['nombre' => 'Calidad del Producto', 'campo' => 'respuesta_1_1'],
            '1_2' => ['nombre' => 'Puntualidad de Entrega', 'campo' => 'respuesta_1_2'],
            '1_3' => ['nombre' => 'Trato del Asesor', 'campo' => 'respuesta_1_3'],
            '1_4' => ['nombre' => 'Precio', 'campo' => 'respuesta_1_4'],
            '1_5' => ['nombre' => 'Rapidez de Programación', 'campo' => 'respuesta_1_5'],
        ];

        foreach ($aspectos as $key => $aspecto) {
            $campo = $aspecto['campo'];
            $promedio = $enviosCompletados->whereNotNull($campo)->avg($campo);
            $aspectos[$key]['promedio'] = round($promedio, 1);
        }

        // Encontrar mejor y peor aspecto
        $promedios = collect($aspectos)->pluck('promedio', 'nombre');
        $mejorAspecto = $promedios->filter()->max();
        $peorAspecto = $promedios->filter()->min();

        $mejorAspectoNombre = $promedios->search($mejorAspecto);
        $peorAspectoNombre = $promedios->search($peorAspecto);

        return [
            'promedio_general' => round($enviosCompletados->avg('promedio_respuesta_1'), 1),
            'mejor_aspecto' => $mejorAspectoNombre ?: 'N/A',
            'peor_aspecto' => $peorAspectoNombre ?: 'N/A',
            'aspectos' => $aspectos
        ];
    }

    /**
     * Obtiene estadísticas en tiempo real (para AJAX)
     */
    public function estadisticasTiempoReal()
    {
        $enviosHoy = Envio::whereDate('fecha_envio', Carbon::today())->count();
        $respuestasHoy = ChatRespuesta::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'envios_hoy' => $enviosHoy,
            'respuestas_hoy' => $respuestasHoy,
            'timestamp' => Carbon::now()->format('H:i:s')
        ]);
    }
}
