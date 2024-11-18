<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Calificacion;
use Illuminate\Support\Facades\DB;

class ResultadosResumenSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $fecha;
    protected $productoId;
    protected $tipoPrueba;

    public function __construct($fecha, $productoId, $tipoPrueba)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->tipoPrueba = $tipoPrueba;
    }

    public function collection()
    {
        $query = DB::table('calificaciones')
            ->select(
                'prueba',
                'cabina',
                DB::raw('COUNT(*) as total_pruebas'),
                DB::raw('COUNT(DISTINCT idpane) as total_panelistas')
            )
            ->where('fecha', $this->fecha)
            ->where('producto', $this->productoId)
            ->groupBy('prueba', 'cabina')
            ->orderBy('cabina')
            ->orderBy('prueba');

        if ($this->tipoPrueba) {
            $query->where('prueba', $this->tipoPrueba);
        }

        $resultados = $query->get();

        if ($resultados->isEmpty()) {
            return collect([[
                'tipo_prueba' => 'No hay datos',
                'cabina' => '-',
                'total_pruebas' => 0,
                'total_panelistas' => 0
            ]]);
        }

        // Transformar los datos para mejor presentación
        return $resultados->map(function ($item) {
            $tipoPrueba = [
                1 => 'Prueba Triangular',
                2 => 'Prueba Duo-Trio',
                3 => 'Prueba de Ordenamiento'
            ][$item->prueba] ?? 'Desconocida';

            return (object)[
                'tipo_prueba' => $tipoPrueba,
                'cabina' => "Cabina {$item->cabina}",
                'total_pruebas' => $item->total_pruebas,
                'total_panelistas' => $item->total_panelistas
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tipo de Prueba',
            'Cabina',
            'Total Pruebas',
            'Total Panelistas'
        ];
    }

    public function title(): string
    {
        return "Resumen General";
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Estilo para encabezados
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '2F855A']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo para el contenido
        $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // Bordes para toda la tabla
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);

        // Ajustar altura de filas
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        return $sheet;
    }
}
