<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\CuentaBancaria;
use App\Models\Moneda;
use Illuminate\Http\Request;

class CuentaBancariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuentasBancarias = CuentaBancaria::with(['banco', 'moneda'])->get();
        $bancos = Banco::all();
        $monedas = Moneda::all();
        return view('admin.configuracion.cuentabancaria.index', compact('bancos','monedas','cuentasBancarias'));
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
            'banco_id' => 'required|exists:bancos,id',
            'descripcion' => 'required|string|max:100',
            'numero' => 'required|string|max:50',
            'moneda_id' => 'required|exists:monedas,id',
            'cci' => 'required|string|max:50',
            'saldo_inicial' => 'required|numeric|min:0',
            'mostrar_comprobante' => 'required|in:Si,No',
        ]);

        $cuentaBancaria = new CuentaBancaria();
        $cuentaBancaria->banco_id = $request->banco_id;
        $cuentaBancaria->descripcion = $request->descripcion;
        $cuentaBancaria->numero = $request->numero;
        $cuentaBancaria->moneda_id = $request->moneda_id;
        $cuentaBancaria->cci = $request->cci;
        $cuentaBancaria->saldo_inicial = $request->saldo_inicial;
        $cuentaBancaria->mostrar_comprobante = $request->mostrar_comprobante;
        $cuentaBancaria->save();

        return redirect()
            ->route('configuracion.cuentabancaria')
            ->with('success', 'Cuenta bancaria registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CuentaBancaria $cuentaBancaria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CuentaBancaria $cuentaBancaria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CuentaBancaria $cuentabancaria)
    
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'descripcion' => 'required|string|max:255',
            'numero' => 'required',
            'moneda_id' => 'required|exists:monedas,id',
            'cci' => 'required',
            'saldo_inicial' => 'required|numeric|min:0',
            'mostrar_comprobante' => 'required|in:Si,No',
        ]);

        $cuentabancaria->banco_id = $request->banco_id;
        $cuentabancaria->descripcion = $request->descripcion;
        $cuentabancaria->numero = $request->numero;
        $cuentabancaria->moneda_id = $request->moneda_id;
        $cuentabancaria->cci = $request->cci;
        $cuentabancaria->saldo_inicial = $request->saldo_inicial;
        $cuentabancaria->mostrar_comprobante = $request->mostrar_comprobante;

        $cuentabancaria->save();

        return redirect()
            ->route('configuracion.cuentabancaria')
            ->with('success', 'Cuenta bancaria actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CuentaBancaria $cuentabancaria)
    {
        $cuentabancaria->delete();
        return redirect()
        ->route('configuracion.cuentabancaria')
        ->with('success', 'Cuenta bancaria eliminado correctamente.');
    }
}
