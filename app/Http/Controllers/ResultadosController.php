<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resultado;

class ResultadoController extends Controller
{
    public function index()
    {
        $resultados = Resultado::all();
        return view('resultado.index', compact('resultados'));
    }

    public function create()
    {
        return view('resultado.create');
    }

    public function store(Request $request)
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

        Resultado::create($data);
        return redirect()->route('resultado.index');
    }

    public function edit(Resultado $resultado)
    {
        return view('resultado.edit', compact('resultado'));
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
}
