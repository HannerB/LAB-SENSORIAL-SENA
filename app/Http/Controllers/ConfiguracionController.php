<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Session;

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

    public function update(Request $request, $id,)
    {
        $configuracion = Configuracion::findOrFail($id);
        $configuracion->producto_habilitado = $request->producto_habilitado;
        $configuracion->save();

        // return response()->json(['success' => true, 'message' => 'Configuración actualizada correctamente']);
        return redirect()->route('admin.panel')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();
        return redirect()->route('configuracion.index');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $password = $request->input('password');

        // Buscar la configuración en la base de datos
        $configuracion = Configuracion::first();

        if ($configuracion) {
            $clave_acceso = $configuracion->clave_acceso;

            if ($password === $clave_acceso) {
                // Autenticación exitosa
                Session::put('accesoadmin', true);
                return redirect()->route('admin.resultados'); // Redirige a la ruta del panel administrativo
            } else {
                // Contraseña incorrecta
                return redirect()->back()->with('alerta', 'Clave de acceso incorrecta!');
            }
        } else {
            // No se encontró la configuración
            return redirect()->back()->with('alerta', 'Configuración no encontrada!');
        }
    }
}
