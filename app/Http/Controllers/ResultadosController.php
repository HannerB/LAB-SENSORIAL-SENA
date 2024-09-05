<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Resultado;
use App\Models\Muestra;
use App\Models\Calificacion;

class ResultadosController extends Controller
{
    public function mostrarResultados(Request $request)
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

            // Obtener los resultados generados
            $resultados = Resultado::where('producto', $productoId)
                ->where('fecha', $fecha)
                ->get();

            // Organizar los resultados por tipo de prueba
            $triangulares = $resultados->where('prueba', 1)->values();
            $duoTrio = $resultados->where('prueba', 2)->values();
            $ordenamiento = $resultados->where('prueba', 3)->sortBy('resultado')->values();

            $data = [
                'triangulares' => $triangulares,
                'duoTrio' => $duoTrio,
                'ordenamiento' => $ordenamiento
            ];

            DB::commit();
            Log::info('Finalizando generación de resultados');
            return response()->json(['message' => 'Resultados generados exitosamente', 'data' => $data]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al generar resultados: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al generar los resultados: ' . $e->getMessage()], 500);
        }
    }

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
                $primeraCalificacion = $calificacionesOrdenamiento->first();
                $secuenciaOrdenamiento = $primeraCalificacion->cod_muestras;

                $resultadoOrdenamiento = Resultado::updateOrCreate(
                    [
                        'producto' => $productoId,
                        'prueba' => 3,
                        'fecha' => $fecha
                    ],
                    [
                        'cod_muestra' => $secuenciaOrdenamiento,
                        'atributo' => 'Dulzura',
                        'resultado' => $calificacionesOrdenamiento->count(),
                        'cabina' => 1
                    ]
                );

                $resultadosFormateados[] = $resultadoOrdenamiento->toArray();
            }

            // Organizar los resultados por tipo de prueba
            $triangulares = collect($resultadosFormateados)->where('prueba', 1)->values();
            $duoTrio = collect($resultadosFormateados)->where('prueba', 2)->values();
            $ordenamiento = collect($resultadosFormateados)->where('prueba', 3)->values();

            // Para la vista, tomamos solo la primera muestra del ordenamiento
            $muestraMasVotada = null;
            if ($ordenamiento->isNotEmpty()) {
                $primerasMuestras = explode(',', $ordenamiento->first()['cod_muestra']);
                $muestraMasVotada = ['cod_muestra' => $primerasMuestras[0]];
            }

            $data = [
                'triangulares' => $triangulares,
                'duoTrio' => $duoTrio,
                'ordenamiento' => $ordenamiento,
                'muestraMasVotada' => $muestraMasVotada
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
}
