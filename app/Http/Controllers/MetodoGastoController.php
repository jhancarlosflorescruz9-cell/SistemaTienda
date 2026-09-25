<?php

namespace App\Http\Controllers;

use App\Models\MetodoGasto;
use Illuminate\Http\Request;

class MetodoGastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $metodosGasto = MetodoGasto::all();
        return view('admin.configuracion.metodopago.index', compact('metodosGasto'));
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
        'descripcion' => 'required|string|max:150',
        ]);

        $metodoGasto = new MetodoGasto();
        $metodoGasto->descripcion = $request->descripcion;
        $metodoGasto->save();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MetodoGasto $metodoGasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetodoGasto $metodoGasto)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MetodoGasto $metodoGasto)
    {
        $request->validate([
            'descripcion' => 'required|string|max:150',
        ]);

        $metodoGasto->descripcion = $request->descripcion;
        $metodoGasto->save();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetodoGasto $metodoGasto)
    {
        $metodoGasto->delete();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto eliminado correctamente.');
    }
}
