<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('src.panel_administracion', compact('productos'));
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
        $producto = Producto::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
        ]);

        $producto->update($data);

        return redirect()->route('admin.panel')->with('success', 'Producto agregado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('producto.index');
    }
}
