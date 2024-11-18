<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use App\Models\Calificacion;

class ResultadosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $fecha;
    protected $productoId;
    protected $tipoPrueba;
    protected $cabina;

    public function __construct($fecha, $productoId, $tipoPrueba = null, $cabina)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->tipoPrueba = $tipoPrueba;
        $this->cabina = $cabina;
    }

    public function collection()
    {
        $query = Calificacion::with(['panelista', 'producto'])
            ->where('fecha', $this->fecha)
            ->where('producto', $this->productoId)
            ->where('cabina', $this->cabina);

        // Si se especifica un tipo de prueba, filtrar por ese tipo
        if ($this->tipoPrueba) {
            $query->where('prueba', $this->tipoPrueba);
        }

        // Ordenar los resultados
        $query->orderBy('prueba')
            ->orderBy('fecha');

        return $query->get();
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
        $tipoPrueba = [
            1 => 'Prueba Triangular',
            2 => 'Prueba Duo-Trio',
            3 => 'Prueba de Ordenamiento'
        ][$calificacion->prueba] ?? 'Desconocido';

        $resultado = '';
        switch ($calificacion->prueba) {
            case 1:
                $resultado = "Muestra seleccionada: " . $calificacion->cod_muestras;
                break;
            case 2:
                $resultado = "Muestra igual a referencia: " . $calificacion->cod_muestras;
                break;
            case 3:
                $muestras = explode(',', $calificacion->cod_muestras);
                $resultado = "Orden: " . implode(' > ', $muestras);
                break;
        }


        $nombreProducto = DB::table('productos')
            ->where('id_producto', $calificacion->producto)
            ->value('nombre') ?? 'N/A';

        return [
            $calificacion->fecha,
            $calificacion->panelista->nombres ?? 'N/A',
            $nombreProducto,
            $tipoPrueba,
            $calificacion->atributo,
            $calificacion->cod_muestras,
            $calificacion->comentario ?? 'Sin comentarios',
            $calificacion->cabina,
            $resultado
        ];
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
        $tipoPruebaTexto = '';
        if ($this->tipoPrueba) {
            $tipos = [
                1 => 'Triangular',
                2 => 'Duo-Trio',
                3 => 'Ordenamiento'
            ];
            $tipoPruebaTexto = ' - ' . ($tipos[$this->tipoPrueba] ?? '');
        }

        return "Resultados Cabina {$this->cabina}{$tipoPruebaTexto} {$this->fecha}";
    }
}
