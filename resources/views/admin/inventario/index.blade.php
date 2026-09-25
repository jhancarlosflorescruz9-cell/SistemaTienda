@extends('layouts.admin.app')

@section('title', 'Inventario')

@section('content')
    <x-admin.header titulo="Control de stock" icono="fa-solid fa-warehouse" seccion="Inventario" />

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        @foreach ([
            ['Unidades en stock', number_format($resumen['unidades']), 'fa-solid fa-cubes', '#0407e2', null],
            ['Inventario valorizado', 'S/ '.number_format($resumen['valorizado'], 2), 'fa-solid fa-coins', '#16a34a', null],
            ['Stock bajo', $resumen['bajo'], 'fa-solid fa-triangle-exclamation', '#ea580c', 'bajo'],
            ['Agotados', $resumen['agotados'], 'fa-solid fa-ban', '#dc2626', 'agotado'],
        ] as [$label, $valor, $icono, $color, $filtro])
            <a href="{{ route('admin.inventario.index', array_filter(['filtro' => $filtro])) }}"
               class="bg-white rounded-2xl shadow-md p-4 flex items-center gap-3 no-underline {{ request('filtro') === $filtro && $filtro ? 'ring-2 ring-[#0407e2]' : '' }}">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: {{ $color }}1a; color: {{ $color }}"><i class="{{ $icono }} text-lg"></i></div>
                <div><div class="text-xs text-slate-500">{{ $label }}</div><div class="text-lg font-bold text-slate-800">{{ $valor }}</div></div>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-3xl shadow-md p-6">
        <form method="GET" class="flex gap-2 mb-4">
            <input type="hidden" name="filtro" value="{{ request('filtro') }}">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o código"
                class="flex-1 rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <button class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold py-2">Código</th><th class="font-semibold">Producto</th><th class="font-semibold">Categoría</th>
                        <th class="font-semibold text-center">Stock</th><th class="font-semibold text-center">Mínimo</th>
                        <th class="font-semibold text-right">Valorizado</th><th class="font-semibold text-right">Ajuste rápido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $p)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $p->codigo }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $p->nombre }}</td>
                            <td class="py-2 text-slate-600">{{ $p->categoria->nombre }}</td>
                            <td class="py-2 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $p->stock <= 0 ? 'bg-red-100 text-red-700' : ($p->stock_bajo ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">{{ $p->stock }}</span>
                            </td>
                            <td class="py-2 text-center text-slate-600">{{ $p->stock_minimo }}</td>
                            <td class="py-2 text-right text-slate-700">S/ {{ number_format($p->stock * $p->precio, 2) }}</td>
                            <td class="py-2">
                                <form method="POST" action="{{ route('admin.inventario.ajustar', $p) }}" class="flex justify-end gap-1">
                                    @csrf
                                    <input type="number" name="cantidad" placeholder="+/-" required
                                        class="w-20 rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-sm text-center">
                                    <button class="px-3 py-1 rounded-md bg-[#0407e2] text-white text-xs font-semibold">Aplicar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay productos con este filtro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $productos->links() }}</div>
    </div>
@endsection
