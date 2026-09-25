<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with(['categoria', 'marca', 'tienda', 'promociones'])
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('nombre', 'like', "%{$b}%")->orWhere('codigo', 'like', "%{$b}%")))
            ->when($request->categoria, fn ($q, $c) => $q->where('categoria_id', $c))
            ->when($request->estado === 'activos', fn ($q) => $q->where('activo', true))
            ->when($request->estado === 'inactivos', fn ($q) => $q->where('activo', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.catalogo.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        return view('admin.catalogo.productos.form', array_merge(
            ['producto' => new Producto(['activo' => true, 'stock_minimo' => 5, 'codigo' => $this->siguienteCodigo()])],
            $this->catalogos()
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['slug'] = $this->slugUnico($data['nombre']);
        $data['imagen'] = $this->subirImagen($request);
        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.catalogo.productos.form', array_merge(compact('producto'), $this->catalogos()));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $this->validar($request, $producto);
        if ($producto->nombre !== $data['nombre']) {
            $data['slug'] = $this->slugUnico($data['nombre'], $producto->id);
        }
        if ($nueva = $this->subirImagen($request)) {
            $this->borrarImagen($producto);
            $data['imagen'] = $nueva;
        } elseif ($request->boolean('quitar_imagen')) {
            $this->borrarImagen($producto);
            $data['imagen'] = null;
        }
        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->borrarImagen($producto);
        $producto->delete();

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    private function validar(Request $request, ?Producto $producto = null): array
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:40', Rule::unique('productos')->ignore($producto)],
            'nombre' => 'required|string|max:200',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'tienda_id' => 'nullable|exists:tiendas,id',
            'descripcion' => 'nullable|string|max:5000',
            'precio' => 'required|numeric|min:0.1',
            'precio_oferta' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:2048',
        ], [
            'precio_oferta.lt' => 'El precio de oferta debe ser menor al precio normal.',
        ]);
        unset($data['imagen']);
        foreach (['envio_gratis', 'destacado', 'activo'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        return $data;
    }

    private function catalogos(): array
    {
        return [
            'categorias' => Categoria::orderBy('nombre')->get(),
            'marcas' => Marca::orderBy('nombre')->get(),
            'tiendas' => Tienda::orderBy('nombre')->get(),
        ];
    }

    private function siguienteCodigo(): string
    {
        return 'PRD-'.str_pad((string) ((Producto::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }

    private function slugUnico(string $nombre, ?int $ignorar = null): string
    {
        $base = Str::slug($nombre) ?: 'producto';
        $slug = $base;
        $i = 2;
        while (Producto::where('slug', $slug)->when($ignorar, fn ($q) => $q->where('id', '!=', $ignorar))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /** Guarda la imagen en public/uploads/productos (no requiere storage:link). */
    private function subirImagen(Request $request): ?string
    {
        if (! $request->hasFile('imagen')) {
            return null;
        }
        $archivo = $request->file('imagen');
        $nombre = Str::uuid().'.'.$archivo->extension();
        $archivo->move(public_path('uploads/productos'), $nombre);

        return 'uploads/productos/'.$nombre;
    }

    private function borrarImagen(Producto $producto): void
    {
        if ($producto->imagen && str_starts_with($producto->imagen, 'uploads/')) {
            File::delete(public_path($producto->imagen));
        }
    }
}
