@extends('layouts.admin.app')

@section('title', 'Categorías')

@section('content')
    <x-admin.header titulo="Listado de categorías" icono="fa-solid fa-layer-group" seccion="Productos">
        <button type="button" onclick="abrirModal('modalNuevaCategoria')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </button>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold w-12 py-2">#</th>
                    <th class="font-semibold">Categoría</th>
                    <th class="font-semibold">Categoría padre</th>
                    <th class="font-semibold text-center">Productos</th>
                    <th class="font-semibold text-center">Orden</th>
                    <th class="font-semibold">Estado</th>
                    <th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $i => $categoria)
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-slate-600">{{ $i + 1 }}</td>
                        <td class="py-2">
                            <span class="inline-flex items-center gap-2 text-blue-800 font-medium">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background: {{ $categoria->color }}22; color: {{ $categoria->color }}">
                                    <i class="{{ $categoria->icono }}"></i>
                                </span>
                                {{ $categoria->nombre }}
                            </span>
                        </td>
                        <td class="py-2 text-slate-600">{{ $categoria->padre->nombre ?? '—' }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $categoria->productos_count }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $categoria->orden }}</td>
                        <td class="py-2"><x-admin.estado :activo="$categoria->activo" /></td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="abrirModal('modalCategoria{{ $categoria->id }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #64DD17;">Editar</button>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.categorias.destroy', $categoria) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay categorías registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($categorias->push(new \App\Models\Categoria(['activo' => true])) as $categoria)
        @php $id = $categoria->exists ? 'modalCategoria'.$categoria->id : 'modalNuevaCategoria'; @endphp
        <x-admin.modal :id="$id" :titulo="$categoria->exists ? 'Editar categoría' : 'Nueva categoría'" icono="fa-solid fa-layer-group">
            <form method="POST" action="{{ $categoria->exists ? route('admin.categorias.update', $categoria) : route('admin.categorias.store') }}" class="space-y-4">
                @csrf
                @if ($categoria->exists) @method('PUT') @endif
                <x-admin.field label="Nombre" name="nombre" :value="$categoria->nombre" required />
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Categoría padre</label>
                    <select name="parent_id" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                        <option value="">— Ninguna (principal) —</option>
                        @foreach ($categorias->where('exists', true) as $opcion)
                            @continue($opcion->id === $categoria->id)
                            <option value="{{ $opcion->id }}" @selected($opcion->id == $categoria->parent_id)>{{ $opcion->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <x-admin.field class="col-span-3 sm:col-span-1" label="Ícono (Font Awesome)" name="icono" :value="$categoria->icono ?? 'fa-solid fa-tag'" placeholder="fa-solid fa-shirt" />
                    <x-admin.field class="col-span-3 sm:col-span-1" label="Color" name="color" type="color" :value="$categoria->color ?? '#fb7701'" style="height: 38px; padding: 2px;" />
                    <x-admin.field class="col-span-3 sm:col-span-1" label="Orden" name="orden" type="number" min="0" :value="$categoria->orden ?? 0" />
                </div>
                <x-admin.toggle name="activo" label="Visible en la tienda" :checked="$categoria->activo" />
                <x-admin.botones :modal="$id" :texto="$categoria->exists ? 'Actualizar' : 'Guardar'" />
            </form>
        </x-admin.modal>
    @endforeach
@endsection
