<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unidades = Unidad::all();
        return view('admin.configuracion.unidad.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:100',
            'simbolo' => 'nullable|string|max:10',
            'estado' => 'required|in:Si,No',
        ]);

        $unidad = new Unidad();
        $unidad->codigo = $request->codigo;
        $unidad->descripcion = $request->descripcion;
        $unidad->simbolo = $request->simbolo;
        $unidad->estado = $request->estado;
        $unidad->save();
        return redirect()
            ->route('configuracion.unidad')
            ->with('success', 'Unidad registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unidad $unidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unidad $unidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unidad $unidad)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:100',
            'simbolo' => 'nullable|string|max:10',
            'estado' => 'required|in:Si,No',
        ]);

        $unidad->codigo = $request->codigo;
        $unidad->descripcion = $request->descripcion;
        $unidad->simbolo = $request->simbolo;
        $unidad->estado = $request->estado;
        $unidad->save();
        return redirect()
            ->route('configuracion.unidad')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unidad $unidad)
    {
        $unidad->delete();
        return redirect()
            ->route('configuracion.unidad')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}
