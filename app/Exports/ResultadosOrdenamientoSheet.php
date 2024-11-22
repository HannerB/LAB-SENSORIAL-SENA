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
    protected $maxPosiciones;

    public function __construct($fecha, $productoId, $cabina = null)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->cabina = $cabina;
        $this->maxPosiciones = 10; // Siempre mostrar 10 posiciones
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
                $emptyResult = [
                    'atributo' => 'Sin datos',
                    'muestra' => '-',
                    'primera_posicion' => 0,
                    'segunda_posicion' => 0,
                    'tercera_posicion' => 0,
                    'cuarta_posicion' => 0,
                    'quinta_posicion' => 0,
                    'sexta_posicion' => 0,
                    'septima_posicion' => 0,
                    'octava_posicion' => 0,
                    'novena_posicion' => 0,
                    'decima_posicion' => 0,
                    'total_evaluaciones' => 0
                ];
                return collect([$emptyResult]);
            }

            // Agrupar por atributo
            $resultadosPorAtributo = $calificaciones->groupBy('atributo');
            $resultados = collect();

            foreach ($resultadosPorAtributo as $atributo => $calificacionesAtributo) {
                // Agrupar las muestras por orden y contar votos
                $votosPorMuestra = [];
                
                foreach ($calificacionesAtributo as $calificacion) {
                    $muestras = explode(',', $calificacion->cod_muestras);
                    foreach ($muestras as $posicion => $muestra) {
                        $key = trim($muestra);
                        if (!isset($votosPorMuestra[$key])) {
                            $votosPorMuestra[$key] = array_fill(0, 10, 0); // Siempre 10 posiciones
                        }
                        if ($posicion < 10) { // Solo contar hasta la décima posición
                            $votosPorMuestra[$key][$posicion]++;
                        }
                    }
                }

                // Formatear resultados para el Excel
                foreach ($votosPorMuestra as $muestra => $votos) {
                    $resultados->push([
                        'atributo' => $atributo,
                        'muestra' => $muestra,
                        'primera_posicion' => $votos[0] ?? 0,
                        'segunda_posicion' => $votos[1] ?? 0,
                        'tercera_posicion' => $votos[2] ?? 0,
                        'cuarta_posicion' => $votos[3] ?? 0,
                        'quinta_posicion' => $votos[4] ?? 0,
                        'sexta_posicion' => $votos[5] ?? 0,
                        'septima_posicion' => $votos[6] ?? 0,
                        'octava_posicion' => $votos[7] ?? 0,
                        'novena_posicion' => $votos[8] ?? 0,
                        'decima_posicion' => $votos[9] ?? 0,
                        'total_evaluaciones' => array_sum($votos)
                    ]);
                }
            }

            return $resultados;

        } catch (\Exception $e) {
            return collect([[
                'atributo' => 'Error',
                'muestra' => $e->getMessage(),
                'primera_posicion' => 0,
                'segunda_posicion' => 0,
                'tercera_posicion' => 0,
                'cuarta_posicion' => 0,
                'quinta_posicion' => 0,
                'sexta_posicion' => 0,
                'septima_posicion' => 0,
                'octava_posicion' => 0,
                'novena_posicion' => 0,
                'decima_posicion' => 0,
                'total_evaluaciones' => 0
            ]]);
        }
    }

    public function headings(): array
    {
        return [
            'Atributo',
            'Código Muestra',
            'Votos 1ra Posición',
            'Votos 2da Posición',
            'Votos 3ra Posición',
            'Votos 4ta Posición',
            'Votos 5ta Posición',
            'Votos 6ta Posición',
            'Votos 7ma Posición',
            'Votos 8va Posición',
            'Votos 9na Posición',
            'Votos 10ma Posición',
            'Total Evaluaciones'
        ];
    }

    public function title(): string
    {
        return $this->cabina ? "Ordenamiento Cabina {$this->cabina}" : "Ordenamiento General";
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
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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

        // Colorear las columnas de votos
        $colores = [
            'C6E0B4', // Verde claro para 1ra posición
            'D9E1F2', // Azul claro para 2da posición
            'FFF2CC', // Amarillo claro para 3ra posición
            'FFE4E1'  // Rojo claro para 4ta posición
        ];

        // Aplicar colores a las columnas de votos (C hasta L para las 10 posiciones)
        for ($i = 0; $i < 10; $i++) {
            $columna = chr(67 + $i); // Comienza desde la columna C
            $color = $colores[$i % count($colores)];
            $sheet->getStyle($columna . '2:' . $columna . $lastRow)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => $color]
                ]
            ]);
        }

        return $sheet;
    }
}