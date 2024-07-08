<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function index()
    {
        return view('pruebas.index');
    }

    public function guardarTri(Request $request)
    {
        // Lógica para guardar datos de la prueba de triángulo
        return response()->json(['success' => true]);
    }

    public function guardarDuo(Request $request)
    {
        // Lógica para guardar datos de la prueba de duo-trio
        return response()->json(['success' => true]);
    }

    public function guardarOrden(Request $request)
    {
        // Lógica para guardar datos de la prueba de ordenamiento
        return response()->json(['success' => true]);
    }
}
