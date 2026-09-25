<?php

namespace App\Http\Controllers;

use App\Models\Plataforma;
use Illuminate\Http\Request;

class PlataformaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plataformas = Plataforma::all();
        return view('admin.configuracion.plataforma.index', compact('plataformas'));
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
            'nombre' => 'required|string|max:100',
            'estado' => 'required|in:Si,No', 
        ]); 
        $plataforma = new Plataforma(); 
        $plataforma->nombre = $request->nombre; 
        $plataforma->estado = $request->estado; 
        $plataforma->save(); 

        return redirect() ->route('configuracion.plataforma') 
        ->with('success', 'Plataforma registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plataforma $plataforma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plataforma $plataforma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plataforma $plataforma)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'required|in:Si,No',
        ]);

        $plataforma->nombre = $request->nombre;
        $plataforma->estado = $request->estado;
        $plataforma->save();

        return redirect()
            ->route('configuracion.plataforma')
            ->with('success', 'Plataforma actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plataforma $plataforma)
    {
        $plataforma->delete();
        return redirect()
        ->route('configuracion.plataforma')
        ->with('success', 'Plataforma eliminada correctamente.');
    }
}
