<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'contra' => 'required|string',
        ]);

        $contra = $request->input('contra');

        $configuracion = Configuracion::first();

        if ($configuracion) {
            $contra_config = $configuracion->clave_acceso;

            if ($contra_config == $contra) {
                Session::put('accesoadmin', true);
                return redirect()->route('admin.panel');
            } else {
                return redirect()->back()->with('alerta', 'Clave de acceso incorrecta!');
            }
        } else {
            return redirect()->back()->with('alerta', 'Configuración no encontrada!');
        }
    }
}
