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
                // Sin cambios para ordenamiento
                Calificacion::create([
                    'idpane' => $request->idpane,
                    'producto' => $request->producto,
                    'prueba' => $request->prueba,
                    'cod_muestra' => $request->cod_muestra,
                    'valor_sabor' => $request->valor_sabor,
                    'valor_olor' => $request->valor_olor,
                    'valor_color' => $request->valor_color,
                    'valor_textura' => $request->valor_textura,
                    'valor_apariencia' => $request->valor_apariencia,
                    'comentario' => $request->comentario,
                    'fecha' => $request->fecha,
                    'cabina' => $request->cabina
                ]);
            } else {
                // Modificado para triangular y duo-trio
                $calificacion = new Calificacion([
                    'idpane' => $request->idpane,
                    'producto' => $request->producto,
                    'prueba' => $request->prueba,
                    'cod_muestra' => $request->cod_muestra,
                    'comentario' => $request->comentario,
                    'fecha' => $request->fecha,
                    'cabina' => $request->cabina
                ]);

                if ($request->prueba == 1) {
                    $calificacion->es_diferente = true;
                } else if ($request->prueba == 2) {
                    $calificacion->es_igual_referencia = true;
                }

                $calificacion->save();
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
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
