@extends('layouts.admin.app')

@section('title', 'Pedidos')

@section('content')
    <x-admin.header titulo="Pedidos de la tienda virtual" icono="fa-solid fa-receipt" seccion="Pedidos" />

    {{-- Pestañas por estado --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.pedidos.index') }}"
           class="px-3 py-1.5 rounded-full text-xs font-semibold no-underline {{ request('estado') ? 'bg-white text-slate-600 shadow-sm' : 'bg-[#0407e2] text-white' }}">
            Todos ({{ $conteos->sum() }})
        </a>
        @foreach (\App\Models\Pedido::ESTADOS as $clave => [$label])
            <a href="{{ route('admin.pedidos.index', ['estado' => $clave]) }}"
               class="px-3 py-1.5 rounded-full text-xs font-semibold no-underline {{ request('estado') === $clave ? 'bg-[#0407e2] text-white' : 'bg-white text-slate-600 shadow-sm' }}">
                {{ $label }} ({{ $conteos[$clave] ?? 0 }})
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-3xl shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <input type="hidden" name="estado" value="{{ request('estado') }}">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Código, cliente o correo"
                class="flex-1 min-w-[200px] rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <button class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold py-2">Fecha</th>
                        <th class="font-semibold">Pedido</th>
                        <th class="font-semibold">Cliente</th>
                        <th class="font-semibold text-center">Ítems</th>
                        <th class="font-semibold">Método de pago</th>
                        <th class="font-semibold">Estado</th>
                        <th class="font-semibold text-right">Total</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600 whitespace-nowrap">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 text-blue-800 font-medium whitespace-nowrap">{{ $pedido->codigo }}</td>
                            <td class="py-2">
                                <div class="text-slate-700">{{ $pedido->cliente->nombre }}</div>
                                <div class="text-xs text-slate-500">{{ $pedido->distrito }}</div>
                            </td>
                            <td class="py-2 text-center text-slate-600">{{ $pedido->items_count }}</td>
                            <td class="py-2 text-slate-600">{{ $pedido->metodo_pago }}</td>
                            <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $pedido->estado_clase }}">{{ $pedido->estado_label }}</span></td>
                            <td class="py-2 text-right font-semibold text-slate-800 whitespace-nowrap">S/ {{ number_format($pedido->total, 2) }}</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}"
                                   class="px-4 py-1.5 rounded-md bg-[#0407e2] text-white text-xs font-semibold no-underline">Gestionar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-4 text-center text-slate-500">No hay pedidos con estos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pedidos->links() }}</div>
    </div>
@endsection
