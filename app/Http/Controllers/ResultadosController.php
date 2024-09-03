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

            // ... (resto del código sin cambios hasta la obtención de resultados)

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

            // Obtener todos los panelistas que participaron en la evaluación
            $panelistas = Calificacion::where('producto', $productoId)
                ->whereDate('created_at', $fecha)
                ->select('idpane') // Usa el nombre del campo correcto
                ->distinct()
                ->get();

            Log::info("Panelistas encontrados: " . $panelistas->count());

            // Eliminamos los resultados existentes para este producto y fecha
            Resultado::where('producto', $productoId)
                ->where('fecha', $fecha)
                ->delete();

            // Crear resultados para todos los panelistas y todas las muestras
            foreach ($panelistas as $panelista) {
                foreach ($muestras as $muestra) {
                    // Crear un resultado para cada combinación de panelista, muestra y atributo
                    Resultado::create([
                        'producto' => $productoId,
                        'prueba' => $muestra->prueba,
                        'atributo' => 'Dulzura',
                        'cod_muestra' => $muestra->cod_muestra,
                        'resultado' => '0',
                        'fecha' => $fecha,
                        'cabina' => $panelista->idpane, // Usa el nombre del campo correcto
                    ]);
                }
            }

            // Obtener las calificaciones para este producto y fecha
            $calificaciones = Calificacion::where('producto', $productoId)
                ->whereDate('created_at', $fecha)
                ->get();

            Log::info("Calificaciones encontradas: " . $calificaciones->count());

            // Actualizar los resultados para las muestras calificadas
            foreach ($calificaciones as $calificacion) {
                Log::info("Calificación: ", $calificacion->toArray()); // Logging detallado de calificación

                Log::info("Actualizando resultados para el panelista: {$calificacion->idpane}, muestra: {$calificacion->cod_muestras}");

                $updatedRows = Resultado::where('producto', $productoId)
                    ->where('cod_muestra', $calificacion->cod_muestras)
                    ->where('fecha', $fecha)
                    ->where('cabina', $calificacion->idpane)
                    ->update(['resultado' => 1]);

                Log::info("Número de filas actualizadas: $updatedRows");
            }

            // Obtener los resultados generados
            $resultados = Resultado::where('producto', $productoId)
                ->where('fecha', $fecha)
                ->get();

            // Organizar los resultados por tipo de prueba
            $triangulares = $resultados->where('prueba', 1)->values();
            $duoTrio = $resultados->where('prueba', 2)->values();
            $ordenamiento = $resultados->where('prueba', 3)->values();

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
}
