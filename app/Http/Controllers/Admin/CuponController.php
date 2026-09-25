<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CuponController extends Controller
{
    public function index()
    {
        $cupones = Cupon::latest()->get();

        return view('admin.marketing.cupones', compact('cupones'));
    }

    public function store(Request $request)
    {
        Cupon::create($this->validar($request));

        return back()->with('success', 'Cupón registrado correctamente.');
    }

    public function update(Request $request, Cupon $cupon)
    {
        $cupon->update($this->validar($request, $cupon));

        return back()->with('success', 'Cupón actualizado correctamente.');
    }

    public function destroy(Cupon $cupon)
    {
        $cupon->delete();

        return back()->with('success', 'Cupón eliminado correctamente.');
    }

    private function validar(Request $request, ?Cupon $cupon = null): array
    {
        $request->merge(['codigo' => strtoupper(trim((string) $request->codigo))]);
        $data = $request->validate([
            'codigo' => ['required', 'alpha_dash', 'max:40', Rule::unique('cupones')->ignore($cupon)],
            'tipo' => 'required|in:porcentaje,monto',
            'valor' => ['required', 'numeric', 'min:0.01', $request->tipo === 'porcentaje' ? 'max:100' : 'max:99999'],
            'minimo_compra' => 'nullable|numeric|min:0',
            'usos_maximos' => 'nullable|integer|min:1',
            'fecha_fin' => 'nullable|date',
        ]);
        $data['minimo_compra'] = $data['minimo_compra'] ?? 0;
        $data['activo'] = $request->boolean('activo');

        return $data;
    }
}
