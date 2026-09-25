@extends('layouts.admin.app')

@section('title', 'Reporte de ventas')

@section('content')
    <x-admin.header titulo="Reporte de ventas" icono="fa-regular fa-file" seccion="Reportes">
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-white">
            <i class="fa-solid fa-print"></i> Imprimir
        </button>
    </x-admin.header>

    <form method="GET" class="bg-white rounded-2xl shadow-md p-4 mb-4 flex flex-wrap items-end gap-3">
        <div><label class="block text-xs font-medium text-blue-700 mb-1">Desde</label>
            <input type="date" name="desde" value="{{ $desde->toDateString() }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="block text-xs font-medium text-blue-700 mb-1">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta->toDateString() }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm"></div>
        <button class="px-4 py-2 rounded-md bg-[#0407e2] text-white text-sm font-semibold">Generar</button>
        <span class="text-xs text-slate-500">Excluye pedidos cancelados.</span>
    </form>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
        @foreach ([
            ['Pedidos', $totales['pedidos']],
            ['Ventas totales', 'S/ '.number_format($totales['ventas'], 2)],
            ['Ticket promedio', 'S/ '.number_format($totales['ticket'], 2)],
            ['Descuentos otorgados', 'S/ '.number_format($totales['descuentos'], 2)],
            ['Cobrado por envío', 'S/ '.number_format($totales['envios'], 2)],
        ] as [$label, $valor])
            <div class="bg-white rounded-2xl shadow-md p-4">
                <div class="text-xs text-slate-500">{{ $label }}</div>
                <div class="text-lg font-bold text-slate-800">{{ $valor }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 lg:col-span-7 bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Productos más vendidos</h2>
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-200 text-black"><th class="font-semibold py-2">#</th><th class="font-semibold">Producto</th><th class="font-semibold text-center">Unidades</th><th class="font-semibold text-right">Importe</th></tr></thead>
                <tbody>
                    @forelse ($topProductos as $i => $fila)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-500">{{ $i + 1 }}</td>
                            <td class="py-2 text-blue-800">{{ $fila->nombre }}</td>
                            <td class="py-2 text-center">{{ $fila->unidades }}</td>
                            <td class="py-2 text-right font-semibold">S/ {{ number_format($fila->importe, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-slate-500">Sin ventas en el periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-span-12 lg:col-span-5 space-y-4">
            <div class="bg-white rounded-3xl shadow-md p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Por método de pago</h2>
                <table class="w-full text-left text-sm">
                    @foreach ($porMetodo as $m)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-700">{{ $m->metodo_pago }}</td>
                            <td class="py-2 text-center text-slate-500">{{ $m->pedidos }}</td>
                            <td class="py-2 text-right font-semibold">S/ {{ number_format($m->total, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="bg-white rounded-3xl shadow-md p-6 max-h-96 overflow-y-auto">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Ventas por día</h2>
                <table class="w-full text-left text-sm">
                    @forelse ($porDia as $fecha => $d)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-700">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                            <td class="py-2 text-center text-slate-500">{{ $d['pedidos'] }} ped.</td>
                            <td class="py-2 text-right font-semibold">S/ {{ number_format($d['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-4 text-center text-slate-500">Sin datos.</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
