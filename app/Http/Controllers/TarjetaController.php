<?php

namespace App\Http\Controllers;

use App\Models\Tarjeta;
use Illuminate\Http\Request;

class TarjetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarjetas = Tarjeta::all();
        return view('admin.configuracion.tarjeta.index', compact('tarjetas'));
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
            'codigo' => 'required|string|max:255|unique:tarjetas,codigo', 
            'descripcion' => 'required|string|max:255', 
        ]); 
        $tarjeta = new Tarjeta(); 
        $tarjeta->codigo = $request->codigo; 
        $tarjeta->descripcion = $request->descripcion; 
        $tarjeta->save(); 
        return redirect() ->route('configuracion.tarjeta') 
        ->with('success', 'Tarjeta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarjeta $tarjeta)
    {
        $request->validate([ 
            'codigo' => 'required|string|max:255|unique:tarjetas,codigo,' . $tarjeta->id, 
            'descripcion' => 'required|string|max:255', 
        ]); 

        $tarjeta->codigo = $request->codigo; 
        $tarjeta->descripcion = $request->descripcion; 
        $tarjeta->save(); 
        return redirect() ->route('configuracion.tarjeta') 
        ->with('success', 'Tarjeta actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarjeta $tarjeta)
    {
        $tarjeta->delete();
        return redirect()
        ->route('configuracion.tarjeta')
        ->with('success', 'Tarjeta eliminada correctamente.');
    }
}
