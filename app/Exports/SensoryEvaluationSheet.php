<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Calificacion;
use App\Models\Muestra;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensoryEvaluationSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
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
            // Obtener todas las muestras para este producto y prueba de ordenamiento
            $muestras = Muestra::where('producto_id', $this->productoId)
                ->where('prueba', 3)
                ->orderBy(DB::raw('CAST(cod_muestra AS UNSIGNED)'))
                ->get();

            // Consulta base para calificaciones
            $query = Calificacion::with('panelista')
                ->where('fecha', $this->fecha)
                ->where('producto', $this->productoId)
                ->where('prueba', 3);

            if ($this->cabina) {
                $query->where('cabina', $this->cabina);
            }

            $calificaciones = $query->get();

            if ($calificaciones->isEmpty()) {
                return $this->getEmptyCollection($muestras);
            }

            $rows = new Collection();

            // Agregar encabezados principales
            $mainHeaders = ['', 'Panelista'];
            foreach ($muestras as $muestra) {
                $mainHeaders[] = "Muestra {$muestra->cod_muestra}";
                $mainHeaders[] = '';
                $mainHeaders[] = '';
                $mainHeaders[] = '';
                $mainHeaders[] = '';
            }
            $rows->push($mainHeaders);

            // Agregar encabezados de atributos
            $attrHeaders = ['', ''];
            foreach ($muestras as $muestra) {
                foreach (['Sabor', 'Olor', 'Color', 'Textura', 'Apariencia'] as $atributo) {
                    if ($muestra->{"tiene_" . strtolower($atributo)}) {
                        $attrHeaders[] = $atributo;
                    } else {
                        $attrHeaders[] = '';
                    }
                }
            }
            $rows->push($attrHeaders);

            // Procesar cada panelista con numeración iniciando desde A3
            $panelistas = $calificaciones->groupBy('idpane');
            $contadorPanelista = 1; // Iniciamos el contador desde 1

            foreach ($panelistas as $idpane => $calificacionesPanelista) {
                $panelista = $calificacionesPanelista->first()->panelista;
                $row = [
                    $contadorPanelista++, // Usamos el contador y luego lo incrementamos
                    $panelista ? $panelista->nombres : 'N/A'
                ];

                // Agregar calificaciones para cada muestra y atributo
                foreach ($muestras as $muestra) {
                    $calificacion = $calificacionesPanelista->where('cod_muestra', $muestra->cod_muestra)->first();
                    foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                        if ($muestra->{"tiene_$atributo"}) {
                            $campo = "valor_$atributo";
                            $valor = $calificacion ? $calificacion->$campo : '-';
                            $row[] = is_numeric($valor) ? $valor : '-';
                        } else {
                            $row[] = '';
                        }
                    }
                }

                $rows->push($row);
            }

            // Agregar filas de estadísticas
            $promedios = $this->calcularEstadisticas($calificaciones, $muestras, 'avg');
            $medianas = $this->calcularEstadisticas($calificaciones, $muestras, 'median');
            $modas = $this->calcularEstadisticas($calificaciones, $muestras, 'mode');

            $rows->push([]);
            $rows->push($promedios);
            $rows->push($medianas);
            $rows->push($modas);

            return $rows;
        } catch (\Exception $e) {
            Log::error('Error en SensoryEvaluationSheet: ' . $e->getMessage());
            return $this->getEmptyCollection($muestras ?? collect());
        }
    }

    protected function calcularEstadisticas($calificaciones, $muestras, $funcion)
    {
        $estadisticas = [
            'avg' => 'PROMEDIO',
            'median' => 'MEDIANA',
            'mode' => 'MODA'
        ][$funcion];

        $fila = [$estadisticas, ''];

        foreach ($muestras as $muestra) {
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                if (!$muestra->{"tiene_$atributo"}) {
                    $fila[] = '';
                    continue;
                }

                $campo = 'valor_' . $atributo;
                $valores = $calificaciones->where('cod_muestra', $muestra->cod_muestra)->pluck($campo)->filter(function ($valor) {
                    return is_numeric($valor);
                });

                if ($valores->isEmpty()) {
                    $fila[] = '-';
                } else {
                    switch ($funcion) {
                        case 'avg':
                            $fila[] = number_format($valores->avg(), 2);
                            break;
                        case 'median':
                            $fila[] = number_format($valores->median(), 2);
                            break;
                        case 'mode':
                            $moda = $valores->mode();
                            $fila[] = is_array($moda) ? implode(', ', $moda) : $moda;
                            break;
                    }
                }
            }
        }

        return $fila;
    }

    protected function getEmptyCollection($muestras)
    {
        $numAtributos = $muestras->sum(function ($muestra) {
            return collect(['sabor', 'olor', 'color', 'textura', 'apariencia'])
                ->filter(function ($atributo) use ($muestra) {
                    return $muestra->{"tiene_$atributo"};
                })
                ->count();
        });

        return new Collection([
            ['', 'Panelista', ...array_fill(0, $numAtributos, '-')]
        ]);
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return $this->cabina ?
            "Evaluación Sensorial - Cabina {$this->cabina}" :
            "Evaluación Sensorial - General";
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Estilo para encabezados principales
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'E0E0E0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Estilo para encabezados de atributos
        $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray([
            'font' => ['bold' => true, 'italic' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F5F5F5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Estilos para filas de estadísticas
        $statsRows = [$lastRow - 2, $lastRow - 1, $lastRow];
        foreach ($statsRows as $row) {
            $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'F5F5F5']
                ]
            ]);
        }

        // Bordes y alineación general
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Centrar números y valores, empezando desde A3
        $sheet->getStyle("A3:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C3:{$lastColumn}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $sheet;
    }
}
