<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bancos = Banco::all();
        return view('admin.configuracion.banco.index', compact('bancos'));
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
            'descripcion' => 'required|string|max:255',
        ]);

        $banco = new Banco();
        $banco->descripcion = $request->descripcion;
        $banco->save();

        return redirect()->route('configuracion.banco')
            ->with('success', 'Banco registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banco $banco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banco $banco)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banco $banco)
    {
        $request->validate([
        'descripcion' => 'required|string|max:255',
        ]);

        $banco->descripcion = $request->descripcion;
        $banco->save();

        return redirect()
            ->route('configuracion.banco')
            ->with('success', 'Banco actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banco $banco)
    {
        $banco->delete();
        return redirect()
        ->route('configuracion.banco')
        ->with('success', 'Banco eliminado correctamente.');
    }
}
