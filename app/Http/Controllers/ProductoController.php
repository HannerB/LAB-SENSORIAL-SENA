<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Configuracion;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        $configuracion = Configuracion::first();
        return view('admin.panel', compact('productos', 'configuracion'));
    }

    public function create()
    {
        return view('producto.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
        ]);

        Producto::create($data);

        return redirect()->route('admin.panel')->with('success', 'Producto agregado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('producto.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente'
        ]);
    }   


    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('producto.index');
    }
}
