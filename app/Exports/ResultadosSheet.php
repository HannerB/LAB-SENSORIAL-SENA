<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Calificacion;

class ResultadosSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $fecha;
    protected $productoId;
    protected $tipoPrueba;
    protected $cabina;

    public function __construct($fecha, $productoId, $tipoPrueba, $cabina)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->tipoPrueba = $tipoPrueba;
        $this->cabina = $cabina;
    }

    public function collection()
    {
        try {
            // Modificado para usar with solo con panelista
            $query = Calificacion::with('panelista')
                ->where('fecha', $this->fecha)
                ->where('producto', $this->productoId)
                ->where('cabina', $this->cabina);

            if ($this->tipoPrueba) {
                $query->where('prueba', $this->tipoPrueba);
            }

            $resultados = $query->orderBy('prueba')->orderBy('fecha')->get();

            if ($resultados->isEmpty()) {
                return collect([[
                    'fecha' => $this->fecha,
                    'nombre_panelista' => 'No hay datos para esta cabina',
                    'producto' => '-',
                    'tipo_prueba' => '-',
                    'atributo' => '-',
                    'codigo_muestras' => '-',
                    'comentario' => '-',
                    'cabina' => $this->cabina,
                    'resultado' => '-'
                ]]);
            }

            return $resultados;
        } catch (\Exception $e) {
            Log::error("Error en ResultadosSheet->collection(): " . $e->getMessage());
            return collect([[
                'fecha' => $this->fecha,
                'nombre_panelista' => 'Error al cargar datos',
                'producto' => '-',
                'tipo_prueba' => '-',
                'atributo' => '-',
                'codigo_muestras' => '-',
                'comentario' => $e->getMessage(),
                'cabina' => $this->cabina,
                'resultado' => '-'
            ]]);
        }
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Nombre Panelista',
            'Producto',
            'Tipo de Prueba',
            'Atributo',
            'Código Muestras',
            'Comentario',
            'Cabina',
            'Resultado'
        ];
    }

    public function map($calificacion): array
    {
        if (is_array($calificacion)) {
            return $calificacion;
        }

        $tipoPrueba = [
            1 => 'Prueba Triangular',
            2 => 'Prueba Duo-Trio',
            3 => 'Prueba de Ordenamiento'
        ][$calificacion->prueba] ?? 'Desconocido';

        // Obtener el nombre del producto
        $nombreProducto = DB::table('productos')
            ->where('id_producto', $calificacion->producto)
            ->value('nombre') ?? 'N/A';

        // Para prueba de ordenamiento, obtener los atributos evaluados
        $atributo = $this->obtenerAtributosEvaluados($calificacion);

        return [
            $calificacion->fecha,
            $calificacion->panelista->nombres ?? 'N/A',
            $nombreProducto,
            $tipoPrueba,
            $atributo,
            $calificacion->cod_muestra ?? '-',
            $calificacion->comentario ?? 'Sin comentarios',
            $calificacion->cabina,
            $this->generarResultado($calificacion)
        ];
    }

    protected function obtenerAtributosEvaluados($calificacion)
    {
        if ($calificacion->prueba != 3) {
            return '-';
        }

        // Obtener la muestra correspondiente para verificar qué atributos están habilitados
        $muestra = DB::table('muestras')
            ->where('producto_id', $calificacion->producto)
            ->where('prueba', 3)
            ->where('cod_muestra', $calificacion->cod_muestra)
            ->first();

        if (!$muestra) {
            return '-';
        }

        $atributosEvaluados = [];

        if ($muestra->tiene_sabor && $calificacion->valor_sabor !== null) {
            $atributosEvaluados[] = 'Sabor';
        }
        if ($muestra->tiene_olor && $calificacion->valor_olor !== null) {
            $atributosEvaluados[] = 'Olor';
        }
        if ($muestra->tiene_color && $calificacion->valor_color !== null) {
            $atributosEvaluados[] = 'Color';
        }
        if ($muestra->tiene_textura && $calificacion->valor_textura !== null) {
            $atributosEvaluados[] = 'Textura';
        }
        if ($muestra->tiene_apariencia && $calificacion->valor_apariencia !== null) {
            $atributosEvaluados[] = 'Apariencia';
        }

        return empty($atributosEvaluados) ? '-' : implode(', ', $atributosEvaluados);
    }

    protected function generarResultado($calificacion)
    {
        switch ($calificacion->prueba) {
            case 1: // Triangular
                return $calificacion->es_diferente ?
                    "Muestra diferente: {$calificacion->cod_muestra}" :
                    "No seleccionada como diferente";

            case 2: // Duo-Trio
                return $calificacion->es_igual_referencia ?
                    "Igual a referencia: {$calificacion->cod_muestra}" :
                    "No seleccionada como igual";

            case 3: // Ordenamiento
                $valores = [];
                if ($calificacion->valor_sabor !== null) $valores[] = "Sabor: {$calificacion->valor_sabor}";
                if ($calificacion->valor_olor !== null) $valores[] = "Olor: {$calificacion->valor_olor}";
                if ($calificacion->valor_color !== null) $valores[] = "Color: {$calificacion->valor_color}";
                if ($calificacion->valor_textura !== null) $valores[] = "Textura: {$calificacion->valor_textura}";
                if ($calificacion->valor_apariencia !== null) $valores[] = "Apariencia: {$calificacion->valor_apariencia}";

                return empty($valores) ? '-' : implode(" | ", $valores);

            default:
                return '-';
        }
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
                'color' => ['rgb' => '2F855A'] // Color verde del SENA
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

    public function title(): string
    {
        return "Cabina {$this->cabina}";
    }
}
