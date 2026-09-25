@extends('layouts.admin.app')

@section('title', 'Vendedores')

@section('content')
    <x-admin.header titulo="Vendedores del marketplace" icono="fa-solid fa-store" seccion="Marketplace">
        <button type="button" onclick="abrirModal('modalNuevoVendedor')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </button>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold py-2">Vendedor</th><th class="font-semibold">RUC</th><th class="font-semibold">Contacto</th>
                    <th class="font-semibold text-center">Productos</th><th class="font-semibold text-center">Unid. vendidas</th>
                    <th class="font-semibold text-center">Comisión</th><th class="font-semibold">Estado</th><th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tiendas as $tienda)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">
                            <div class="text-blue-800 font-medium">{{ $tienda->nombre }}
                                @if ($tienda->verificada)<i class="fa-solid fa-circle-check text-blue-500" title="Verificado"></i>@endif
                            </div>
                            <div class="text-xs text-amber-500"><i class="fa-solid fa-star"></i> {{ $tienda->calificacion }}</div>
                        </td>
                        <td class="py-2 text-slate-600">{{ $tienda->ruc ?: '—' }}</td>
                        <td class="py-2 text-slate-600 text-xs">{{ $tienda->email }}<br>{{ $tienda->telefono }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $tienda->productos_count }}</td>
                        <td class="py-2 text-center text-slate-600">{{ (int) $tienda->unidades_vendidas }}</td>
                        <td class="py-2 text-center text-slate-600">{{ rtrim(rtrim($tienda->comision, '0'), '.') }}%</td>
                        <td class="py-2"><x-admin.estado :activo="$tienda->activo" /></td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="abrirModal('modalVendedor{{ $tienda->id }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #64DD17;">Editar</button>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.vendedores.destroy', $tienda) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 text-center text-slate-500">No hay vendedores registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($tiendas->push(new \App\Models\Tienda(['activo' => true, 'comision' => 10])) as $tienda)
        @php $id = $tienda->exists ? 'modalVendedor'.$tienda->id : 'modalNuevoVendedor'; @endphp
        <x-admin.modal :id="$id" :titulo="$tienda->exists ? 'Editar vendedor' : 'Nuevo vendedor'" icono="fa-solid fa-store">
            <form method="POST" action="{{ $tienda->exists ? route('admin.vendedores.update', $tienda) : route('admin.vendedores.store') }}" class="space-y-4">
                @csrf
                @if ($tienda->exists) @method('PUT') @endif
                <div class="grid grid-cols-2 gap-3">
                    <x-admin.field class="col-span-2" label="Nombre comercial" name="nombre" :value="$tienda->nombre" required />
                    <x-admin.field label="RUC" name="ruc" :value="$tienda->ruc" maxlength="20" />
                    <x-admin.field label="Comisión (%)" name="comision" type="number" step="0.01" min="0" max="100" :value="$tienda->comision" required />
                    <x-admin.field label="Correo" name="email" type="email" :value="$tienda->email" />
                    <x-admin.field label="Teléfono" name="telefono" :value="$tienda->telefono" />
                </div>
                <div class="flex gap-6">
                    <x-admin.toggle name="verificada" label="Vendedor verificado" :checked="$tienda->verificada" />
                    <x-admin.toggle name="activo" label="Activo" :checked="$tienda->activo" />
                </div>
                <x-admin.botones :modal="$id" :texto="$tienda->exists ? 'Actualizar' : 'Guardar'" />
            </form>
        </x-admin.modal>
    @endforeach
@endsection
