<?php

namespace App\Http\Controllers;

use App\Models\Detraccion;
use Illuminate\Http\Request;

class DetraccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detracciones = Detraccion::all();
        return view('admin.configuracion.detraccion.index', compact('detracciones'));
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
            'tipo_operacion' => 'required|in:1001,1004',
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:150',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'estado' => 'required|in:Si,No',
        ]);

        $detraccion = new Detraccion();
        $detraccion->tipo_operacion = $request->tipo_operacion;
        $detraccion->codigo = $request->codigo;
        $detraccion->descripcion = $request->descripcion;
        $detraccion->porcentaje = $request->porcentaje;
        $detraccion->estado = $request->estado;

        $detraccion->save();

        return redirect()
            ->route('configuracion.detraccion')
            ->with('success', 'Detracción registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Detraccion $detraccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Detraccion $detraccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Detraccion $detraccion)
    {
        $request->validate([
            'tipo_operacion' => 'required|in:1001,1004',
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:150',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'estado' => 'required|in:Si,No',
        ]);

        $detraccion->tipo_operacion = $request->tipo_operacion;
        $detraccion->codigo = $request->codigo;
        $detraccion->descripcion = $request->descripcion;
        $detraccion->porcentaje = $request->porcentaje;
        $detraccion->estado = $request->estado;
        $detraccion->save();
        return redirect()
            ->route('configuracion.detraccion')
            ->with('success', 'Detracción actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Detraccion $detraccion)
    {
        $detraccion->delete();
        return redirect()
        ->route('configuracion.detraccion')
        ->with('success', 'Detraccion eliminado correctamente.');
    }
}
