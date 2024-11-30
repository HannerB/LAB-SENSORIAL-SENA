<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use App\Models\Calificacion;
use App\Exports\ResultadosSheet;
use App\Exports\ResultadosResumenSheet;
use App\Exports\SensoryEvaluationSheet;

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

        // Verificar las cabinas que tienen datos
        $cabinasConDatos = Calificacion::where('fecha', $this->fecha)
            ->where('producto', $this->productoId)
            ->when($this->tipoPrueba, function ($query) {
                return $query->where('prueba', $this->tipoPrueba);
            })
            ->select('cabina')
            ->distinct()
            ->pluck('cabina')
            ->toArray();

        if ($this->cabina === null) {
            // Procesar cada cabina que tenga datos
            foreach ($cabinasConDatos as $numCabina) {
                // Agregar hoja de resultados generales
                $sheets[] = new ResultadosSheet(
                    $this->fecha,
                    $this->productoId,
                    $this->tipoPrueba,
                    $numCabina
                );

                // Verificar si hay pruebas de ordenamiento para esta cabina
                if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                    $ordenamientoQuery = Calificacion::where('fecha', $this->fecha)
                        ->where('producto', $this->productoId)
                        ->where('cabina', $numCabina)
                        ->where('prueba', 3);

                    if ($ordenamientoQuery->exists()) {
                        $sheets[] = new SensoryEvaluationSheet(
                            $this->fecha,
                            $this->productoId,
                            $numCabina
                        );
                    }
                }
            }

            // Solo agregar resumen general si hay datos
            if (!empty($cabinasConDatos)) {
                // Agregar resumen general
                $sheets[] = new ResultadosResumenSheet(
                    $this->fecha,
                    $this->productoId,
                    $this->tipoPrueba
                );

                // Resumen general sensorial si hay datos de ordenamiento
                if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                    $ordenamientoGeneralQuery = Calificacion::where('fecha', $this->fecha)
                        ->where('producto', $this->productoId)
                        ->where('prueba', 3);

                    if ($ordenamientoGeneralQuery->exists()) {
                        $sheets[] = new SensoryEvaluationSheet(
                            $this->fecha,
                            $this->productoId
                        );
                    }
                }
            }
        } else {
            // Verificar si la cabina específica tiene datos
            if (in_array($this->cabina, $cabinasConDatos)) {
                // Resultados generales para la cabina específica
                $sheets[] = new ResultadosSheet(
                    $this->fecha,
                    $this->productoId,
                    $this->tipoPrueba,
                    $this->cabina
                );

                // Verificar pruebas de ordenamiento para esta cabina
                if (!$this->tipoPrueba || $this->tipoPrueba == 3) {
                    $ordenamientoQuery = Calificacion::where('fecha', $this->fecha)
                        ->where('producto', $this->productoId)
                        ->where('cabina', $this->cabina)
                        ->where('prueba', 3);

                    if ($ordenamientoQuery->exists()) {
                        $sheets[] = new SensoryEvaluationSheet(
                            $this->fecha,
                            $this->productoId,
                            $this->cabina
                        );
                    }
                }
            }
        }

        Log::info("Total de hojas creadas: " . count($sheets));
        return $sheets;
    }
}
