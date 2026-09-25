@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-2 mb-5">
        <div>
            <h1 class="text-lg font-semibold text-slate-800 m-0">Hola, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-slate-500 m-0">Resumen de tu tienda virtual · {{ now()->translatedFormat('d/m/Y') }}</p>
        </div>
        <a href="{{ route('tienda.inicio') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold no-underline">
            <i class="fa-solid fa-store"></i> Ver tienda
        </a>
    </div>

    {{-- KPIs --}}
    @php
        $tarjetas = [
            ['Ventas de hoy', 'S/ '.number_format($kpis['ventas_hoy'], 2), 'fa-solid fa-sack-dollar', '#0407e2', route('admin.reportes.ventas')],
            ['Ventas del mes', 'S/ '.number_format($kpis['ventas_mes'], 2), 'fa-solid fa-chart-line', '#16a34a', route('admin.reportes.ventas')],
            ['Pedidos del mes', $kpis['pedidos_mes'], 'fa-solid fa-receipt', '#7c3aed', route('admin.pedidos.index')],
            ['Ticket promedio', 'S/ '.number_format($kpis['ticket'], 2), 'fa-solid fa-ticket', '#0891b2', route('admin.reportes.ventas')],
            ['Por despachar', $kpis['por_despachar'], 'fa-solid fa-truck-fast', '#ea580c', route('admin.pedidos.index', ['estado' => 'pagado'])],
            ['Clientes', $kpis['clientes'], 'fa-solid fa-users', '#0f766e', route('admin.clientes.index')],
            ['Productos activos', $kpis['productos'], 'fa-solid fa-box-open', '#2563eb', route('admin.productos.index')],
            ['Stock bajo', $kpis['stock_bajo'], 'fa-solid fa-triangle-exclamation', '#dc2626', route('admin.inventario.index', ['filtro' => 'bajo'])],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        @foreach ($tarjetas as [$label, $valor, $icono, $color, $url])
            <a href="{{ $url }}" class="bg-white rounded-2xl shadow-md p-4 flex items-center gap-3 no-underline hover:shadow-lg transition">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $color }}1a; color: {{ $color }}">
                    <i class="{{ $icono }} text-lg"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-slate-500">{{ $label }}</div>
                    <div class="text-lg font-bold text-slate-800 truncate">{{ $valor }}</div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-4 mb-5">
        {{-- Gráfico de ventas --}}
        <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl shadow-md p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Ventas de los últimos 14 días</h2>
            <div class="relative h-64"><canvas id="graficoVentas"></canvas></div>
        </div>

        {{-- Estado de pedidos --}}
        <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl shadow-md p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Pedidos por estado</h2>
            <ul class="space-y-3 p-0 m-0 list-none">
                @foreach (\App\Models\Pedido::ESTADOS as $clave => [$label, $clase])
                    <li>
                        <a href="{{ route('admin.pedidos.index', ['estado' => $clave]) }}" class="flex items-center justify-between no-underline">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $clase }}">{{ $label }}</span>
                            <span class="text-sm font-bold text-slate-700">{{ $estados[$clave] ?? 0 }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            @if ($kpis['resenas_pendientes'])
                <a href="{{ route('admin.resenas.index', ['estado' => 'pendientes']) }}"
                   class="mt-5 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 rounded-lg px-3 py-2 no-underline">
                    <i class="fa-solid fa-star"></i> {{ $kpis['resenas_pendientes'] }} reseñas por revisar
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4">
        {{-- Últimos pedidos --}}
        <div class="col-span-12 xl:col-span-6 bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-700 m-0">Últimos pedidos</h2>
                <a href="{{ route('admin.pedidos.index') }}" class="text-xs text-blue-700 no-underline">Ver todos</a>
            </div>
            <table class="w-full text-left text-sm">
                <tbody>
                    @forelse ($ultimosPedidos as $pedido)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="text-blue-800 font-medium no-underline">{{ $pedido->codigo }}</a>
                                <div class="text-xs text-slate-500">{{ $pedido->cliente->nombre }}</div>
                            </td>
                            <td class="py-2 text-xs text-slate-500">{{ $pedido->created_at->diffForHumans() }}</td>
                            <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $pedido->estado_clase }}">{{ $pedido->estado_label }}</span></td>
                            <td class="py-2 text-right font-semibold text-slate-700">S/ {{ number_format($pedido->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td class="py-4 text-center text-slate-500">Aún no hay pedidos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Más vendidos --}}
        <div class="col-span-12 md:col-span-6 xl:col-span-3 bg-white rounded-3xl shadow-md p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Más vendidos</h2>
            <ul class="space-y-3 p-0 m-0 list-none">
                @foreach ($masVendidos as $p)
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0"><x-producto-imagen :producto="$p" icono="text-sm" /></div>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('admin.productos.edit', $p) }}" class="block text-sm text-slate-700 truncate no-underline">{{ $p->nombre }}</a>
                            <div class="text-xs text-slate-500">{{ $p->vendidos }} vendidos</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Stock bajo --}}
        <div class="col-span-12 md:col-span-6 xl:col-span-3 bg-white rounded-3xl shadow-md p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Reponer stock</h2>
            <ul class="space-y-3 p-0 m-0 list-none">
                @forelse ($stockBajo as $p)
                    <li class="flex items-center justify-between gap-2">
                        <a href="{{ route('admin.inventario.index', ['buscar' => $p->codigo]) }}" class="text-sm text-slate-700 truncate no-underline">{{ $p->nombre }}</a>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $p->stock ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }}">{{ $p->stock }}</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Todo el inventario está en orden.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const datos = @json($grafico);
            new Chart(document.getElementById('graficoVentas'), {
                type: 'bar',
                data: {
                    labels: datos.map(d => d.dia),
                    datasets: [{
                        label: 'Ventas (S/)',
                        data: datos.map(d => d.total),
                        backgroundColor: '#0407e2',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { callback: v => 'S/ ' + v } }
                    }
                }
            });
        });
    </script>
@endsection
