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
        try {
            $fecha = $request->fecha;
            $producto_id = $request->producto_id;
            $cabina = $request->cabina;

            // Obtener todas las muestras para este producto
            $muestrasTriangulares = Muestra::where('producto_id', $producto_id)
                ->where('prueba', 1)
                ->get();

            $muestrasDuoTrio = Muestra::where('producto_id', $producto_id)
                ->where('prueba', 2)
                ->get();

            // Obtener las calificaciones
            $calificacionesTriangulares = Calificacion::where('producto', $producto_id)
                ->where('prueba', 1)
                ->where('fecha', $fecha)
                ->where('cabina', $cabina)
                ->get();

            $calificacionesDuoTrio = Calificacion::where('producto', $producto_id)
                ->where('prueba', 2)
                ->where('fecha', $fecha)
                ->where('cabina', $cabina)
                ->get();

            // Preparar resultados triangulares
            $resultadosTriangulares = [];
            foreach ($muestrasTriangulares as $muestra) {
                $votos = $calificacionesTriangulares
                    ->where('cod_muestra', $muestra->cod_muestra)
                    ->count();

                $resultadosTriangulares[] = [
                    'cod_muestra' => $muestra->cod_muestra,
                    'resultado' => $votos,
                    'total_evaluaciones' => $calificacionesTriangulares->count()
                ];
            }

            // Preparar resultados duo-trio
            $resultadosDuoTrio = [];
            foreach ($muestrasDuoTrio as $muestra) {
                $votos = $calificacionesDuoTrio
                    ->where('cod_muestra', $muestra->cod_muestra)
                    ->count();

                $resultadosDuoTrio[] = [
                    'cod_muestra' => $muestra->cod_muestra,
                    'resultado' => $votos,
                    'total_evaluaciones' => $calificacionesDuoTrio->count()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'triangulares' => $resultadosTriangulares,
                    'duoTrio' => $resultadosDuoTrio,
                    'ordenamiento' => $this->obtenerResultadosOrdenamiento($producto_id, $fecha, $cabina)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en generación de resultados: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

    private function procesarResultadosOrdenamiento($producto_id, $fecha, $cabina)
    {
        $muestras = Muestra::where('producto_id', $producto_id)
            ->where('prueba', 3)
            ->get();

        foreach ($muestras as $muestra) {
            // Procesar cada atributo activo
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                $tieneAtributo = 'tiene_' . $atributo;
                if (!$muestra->$tieneAtributo) continue;

                $calificaciones = Calificacion::where('producto', $producto_id)
                    ->where('prueba', 3)
                    ->where('fecha', $fecha)
                    ->where('cabina', $cabina)
                    ->where('atributo_evaluado', $atributo)
                    ->get();

                $totalOrden = 0;
                $cantidadVotos = 0;

                foreach ($calificaciones as $calificacion) {
                    $muestrasOrdenadas = explode(',', $calificacion->cod_muestras);
                    $posicion = array_search($muestra->cod_muestra, $muestrasOrdenadas);
                    if ($posicion !== false) {
                        $totalOrden += ($posicion + 1);
                        $cantidadVotos++;
                    }
                }

                if ($cantidadVotos > 0) {
                    Resultado::create([
                        'producto' => $producto_id,
                        'prueba' => 3,
                        'cod_muestra' => $muestra->cod_muestra,
                        'resultado' => number_format($totalOrden / $cantidadVotos, 2),
                        'fecha' => $fecha,
                        'cabina' => $cabina,
                        'atributo_evaluado' => $atributo
                    ]);
                }
            }
        }
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

            // Query base
            $query = DB::table('calificaciones')
                ->join('panelistas', 'calificaciones.idpane', '=', 'panelistas.idpane')
                ->where('calificaciones.fecha', $fecha)
                ->where('calificaciones.producto', $productoId)
                ->where('calificaciones.prueba', $testType);

            if ($cabina !== 'all') {
                $query->where('calificaciones.cabina', $cabina);
            }

            $results = $query->select(
                'panelistas.nombres as nombre_panelista',
                'calificaciones.cabina',
                'calificaciones.cod_muestra',
                'calificaciones.valor_sabor',
                'calificaciones.valor_olor',
                'calificaciones.valor_color',
                'calificaciones.valor_textura',
                'calificaciones.valor_apariencia',
                'calificaciones.fecha'
            )->get();

            // Formatear resultados según el tipo de prueba
            $formattedResults = $results->map(function ($item) use ($testType) {
                $respuesta = '';

                switch ($testType) {
                    case '1': // Triangular
                        $respuesta = "Muestra diferente seleccionada: {$item->cod_muestra}";
                        break;

                    case '2': // Duo-Trio
                        $respuesta = "Muestra igual a referencia: {$item->cod_muestra}";
                        break;

                    case '3': // Ordenamiento
                        $respuestasAtributos = [];

                        if ($item->valor_sabor)
                            $respuestasAtributos[] = "Sabor: {$item->valor_sabor}";
                        if ($item->valor_olor)
                            $respuestasAtributos[] = "Olor: {$item->valor_olor}";
                        if ($item->valor_color)
                            $respuestasAtributos[] = "Color: {$item->valor_color}";
                        if ($item->valor_textura)
                            $respuestasAtributos[] = "Textura: {$item->valor_textura}";
                        if ($item->valor_apariencia)
                            $respuestasAtributos[] = "Apariencia: {$item->valor_apariencia}";

                        $respuesta = "Muestra {$item->cod_muestra}: " . implode(' | ', $respuestasAtributos);
                        break;
                }

                return [
                    'nombre_panelista' => $item->nombre_panelista,
                    'cabina' => $item->cabina,
                    'respuesta' => $respuesta,
                    'fecha_evaluacion' => $item->fecha
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedResults
            ]);
        } catch (\Exception $e) {
            Log::error('Error en mostrarResultadosPanelistas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los resultados: ' . $e->getMessage()
            ], 500);
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

    private function obtenerResultadosOrdenamiento($producto_id, $fecha, $cabina)
    {
        $muestras = Muestra::where('producto_id', $producto_id)
            ->where('prueba', 3)
            ->get();

        $resultados = [];

        foreach ($muestras as $muestra) {
            // Para cada atributo que la muestra tiene habilitado
            foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                $tieneAtributo = 'tiene_' . $atributo;
                if (!$muestra->$tieneAtributo) continue;

                $valorAtributo = "valor_{$atributo}";

                // Obtener todas las calificaciones donde se evaluó esta muestra específica
                $calificaciones = DB::table('calificaciones')
                    ->where('producto', $producto_id)
                    ->where('prueba', 3)
                    ->where('fecha', $fecha)
                    ->where('cabina', $cabina)
                    ->where('cod_muestra', $muestra->cod_muestra) // Aquí está la clave
                    ->whereNotNull($valorAtributo)
                    ->select($valorAtributo)
                    ->get();

                if ($calificaciones->isNotEmpty()) {
                    $total = $calificaciones->sum($valorAtributo);
                    $count = $calificaciones->count();

                    $resultados[] = [
                        'atributo' => ucfirst($atributo),
                        'cod_muestra' => $muestra->cod_muestra,
                        'promedio' => round($total / $count, 2),
                        'total_evaluaciones' => $count
                    ];
                }
            }
        }

        // Ordenar resultados por promedio dentro de cada atributo
        return collect($resultados)
            ->groupBy('atributo')
            ->map(function ($grupo) {
                return $grupo->sortBy('promedio')->values();
            });
    }

    private function procesarResultadosTriangulares($producto_id, $fecha, $cabina)
    {
        $calificaciones = Calificacion::where('producto', $producto_id)
            ->where('prueba', 1) // Prueba triangular
            ->where('fecha', $fecha)
            ->where('cabina', $cabina)
            ->get();

        // Agrupar por muestra seleccionada
        $muestrasSeleccionadas = $calificaciones->groupBy('cod_muestra');

        foreach ($muestrasSeleccionadas as $cod_muestra => $grupo) {
            Resultado::create([
                'producto' => $producto_id,
                'prueba' => 1,
                'cod_muestra' => $cod_muestra,
                'resultado' => count($grupo), // Número de veces que fue seleccionada
                'fecha' => $fecha,
                'cabina' => $cabina
            ]);
        }
    }

    private function procesarResultadosDuoTrio($producto_id, $fecha, $cabina)
    {
        $calificaciones = Calificacion::where('producto', $producto_id)
            ->where('prueba', 2) // Prueba duo-trio
            ->where('fecha', $fecha)
            ->where('cabina', $cabina)
            ->get();

        // Agrupar por muestra seleccionada
        $muestrasSeleccionadas = $calificaciones->groupBy('cod_muestra');

        foreach ($muestrasSeleccionadas as $cod_muestra => $grupo) {
            Resultado::create([
                'producto' => $producto_id,
                'prueba' => 2,
                'cod_muestra' => $cod_muestra,
                'resultado' => count($grupo), // Número de veces que fue seleccionada
                'fecha' => $fecha,
                'cabina' => $cabina
            ]);
        }
    }

    private function obtenerResultadosTriangulares($producto_id, $fecha, $cabina)
    {
        return Resultado::where('producto', $producto_id)
            ->where('prueba', 1)
            ->where('fecha', $fecha)
            ->where('cabina', $cabina)
            ->orderBy('resultado', 'desc')
            ->get();
    }

    private function obtenerResultadosDuoTrio($producto_id, $fecha, $cabina)
    {
        return Resultado::where('producto', $producto_id)
            ->where('prueba', 2)
            ->where('fecha', $fecha)
            ->where('cabina', $cabina)
            ->orderBy('resultado', 'desc')
            ->get();
    }
}
