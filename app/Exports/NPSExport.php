<?php

namespace App\Exports;

use App\Models\Envio;
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

class NPSExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithProperties, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Envio::with('cliente')
            ->where('estado', 'completado')
            ->whereNotNull('promedio_respuesta_1')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID ENCUESTA',
            'CLIENTE - RAZÓN SOCIAL',
            'ASESOR COMERCIAL',
            'FECHA RESPUESTA',
            'CALIFICACIÓN 1.1 - CALIDAD DEL PRODUCTO',
            'CALIFICACIÓN 1.2 - PUNTUALIDAD DE ENTREGA',
            'CALIFICACIÓN 1.3 - TRATO DEL ASESOR COMERCIAL',
            'CALIFICACIÓN 1.4 - PRECIO',
            'CALIFICACIÓN 1.5 - RAPIDEZ EN PROGRAMACIÓN',
            'PROMEDIO CALIDAD (NPS)',
            'CATEGORÍA NPS',
            'RECOMENDACIÓN',
            'SUGERENCIAS MEJORA',
            'TIEMPO RESPUESTA (HORAS)',
            'PERIODO ENVÍO'
        ];
    }

    public function map($envio): array
    {
        // Calcular tiempo de respuesta en horas
        $tiempoRespuesta = null;
        if ($envio->fecha_envio && $envio->fecha_respuesta) {
            $tiempoRespuesta = round($envio->fecha_envio->diffInHours($envio->fecha_respuesta), 2);
        }

        // Determinar categoría NPS basado en promedio_respuesta_1
        $respuesta = (float) $envio->promedio_respuesta_1;
        if ($respuesta >= 9) {
            $categoriaNPS = 'PROMOTOR';
        } elseif ($respuesta >= 7) {
            $categoriaNPS = 'PASIVO';
        } else {
            $categoriaNPS = 'DETRACTOR';
        }

        // Formatear período de envío
        $periodoEnvio = $envio->fecha_envio ? $envio->fecha_envio->format('M Y') : 'N/A';

        return [
            $envio->idenvio,
            $envio->cliente->razon_social ?? 'N/A',
            $envio->cliente->asesor_comercial ?? 'N/A',
            $envio->fecha_respuesta ? $envio->fecha_respuesta->format('d/m/Y H:i:s') : 'N/A',
            $envio->respuesta_1_1 ?? 'N/A',
            $envio->respuesta_1_2 ?? 'N/A',
            $envio->respuesta_1_3 ?? 'N/A',
            $envio->respuesta_1_4 ?? 'N/A',
            $envio->respuesta_1_5 ?? 'N/A',
            $envio->promedio_respuesta_1 ?? 'N/A',
            $categoriaNPS,
            $envio->respuesta_2 ?? 'N/A',
            $envio->respuesta_3 ?? 'N/A',
            $tiempoRespuesta ?? 'N/A',
            $periodoEnvio
        ];
    }

    public function styles($sheet)
    {
        // Estilo para el título principal
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745']
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

        // Estilo para las celdas de datos
        $sheet->getStyle('A2:O' . ($sheet->getHighestRow()))->applyFromArray([
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

        // Estilo especial para la columna de promedio NPS
        $sheet->getStyle('J2:J' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Estilo especial para la columna de categoría NPS
        $sheet->getStyle('K2:K' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Colorear filas según categoría NPS
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $categoria = $sheet->getCell('K' . $row)->getValue();
            if ($categoria === 'PROMOTOR') {
                $sheet->getStyle('A' . $row . ':O' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('D4EDDA'));
            } elseif ($categoria === 'PASIVO') {
                $sheet->getStyle('A' . $row . ':O' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('FFF3CD'));
            } elseif ($categoria === 'DETRACTOR') {
                $sheet->getStyle('A' . $row . ':O' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('F8D7DA'));
            }
        }

        // Estilo para encabezados de columnas específicas
        $sheet->getStyle('A1')->getFill()->setStartColor(new Color('1F4E79'));
        $sheet->getStyle('B1')->getFill()->setStartColor(new Color('2E5BBA'));
        $sheet->getStyle('C1')->getFill()->setStartColor(new Color('1F4E79'));
        $sheet->getStyle('D1')->getFill()->setStartColor(new Color('2E5BBA'));
        $sheet->getStyle('E1:I1')->getFill()->setStartColor(new Color('28A745')); // Calificaciones individuales
        $sheet->getStyle('J1:K1')->getFill()->setStartColor(new Color('17A2B8')); // NPS y categoría
        $sheet->getStyle('L1:M1')->getFill()->setStartColor(new Color('FFC107')); // Recomendación y sugerencias
        $sheet->getStyle('N1:O1')->getFill()->setStartColor(new Color('6C757D')); // Metadatos

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID ENCUESTA
            'B' => 30,  // CLIENTE - RAZÓN SOCIAL
            'C' => 20,  // ASESOR COMERCIAL
            'D' => 20,  // FECHA RESPUESTA
            'E' => 25,  // CALIFICACIÓN 1.1 - CALIDAD DEL PRODUCTO
            'F' => 25,  // CALIFICACIÓN 1.2 - PUNTUALIDAD DE ENTREGA
            'G' => 25,  // CALIFICACIÓN 1.3 - TRATO DEL ASESOR COMERCIAL
            'H' => 25,  // CALIFICACIÓN 1.4 - PRECIO
            'I' => 30,  // CALIFICACIÓN 1.5 - RAPIDEZ EN PROGRAMACIÓN
            'J' => 20,  // PROMEDIO CALIDAD (NPS)
            'K' => 18,  // CATEGORÍA NPS
            'L' => 25,  // RECOMENDACIÓN
            'M' => 40,  // SUGERENCIAS MEJORA
            'N' => 25,  // TIEMPO RESPUESTA (HORAS)
            'O' => 15,  // PERIODO ENVÍO
        ];
    }

    public function title(): string
    {
        return 'Net Promoter Score (NPS)';
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Sistema de Encuestas Konkret',
            'lastModifiedBy' => 'Sistema de Encuestas Konkret',
            'title'          => 'Reporte de Net Promoter Score (NPS)',
            'description'    => 'Exportación de datos NPS de encuestas de satisfacción del cliente',
            'subject'        => 'Net Promoter Score (NPS)',
            'keywords'       => 'NPS, satisfacción, promotores, detractores, clientes, Konkret',
            'category'       => 'Reportes NPS',
            'manager'        => 'Sistema de Encuestas',
            'company'        => 'Konkret',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setBold(true);
                
                // Agregar filtros a los encabezados
                $event->sheet->getDelegate()->setAutoFilter('A1:O1');
                
                // Congelar la primera fila
                $event->sheet->getDelegate()->freezePane('A2');
                
                // Agregar resumen NPS en la parte superior
                $event->sheet->insertNewRowBefore(1, 3);
                
                // Título principal
                $event->sheet->mergeCells('A1:O1');
                $event->sheet->setCellValue('A1', 'REPORTE DE NET PROMOTER SCORE (NPS)');
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
                $event->sheet->mergeCells('A2:O2');
                $event->sheet->setCellValue('A2', 'Encuestas de Satisfacción del Cliente - Konkret');
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
                $event->sheet->mergeCells('A3:O3');
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
}
