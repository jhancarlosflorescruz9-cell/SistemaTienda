@extends('layouts.admin.app')

@section('title', $producto->exists ? 'Editar producto' : 'Nuevo producto')

@section('content')
    <x-admin.header :titulo="$producto->exists ? 'Editar producto: '.$producto->nombre : 'Nuevo producto'" icono="fa-solid fa-box-open" seccion="Productos">
        <a href="{{ route('admin.productos.index') }}" class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-white no-underline">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </x-admin.header>

    <form method="POST" enctype="multipart/form-data"
          action="{{ $producto->exists ? route('admin.productos.update', $producto) : route('admin.productos.store') }}"
          class="grid grid-cols-12 gap-4">
        @csrf
        @if ($producto->exists) @method('PUT') @endif

        <div class="col-span-12 lg:col-span-8 space-y-4">
            <div class="bg-white rounded-3xl shadow-md p-6 space-y-4">
                <h2 class="text-sm font-semibold text-slate-700">Información general</h2>
                <div class="grid grid-cols-12 gap-3">
                    <x-admin.field class="col-span-12 sm:col-span-4" label="Código / SKU" name="codigo" :value="$producto->codigo" required />
                    <x-admin.field class="col-span-12 sm:col-span-8" label="Nombre del producto" name="nombre" :value="$producto->nombre" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="6"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-4">
                        <label class="block text-sm font-medium text-blue-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <select name="categoria_id" required class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="">Seleccione</option>
                            @foreach ($categorias as $c)
                                <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id) == $c->id)>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <label class="block text-sm font-medium text-blue-700 mb-1">Marca</label>
                        <select name="marca_id" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="">Sin marca</option>
                            @foreach ($marcas as $m)
                                <option value="{{ $m->id }}" @selected(old('marca_id', $producto->marca_id) == $m->id)>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <label class="block text-sm font-medium text-blue-700 mb-1">Vendedor</label>
                        <select name="tienda_id" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="">Tienda propia</option>
                            @foreach ($tiendas as $t)
                                <option value="{{ $t->id }}" @selected(old('tienda_id', $producto->tienda_id) == $t->id)>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-md p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Precio e inventario</h2>
                <div class="grid grid-cols-12 gap-3">
                    <x-admin.field class="col-span-6 sm:col-span-3" label="Precio (S/)" name="precio" type="number" step="0.01" min="0" :value="$producto->precio" required />
                    <x-admin.field class="col-span-6 sm:col-span-3" label="Precio oferta (S/)" name="precio_oferta" type="number" step="0.01" min="0" :value="$producto->precio_oferta" />
                    <x-admin.field class="col-span-6 sm:col-span-3" label="Stock" name="stock" type="number" min="0" :value="$producto->stock ?? 0" required />
                    <x-admin.field class="col-span-6 sm:col-span-3" label="Stock mínimo" name="stock_minimo" type="number" min="0" :value="$producto->stock_minimo" required />
                </div>
                <p class="text-xs text-slate-500 mt-3 mb-0">
                    <i class="fa-solid fa-circle-info"></i> Si el producto participa en una oferta flash vigente, la tienda muestra el precio más bajo entre la oferta y la promoción.
                </p>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl shadow-md p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Imagen</h2>
                <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 mb-3">
                    @if ($producto->exists)
                        <x-producto-imagen :producto="$producto" icono="text-6xl" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-regular fa-image text-6xl"></i></div>
                    @endif
                </div>
                <input type="file" name="imagen" accept="image/*" class="w-full text-sm">
                <p class="text-xs text-slate-500 mt-2 mb-0">JPG, PNG o WEBP. Máximo 2 MB. Recomendado: cuadrada 800×800.</p>
                @if ($producto->imagen)
                    <div class="mt-3"><x-admin.toggle name="quitar_imagen" label="Quitar imagen actual" /></div>
                @endif
            </div>

            <div class="bg-white rounded-3xl shadow-md p-6 space-y-3">
                <h2 class="text-sm font-semibold text-slate-700 mb-1">Publicación</h2>
                <div><x-admin.toggle name="activo" label="Publicado en la tienda" :checked="old('activo', $producto->activo)" /></div>
                <div><x-admin.toggle name="destacado" label="Destacado en portada" :checked="old('destacado', $producto->destacado)" /></div>
                <div><x-admin.toggle name="envio_gratis" label="Envío gratis" :checked="old('envio_gratis', $producto->envio_gratis)" /></div>
            </div>

            <button type="submit" class="w-full px-4 py-3 rounded-xl bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
                <i class="fa-solid fa-floppy-disk"></i> {{ $producto->exists ? 'Actualizar producto' : 'Guardar producto' }}
            </button>
        </div>
    </form>
@endsection
