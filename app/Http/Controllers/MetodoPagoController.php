<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use App\Models\MetodoGasto;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $metodosGasto = MetodoGasto::all();
        $metodosPago = MetodoPago::orderBy('id')->get();
        return view('admin.configuracion.metodopago.index', compact('metodosPago','metodosGasto'));
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
            'descripcion' => 'required|string|max:150',
            'condicion_pago' => 'required|in:Contado,Crédito',
        ]);

        $metodoPago = new MetodoPago();
        $metodoPago->codigo = $request->codigo;
        $metodoPago->descripcion = $request->descripcion;
        $metodoPago->condicion_pago = $request->condicion_pago;
        $metodoPago->save();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MetodoPago $metodoPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetodoPago $metodoPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MetodoPago $metodopago)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
            'descripcion' => 'required|string|max:150',
            'condicion_pago' => 'required|in:Contado,Crédito',
        ]);

        $metodopago->codigo = $request->codigo;
        $metodopago->descripcion = $request->descripcion;
        $metodopago->condicion_pago = $request->condicion_pago;
        $metodopago->save();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetodoPago $metodopago)
    {
        $metodopago->delete();
        return redirect()
            ->route('configuracion.metodopago')
            ->with('success', 'Método de gasto eliminado correctamente.');
    }
}
