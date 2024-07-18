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
        $contra = $request->input('contra');

        $configuracion = Configuracion::find(1);

        if ($configuracion) {
            $contra_config = $configuracion->clave_acceso;

            if ($contra_config == $contra) {
                Session::put('accesoadmin', true);
                return redirect()->route('admin.panel');
            } else {
                $alerta = "Clave de acceso incorrecta!";
                return redirect()->back()->with('alerta', $alerta);
            }
        }
    }
}
