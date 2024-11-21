<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacion;

class CalificacionesController extends Controller
{


    public function create()
    {
        return view('calificacion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idpane' => 'nullable|exists:panelistas,idpane',
            'producto' => 'required|exists:productos,id_producto',
            'prueba' => 'required|integer',
            'atributo' => 'nullable|string|max:50',
            'cod_muestras' => 'required|string|max:250',
            'comentario' => 'nullable|string|max:250',
            'fecha' => 'nullable|date',
            'cabina' => 'required|integer',
        ]);

        // Asignar un valor por defecto al atributo si no se proporciona
        $data['atributo'] = $data['atributo'] ?? '';

        Calificacion::create($data);

        return redirect()->route('index');
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
