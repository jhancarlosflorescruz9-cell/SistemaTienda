@extends('layouts.tienda.app')

@section('title', 'Rastrear pedido')

@section('content')
    <div class="max-w-2xl mx-auto px-4 mt-8">
        <div class="bg-white rounded-2xl p-6">
            <h1 class="text-xl font-extrabold mb-4"><i class="fa-solid fa-box text-marca"></i> Rastrear mi pedido</h1>
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                <input name="codigo" value="{{ request('codigo') }}" required placeholder="Código (PED-...)" class="sm:col-span-2 border rounded-lg px-3 py-2.5 text-sm uppercase">
                <input name="email" type="email" value="{{ request('email') }}" required placeholder="Correo de compra" class="sm:col-span-2 border rounded-lg px-3 py-2.5 text-sm">
                <button class="bg-slate-900 text-white font-bold rounded-lg py-2.5">Buscar</button>
            </form>
        </div>

        @if ($pedido)
            @php
                $pasos = ['pendiente' => ['Recibido', 'fa-receipt'], 'pagado' => ['Confirmado', 'fa-credit-card'], 'enviado' => ['En camino', 'fa-truck-fast'], 'entregado' => ['Entregado', 'fa-house-circle-check']];
                $indice = array_search($pedido->estado, array_keys($pasos));
            @endphp
            <div class="bg-white rounded-2xl p-6 mt-4">
                <div class="flex justify-between items-center mb-6">
                    <div><div class="font-extrabold">{{ $pedido->codigo }}</div><div class="text-xs text-slate-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</div></div>
                    <div class="font-extrabold text-marca">S/ {{ number_format($pedido->total, 2) }}</div>
                </div>

                @if ($pedido->estado === 'cancelado')
                    <div class="bg-red-50 text-red-600 rounded-lg p-3 text-sm font-bold"><i class="fa-solid fa-ban"></i> Este pedido fue cancelado.</div>
                @else
                    <div class="flex items-start">
                        @foreach ($pasos as $clave => [$label, $icono])
                            @php $hecho = $loop->index <= $indice; @endphp
                            <div class="flex flex-col items-center w-20 shrink-0 text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $hecho ? 'bg-marca text-white' : 'bg-slate-100 text-slate-400' }}"><i class="fa-solid {{ $icono }}"></i></div>
                                <span class="text-xs mt-1 {{ $hecho ? 'font-bold' : 'text-slate-400' }}">{{ $label }}</span>
                            </div>
                            @if (! $loop->last)<div class="flex-1 h-1 mt-5 rounded {{ $loop->index < $indice ? 'bg-marca' : 'bg-slate-100' }}"></div>@endif
                        @endforeach
                    </div>
                    @if ($pedido->tracking)
                        <p class="text-sm mt-4">N° de seguimiento del courier: <b>{{ $pedido->tracking }}</b></p>
                    @endif
                @endif

                <div class="border-t mt-6 pt-4 text-sm space-y-1">
                    @foreach ($pedido->items as $item)
                        <div class="flex justify-between gap-3"><span>{{ $item->cantidad }} × {{ $item->nombre }}</span><span class="shrink-0">S/ {{ number_format($item->subtotal, 2) }}</span></div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
