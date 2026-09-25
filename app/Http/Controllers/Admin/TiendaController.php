<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use Illuminate\Http\Request;

/** Vendedores del marketplace. */
class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::withCount('productos')
            ->withSum('productos as unidades_vendidas', 'vendidos')
            ->orderBy('nombre')->get();

        return view('admin.vendedores.index', compact('tiendas'));
    }

    public function store(Request $request)
    {
        Tienda::create($this->validar($request));

        return back()->with('success', 'Vendedor registrado correctamente.');
    }

    public function update(Request $request, Tienda $tienda)
    {
        $tienda->update($this->validar($request));

        return back()->with('success', 'Vendedor actualizado correctamente.');
    }

    public function destroy(Tienda $tienda)
    {
        $tienda->delete();

        return back()->with('success', 'Vendedor eliminado correctamente.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'ruc' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'telefono' => 'nullable|string|max:30',
            'comision' => 'required|numeric|min:0|max:100',
        ]);
        $data['verificada'] = $request->boolean('verificada');
        $data['activo'] = $request->boolean('activo');

        return $data;
    }
}
