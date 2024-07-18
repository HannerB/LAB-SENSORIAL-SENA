<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuraciones = Configuracion::all();
        return view('configuracion.index', compact('configuraciones'));
    }

    public function create()
    {
        return view('configuracion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'num_cabina' => 'required|integer',
            'producto_habilitado' => 'nullable|exists:productos,id_producto',
            'clave_acceso' => 'required|string|max:250',
        ]);

        Configuracion::create($data);
        return redirect()->route('configuracion.index');
    }

    public function edit(Configuracion $configuracion)
    {
        return view('configuracion.edit', compact('configuracion'));
    }

    public function update(Request $request, Configuracion $configuracion)
    {
        $data = $request->validate([
            'num_cabina' => 'required|integer',
            'producto_habilitado' => 'nullable|exists:productos,id_producto',
            'clave_acceso' => 'required|string|max:250',
        ]);

        $configuracion->update($data);
        return redirect()->route('configuracion.index');
    }

    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();
        return redirect()->route('configuracion.index');
    }
}

