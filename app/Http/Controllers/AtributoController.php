<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use Illuminate\Http\Request;

class AtributoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $atributos = Atributo::all();
        return view('admin.configuracion.atributo.index', compact('atributos'));
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
            'codigo' => 'required|string|max:20|unique:atributos,codigo', 
            'descripcion' => 'required|string|max:150',
            'estado' => 'required|in:Si,No', 
        ]); 
        $atributo = new Atributo(); 
        $atributo->codigo = $request->codigo; 
        $atributo->descripcion = $request->descripcion; 
        $atributo->estado = $request->estado; 
        $atributo->save(); 
        return redirect() 
        ->route('configuracion.atributo') 
        ->with('success', 'Atributo registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Atributo $atributo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atributo $atributo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atributo $atributo)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:atributos,codigo,' . $atributo->id,
            'descripcion' => 'required|string|max:150',
            'estado' => 'required|in:Si,No',
        ]);

        $atributo->codigo = $request->codigo;
        $atributo->descripcion = $request->descripcion;
        $atributo->estado = $request->estado;
        $atributo->save();
        return redirect()
            ->route('configuracion.atributo')
            ->with('success', 'Atributo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atributo $atributo)
    {
        $atributo->delete();
        return redirect()
        ->route('configuracion.atributo')
        ->with('success', 'Atributo eliminado correctamente.');
    }
}
