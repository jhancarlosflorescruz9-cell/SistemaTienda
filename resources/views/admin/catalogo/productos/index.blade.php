@extends('layouts.admin.app')

@section('title', 'Productos')

@section('content')
    <x-admin.header titulo="Listado de productos" icono="fa-solid fa-box-open" seccion="Productos">
        <a href="{{ route('admin.productos.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold no-underline">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </a>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o código"
                class="flex-1 min-w-[200px] rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <select name="categoria" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" @selected(request('categoria') == $c->id)>{{ $c->nombre }}</option>
                @endforeach
            </select>
            <select name="estado" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                <option value="">Todos</option>
                <option value="activos" @selected(request('estado') === 'activos')>Activos</option>
                <option value="inactivos" @selected(request('estado') === 'inactivos')>Inactivos</option>
            </select>
            <button class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold py-2">Producto</th>
                        <th class="font-semibold">Categoría</th>
                        <th class="font-semibold">Vendedor</th>
                        <th class="font-semibold text-right">Precio</th>
                        <th class="font-semibold text-center">Stock</th>
                        <th class="font-semibold text-center">Vendidos</th>
                        <th class="font-semibold">Estado</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-lg overflow-hidden shrink-0"><x-producto-imagen :producto="$producto" icono="text-base" /></div>
                                    <div class="min-w-0">
                                        <div class="text-blue-800 font-medium">{{ $producto->nombre }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $producto->codigo }}
                                            @if ($producto->destacado)<span class="ml-1 text-amber-600"><i class="fa-solid fa-star"></i> Destacado</span>@endif
                                            @if ($producto->envio_gratis)<span class="ml-1 text-green-600"><i class="fa-solid fa-truck"></i> Envío gratis</span>@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 text-slate-600">{{ $producto->categoria->nombre }}</td>
                            <td class="py-2 text-slate-600">{{ $producto->tienda->nombre ?? 'Tienda propia' }}</td>
                            <td class="py-2 text-right whitespace-nowrap">
                                <div class="font-semibold text-slate-800">S/ {{ number_format($producto->precio_final, 2) }}</div>
                                @if ($producto->porcentaje_descuento > 0)
                                    <div class="text-xs text-slate-400 line-through">S/ {{ number_format($producto->precio, 2) }}</div>
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $producto->stock <= 0 ? 'bg-red-100 text-red-700' : ($producto->stock_bajo ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">{{ $producto->stock }}</span>
                            </td>
                            <td class="py-2 text-center text-slate-600">{{ $producto->vendidos }}</td>
                            <td class="py-2"><x-admin.estado :activo="$producto->activo" /></td>
                            <td class="py-2">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('tienda.producto', $producto) }}" target="_blank" title="Ver en tienda"
                                       class="px-3 py-1.5 rounded-md border border-slate-200 text-slate-600 text-xs font-semibold no-underline"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('admin.productos.edit', $producto) }}"
                                       class="px-4 py-1.5 rounded-md text-white text-xs font-semibold no-underline" style="background-color: #64DD17;">Editar</a>
                                    <button type="button" onclick="confirmarEliminar('{{ route('admin.productos.destroy', $producto) }}')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-4 text-center text-slate-500">No se encontraron productos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $productos->links() }}</div>
    </div>
@endsection
