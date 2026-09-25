@extends('layouts.tienda.app')

@section('title', '¡Pedido confirmado!')

@section('content')
    <div class="max-w-2xl mx-auto px-4 mt-8">
        <div class="bg-white rounded-2xl p-8 text-center">
            <div class="w-20 h-20 rounded-full bg-green-100 text-[#0a8800] flex items-center justify-center mx-auto text-4xl"><i class="fa-solid fa-check"></i></div>
            <h1 class="text-2xl font-extrabold mt-4">¡Gracias por tu compra, {{ \Illuminate\Support\Str::before($pedido->cliente->nombre, ' ') }}!</h1>
            <p class="text-slate-500 mt-1">Enviamos el detalle a <b>{{ $pedido->cliente->email }}</b></p>
            <div class="inline-block mt-4 bg-marca-claro text-marca font-extrabold text-lg rounded-xl px-5 py-2">{{ $pedido->codigo }}</div>
            <p class="text-xs text-slate-500 mt-2">Guarda este código para rastrear tu pedido.</p>

            <div class="text-left mt-6 border-t pt-4 text-sm space-y-2">
                @foreach ($pedido->items as $item)
                    <div class="flex justify-between gap-3"><span class="line-clamp-1">{{ $item->cantidad }} × {{ $item->nombre }}</span><span class="shrink-0">S/ {{ number_format($item->subtotal, 2) }}</span></div>
                @endforeach
                @if ($pedido->descuento > 0)
                    <div class="flex justify-between text-[#0a8800]"><span>Descuento</span><span>- S/ {{ number_format($pedido->descuento, 2) }}</span></div>
                @endif
                <div class="flex justify-between"><span>Envío</span><span>{{ $pedido->envio > 0 ? 'S/ '.number_format($pedido->envio, 2) : 'Gratis' }}</span></div>
                <div class="flex justify-between font-extrabold text-lg border-t pt-2"><span>Total</span><span class="text-marca">S/ {{ number_format($pedido->total, 2) }}</span></div>
                <div class="text-slate-500 pt-2"><i class="fa-solid fa-location-dot"></i> {{ $pedido->direccion_envio }}, {{ $pedido->distrito }}</div>
                <div class="text-slate-500"><i class="fa-solid fa-wallet"></i> {{ $pedido->metodo_pago }} · Estado: <b>{{ $pedido->estado_label }}</b></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <a href="{{ route('tienda.seguimiento', ['codigo' => $pedido->codigo, 'email' => $pedido->cliente->email]) }}" class="flex-1 border-2 border-slate-900 font-bold rounded-full py-3">Rastrear pedido</a>
                <a href="{{ route('tienda.inicio') }}" class="flex-1 bg-marca text-white font-bold rounded-full py-3">Seguir comprando</a>
            </div>
        </div>
    </div>
@endsection
