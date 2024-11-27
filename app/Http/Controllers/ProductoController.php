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

    public function update(Request $request)
    {
        $data = $request->validate([
            'productos' => 'required|array',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.nombre' => 'required|string|max:50',
        ]);

        foreach ($data['productos'] as $productoData) {
            $producto = Producto::findOrFail($productoData['id_producto']);
            $producto->update(['nombre' => $productoData['nombre']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Productos actualizados correctamente.',
        ]);
    }


    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('producto.index');
    }
}
