<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::withCount('productos')->orderBy('nombre')->get();

        return view('admin.catalogo.marcas', compact('marcas'));
    }

    public function store(Request $request)
    {
        Marca::create($this->validar($request));

        return back()->with('success', 'Marca registrada correctamente.');
    }

    public function update(Request $request, Marca $marca)
    {
        $marca->update($this->validar($request));

        return back()->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return back()->with('success', 'Marca eliminada correctamente.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate(['nombre' => 'required|string|max:120']);
        $data['activo'] = $request->boolean('activo');

        return $data;
    }
}
