<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Resena;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $validos = fn () => Pedido::where('estado', '!=', 'cancelado');

        $kpis = [
            'ventas_hoy' => (float) $validos()->whereDate('created_at', today())->sum('total'),
            'ventas_mes' => (float) $validos()->where('created_at', '>=', now()->startOfMonth())->sum('total'),
            'pedidos_mes' => $validos()->where('created_at', '>=', now()->startOfMonth())->count(),
            'por_despachar' => Pedido::whereIn('estado', ['pendiente', 'pagado'])->count(),
            'clientes' => Cliente::count(),
            'productos' => Producto::where('activo', true)->count(),
            'stock_bajo' => Producto::whereColumn('stock', '<=', 'stock_minimo')->count(),
            'resenas_pendientes' => Resena::where('aprobada', false)->count(),
        ];
        $kpis['ticket'] = $kpis['pedidos_mes'] ? $kpis['ventas_mes'] / $kpis['pedidos_mes'] : 0;

        // Ventas de los últimos 14 días para el gráfico
        $ventas = $validos()->where('created_at', '>=', now()->subDays(13)->startOfDay())->get(['created_at', 'total'])
            ->groupBy(fn ($p) => $p->created_at->toDateString());
        $grafico = collect(range(13, 0))->map(function ($d) use ($ventas) {
            $fecha = now()->subDays($d);

            return [
                'dia' => $fecha->format('d/m'),
                'total' => round((float) optional($ventas->get($fecha->toDateString()))->sum('total'), 2),
            ];
        });

        $ultimosPedidos = Pedido::with('cliente')->latest()->limit(6)->get();
        $masVendidos = Producto::with('categoria')->orderByDesc('vendidos')->limit(5)->get();
        $stockBajo = Producto::whereColumn('stock', '<=', 'stock_minimo')->orderBy('stock')->limit(5)->get();
        $estados = Pedido::selectRaw('estado, COUNT(*) as total')->groupBy('estado')->pluck('total', 'estado');

        return view('admin.dashboard', compact('kpis', 'grafico', 'ultimosPedidos', 'masVendidos', 'stockBajo', 'estados'));
    }
}
