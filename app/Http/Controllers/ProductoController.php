<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all(); // Obtener todos los productos desde la base de datos
        return view('src.panel_administracion', compact('productos')); // Pasar la variable $productos a la vista
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

    $producto = Producto::create($data);

    $productos = Producto::all(); // Obtener todos los productos desde la base de datos

    return view('src.panel_administracion', compact('productos')); // Retornar los datos del producto creado como respuesta AJAX
}

    public function edit(Producto $producto)
    {
        return view('producto.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
        ]);

        $producto->update($data);
        return redirect()->route('producto.index');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('producto.index');
    }
}
