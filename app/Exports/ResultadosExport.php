<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use App\Models\Calificacion;

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

                // Siempre creamos la hoja, incluso si no hay datos
                $sheets[] = new ResultadosSheet(
                    $this->fecha,
                    $this->productoId,
                    $this->tipoPrueba,
                    $i
                );
            }

            // Siempre agregamos la hoja de resumen
            $sheets[] = new ResultadosResumenSheet(
                $this->fecha,
                $this->productoId,
                $this->tipoPrueba
            );
        } else {
            // Para una sola cabina
            $sheets[] = new ResultadosSheet(
                $this->fecha,
                $this->productoId,
                $this->tipoPrueba,
                $this->cabina
            );
        }

        Log::info("Total de hojas creadas: " . count($sheets));
        return $sheets;
    }
}
