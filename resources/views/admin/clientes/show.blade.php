@extends('layouts.admin.app')

@section('title', $cliente->nombre)

@section('content')
    <x-admin.header :titulo="$cliente->nombre" icono="fa-regular fa-address-card" seccion="Clientes">
        <a href="{{ route('admin.clientes.index') }}" class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-white no-underline">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </x-admin.header>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-md p-6 text-sm space-y-1">
            <div class="w-14 h-14 rounded-full bg-blue-50 text-[#0407e2] flex items-center justify-center text-xl font-bold mb-3">
                {{ mb_strtoupper(mb_substr($cliente->nombre, 0, 1)) }}
            </div>
            <div class="font-semibold text-slate-800">{{ $cliente->nombre }}</div>
            <div class="text-slate-600"><i class="fa-regular fa-envelope w-4"></i> {{ $cliente->email }}</div>
            <div class="text-slate-600"><i class="fa-solid fa-phone w-4"></i> {{ $cliente->telefono ?: '—' }}</div>
            <div class="text-slate-600"><i class="fa-regular fa-id-card w-4"></i> {{ $cliente->documento ?: '—' }}</div>
            <div class="text-slate-600"><i class="fa-solid fa-location-dot w-4"></i> {{ $cliente->direccion }} {{ $cliente->distrito ? '· '.$cliente->distrito : '' }}</div>
            <hr class="my-3">
            <div class="flex justify-between"><span class="text-slate-500">Pedidos</span><strong>{{ $cliente->pedidos->count() }}</strong></div>
            <div class="flex justify-between"><span class="text-slate-500">Total comprado</span><strong>S/ {{ number_format($cliente->pedidos->where('estado', '!=', 'cancelado')->sum('total'), 2) }}</strong></div>
            <div class="flex justify-between"><span class="text-slate-500">Cliente desde</span><strong>{{ $cliente->created_at->format('d/m/Y') }}</strong></div>
        </div>

        <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Historial de pedidos</h2>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold py-2">Fecha</th><th class="font-semibold">Pedido</th><th class="font-semibold text-center">Ítems</th>
                        <th class="font-semibold">Estado</th><th class="font-semibold text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cliente->pedidos as $pedido)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $pedido->created_at->format('d/m/Y') }}</td>
                            <td class="py-2"><a href="{{ route('admin.pedidos.show', $pedido) }}" class="text-blue-800 font-medium no-underline">{{ $pedido->codigo }}</a></td>
                            <td class="py-2 text-center">{{ $pedido->items_count }}</td>
                            <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $pedido->estado_clase }}">{{ $pedido->estado_label }}</span></td>
                            <td class="py-2 text-right font-semibold">S/ {{ number_format($pedido->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
