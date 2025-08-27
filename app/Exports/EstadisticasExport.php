<?php

namespace App\Exports;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Color;

class EstadisticasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithProperties, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        // Crear una colección con las estadísticas
        $estadisticas = collect();
        
        // Estadísticas generales
        $totalEnvios = Envio::count();
        $enviosCompletados = Envio::where('estado', 'completado')->count();
        $enviosCancelados = Envio::where('estado', 'cancelado')->count();
        $enviosPendientes = Envio::where('estado', 'pendiente')->count();
        
        // Calcular NPS
        $npsData = $this->calcularNPS();
        
        // Estadísticas por asesor
        $estadisticasAsesores = Cliente::select(
                'asesor_comercial',
                DB::raw('count(envios.idenvio) as total_envios'),
                DB::raw('sum(case when envios.estado = "completado" then 1 else 0 end) as completados'),
                DB::raw('sum(case when envios.estado = "cancelado" then 1 else 0 end) as cancelados'),
            )
            ->join('envios', 'clientes.idcliente', '=', 'envios.cliente_id')
            ->groupBy('asesor_comercial')
            ->orderByDesc('total_envios')
            ->get();

        // Agregar estadísticas generales
        $estadisticas->push([
            'seccion' => 'ESTADISTICAS_GENERALES',
            'metrica' => 'Total Envíos',
            'valor' => $totalEnvios,
            'porcentaje' => '100%',
            'categoria' => 'general'
        ]);
        
        $estadisticas->push([
            'seccion' => 'ESTADISTICAS_GENERALES',
            'metrica' => 'Envíos Completados',
            'valor' => $enviosCompletados,
            'porcentaje' => $totalEnvios > 0 ? round(($enviosCompletados / $totalEnvios) * 100, 1) . '%' : '0%',
            'categoria' => 'general'
        ]);
        
        $estadisticas->push([
            'seccion' => 'ESTADISTICAS_GENERALES',
            'metrica' => 'Envíos Cancelados',
            'valor' => $enviosCancelados,
            'porcentaje' => $totalEnvios > 0 ? round(($enviosCancelados / $totalEnvios) * 100, 1) . '%' : '0%',
            'categoria' => 'general'
        ]);
        
        $estadisticas->push([
            'seccion' => 'ESTADISTICAS_GENERALES',
            'metrica' => 'Envíos Pendientes',
            'valor' => $enviosPendientes,
            'porcentaje' => $totalEnvios > 0 ? round(($enviosPendientes / $totalEnvios) * 100, 1) . '%' : '0%',
            'categoria' => 'general'
        ]);

        // Agregar NPS
        $estadisticas->push([
            'seccion' => 'NET_PROMOTER_SCORE',
            'metrica' => 'NPS Score',
            'valor' => $npsData['nps_score'],
            'porcentaje' => 'N/A',
            'categoria' => 'nps'
        ]);
        
        $estadisticas->push([
            'seccion' => 'NET_PROMOTER_SCORE',
            'metrica' => 'Promotores',
            'valor' => $npsData['promotores'],
            'porcentaje' => $npsData['porcentaje_promotores'] . '%',
            'categoria' => 'nps'
        ]);
        
        $estadisticas->push([
            'seccion' => 'NET_PROMOTER_SCORE',
            'metrica' => 'Pasivos',
            'valor' => $npsData['pasivos'],
            'porcentaje' => $npsData['porcentaje_pasivos'] . '%',
            'categoria' => 'nps'
        ]);
        
        $estadisticas->push([
            'seccion' => 'NET_PROMOTER_SCORE',
            'metrica' => 'Detractores',
            'valor' => $npsData['detractores'],
            'porcentaje' => $npsData['porcentaje_detractores'] . '%',
            'categoria' => 'nps'
        ]);
        
        $estadisticas->push([
            'seccion' => 'NET_PROMOTER_SCORE',
            'metrica' => 'Total Respuestas',
            'valor' => $npsData['total'],
            'porcentaje' => '100%',
            'categoria' => 'nps'
        ]);

        // Agregar estadísticas por asesor
        foreach ($estadisticasAsesores as $asesor) {
            $tasaRespuesta = $asesor->total_envios > 0 ? 
                round(($asesor->completados / $asesor->total_envios) * 100, 1) : 0;
            
            $estadisticas->push([
                'seccion' => 'ESTADISTICAS_POR_ASESOR',
                'metrica' => $asesor->asesor_comercial,
                'valor' => $asesor->total_envios,
                'porcentaje' => $tasaRespuesta . '%',
                'categoria' => 'asesor'
            ]);
        }

        // Agregar información del reporte
        $estadisticas->push([
            'seccion' => 'INFORMACION_DEL_REPORTE',
            'metrica' => 'Fecha Generación',
            'valor' => now()->format('d/m/Y H:i:s'),
            'porcentaje' => 'N/A',
            'categoria' => 'info'
        ]);
        
        $estadisticas->push([
            'seccion' => 'INFORMACION_DEL_REPORTE',
            'metrica' => 'Total Registros',
            'valor' => $totalEnvios,
            'porcentaje' => 'N/A',
            'categoria' => 'info'
        ]);
        
        $estadisticas->push([
            'seccion' => 'INFORMACION_DEL_REPORTE',
            'metrica' => 'Período Análisis',
            'valor' => 'Desde el inicio hasta ' . now()->format('d/m/Y'),
            'porcentaje' => 'N/A',
            'categoria' => 'info'
        ]);

        return $estadisticas;
    }

    public function headings(): array
    {
        return [
            'SECCIÓN',
            'MÉTRICA',
            'VALOR',
            'PORCENTAJE'
        ];
    }

    public function map($estadistica): array
    {
        return [
            $estadistica['seccion'],
            $estadistica['metrica'],
            $estadistica['valor'],
            $estadistica['porcentaje']
        ];
    }

    public function styles($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Estilo para encabezados
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E5BBA']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Estilo para todas las celdas de datos
        $sheet->getStyle('A2:D' . $highestRow)->applyFromArray([
            'font' => [
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Estilos específicos por sección
        for ($row = 2; $row <= $highestRow; $row++) {
            $seccion = $sheet->getCell('A' . $row)->getValue();
            
            if (strpos($seccion, 'ESTADISTICAS_GENERALES') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('E3F2FD'));
            } elseif (strpos($seccion, 'NET_PROMOTER_SCORE') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('E8F5E8'));
            } elseif (strpos($seccion, 'ESTADISTICAS_POR_ASESOR') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('FFF3E0'));
            } elseif (strpos($seccion, 'INFORMACION_DEL_REPORTE') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('F3E5F5'));
            }
        }

        // Estilo para títulos de sección
        for ($row = 2; $row <= $highestRow; $row++) {
            $seccion = $sheet->getCell('A' . $row)->getValue();
            if (strpos($seccion, '_') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => new Color('1F4E79')
                    ]
                ]);
            }
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,  // SECCIÓN
            'B' => 40,  // MÉTRICA
            'C' => 20,  // VALOR
            'D' => 20,  // PORCENTAJE
        ];
    }

    public function title(): string
    {
        return 'Estadísticas Generales';
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Sistema de Encuestas Konkret',
            'lastModifiedBy' => 'Sistema de Encuestas Konkret',
            'title'          => 'Reporte de Estadísticas Generales',
            'description'    => 'Estadísticas resumidas de encuestas de satisfacción del cliente',
            'subject'        => 'Estadísticas Generales',
            'keywords'       => 'estadísticas, encuestas, satisfacción, NPS, clientes, Konkret',
            'category'       => 'Reportes Estadísticos',
            'manager'        => 'Sistema de Encuestas',
            'company'        => 'Konkret',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:D1')->getFont()->setBold(true);
                
                // Agregar filtros a los encabezados
                $event->sheet->getDelegate()->setAutoFilter('A1:D1');
                
                // Congelar la primera fila
                $event->sheet->getDelegate()->freezePane('A2');
                
                // Agregar título principal en la parte superior
                $event->sheet->insertNewRowBefore(1, 3);
                
                // Título principal
                $event->sheet->mergeCells('A1:D1');
                $event->sheet->setCellValue('A1', 'REPORTE DE ESTADÍSTICAS GENERALES');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E79']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Subtítulo
                $event->sheet->mergeCells('A2:D2');
                $event->sheet->setCellValue('A2', 'Sistema de Encuestas de Satisfacción - Konkret');
                $event->sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E5BBA']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Fecha de generación
                $event->sheet->mergeCells('A3:D3');
                $event->sheet->setCellValue('A3', 'Generado el: ' . now()->format('d/m/Y H:i:s'));
                $event->sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'italic' => true
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Ajustar altura de filas
                $event->sheet->getRowDimension(1)->setRowHeight(25);
                $event->sheet->getRowDimension(2)->setRowHeight(20);
                $event->sheet->getRowDimension(3)->setRowHeight(18);
            },
        ];
    }

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
