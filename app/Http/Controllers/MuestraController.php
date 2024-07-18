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
        $data = $request->validate([
            'cod_muestra' => 'required|string|max:50',
            'id_producto' => 'nullable|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'required|string|max:250',
        ]);

        Muestra::create($data);
        return redirect()->route('muestra.index');
    }

    public function edit(Muestra $muestra)
    {
        return view('muestra.edit', compact('muestra'));
    }

    public function update(Request $request, Muestra $muestra)
    {
        $data = $request->validate([
            'cod_muestra' => 'required|string|max:50',
            'id_producto' => 'nullable|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'required|string|max:250',
        ]);

        $muestra->update($data);
        return redirect()->route('muestra.index');
    }

    public function destroy(Muestra $muestra)
    {
        $muestra->delete();
        return redirect()->route('muestra.index');
    }
}
