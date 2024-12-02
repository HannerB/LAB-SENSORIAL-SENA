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
            $muestras = Muestra::where('producto_id', $this->productoId)
                ->where('prueba', 3)
                ->orderBy(DB::raw('CAST(cod_muestra AS UNSIGNED)'))
                ->get();

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

            // Crear encabezados más detallados
            $mainHeaders = ['', 'Panelista'];
            $attrHeaders = ['', ''];

            foreach ($muestras as $muestra) {
                $atributosActivos = [];
                foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                    if ($muestra->{"tiene_$atributo"}) {
                        $mainHeaders[] = "Muestra {$muestra->cod_muestra}";
                        $attrHeaders[] = ucfirst($atributo);
                        $atributosActivos[] = $atributo;
                    }
                }
            }

            $rows->push($mainHeaders);
            $rows->push($attrHeaders);

            // Procesar cada panelista
            $panelistas = $calificaciones->groupBy('idpane');
            $contadorPanelista = 1;

            foreach ($panelistas as $idpane => $calificacionesPanelista) {
                $panelista = $calificacionesPanelista->first()->panelista;
                $row = [
                    $contadorPanelista++,
                    $panelista ? $panelista->nombres : 'N/A'
                ];

                foreach ($muestras as $muestra) {
                    $calificacion = $calificacionesPanelista->where('cod_muestra', $muestra->cod_muestra)->first();

                    foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                        if ($muestra->{"tiene_$atributo"}) {
                            $campo = "valor_$atributo";
                            $valor = $calificacion ? $calificacion->$campo : '-';
                            $row[] = is_numeric($valor) ? $valor : '-';
                        }
                    }
                }

                $rows->push($row);
            }

            // Agregar estadísticas
            $rows->push([]);  // Fila vacía antes de estadísticas
            $rows->push($this->calcularEstadisticas($calificaciones, $muestras, 'avg'));
            $rows->push($this->calcularPromediosPorMuestra($calificaciones, $muestras));
            $rows->push($this->calcularEstadisticas($calificaciones, $muestras, 'median'));
            $rows->push($this->calcularEstadisticas($calificaciones, $muestras, 'mode'));
            $rows->push([]);  // Fila vacía antes de los promedios por muestra

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
        $promediosMuestra = [];

        foreach ($muestras as $muestra) {
            $promediosAtributos = []; // Array para guardar los promedios de cada atributo

            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                if (!$muestra->{"tiene_$atributo"}) {
                    continue;
                }

                $campo = "valor_$atributo";
                $valores = $calificaciones->where('cod_muestra', $muestra->cod_muestra)
                    ->pluck($campo)
                    ->filter(function ($valor) {
                        return is_numeric($valor);
                    });

                if ($valores->isEmpty()) {
                    $fila[] = '-';
                } else {
                    switch ($funcion) {
                        case 'avg':
                            $promedio = $valores->avg();
                            $fila[] = number_format($promedio, 2);
                            $promediosAtributos[] = $promedio;
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

            // Calcular promedio de los promedios para esta muestra
            if ($funcion === 'avg' && !empty($promediosAtributos)) {
                $promedioMuestra = array_sum($promediosAtributos) / count($promediosAtributos);
                $promediosMuestra[] = $promedioMuestra;
            }
        }

        return $fila;
    }

    protected function calcularPromediosPorMuestra($calificaciones, $muestras)
    {
        $fila = ['PROMEDIO MUESTRA', ''];

        foreach ($muestras as $muestra) {
            $promediosAtributos = [];
            $atributosActivos = 0;

            // Primero contar cuántos atributos activos hay
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                if ($muestra->{"tiene_$atributo"}) {
                    $atributosActivos++;

                    $campo = "valor_$atributo";
                    $valores = $calificaciones->where('cod_muestra', $muestra->cod_muestra)
                        ->pluck($campo)
                        ->filter(function ($valor) {
                            return is_numeric($valor);
                        });

                    if (!$valores->isEmpty()) {
                        $promediosAtributos[] = $valores->avg();
                    }
                }
            }

            // Calcular el promedio de la muestra
            if (!empty($promediosAtributos)) {
                $promedioMuestra = number_format(array_sum($promediosAtributos) / count($promediosAtributos), 2);

                // Agregar el promedio en la primera columna del grupo
                $fila[] = $promedioMuestra;

                // Agregar espacios vacíos para el resto de los atributos de esta muestra
                for ($i = 1; $i < $atributosActivos; $i++) {
                    $fila[] = '';
                }
            } else {
                // Si no hay promedios, llenar con espacios vacíos
                for ($i = 0; $i < $atributosActivos; $i++) {
                    $fila[] = '';
                }
            }
        }

        return $fila;
    }

    protected function getEmptyCollection($muestras)
    {
        $headers = ['', 'Panelista'];
        foreach ($muestras as $muestra) {
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                if ($muestra->{"tiene_$atributo"}) {
                    $headers[] = "Muestra {$muestra->cod_muestra} - " . ucfirst($atributo);
                }
            }
        }
        return new Collection([$headers, array_fill(0, count($headers), '')]);
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

    protected function generateRandomColor()
    {
        // Lista de colores pastel predefinidos
        $colors = [
            'FFB6C1', // Rosa claro
            'B0E0E6', // Azul polvo
            'DDA0DD', // Ciruela
            'F0E68C', // Caqui
            '98FB98', // Verde pálido
            'FFA07A', // Salmón claro
            'E6E6FA', // Lavanda
            'FFE4B5', // Mocasín
            'AFEEEE', // Turquesa pálido
            'D8BFD8', // Cardo
            'F0FFF0', // Miel rocío
            'FFF0F5', // Rubor lavanda
            'F5DEB3', // Trigo
            'E0FFFF', // Cian claro
            'FFDAB9', // Melocotón
            'B8860B', // Dorado oscuro
            'C0C0C0', // Plateado
            '87CEEB', // Azul cielo
            'ADD8E6', // Azul claro
            '9370DB'  // Púrpura medio
        ];

        return $colors[array_rand($colors)];
    }

    protected function lightenColor($hex, $percent)
    {
        // Convertir hex a RGB
        $rgb = array_map('hexdec', str_split(str_replace('#', '', $hex), 2));

        // Aclarar cada componente
        foreach ($rgb as &$color) {
            $color = min(255, $color + ($percent / 100) * (255 - $color));
            $color = str_pad(dechex(round($color)), 2, '0', STR_PAD_LEFT);
        }

        return implode('', $rgb);
    }


    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Estilo base para los encabezados principales
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'E0E0E0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo para los encabezados de atributos
        $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray([
            'font' => ['bold' => true, 'italic' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F5F5F5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilos para las filas de estadísticas
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

        // Centrar números y valores
        $sheet->getStyle("A3:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C3:{$lastColumn}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Fusionar celdas para encabezados de muestras
        $this->mergeSampleHeaders($sheet);

        return $sheet;
    }

    protected function mergeSampleHeaders($sheet)
    {
        $column = 'C';
        $muestras = Muestra::where('producto_id', $this->productoId)
            ->where('prueba', 3)
            ->orderBy(DB::raw('CAST(cod_muestra AS UNSIGNED)'))
            ->get();

        foreach ($muestras as $muestra) {
            $atributosActivos = 0;
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                if ($muestra->{"tiene_$atributo"}) {
                    $atributosActivos++;
                }
            }

            if ($atributosActivos > 0) {
                $endColumn = chr(ord($column) + $atributosActivos - 1);
                if ($column != $endColumn) {
                    $sheet->mergeCells("{$column}1:{$endColumn}1");
                }

                // Aplicar color aleatorio al encabezado de la muestra
                $randomColor = $this->generateRandomColor();
                $sheet->getStyle("{$column}1:{$endColumn}1")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $randomColor]
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'] // Texto negro para mejor contraste
                    ]
                ]);

                // Aplicar un color más claro para la fila de atributos
                $sheet->getStyle("{$column}2:{$endColumn}2")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $this->lightenColor($randomColor, 30)]
                    ],
                    'font' => [
                        'bold' => true,
                        'italic' => true,
                        'color' => ['rgb' => '000000']
                    ]
                ]);

                $column = chr(ord($endColumn) + 1);
            }
        }
    }
}
