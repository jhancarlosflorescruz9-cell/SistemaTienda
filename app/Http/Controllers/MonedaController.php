<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monedas = Moneda::all();
        return view('admin.configuracion.moneda.index', compact('monedas'));
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
        //$datos = request()->all();
        //return response()->json($datos);
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:100',
            'simbolo' => 'required|string|max:10',
            'estado' => 'required|in:Si,No',
        ]);

        $moneda = new Moneda();
        $moneda->codigo = $request->codigo;
        $moneda->descripcion = $request->descripcion;
        $moneda->simbolo = $request->simbolo;
        $moneda->estado = $request->estado;
        $moneda->save();

        return redirect()
            ->route('configuracion.moneda')
            ->with('success', 'Moneda registrada correctamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Moneda $moneda)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Moneda $moneda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Moneda $moneda)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:100',
            'simbolo' => 'required|string|max:10',
            'estado' => 'required|in:Si,No',
        ]);

        $moneda->codigo = $request->codigo;
        $moneda->descripcion = $request->descripcion;
        $moneda->simbolo = $request->simbolo;
        $moneda->estado = $request->estado;

        $moneda->save();

        return redirect()
            ->route('configuracion.moneda')
            ->with('success', 'Moneda actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Moneda $moneda)
    {
        $moneda->delete();
        return redirect()
        ->route('configuracion.moneda')
        ->with('success', 'Moneda eliminado correctamente.');
    }
}
