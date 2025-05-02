<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductosExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
{
    protected $columnas;

    public function __construct(array $columnas)
    {
        $this->columnas = $columnas;
    }

    public function collection()
    {
        $productos = Producto::with('categoria', 'marca', 'precios')->get();

        return $productos->map(function ($producto) {
            $row = [];
            foreach ($this->columnas as $col) {
                switch ($col) {
                    case 'categoria':
                        $row[] = $producto->categoria->nombre ?? '';
                        break;
                    case 'marca':
                        $row[] = $producto->marca->nombre ?? '';
                        break;
                    case 'precio_publico':
                        $row[] = optional($producto->precios->firstWhere('tipo', 'publico'))->valor;
                        break;
                    case 'precio_costo':
                        $row[] = optional($producto->precios->firstWhere('tipo', 'costo'))->valor;
                        break;
                    case 'precio_preferencial':
                        $row[] = optional($producto->precios->firstWhere('tipo', 'preferencial'))->valor;
                        break;
                    default:
                        $row[] = $producto->$col;
                        break;
                }
            }
            return collect($row);
        });
    }

    public function headings(): array
    {
        $customHeadings = [
            'precio_publico' => 'Precio Público',
            'precio_costo' => 'Precio Costo',
            'precio_preferencial' => 'Precio Preferencial',
        ];

        return array_map(function ($col) use ($customHeadings) {
            return $customHeadings[$col] ?? ucfirst(str_replace('_', ' ', $col));
        }, $this->columnas);
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para la cabecera
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo para el cuerpo
        $sheet->getStyle('A2:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Autoajustar columnas
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->columnas as $index => $column) {
            if (str_starts_with($column, 'precio_')) {
                $formats[chr(65 + $index)] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
            }
        }
        return $formats;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Congelar la fila de cabecera
                $event->sheet->freezePane('A2');
                
                // Alinear números a la derecha
                $highestRow = $event->sheet->getHighestRow();
                foreach ($this->columnas as $colIndex => $column) {
                    if (str_starts_with($column, 'precio_')) {
                        $columnLetter = chr(65 + $colIndex);
                        $event->sheet->getStyle("{$columnLetter}2:{$columnLetter}{$highestRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                }
            },
        ];
    }
}