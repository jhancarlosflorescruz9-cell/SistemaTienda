<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Resena;
use Illuminate\Http\Request;

/** Vitrina pública de la tienda virtual. */
class TiendaController extends Controller
{
    public function inicio()
    {
        $categorias = Categoria::activas()->whereNull('parent_id')->withCount(['productos' => fn ($q) => $q->publicados()])->get();

        $flash = Promocion::vigentes()->where('tipo', 'flash')->orderBy('fecha_fin')->first();
        $productosFlash = $flash
            ? $flash->productos()->with('promociones')->publicados()->limit(12)->get()
            : collect();

        $masVendidos = Producto::with('promociones')->publicados()->orderByDesc('vendidos')->limit(10)->get();
        $recomendados = Producto::with('promociones')->publicados()
            ->orderByDesc('destacado')->latest()->paginate(20);

        return view('tienda.inicio', compact('categorias', 'flash', 'productosFlash', 'masVendidos', 'recomendados'));
    }

    /** Listado con búsqueda, categoría, filtros y orden. */
    public function catalogo(Request $request, ?Categoria $categoria = null)
    {
        $ids = $categoria ? $categoria->hijas()->pluck('id')->push($categoria->id) : null;

        $productos = Producto::with('promociones')->publicados()
            ->when($ids, fn ($q) => $q->whereIn('categoria_id', $ids))
            ->when($request->q, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('nombre', 'like', "%{$b}%")->orWhere('descripcion', 'like', "%{$b}%")))
            ->when($request->boolean('envio_gratis'), fn ($q) => $q->where('envio_gratis', true))
            ->when($request->min, fn ($q, $v) => $q->where('precio', '>=', $v))
            ->when($request->max, fn ($q, $v) => $q->where('precio', '<=', $v))
            ->when($request->orden, function ($q, $orden) {
                return match ($orden) {
                    'precio_asc' => $q->orderBy('precio'),
                    'precio_desc' => $q->orderByDesc('precio'),
                    'nuevos' => $q->latest(),
                    'valorados' => $q->orderByDesc('calificacion'),
                    default => $q->orderByDesc('vendidos'),
                };
            }, fn ($q) => $q->orderByDesc('vendidos'))
            ->paginate(24)
            ->withQueryString();

        $categorias = Categoria::activas()->whereNull('parent_id')->get();

        return view('tienda.catalogo', compact('productos', 'categorias', 'categoria'));
    }

    public function producto(Producto $producto)
    {
        abort_unless($producto->activo, 404);
        $producto->load(['categoria', 'marca', 'tienda', 'promociones']);

        $resenas = $producto->resenas()->where('aprobada', true)->latest()->limit(10)->get();
        $relacionados = Producto::with('promociones')->publicados()
            ->where('categoria_id', $producto->categoria_id)
            ->whereKeyNot($producto->id)
            ->orderByDesc('vendidos')->limit(10)->get();

        return view('tienda.producto', compact('producto', 'resenas', 'relacionados'));
    }

    public function resena(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'autor' => 'required|string|max:120',
            'calificacion' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:1000',
        ]);
        $producto->resenas()->create($data + ['aprobada' => false]);

        return back()->with('success', '¡Gracias! Tu reseña se publicará cuando sea revisada.');
    }
}
