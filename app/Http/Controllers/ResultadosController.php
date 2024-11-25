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
                'cabina' => ['required', function ($attribute, $value, $fail) {
                    if ($value !== 'all' && (!is_numeric($value) || $value < 1 || $value > 3)) {
                        $fail('El campo cabina debe ser "all" o un número entre 1 y 3.');
                    }
                }],
            ]);

            $fecha = $request->fecha;
            $productoId = $request->producto_id;
            $cabina = $request->cabina;

            // Si la cabina es 'all', procesar todas las cabinas
            if ($cabina === 'all') {
                $resultados = $this->procesarTodasLasCabinas($fecha, $productoId);
            } else {
                $resultados = $this->procesarCabinaIndividual($fecha, $productoId, intval($cabina));
            }

            DB::commit();
            Log::info('Finalizando generación y guardado de resultados');
            return response()->json(['success' => true, 'data' => $resultados]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al generar y guardar resultados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al generar y guardar los resultados: ' . $e->getMessage()], 500);
        }
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
            'tipo_prueba' => 'nullable|in:1,2,3',
            'cabina' => 'required|integer|min:1|max:3'
        ]);

        try {
            return Excel::download(
                new ResultadosExport(
                    $request->fecha,
                    $request->producto_id,
                    $request->tipo_prueba,
                    $request->cabina
                ),
                "resultados_cabina_{$request->cabina}_{$request->fecha}.xlsx"
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar resultados: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el archivo Excel');
        }
    }

    public function exportarTodas(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'producto_id' => 'required|exists:productos,id_producto'
        ]);

        try {
            return Excel::download(
                new ResultadosExport(
                    $request->fecha,
                    $request->producto_id,
                    null, // Siempre pasamos null para obtener todas las pruebas
                    null  // null para todas las cabinas
                ),
                "resultados_todas_cabinas_{$request->fecha}.xlsx"
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar resultados de todas las cabinas: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el archivo Excel');
        }
    }

    private function procesarResultados($muestras, $calificaciones, $productoId, $fecha, $cabina)
    {
        $resultados = [];
        $resultadosFormateados = [];

        // Procesar pruebas 1 y 2
        foreach ($calificaciones->whereIn('prueba', [1, 2]) as $calificacion) {
            $codMuestra = $calificacion->cod_muestras;
            $resultados[$codMuestra] = ($resultados[$codMuestra] ?? 0) + 1;
        }

        foreach ($muestras->whereIn('prueba', [1, 2]) as $muestra) {
            $codMuestra = $muestra->cod_muestra;
            $votos = $resultados[$codMuestra] ?? 0;

            $resultadoNuevo = [
                'producto' => $productoId,
                'prueba' => $muestra->prueba,
                'cod_muestra' => $codMuestra,
                'fecha' => $fecha,
                'cabina' => $cabina,
                'atributo' => '',
                'resultado' => $votos
            ];

            $resultadosFormateados[] = $resultadoNuevo;
        }

        // Procesar prueba de ordenamiento (prueba 3)
        $calificacionesOrdenamiento = $calificaciones->where('prueba', 3);
        if ($calificacionesOrdenamiento->isNotEmpty()) {
            $calificacionesPorAtributo = $calificacionesOrdenamiento->groupBy('atributo');

            foreach ($calificacionesPorAtributo as $atributo => $calificaciones) {
                $votosOrdenamiento = [];

                foreach ($calificaciones as $calificacion) {
                    $secuenciaMuestras = explode(',', $calificacion->cod_muestras);
                    if (!empty($secuenciaMuestras)) {
                        $primeraMuestra = $secuenciaMuestras[0];
                        $votosOrdenamiento[$primeraMuestra] = ($votosOrdenamiento[$primeraMuestra] ?? 0) + 1;
                    }
                }

                foreach ($votosOrdenamiento as $codMuestra => $votos) {
                    $resultadoNuevo = [
                        'producto' => $productoId,
                        'prueba' => 3,
                        'cod_muestra' => $codMuestra,
                        'fecha' => $fecha,
                        'cabina' => $cabina,
                        'atributo' => $atributo,
                        'resultado' => $votos
                    ];

                    $resultadosFormateados[] = $resultadoNuevo;
                }
            }
        }

        return [
            'triangulares' => collect($resultadosFormateados)->where('prueba', 1)->values(),
            'duoTrio' => collect($resultadosFormateados)->where('prueba', 2)->values(),
            'ordenamiento' => collect($resultadosFormateados)->where('prueba', 3)->values()
        ];
    }

    private function procesarCabinaIndividual($fecha, $productoId, $cabina)
    {
        // Obtener muestras y calificaciones
        $muestras = Muestra::where('producto_id', $productoId)->get();
        $calificaciones = Calificacion::where('producto', $productoId)
            ->whereDate('fecha', $fecha)
            ->where('cabina', $cabina)
            ->get();

        return $this->procesarResultados($muestras, $calificaciones, $productoId, $fecha, $cabina);
    }


    public function mostrarResultadosPanelistas(Request $request)
    {
        try {
            $testType = $request->input('test_type');
            $fecha = $request->input('fecha');
            $productoId = $request->input('producto_id');
            $cabina = $request->input('cabina');

            Log::info('Iniciando consulta de resultados por panelista', [
                'test_type' => $testType,
                'fecha' => $fecha,
                'producto_id' => $productoId,
                'cabina' => $cabina
            ]);

            $query = DB::table('calificaciones')
                ->join('panelistas', 'calificaciones.idpane', '=', 'panelistas.idpane')
                ->whereDate('calificaciones.fecha', $fecha)
                ->where('calificaciones.producto', $productoId);

            // Si no es 'all', filtrar por cabina específica
            if ($cabina !== 'all') {
                $query->where('calificaciones.cabina', $cabina);
            }

            if ($testType && in_array($testType, ['1', '2', '3'])) {
                $query->where('calificaciones.prueba', $testType);
            }

            $results = $query->select(
                'panelistas.nombres as nombre_panelista',
                'calificaciones.cod_muestras',
                'calificaciones.prueba',
                'calificaciones.cabina'
            )->get()->map(function ($item) {
                return [
                    'nombre_panelista' => $item->nombre_panelista,
                    'respuesta' => $this->formatearRespuesta($item->prueba, $item->cod_muestras),
                    'cabina' => $item->cabina
                ];
            });

            Log::info('Consulta de resultados por panelista exitosa', ['count' => $results->count()]);
            return response()->json(['success' => true, 'data' => $results]);
        } catch (\Exception $e) {
            Log::error('Error al mostrar resultados de panelistas: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los resultados: ' . $e->getMessage()], 500);
        }
    }

    private function procesarTodasLasCabinas($fecha, $productoId)
    {
        // Obtener muestras para el producto
        $muestras = Muestra::where('producto_id', $productoId)->get();

        // Obtener todas las calificaciones sin filtrar por cabina
        $calificacionesTotales = Calificacion::where('producto', $productoId)
            ->whereDate('fecha', $fecha)
            ->get();

        // Agrupar calificaciones por cabina
        $resultadosPorCabina = [];

        // Procesar los resultados para cada cabina (1, 2 y 3)
        for ($cabina = 1; $cabina <= 3; $cabina++) {
            $calificacionesCabina = $calificacionesTotales->where('cabina', $cabina);
            $resultadosCabina = $this->procesarResultados($muestras, $calificacionesCabina, $productoId, $fecha, $cabina);
            foreach ($resultadosCabina as $tipo => $resultados) {
                if (!isset($resultadosPorCabina[$tipo])) {
                    $resultadosPorCabina[$tipo] = collect();
                }
                $resultadosPorCabina[$tipo] = $resultadosPorCabina[$tipo]->concat($resultados);
            }
        }

        // Combinar resultados de todas las cabinas
        $resultadosFinales = [];

        // Procesar Triangulares y Duo-Trio
        foreach (['triangulares', 'duoTrio'] as $tipo) {
            if (isset($resultadosPorCabina[$tipo])) {
                $resultadosFinales[$tipo] = $resultadosPorCabina[$tipo]
                    ->groupBy('cod_muestra')
                    ->map(function ($grupo) use ($productoId, $fecha) {
                        $primerResultado = $grupo->first();
                        return [
                            'producto' => $productoId,
                            'prueba' => $primerResultado['prueba'],
                            'cod_muestra' => $primerResultado['cod_muestra'],
                            'fecha' => $fecha,
                            'cabina' => 'all',
                            'atributo' => '',
                            'resultado' => $grupo->sum('resultado')
                        ];
                    })->values();
            } else {
                $resultadosFinales[$tipo] = collect();
            }
        }

        // Procesar Ordenamiento
        if (isset($resultadosPorCabina['ordenamiento'])) {
            $resultadosFinales['ordenamiento'] = $resultadosPorCabina['ordenamiento']
                ->groupBy(function ($item) {
                    return $item['atributo'] . '_' . $item['cod_muestra'];
                })
                ->map(function ($grupo) use ($productoId, $fecha) {
                    $primerResultado = $grupo->first();
                    return [
                        'producto' => $productoId,
                        'prueba' => 3,
                        'cod_muestra' => $primerResultado['cod_muestra'],
                        'fecha' => $fecha,
                        'cabina' => 'all',
                        'atributo' => $primerResultado['atributo'],
                        'resultado' => $grupo->sum('resultado')
                    ];
                })->values();
        } else {
            $resultadosFinales['ordenamiento'] = collect();
        }

        return $resultadosFinales;
    }
}
