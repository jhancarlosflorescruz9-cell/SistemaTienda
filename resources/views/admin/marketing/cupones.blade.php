@extends('layouts.admin.app')

@section('title', 'Cupones')

@section('content')
    <x-admin.header titulo="Cupones de descuento" icono="fa-solid fa-ticket" seccion="Marketing">
        <button type="button" onclick="abrirModal('modalNuevoCupon')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </button>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold py-2">Código</th><th class="font-semibold">Descuento</th><th class="font-semibold text-right">Compra mínima</th>
                    <th class="font-semibold text-center">Usos</th><th class="font-semibold">Vence</th><th class="font-semibold">Estado</th>
                    <th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cupones as $cupon)
                    <tr class="border-b border-slate-100">
                        <td class="py-2"><span class="font-mono font-bold text-blue-800 bg-blue-50 border border-dashed border-blue-300 px-2 py-0.5 rounded">{{ $cupon->codigo }}</span></td>
                        <td class="py-2 font-semibold text-slate-700">{{ $cupon->tipo === 'porcentaje' ? rtrim(rtrim($cupon->valor, '0'), '.').'%' : 'S/ '.number_format($cupon->valor, 2) }}</td>
                        <td class="py-2 text-right text-slate-600">S/ {{ number_format($cupon->minimo_compra, 2) }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $cupon->usos }} / {{ $cupon->usos_maximos ?? '∞' }}</td>
                        <td class="py-2 text-slate-600">{{ $cupon->fecha_fin?->format('d/m/Y') ?? 'Sin vencimiento' }}</td>
                        <td class="py-2">
                            @php $motivo = $cupon->motivoInvalido(PHP_FLOAT_MAX); @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $motivo ? 'bg-slate-100 text-slate-500' : 'bg-green-100 text-green-700' }}" title="{{ $motivo }}">{{ $motivo ? 'No disponible' : 'Disponible' }}</span>
                        </td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="abrirModal('modalCupon{{ $cupon->id }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #64DD17;">Editar</button>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.cupones.destroy', $cupon) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay cupones registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($cupones->push(new \App\Models\Cupon(['activo' => true, 'tipo' => 'porcentaje'])) as $cupon)
        @php $id = $cupon->exists ? 'modalCupon'.$cupon->id : 'modalNuevoCupon'; @endphp
        <x-admin.modal :id="$id" :titulo="$cupon->exists ? 'Editar cupón' : 'Nuevo cupón'" icono="fa-solid fa-ticket">
            <form method="POST" action="{{ $cupon->exists ? route('admin.cupones.update', $cupon) : route('admin.cupones.store') }}" class="space-y-4">
                @csrf
                @if ($cupon->exists) @method('PUT') @endif
                <div class="grid grid-cols-2 gap-3">
                    <x-admin.field class="col-span-2" label="Código" name="codigo" :value="$cupon->codigo" required placeholder="BIENVENIDO10" style="text-transform: uppercase" />
                    <div>
                        <label class="block text-sm font-medium text-blue-700 mb-1">Tipo</label>
                        <select name="tipo" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="porcentaje" @selected($cupon->tipo === 'porcentaje')>Porcentaje (%)</option>
                            <option value="monto" @selected($cupon->tipo === 'monto')>Monto fijo (S/)</option>
                        </select>
                    </div>
                    <x-admin.field label="Valor" name="valor" type="number" step="0.01" min="0.01" :value="$cupon->valor" required />
                    <x-admin.field label="Compra mínima (S/)" name="minimo_compra" type="number" step="0.01" min="0" :value="$cupon->minimo_compra ?? 0" />
                    <x-admin.field label="Usos máximos" name="usos_maximos" type="number" min="1" :value="$cupon->usos_maximos" placeholder="Ilimitado" />
                    <x-admin.field class="col-span-2" label="Fecha de vencimiento" name="fecha_fin" type="date" :value="$cupon->fecha_fin?->format('Y-m-d')" />
                </div>
                <x-admin.toggle name="activo" label="Activo" :checked="$cupon->activo" />
                <x-admin.botones :modal="$id" :texto="$cupon->exists ? 'Actualizar' : 'Guardar'" />
            </form>
        </x-admin.modal>
    @endforeach
@endsection
