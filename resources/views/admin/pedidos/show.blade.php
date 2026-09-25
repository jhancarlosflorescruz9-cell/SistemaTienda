@extends('layouts.admin.app')

@section('title', 'Pedido '.$pedido->codigo)

@section('content')
    <x-admin.header :titulo="'Pedido '.$pedido->codigo" icono="fa-solid fa-receipt" seccion="Pedidos">
        <a href="{{ route('admin.pedidos.index') }}" class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-white no-underline">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-white">
            <i class="fa-solid fa-print"></i> Imprimir
        </button>
    </x-admin.header>

    @php
        $pasos = ['pendiente', 'pagado', 'enviado', 'entregado'];
        $actual = array_search($pedido->estado, $pasos);
    @endphp

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 lg:col-span-8 space-y-4">
            {{-- Línea de tiempo --}}
            <div class="bg-white rounded-3xl shadow-md p-6">
                @if ($pedido->estado === 'cancelado')
                    <div class="text-red-600 font-semibold text-sm"><i class="fa-solid fa-ban"></i> Pedido cancelado — el stock fue devuelto al inventario.</div>
                @else
                    <div class="flex items-center">
                        @foreach ($pasos as $i => $paso)
                            <div class="flex flex-col items-center text-center" style="min-width: 70px">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm {{ $i <= $actual ? 'bg-[#0407e2] text-white' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fa-solid {{ ['fa-clock', 'fa-credit-card', 'fa-truck-fast', 'fa-house-circle-check'][$i] }}"></i>
                                </div>
                                <span class="text-xs mt-1 {{ $i <= $actual ? 'text-slate-800 font-semibold' : 'text-slate-400' }}">{{ \App\Models\Pedido::ESTADOS[$paso][0] }}</span>
                            </div>
                            @if (! $loop->last)
                                <div class="flex-1 h-1 rounded {{ $i < $actual ? 'bg-[#0407e2]' : 'bg-slate-100' }} mb-4"></div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Productos --}}
            <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Productos</h2>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-black">
                            <th class="font-semibold py-2">Producto</th>
                            <th class="font-semibold text-right">Precio</th>
                            <th class="font-semibold text-center">Cant.</th>
                            <th class="font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->items as $item)
                            <tr class="border-b border-slate-100">
                                <td class="py-2">
                                    <div class="text-blue-800 font-medium">{{ $item->nombre }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->producto->codigo ?? 'Producto eliminado' }}</div>
                                </td>
                                <td class="py-2 text-right">S/ {{ number_format($item->precio, 2) }}</td>
                                <td class="py-2 text-center">{{ $item->cantidad }}</td>
                                <td class="py-2 text-right font-semibold">S/ {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-slate-700">
                        <tr><td colspan="3" class="pt-3 text-right">Subtotal</td><td class="pt-3 text-right">S/ {{ number_format($pedido->subtotal, 2) }}</td></tr>
                        @if ($pedido->descuento > 0)
                            <tr><td colspan="3" class="text-right text-green-700">Descuento {{ $pedido->cupon ? '('.$pedido->cupon->codigo.')' : '' }}</td><td class="text-right text-green-700">- S/ {{ number_format($pedido->descuento, 2) }}</td></tr>
                        @endif
                        <tr><td colspan="3" class="text-right">Envío</td><td class="text-right">{{ $pedido->envio > 0 ? 'S/ '.number_format($pedido->envio, 2) : 'Gratis' }}</td></tr>
                        <tr><td colspan="3" class="pt-2 text-right font-bold">Total</td><td class="pt-2 text-right font-bold text-lg">S/ {{ number_format($pedido->total, 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl shadow-md p-6 text-sm">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Cliente y envío</h2>
                <a href="{{ route('admin.clientes.show', $pedido->cliente) }}" class="font-semibold text-blue-800 no-underline">{{ $pedido->cliente->nombre }}</a>
                <div class="text-slate-600">{{ $pedido->cliente->email }}</div>
                <div class="text-slate-600">{{ $pedido->cliente->telefono }}</div>
                @if ($pedido->cliente->documento)<div class="text-slate-600">Doc: {{ $pedido->cliente->documento }}</div>@endif
                <hr class="my-3">
                <div class="text-slate-700"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $pedido->direccion_envio }}</div>
                <div class="text-slate-600 ml-4">{{ $pedido->distrito }}</div>
                <div class="text-slate-700 mt-2"><i class="fa-solid fa-wallet text-slate-400"></i> {{ $pedido->metodo_pago }}</div>
                <div class="text-slate-500 mt-2 text-xs">Registrado: {{ $pedido->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}" class="bg-white rounded-3xl shadow-md p-6 space-y-3">
                @csrf
                @method('PUT')
                <h2 class="text-sm font-semibold text-slate-700">Actualizar pedido</h2>
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Estado</label>
                    <select name="estado" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                        @foreach (\App\Models\Pedido::ESTADOS as $clave => [$label])
                            <option value="{{ $clave }}" @selected($pedido->estado === $clave)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-admin.field label="N° de seguimiento (courier)" name="tracking" :value="$pedido->tracking" placeholder="Ej. OLVA-123456" />
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Notas</label>
                    <textarea name="notas" rows="3" class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">{{ old('notas', $pedido->notas) }}</textarea>
                </div>
                <button class="w-full px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">Guardar cambios</button>
            </form>
        </div>
    </div>
@endsection
