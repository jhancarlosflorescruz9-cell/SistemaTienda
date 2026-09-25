<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('padre')->withCount('productos')->orderBy('orden')->orderBy('nombre')->get();

        return view('admin.catalogo.categorias', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['slug'] = $this->slugUnico($data['nombre']);
        Categoria::create($data);

        return back()->with('success', 'Categoría registrada correctamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $this->validar($request, $categoria);
        if ($categoria->nombre !== $data['nombre']) {
            $data['slug'] = $this->slugUnico($data['nombre'], $categoria->id);
        }
        $categoria->update($data);

        return back()->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return back()->with('error', 'No se puede eliminar: la categoría tiene productos asociados.');
        }
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada correctamente.');
    }

    private function validar(Request $request, ?Categoria $categoria = null): array
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:120',
            'parent_id' => ['nullable', 'exists:categorias,id', Rule::notIn([$categoria?->id])],
            'icono' => 'nullable|string|max:60',
            'color' => 'nullable|string|max:20',
            'orden' => 'nullable|integer|min:0',
        ]);
        $data['icono'] = $data['icono'] ?: 'fa-solid fa-tag';
        $data['color'] = $data['color'] ?: '#fb7701';
        $data['orden'] = $data['orden'] ?? 0;
        $data['activo'] = $request->boolean('activo');

        return $data;
    }

    private function slugUnico(string $nombre, ?int $ignorar = null): string
    {
        $base = Str::slug($nombre) ?: 'categoria';
        $slug = $base;
        $i = 2;
        while (Categoria::where('slug', $slug)->when($ignorar, fn ($q) => $q->where('id', '!=', $ignorar))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
