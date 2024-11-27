<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muestra;
use Illuminate\Support\Facades\DB;

class MuestraController extends Controller
{
    public function index()
    {
        $muestras = Muestra::all();
        return view('muestra.index', compact('muestras'));
    }

    public function create()
    {
        return view('muestra.create');
    }

    public function store(Request $request)
    {
        try {
            $muestra = new Muestra();
            $muestra->cod_muestra = $request->cod_muestra;
            $muestra->producto_id = $request->producto_id;
            $muestra->prueba = $request->prueba;

            // Manejar atributos para prueba de ordenamiento
            if ($request->prueba == 3) {
                $atributos = $request->input('atributos', []);
                $muestra->tiene_sabor = in_array('sabor', $atributos);
                $muestra->tiene_olor = in_array('olor', $atributos);
                $muestra->tiene_color = in_array('color', $atributos);
                $muestra->tiene_textura = in_array('textura', $atributos);
                $muestra->tiene_apariencia = in_array('apariencia', $atributos);

                // Validar que al menos un atributo esté seleccionado
                if (count($atributos) == 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe seleccionar al menos un atributo para la prueba de ordenamiento'
                    ], 422);
                }
            }

            $muestra->save();

            return response()->json([
                'success' => true,
                'message' => 'Muestra creada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la muestra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Muestra $muestra)
    {
        return view('muestra.edit', compact('muestra'));
    }

    public function update(Request $request, Muestra $muestra)
    {
        $data = $request->validate([
            'cod_muestra' => 'required|string|max:50',
            'producto_id' => 'nullable|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'nullable|string|max:250',
        ]);

        $muestra->update($data);
        return redirect()->route('muestra.index');
    }

    public function destroy($id)
    {
        try {
            $muestra = Muestra::findOrFail($id);
            $muestra->delete();

            return response()->json([
                'success' => true,
                'message' => 'Muestra eliminada correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La muestra no fue encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la muestra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMuestrasByProducto($id)
    {
        try {
            $muestras = [
                'triangular' => Muestra::where('producto_id', $id)
                    ->where('prueba', 1)
                    ->get(),
                'duo_trio' => Muestra::where('producto_id', $id)
                    ->where('prueba', 2)
                    ->get(),
                'ordenamiento' => Muestra::where('producto_id', $id)
                    ->where('prueba', 3)
                    ->get()
            ];

            return response()->json($muestras);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function actualizarAtributo(Request $request)
    {
        DB::beginTransaction();
        try {
            $productoId = $request->producto_id;
            $atributos = $request->atributos;

            // Actualizar todas las muestras de ordenamiento del producto
            $muestras = Muestra::where('producto_id', $productoId)
                ->where('prueba', 3)
                ->get();

            foreach ($muestras as $muestra) {
                $muestra->tiene_sabor = in_array('sabor', $atributos);
                $muestra->tiene_olor = in_array('olor', $atributos);
                $muestra->tiene_color = in_array('color', $atributos);
                $muestra->tiene_textura = in_array('textura', $atributos);
                $muestra->tiene_apariencia = in_array('apariencia', $atributos);
                $muestra->save();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Atributos actualizados exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los atributos: ' . $e->getMessage()
            ], 500);
        }
    }
}
