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
use Illuminate\Support\Collection;

class ResultadosOrdenamientoSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $fecha;
    protected $productoId;
    protected $cabina;

    public function __construct($fecha, $productoId, $cabina = null)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->cabina = $cabina;
    }

    public function collection()
    {
        try {
            $query = Calificacion::where('fecha', $this->fecha)
                ->where('producto', $this->productoId)
                ->where('prueba', 3); // Prueba de ordenamiento

            if ($this->cabina) {
                $query->where('cabina', $this->cabina);
            }

            $calificaciones = $query->get();

            if ($calificaciones->isEmpty()) {
                return collect([[
                    'atributo' => 'Sin datos',
                    'muestra' => '-',
                    'primera_eleccion' => '0 votos (0%)',
                    'segunda_eleccion' => '0 votos (0%)',
                    'tercera_eleccion' => '0 votos (0%)',
                    'total_evaluaciones' => 0
                ]]);
            }

            // Agrupar por atributo
            $resultadosPorAtributo = $calificaciones->groupBy('atributo');
            $resultados = collect();

            foreach ($resultadosPorAtributo as $atributo => $calificacionesAtributo) {
                $totalEvaluaciones = $calificacionesAtributo->count();
                $votosPorMuestra = [];

                // Procesar cada calificación
                foreach ($calificacionesAtributo as $calificacion) {
                    $muestras = explode(',', $calificacion->cod_muestras);
                    foreach ($muestras as $posicion => $muestra) {
                        $muestra = trim($muestra);
                        if (!isset($votosPorMuestra[$muestra])) {
                            $votosPorMuestra[$muestra] = array_fill(0, 3, 0); // Solo primeras 3 posiciones
                        }
                        if ($posicion < 3) {
                            $votosPorMuestra[$muestra][$posicion]++;
                        }
                    }
                }

                // Formatear los resultados
                foreach ($votosPorMuestra as $muestra => $votos) {
                    $porcentajes = array_map(function ($votos) use ($totalEvaluaciones) {
                        $porcentaje = ($votos / $totalEvaluaciones) * 100;
                        return sprintf("%d votos (%.1f%%)", $votos, $porcentaje);
                    }, $votos);

                    $resultados->push([
                        'atributo' => $atributo,
                        'muestra' => $muestra,
                        'primera_eleccion' => $porcentajes[0],
                        'segunda_eleccion' => $porcentajes[1],
                        'tercera_eleccion' => $porcentajes[2],
                        'total_evaluaciones' => $totalEvaluaciones
                    ]);
                }

                // Agregar una fila en blanco entre atributos si hay más de uno
                if ($resultadosPorAtributo->count() > 1) {
                    $resultados->push([
                        'atributo' => '',
                        'muestra' => '',
                        'primera_eleccion' => '',
                        'segunda_eleccion' => '',
                        'tercera_eleccion' => '',
                        'total_evaluaciones' => ''
                    ]);
                }
            }

            return $resultados;
        } catch (\Exception $e) {
            return collect([[
                'atributo' => 'Error',
                'muestra' => $e->getMessage(),
                'primera_eleccion' => '0 votos (0%)',
                'segunda_eleccion' => '0 votos (0%)',
                'tercera_eleccion' => '0 votos (0%)',
                'total_evaluaciones' => 0
            ]]);
        }
    }

    public function headings(): array
    {
        return [
            'Atributo',
            'Código Muestra',
            'Primera Elección',
            'Segunda Elección',
            'Tercera Elección',
            'Total Evaluaciones'
        ];
    }

    public function title(): string
    {
        return $this->cabina ?
            "Resumen Ordenamiento Cabina {$this->cabina}" :
            "Resumen Ordenamiento General";
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

        // Estilo para las columnas de posiciones (primera, segunda y tercera elección)
        $positionColumns = ['C', 'D', 'E'];
        $colors = [
            'C6E0B4', // Verde claro para 1ra posición
            'D9E1F2', // Azul claro para 2da posición
            'FFF2CC'  // Amarillo claro para 3ra posición
        ];

        foreach ($positionColumns as $index => $column) {
            $sheet->getStyle($column . '2:' . $column . $lastRow)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => $colors[$index]]
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ]);
        }

        // Estilo para el resto del contenido
        $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo para la columna de atributo y muestra (alineación izquierda)
        $sheet->getStyle('A2:B' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ]);

        // Estilo para la columna de total (negrita y centrado)
        $sheet->getStyle('F2:F' . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
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

        // Agregar filtros automáticos
        $sheet->setAutoFilter('A1:' . $lastColumn . '1');

        return $sheet;
    }
}
