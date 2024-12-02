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
use App\Models\Panelista;
use App\Models\Muestra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ResultadosResumenSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $fecha;
    protected $productoId;
    protected $tipoPrueba;
    protected $spacingColumns = 3; // Número de columnas de separación
    protected $firstSectionColumns = [];
    protected $secondSectionColumns = [];

    public function __construct($fecha, $productoId, $tipoPrueba)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->tipoPrueba = $tipoPrueba;
    }

    public function collection()
    {
        try {
            $firstSection = $this->getDetailedRatings();
            $secondSection = $this->getPreferences();

            // Guardar el número de columnas de cada sección
            $this->firstSectionColumns = range('A', chr(64 + count($firstSection[0])));
            $startChar = chr(64 + count($firstSection[0]) + $this->spacingColumns + 1);
            $this->secondSectionColumns = range($startChar, chr(64 + count($firstSection[0]) + $this->spacingColumns + count($secondSection[0])));

            return $this->combinarSecciones($firstSection, $secondSection);
        } catch (\Exception $e) {
            return new Collection([['Error: ' . $e->getMessage()]]);
        }
    }

    protected function getDetailedRatings()
    {
        // Obtener todas las muestras para este producto
        $muestras = Muestra::where('producto_id', $this->productoId)
            ->where('prueba', 3)
            ->orderBy(DB::raw('CAST(cod_muestra AS UNSIGNED)'))
            ->get();

        // Obtener los atributos activos de la primera muestra
        $primeraMuestra = $muestras->first();
        $atributos = collect([
            'tiene_sabor' => 'Sabor',
            'tiene_olor' => 'Olor',
            'tiene_color' => 'Color',
            'tiene_textura' => 'Textura',
            'tiene_apariencia' => 'Apariencia'
        ])->filter(function ($value, $key) use ($primeraMuestra) {
            return $primeraMuestra->$key;
        })->values()->toArray();

        // Crear encabezados dinámicos
        $headers = ['#', 'Panelista', 'Panelista', 'Muestra', ...$atributos];
        $rows = new Collection([$headers]);
        $counter = 1;

        foreach ($muestras as $muestra) {
            $calificaciones = DB::table('calificaciones')
                ->join('panelistas', 'calificaciones.idpane', '=', 'panelistas.idpane')
                ->select(
                    'panelistas.idpane',
                    'panelistas.nombres',
                    'calificaciones.cod_muestra',
                    'calificaciones.valor_sabor',
                    'calificaciones.valor_olor',
                    'calificaciones.valor_color',
                    'calificaciones.valor_textura',
                    'calificaciones.valor_apariencia'
                )
                ->where('calificaciones.fecha', $this->fecha)
                ->where('calificaciones.producto', $this->productoId)
                ->where('calificaciones.prueba', 3)
                ->where('calificaciones.cod_muestra', $muestra->cod_muestra)
                ->orderBy('panelistas.idpane')
                ->get();

            foreach ($calificaciones as $calificacion) {
                $row = [
                    $counter++,
                    $calificacion->nombres,
                    $calificacion->idpane,
                    $muestra->cod_muestra
                ];

                if ($muestra->tiene_sabor) $row[] = $calificacion->valor_sabor ?? '-';
                if ($muestra->tiene_olor) $row[] = $calificacion->valor_olor ?? '-';
                if ($muestra->tiene_color) $row[] = $calificacion->valor_color ?? '-';
                if ($muestra->tiene_textura) $row[] = $calificacion->valor_textura ?? '-';
                if ($muestra->tiene_apariencia) $row[] = $calificacion->valor_apariencia ?? '-';

                $rows->push($row);
            }
        }

        return $rows;
    }

    protected function getPreferences()
    {
        $rows = new Collection();

        // Encabezados de la sección de preferencias
        $rows->push(['#', 'Panelista', 'Preferencia']);

        // Obtener panelistas ordenados
        $panelistas = DB::table('calificaciones')
            ->join('panelistas', 'calificaciones.idpane', '=', 'panelistas.idpane')
            ->where('calificaciones.fecha', $this->fecha)
            ->where('calificaciones.producto', $this->productoId)
            ->where('calificaciones.prueba', 3)
            ->select('panelistas.idpane', 'panelistas.nombres')
            ->distinct()
            ->orderBy('panelistas.idpane')
            ->get();

        $counter = 1;

        foreach ($panelistas as $panelista) {
            // Obtener calificaciones del panelista
            $calificaciones = DB::table('calificaciones')
                ->where('fecha', $this->fecha)
                ->where('producto', $this->productoId)
                ->where('prueba', 3)
                ->where('idpane', $panelista->idpane)
                ->get();

            // Calcular suma por muestra
            $sumasPorMuestra = [];
            foreach ($calificaciones as $cal) {
                $suma = ($cal->valor_sabor ?? 0) +
                    ($cal->valor_olor ?? 0) +
                    ($cal->valor_color ?? 0) +
                    ($cal->valor_textura ?? 0) +
                    ($cal->valor_apariencia ?? 0);
                $sumasPorMuestra[$cal->cod_muestra] = $suma;
            }

            // Encontrar la muestra preferida
            arsort($sumasPorMuestra);
            $muestraPreferida = key($sumasPorMuestra);

            $rows->push([
                $counter++,
                $panelista->nombres,
                $muestraPreferida
            ]);
        }

        return $rows;
    }

    protected function combinarSecciones($firstSection, $secondSection)
    {
        $combinedRows = new Collection();
        $maxRows = max($firstSection->count(), $secondSection->count());

        for ($i = 0; $i < $maxRows; $i++) {
            $row = [];

            // Agregar datos de la primera sección
            if ($i < $firstSection->count()) {
                $row = $firstSection[$i];
            } else {
                $row = array_fill(0, count($firstSection[0]), '');
            }

            // Agregar columnas de separación
            for ($j = 0; $j < $this->spacingColumns; $j++) {
                $row[] = '';
            }

            // Agregar datos de la segunda sección
            if ($i < $secondSection->count()) {
                $row = array_merge($row, $secondSection[$i]);
            }

            $combinedRows->push($row);
        }

        return $combinedRows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Aplicar estilos para la primera sección
        $this->applyHeaderStyles($sheet, $this->firstSectionColumns[0] . '1:' . end($this->firstSectionColumns) . '1');
        $this->applyContentStyles($sheet, $this->firstSectionColumns[0] . '2:' . end($this->firstSectionColumns) . $lastRow);
        $this->applyBorders($sheet, $this->firstSectionColumns[0] . '1:' . end($this->firstSectionColumns) . $lastRow);
        $this->applyAlternatingRows($sheet, $this->firstSectionColumns[0], end($this->firstSectionColumns), $lastRow);

        // Aplicar estilos para la segunda sección
        $this->applyHeaderStyles($sheet, $this->secondSectionColumns[0] . '1:' . end($this->secondSectionColumns) . '1');
        $this->applyContentStyles($sheet, $this->secondSectionColumns[0] . '2:' . end($this->secondSectionColumns) . $lastRow);
        $this->applyBorders($sheet, $this->secondSectionColumns[0] . '1:' . end($this->secondSectionColumns) . $lastRow);
        $this->applyAlternatingRows($sheet, $this->secondSectionColumns[0], end($this->secondSectionColumns), $lastRow);

        // Nombres alineados a la izquierda en ambas secciones
        $sheet->getStyle('B2:B' . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);

        $nameColumnSecondSection = $this->secondSectionColumns[1];
        $sheet->getStyle($nameColumnSecondSection . '2:' . $nameColumnSecondSection . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);

        // Ocultar columna de ID de panelista
        $sheet->getColumnDimension('C')->setVisible(false);

        // Ajustar altura de filas
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        return $sheet;
    }


    protected function applyHeaderStyles($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2F855A']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    protected function applyContentStyles($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    protected function applyBorders($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);
    }

    protected function applyAlternatingRows($sheet, $startColumn, $endColumn, $lastRow)
    {
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle($startColumn . $row . ':' . $endColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F3F4F6']
                    ]
                ]);
            }
        }
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return "Resumen General";
    }
}
