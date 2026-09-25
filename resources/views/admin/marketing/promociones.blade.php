@extends('layouts.admin.app')

@section('title', 'Ofertas flash y promociones')

@section('content')
    <x-admin.header titulo="Ofertas flash y promociones" icono="fa-solid fa-bolt" seccion="Marketing">
        <button type="button" onclick="abrirModal('modalNuevaPromocion')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i> Nuevo
        </button>
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold py-2">Promoción</th><th class="font-semibold">Tipo</th><th class="font-semibold text-center">Descuento</th>
                    <th class="font-semibold">Vigencia</th><th class="font-semibold text-center">Productos</th><th class="font-semibold">Estado</th>
                    <th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promociones as $promo)
                    @php
                        $estado = ! $promo->activo ? ['Inactiva', 'bg-slate-100 text-slate-500']
                            : ($promo->estaVigente() ? ['En curso', 'bg-green-100 text-green-700']
                            : ($promo->fecha_inicio->isFuture() ? ['Programada', 'bg-blue-100 text-blue-700'] : ['Finalizada', 'bg-slate-100 text-slate-500']));
                    @endphp
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-blue-800 font-medium">{{ $promo->nombre }}</td>
                        <td class="py-2 text-slate-600 capitalize">{{ $promo->tipo === 'liquidacion' ? 'Liquidación' : $promo->tipo }}</td>
                        <td class="py-2 text-center font-bold text-orange-600">-{{ $promo->descuento }}%</td>
                        <td class="py-2 text-slate-600 text-xs whitespace-nowrap">{{ $promo->fecha_inicio->format('d/m/Y H:i') }}<br>{{ $promo->fecha_fin->format('d/m/Y H:i') }}</td>
                        <td class="py-2 text-center text-slate-600">{{ $promo->productos_count }}</td>
                        <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $estado[1] }}">{{ $estado[0] }}</span></td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="abrirModal('modalPromocion{{ $promo->id }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #64DD17;">Editar</button>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.promociones.destroy', $promo) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay promociones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($promociones->push(new \App\Models\Promocion(['activo' => true, 'tipo' => 'flash'])) as $promo)
        @php
            $id = $promo->exists ? 'modalPromocion'.$promo->id : 'modalNuevaPromocion';
            $seleccionados = $promo->exists ? $promo->productos->pluck('id')->all() : [];
        @endphp
        <x-admin.modal :id="$id" :titulo="$promo->exists ? 'Editar promoción' : 'Nueva promoción'" icono="fa-solid fa-bolt" ancho="max-w-2xl">
            <form method="POST" action="{{ $promo->exists ? route('admin.promociones.update', $promo) : route('admin.promociones.store') }}" class="space-y-4">
                @csrf
                @if ($promo->exists) @method('PUT') @endif
                <div class="grid grid-cols-12 gap-3">
                    <x-admin.field class="col-span-12 sm:col-span-6" label="Nombre" name="nombre" :value="$promo->nombre" required placeholder="Ofertas relámpago de medianoche" />
                    <div class="col-span-6 sm:col-span-3">
                        <label class="block text-sm font-medium text-blue-700 mb-1">Tipo</label>
                        <select name="tipo" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="flash" @selected($promo->tipo === 'flash')>Flash (con cuenta regresiva)</option>
                            <option value="temporada" @selected($promo->tipo === 'temporada')>Temporada</option>
                            <option value="liquidacion" @selected($promo->tipo === 'liquidacion')>Liquidación</option>
                        </select>
                    </div>
                    <x-admin.field class="col-span-6 sm:col-span-3" label="Descuento %" name="descuento" type="number" min="1" max="95" :value="$promo->descuento" required />
                    <x-admin.field class="col-span-6" label="Inicio" name="fecha_inicio" type="datetime-local" :value="optional($promo->fecha_inicio)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')" required />
                    <x-admin.field class="col-span-6" label="Fin" name="fecha_fin" type="datetime-local" :value="optional($promo->fecha_fin)->format('Y-m-d\TH:i') ?? now()->addDay()->format('Y-m-d\TH:i')" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Productos incluidos</label>
                    <input type="text" placeholder="Filtrar productos..." oninput="filtrarLista(this)"
                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-1.5 text-sm mb-2">
                    <div class="max-h-56 overflow-y-auto border border-slate-200 rounded-md p-2 bg-slate-50 space-y-1">
                        @foreach ($productos as $p)
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer" data-texto="{{ strtolower($p->nombre.' '.$p->codigo) }}">
                                <input type="checkbox" name="productos[]" value="{{ $p->id }}" @checked(in_array($p->id, $seleccionados)) class="accent-[#0407e2]">
                                <span class="text-xs text-slate-400">{{ $p->codigo }}</span> {{ $p->nombre }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <x-admin.toggle name="activo" label="Activa" :checked="$promo->activo" />
                <x-admin.botones :modal="$id" :texto="$promo->exists ? 'Actualizar' : 'Guardar'" />
            </form>
        </x-admin.modal>
    @endforeach
@endsection

@section('js')
    <script>
        function filtrarLista(input) {
            const q = input.value.toLowerCase();
            input.nextElementSibling.querySelectorAll('[data-texto]').forEach(el => {
                el.style.display = el.dataset.texto.includes(q) ? '' : 'none';
            });
        }
    </script>
@endsection
