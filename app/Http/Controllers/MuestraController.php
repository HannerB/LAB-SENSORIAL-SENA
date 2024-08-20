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

        $muestra = Muestra::create([
            'cod_muestra' => $request->input('cod_muestra'),
            'prueba' => $request->input('prueba'),
            'atributo' => $request->input('atributo'),
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

    public function destroy(Muestra $muestra)
    {
        $muestra->delete();
        return redirect()->route('muestra.index');
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
}
