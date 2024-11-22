<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use App\Models\Calificacion;
use App\Exports\ResultadosSheet;
use App\Exports\ResultadosResumenSheet;
use App\Exports\ResultadosOrdenamientoSheet;

class ResultadosExport implements WithMultipleSheets
{
    use Exportable;

    protected $fecha;
    protected $productoId;
    protected $tipoPrueba;
    protected $cabina;

    public function __construct($fecha, $productoId, $tipoPrueba = null, $cabina = null)
    {
        $this->fecha = $fecha;
        $this->productoId = $productoId;
        $this->tipoPrueba = $tipoPrueba;
        $this->cabina = $cabina;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->cabina === null) {
            // Crear hojas para cada cabina (1-3)
            for ($i = 1; $i <= 3; $i++) {
                $query = Calificacion::where('fecha', $this->fecha)
                    ->where('producto', $this->productoId)
                    ->where('cabina', $i);

                if ($this->tipoPrueba) {
                    $query->where('prueba', $this->tipoPrueba);
                }

                $count = $query->count();
                Log::info("Cabina {$i}: {$count} calificaciones encontradas");

                // Si hay datos o si queremos todas las hojas
                $sheets[] = new ResultadosSheet(
                    $this->fecha,
                    $this->productoId,
                    $this->tipoPrueba,
                    $i
                );

                // Si hay pruebas de ordenamiento o no se especificó tipo de prueba
                if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                    $ordenamientoQuery = clone $query;
                    $ordenamientoQuery->where('prueba', 3);
                    if ($ordenamientoQuery->count() > 0) {
                        $sheets[] = new ResultadosOrdenamientoSheet(
                            $this->fecha,
                            $this->productoId,
                            $i
                        );
                    }
                }
            }

            // Agregar resumen general
            $sheets[] = new ResultadosResumenSheet(
                $this->fecha,
                $this->productoId,
                $this->tipoPrueba
            );

            // Si hay pruebas de ordenamiento, agregar resumen general de ordenamiento
            if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                $ordenamientoGeneralQuery = Calificacion::where('fecha', $this->fecha)
                    ->where('producto', $this->productoId)
                    ->where('prueba', 3);

                if ($ordenamientoGeneralQuery->count() > 0) {
                    $sheets[] = new ResultadosOrdenamientoSheet(
                        $this->fecha,
                        $this->productoId
                    );
                }
            }
        } else {
            // Para una sola cabina
            $sheets[] = new ResultadosSheet(
                $this->fecha,
                $this->productoId,
                $this->tipoPrueba,
                $this->cabina
            );

            // Si hay pruebas de ordenamiento en esta cabina
            if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                $ordenamientoQuery = Calificacion::where('fecha', $this->fecha)
                    ->where('producto', $this->productoId)
                    ->where('cabina', $this->cabina)
                    ->where('prueba', 3);

                if ($ordenamientoQuery->count() > 0) {
                    $sheets[] = new ResultadosOrdenamientoSheet(
                        $this->fecha,
                        $this->productoId,
                        $this->cabina
                    );
                }
            }
        }

        Log::info("Total de hojas creadas: " . count($sheets));
        return $sheets;
    }
}
