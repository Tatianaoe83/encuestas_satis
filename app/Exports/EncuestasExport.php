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

class EncuestasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithProperties, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Envio::with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID ENCUESTA',
            'CLIENTE - RAZÓN SOCIAL',
            'CLIENTE - NOMBRE COMPLETO',
            'ASESOR COMERCIAL',
            'CLIENTE - PUESTO',
            'CLIENTE - CELULAR',
            'CLIENTE - CORREO',
            'ESTADO ENCUESTA',
            'FECHA ENVÍO',
            'FECHA RESPUESTA',
            'FECHA CREACIÓN',
            'PREGUNTA 1.1 - CALIDAD DEL PRODUCTO',
            'RESPUESTA 1.1',
            'PREGUNTA 1.2 - PUNTUALIDAD DE ENTREGA',
            'RESPUESTA 1.2',
            'PREGUNTA 1.3 - TRATO DEL ASESOR COMERCIAL',
            'RESPUESTA 1.3',
            'PREGUNTA 1.4 - PRECIO',
            'RESPUESTA 1.4',
            'PREGUNTA 1.5 - RAPIDEZ EN PROGRAMACIÓN',
            'RESPUESTA 1.5',
            'PROMEDIO CALIDAD (NPS)',
            'CATEGORÍA NPS',
            'PREGUNTA 2 - RECOMENDACIÓN',
            'RESPUESTA 2 - RECOMENDACIÓN',
            'PREGUNTA 3 - SUGERENCIAS',
            'RESPUESTA 3 - SUGERENCIAS',
            'TIEMPO RESPUESTA (HORAS)',
            'DÍA SEMANA ENVÍO',
            'MES ENVÍO',
            'AÑO ENVÍO'
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
        $categoriaNPS = '';
        if ($envio->promedio_respuesta_1 !== null && $envio->promedio_respuesta_1 !== '') {
            $respuesta = (float) $envio->promedio_respuesta_1;
            if ($respuesta >= 9) {
                $categoriaNPS = 'PROMOTOR';
            } elseif ($respuesta >= 7) {
                $categoriaNPS = 'PASIVO';
            } else {
                $categoriaNPS = 'DETRACTOR';
            }
        }

        // Obtener información del cliente
        $cliente = $envio->cliente;
        
        return [
            $envio->idenvio,
            $cliente->razon_social ?? 'N/A',
            $cliente->nombre_completo ?? 'N/A',
            $cliente->asesor_comercial ?? 'N/A',
            $cliente->puesto ?? 'N/A',
            $cliente->celular ?? 'N/A',
            $cliente->correo ?? 'N/A',
            strtoupper($envio->estado === 'completado' ? 'Respondido por el usuario' : ($envio->estado === 'en_proceso' ? 'Usuario respondiendo' : ($envio->estado === 'pendiente' ? 'Pendiente de envío' : ($envio->estado === 'enviado' ? 'Enviado por Whatsapp' : ($envio->estado === 'cancelado' ? 'Sin respuesta' : 'Sin respuesta'))))),
            $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i:s') : 'N/A',
            $envio->fecha_respuesta ? $envio->fecha_respuesta->format('d/m/Y H:i:s') : 'N/A',
            $envio->created_at->format('d/m/Y H:i:s'),
            'Calidad del producto',
            $envio->respuesta_1_1 ?? 'N/A',
            'Puntualidad de entrega',
            $envio->respuesta_1_2 ?? 'N/A',
            'Trato del asesor comercial',
            $envio->respuesta_1_3 ?? 'N/A',
            'Precio',
            $envio->respuesta_1_4 ?? 'N/A',
            'Rapidez en programación',
            $envio->respuesta_1_5 ?? 'N/A',
            $envio->promedio_respuesta_1 ?? 'N/A',
            $categoriaNPS,
            '¿Recomendarías a Konkret?',
            $envio->respuesta_2 ?? 'N/A',
            '¿Qué podríamos hacer para mejorar tu experiencia?',
            $envio->respuesta_3 ?? 'N/A',
            $tiempoRespuesta ?? 'N/A',
            $envio->fecha_envio ? $envio->fecha_envio->format('l') : 'N/A',
            $envio->fecha_envio ? $envio->fecha_envio->format('F') : 'N/A',
            $envio->fecha_envio ? $envio->fecha_envio->format('Y') : 'N/A'
        ];
    }

    public function styles($sheet)
    {
        // Estilo para el título
        $sheet->getStyle('A1:AE1')->applyFromArray([
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

        // Estilo para las celdas de datos
        $sheet->getStyle('A2:AE' . ($sheet->getHighestRow()))->applyFromArray([
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

        // Estilo especial para la columna de categoría NPS
        $sheet->getStyle('W2:W' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Colorear filas según categoría NPS
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $categoria = $sheet->getCell('W' . $row)->getValue();
            if ($categoria === 'PROMOTOR') {
                $sheet->getStyle('A' . $row . ':AE' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('D4EDDA'));
            } elseif ($categoria === 'PASIVO') {
                $sheet->getStyle('A' . $row . ':AE' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('FFF3CD'));
            } elseif ($categoria === 'DETRACTOR') {
                $sheet->getStyle('A' . $row . ':AE' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('F8D7DA'));
            }
        }

        // Estilo para encabezados de columnas específicas
        $sheet->getStyle('A1')->getFill()->setStartColor(new Color('1F4E79'));
        $sheet->getStyle('B1:C1')->getFill()->setStartColor(new Color('2E5BBA'));
        $sheet->getStyle('D1')->getFill()->setStartColor(new Color('1F4E79'));
        $sheet->getStyle('E1:G1')->getFill()->setStartColor(new Color('2E5BBA'));
        $sheet->getStyle('H1')->getFill()->setStartColor(new Color('1F4E79'));
        $sheet->getStyle('I1:K1')->getFill()->setStartColor(new Color('2E5BBA'));
        $sheet->getStyle('L1:V1')->getFill()->setStartColor(new Color('28A745')); // Calidad del producto
        $sheet->getStyle('W1')->getFill()->setStartColor(new Color('17A2B8')); // Categoría NPS
        $sheet->getStyle('X1:Y1')->getFill()->setStartColor(new Color('FFC107')); // Recomendación
        $sheet->getStyle('Z1:AA1')->getFill()->setStartColor(new Color('6C757D')); // Sugerencias
        $sheet->getStyle('AB1:AE1')->getFill()->setStartColor(new Color('6C757D')); // Metadatos

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID ENCUESTA
            'B' => 25,  // CLIENTE - RAZÓN SOCIAL
            'C' => 25,  // CLIENTE - NOMBRE COMPLETO
            'D' => 20,  // ASESOR COMERCIAL
            'E' => 20,  // CLIENTE - PUESTO
            'F' => 15,  // CLIENTE - CELULAR
            'G' => 30,  // CLIENTE - CORREO
            'H' => 15,  // ESTADO ENCUESTA
            'I' => 20,  // FECHA ENVÍO
            'J' => 20,  // FECHA RESPUESTA
            'K' => 20,  // FECHA CREACIÓN
            'L' => 20,  // PREGUNTA 1.1 - CALIDAD GENERAL
            'M' => 15,  // RESPUESTA 1.1
            'N' => 20,  // PREGUNTA 1.2 - DURABILIDAD
            'O' => 15,  // RESPUESTA 1.2
            'P' => 20,  // PREGUNTA 1.3 - PRESENTACIÓN
            'Q' => 15,  // RESPUESTA 1.3
            'R' => 20,  // PREGUNTA 1.4 - FUNCIONALIDAD
            'S' => 15,  // RESPUESTA 1.4
            'T' => 25,  // PREGUNTA 1.5 - SATISFACCIÓN GENERAL
            'U' => 15,  // RESPUESTA 1.5
            'V' => 20,  // PROMEDIO CALIDAD (NPS)
            'W' => 15,  // CATEGORÍA NPS
            'X' => 30,  // PREGUNTA 2 - RECOMENDACIÓN
            'Y' => 25,  // RESPUESTA 2 - RECOMENDACIÓN
            'Z' => 40,  // PREGUNTA 3 - SUGERENCIAS
            'AA' => 40, // RESPUESTA 3 - SUGERENCIAS
            'AB' => 25, // TIEMPO RESPUESTA (HORAS)
            'AC' => 20, // DÍA SEMANA ENVÍO
            'AD' => 15, // MES ENVÍO
            'AE' => 10, // AÑO ENVÍO
        ];
    }

    public function title(): string
    {
        return 'Encuestas de Satisfacción';
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Sistema de Encuestas Konkret',
            'lastModifiedBy' => 'Sistema de Encuestas Konkret',
            'title'          => 'Reporte de Encuestas de Satisfacción',
            'description'    => 'Exportación completa de todas las encuestas de satisfacción del cliente',
            'subject'        => 'Encuestas de Satisfacción',
            'keywords'       => 'encuestas, satisfacción, NPS, clientes, Konkret',
            'category'       => 'Reportes',
            'manager'        => 'Sistema de Encuestas',
            'company'        => 'Konkret',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:X1')->getFont()->setBold(true);
                
                // Agregar filtros a los encabezados
                $event->sheet->getDelegate()->setAutoFilter('A1:AE1');
                
                // Congelar la primera fila
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}
