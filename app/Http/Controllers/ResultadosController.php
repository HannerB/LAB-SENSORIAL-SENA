<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResultadosExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Resultado;
use App\Models\Muestra;
use App\Models\Calificacion;

class ResultadosController extends Controller
{
    public function update(Request $request, Resultado $resultado)
    {
        $data = $request->validate([
            'producto' => 'required|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'required|string|max:50',
            'cod_muestra' => 'nullable|string|max:50',
            'resultado' => 'nullable|string|max:50',
            'fecha' => 'nullable|date',
            'cabina' => 'required|integer',
        ]);

        $resultado->update($data);
        return redirect()->route('resultado.index');
    }

    public function destroy(Resultado $resultado)
    {
        $resultado->delete();
        return redirect()->route('resultado.index');
    }

    public function generarResultados(Request $request)
    {
        Log::info('Iniciando generación de resultados');
        Log::info($request->all());

        try {
            DB::beginTransaction();

            $request->validate([
                'fecha' => 'required|date',
                'producto_id' => 'required|exists:productos,id_producto',
            ]);

            $fecha = $request->fecha;
            $productoId = $request->producto_id;

            Log::info("Fecha: $fecha, Producto ID: $productoId");

            // Obtener todas las muestras del producto
            $muestras = Muestra::where('producto_id', $productoId)->get();
            Log::info("Muestras encontradas: " . $muestras->count());

            // Obtener todas las calificaciones para este producto y fecha
            $calificaciones = Calificacion::where('producto', $productoId)
                ->whereDate('created_at', $fecha)
                ->get();

            Log::info("Calificaciones encontradas: " . $calificaciones->count());

            // Inicializar un array para contar los votos por muestra
            $resultados = [];

            // Contar votos para cada muestra (para pruebas 1 y 2)
            foreach ($calificaciones->whereIn('prueba', [1, 2]) as $calificacion) {
                $codMuestra = $calificacion->cod_muestras;
                $resultados[$codMuestra] = ($resultados[$codMuestra] ?? 0) + 1;
            }

            $resultadosFormateados = [];

            // Procesar pruebas 1 y 2
            foreach ($muestras->whereIn('prueba', [1, 2]) as $muestra) {
                $codMuestra = $muestra->cod_muestra;
                $votos = $resultados[$codMuestra] ?? 0;

                $resultadoNuevo = Resultado::updateOrCreate(
                    [
                        'producto' => $productoId,
                        'prueba' => $muestra->prueba,
                        'cod_muestra' => $codMuestra,
                        'fecha' => $fecha
                    ],
                    [
                        'atributo' => 'Dulzura',
                        'resultado' => $votos,
                        'cabina' => 1
                    ]
                );

                $resultadosFormateados[] = $resultadoNuevo->toArray();
            }

            // Procesar prueba de ordenamiento (prueba 3)
            $calificacionesOrdenamiento = $calificaciones->where('prueba', 3);
            if ($calificacionesOrdenamiento->isNotEmpty()) {
                $votosOrdenamiento = [];

                // Contar cuántas veces cada muestra fue seleccionada en la primera posición
                foreach ($calificacionesOrdenamiento as $calificacion) {
                    $secuenciaMuestras = explode(',', $calificacion->cod_muestras);
                    $primeraMuestra = $secuenciaMuestras[0];  // La primera muestra seleccionada

                    $votosOrdenamiento[$primeraMuestra] = ($votosOrdenamiento[$primeraMuestra] ?? 0) + 1;
                }

                // Ordenar las muestras por la cantidad de veces que fueron votadas en primera posición
                arsort($votosOrdenamiento);

                // Obtener la muestra más votada
                $muestraMasVotada = key($votosOrdenamiento);

                $resultadoOrdenamiento = Resultado::updateOrCreate(
                    [
                        'producto' => $productoId,
                        'prueba' => 3,
                        'fecha' => $fecha
                    ],
                    [
                        'cod_muestra' => $muestraMasVotada,
                        'atributo' => 'Dulzura',
                        'resultado' => $votosOrdenamiento[$muestraMasVotada],
                        'cabina' => 1
                    ]
                );

                $resultadosFormateados[] = $resultadoOrdenamiento->toArray();
            }

            // Organizar los resultados por tipo de prueba
            $triangulares = collect($resultadosFormateados)->where('prueba', 1)->values();
            $duoTrio = collect($resultadosFormateados)->where('prueba', 2)->values();
            $ordenamiento = collect($resultadosFormateados)->where('prueba', 3)->values();

            $data = [
                'triangulares' => $triangulares,
                'duoTrio' => $duoTrio,
                'ordenamiento' => $ordenamiento,
                'muestraMasVotada' => ['cod_muestra' => $muestraMasVotada]
            ];

            DB::commit();
            Log::info('Finalizando generación y guardado de resultados');
            return response()->json(['message' => 'Resultados generados y guardados exitosamente', 'data' => $data]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al generar y guardar resultados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al generar y guardar los resultados: ' . $e->getMessage()], 500);
        }
    }

    public function mostrarResultadosPanelistas(Request $request)
    {
        $testType = $request->input('test_type');
        $fecha = $request->input('fecha');
        $productoId = $request->input('producto_id');

        $query = DB::table('calificaciones')
            ->join('panelistas', 'calificaciones.idpane', '=', 'panelistas.idpane')
            ->where('calificaciones.fecha', $fecha)
            ->where('calificaciones.producto', $productoId)
            ->select('panelistas.nombres as nombre_panelista', 'calificaciones.cod_muestras', 'calificaciones.prueba');

        switch ($testType) {
            case '1': // Prueba Triangular
                $query->where('calificaciones.prueba', 1);
                break;
            case '2': // Prueba Duo-Trio
                $query->where('calificaciones.prueba', 2);
                break;
            case '3': // Prueba Ordenamiento
                $query->where('calificaciones.prueba', 3);
                break;
        }

        $results = $query->get()->map(function ($item) {
            $respuesta = $this->formatearRespuesta($item->prueba, $item->cod_muestras);
            return [
                'nombre_panelista' => $item->nombre_panelista,
                'respuesta' => $respuesta
            ];
        });

        Log::info($results);

        return response()->json(['data' => $results]);
    }

    private function formatearRespuesta($tipoPrueba, $codMuestras)
    {
        $muestras = explode(',', $codMuestras);

        switch ($tipoPrueba) {
            case 1: // Triangular
            case 2: // Duo-Trio
                return "Muestra seleccionada: " . $muestras[0];
            case 3: // Ordenamiento
                return "Orden: " . implode(' > ', $muestras);
            default:
                return $codMuestras;
        }
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'producto_id' => 'required|exists:productos,id_producto',
            'tipo_prueba' => 'nullable|in:1,2,3'
        ]);

        try {
            return Excel::download(
                new ResultadosExport(
                    $request->fecha,
                    $request->producto_id,
                    $request->tipo_prueba
                ),
                "resultados_{$request->fecha}.xlsx"
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar resultados: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el archivo Excel');
        }
    }
}
