@extends('layouts.admin.app')

@section('title', 'Marcas')

@section('content')
    <x-admin.header titulo="Listado de marcas" icono="fa-solid fa-copyright" seccion="Productos">
        <button type="button" onclick="abrirModal('modalNuevaMarca')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </button>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold w-12 py-2">#</th>
                    <th class="font-semibold">Marca</th>
                    <th class="font-semibold text-center">Productos</th>
                    <th class="font-semibold">Estado</th>
                    <th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($marcas as $i => $marca)
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-slate-600">{{ $i + 1 }}</td>
                        <td class="py-2 text-blue-800 font-medium">{{ $marca->nombre }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $marca->productos_count }}</td>
                        <td class="py-2"><x-admin.estado :activo="$marca->activo" /></td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="abrirModal('modalMarca{{ $marca->id }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #64DD17;">Editar</button>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.marcas.destroy', $marca) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">No hay marcas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($marcas->push(new \App\Models\Marca(['activo' => true])) as $marca)
        @php $id = $marca->exists ? 'modalMarca'.$marca->id : 'modalNuevaMarca'; @endphp
        <x-admin.modal :id="$id" :titulo="$marca->exists ? 'Editar marca' : 'Nueva marca'" icono="fa-solid fa-copyright">
            <form method="POST" action="{{ $marca->exists ? route('admin.marcas.update', $marca) : route('admin.marcas.store') }}" class="space-y-4">
                @csrf
                @if ($marca->exists) @method('PUT') @endif
                <x-admin.field label="Nombre" name="nombre" :value="$marca->nombre" required />
                <x-admin.toggle name="activo" label="Activa" :checked="$marca->activo" />
                <x-admin.botones :modal="$id" :texto="$marca->exists ? 'Actualizar' : 'Guardar'" />
            </form>
        </x-admin.modal>
    @endforeach
@endsection
