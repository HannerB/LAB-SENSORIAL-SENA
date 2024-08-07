<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panelista;

class PanelistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panelistas = Panelista::all();
        return view('panelistas.index', compact('panelistas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('panelistas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:50',
            'fecha' => 'required|date',
        ]);

        Panelista::create($request->all());

        return redirect()->route('index')->with('success', 'Panelista creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Panelista $panelista)
    {
        return view('panelistas.show', compact('panelista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Panelista $panelista)
    {
        return view('panelistas.edit', compact('panelista'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Panelista $panelista)
    {
        $request->validate([
            'nombres' => 'required|string|max:50',
        ]);

        $panelista->update($request->all());

        return redirect()->route('panelistas.index')
                         ->with('success', 'Panelista actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Panelista $panelista)
    {
        $panelista->delete();

        return redirect()->route('panelistas.index')
                         ->with('success', 'Panelista eliminado exitosamente.');
    }
}
