<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muestra;

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
        $request->validate([
            'cod_muestra' => 'required|string|max:50',
            'prueba' => 'required|integer',
            'atributo' => 'nullable|string|max:250',
            'producto_id' => 'required|exists:productos,id_producto'
        ]);

        // Si es una muestra de ordenamiento y no se especificó un atributo,
        // buscar el atributo de otras muestras de ordenamiento del mismo producto
        if ($request->input('prueba') == 3 && empty($request->input('atributo'))) {
            $atributoExistente = Muestra::where('producto_id', $request->input('producto_id'))
                ->where('prueba', 3)
                ->whereNotNull('atributo')
                ->value('atributo');

            $atributo = $atributoExistente ?: $request->input('atributo');
        } else {
            $atributo = $request->input('atributo');
        }

        $muestra = Muestra::create([
            'cod_muestra' => $request->input('cod_muestra'),
            'prueba' => $request->input('prueba'),
            'atributo' => $atributo,
            'producto_id' => $request->input('producto_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Muestra guardada correctamente.',
            'muestra' => $muestra
        ], 201);
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
        $muestrasTriangular = Muestra::where('producto_id', $id)->where('prueba', 1)->get();
        $muestrasDuoTrio = Muestra::where('producto_id', $id)->where('prueba', 2)->get();
        $muestrasOrdenamiento = Muestra::where('producto_id', $id)->where('prueba', 3)->get();

        return response()->json([
            'triangular' => $muestrasTriangular,
            'duo_trio' => $muestrasDuoTrio,
            'ordenamiento' => $muestrasOrdenamiento
        ]);
    }

    public function actualizarAtributo(Request $request)
    {
        try {
            $request->validate([
                'producto_id' => 'required|exists:productos,id_producto',
                'atributo' => 'required|string|in:Sabor,Olor,Color,Textura,Apariencia'
            ]);

            $productoId = $request->input('producto_id');
            $atributo = $request->input('atributo');

            // Actualizar todas las muestras de ordenamiento para este producto
            Muestra::where('producto_id', $productoId)
                ->where('prueba', 3) // Solo muestras de ordenamiento
                ->update(['atributo' => $atributo]);

            return response()->json([
                'success' => true,
                'message' => 'Atributo actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el atributo: ' . $e->getMessage()
            ], 500);
        }
    }
}
