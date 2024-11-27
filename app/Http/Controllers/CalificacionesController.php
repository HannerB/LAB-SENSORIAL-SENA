<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacion;
use App\Models\Muestra;
use Illuminate\Support\Facades\DB;

class CalificacionesController extends Controller
{


    public function create()
    {
        return view('calificacion.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->prueba == 3) {
                // Para pruebas de ordenamiento, crear una calificación por cada atributo
                $muestra = Muestra::where('producto_id', $request->producto)
                    ->where('prueba', 3)
                    ->first();

                if (!$muestra) {
                    throw new \Exception('No se encontró la muestra de ordenamiento');
                }

                foreach (['sabor', 'olor', 'color', 'textura', 'apariencia'] as $atributo) {
                    $tieneAtributo = 'tiene_' . $atributo;
                    if ($muestra->$tieneAtributo) {
                        Calificacion::create([
                            'idpane' => $request->idpane,
                            'producto' => $request->producto,
                            'prueba' => $request->prueba,
                            'cod_muestras' => $request->cod_muestras,
                            'comentario' => $request->comentario,
                            'fecha' => $request->fecha,
                            'cabina' => $request->cabina,
                            'atributo_evaluado' => $atributo
                        ]);
                    }
                }
            } else {
                // Para pruebas triangular y duo-trio
                Calificacion::create([
                    'idpane' => $request->idpane,
                    'producto' => $request->producto,
                    'prueba' => $request->prueba,
                    'cod_muestras' => $request->cod_muestras,
                    'comentario' => $request->comentario,
                    'fecha' => $request->fecha,
                    'cabina' => $request->cabina
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar calificación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Calificacion $calificacion)
    {
        return view('calificacion.edit', compact('calificacion'));
    }

    public function update(Request $request, Calificacion $calificacion)
    {
        $data = $request->validate([
            'idpane' => 'nullable|exists:panelistas,idpane',
            'producto' => 'required|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'required|string|max:50',
            'cod_muestras' => 'required|string|max:250',
            'comentario' => 'nullable|string|max:250',
            'fecha' => 'nullable|date',
            'cabina' => 'required|integer',
        ]);

        $calificacion->update($data);
        return redirect()->route('calificacion.index');
    }

    public function destroy(Calificacion $calificacion)
    {
        $calificacion->delete();
        return redirect()->route('calificacion.index');
    }
}
