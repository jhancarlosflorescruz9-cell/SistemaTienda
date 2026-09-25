<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::withCount('productos')->with('productos:id')->latest('fecha_inicio')->get();
        $productos = Producto::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'codigo']);

        return view('admin.marketing.promociones', compact('promociones', 'productos'));
    }

    public function store(Request $request)
    {
        [$data, $productos] = $this->validar($request);
        Promocion::create($data)->productos()->sync($productos);

        return back()->with('success', 'Promoción registrada correctamente.');
    }

    public function update(Request $request, Promocion $promocion)
    {
        [$data, $productos] = $this->validar($request);
        $promocion->update($data);
        $promocion->productos()->sync($productos);

        return back()->with('success', 'Promoción actualizada correctamente.');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->delete();

        return back()->with('success', 'Promoción eliminada correctamente.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:flash,temporada,liquidacion',
            'descuento' => 'required|integer|min:1|max:95',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'productos' => 'nullable|array',
            'productos.*' => 'exists:productos,id',
        ]);
        $productos = $data['productos'] ?? [];
        unset($data['productos']);
        $data['activo'] = $request->boolean('activo');

        return [$data, $productos];
    }
}
