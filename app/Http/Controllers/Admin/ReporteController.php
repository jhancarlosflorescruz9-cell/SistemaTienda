<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function ventas(Request $request)
    {
        $desde = Carbon::parse($request->input('desde', now()->subDays(29)->toDateString()))->startOfDay();
        $hasta = Carbon::parse($request->input('hasta', now()->toDateString()))->endOfDay();

        $base = Pedido::where('estado', '!=', 'cancelado')->whereBetween('created_at', [$desde, $hasta]);

        $totales = [
            'pedidos' => (clone $base)->count(),
            'ventas' => (float) (clone $base)->sum('total'),
            'descuentos' => (float) (clone $base)->sum('descuento'),
            'envios' => (float) (clone $base)->sum('envio'),
        ];
        $totales['ticket'] = $totales['pedidos'] ? $totales['ventas'] / $totales['pedidos'] : 0;

        $porDia = (clone $base)->get(['created_at', 'total'])
            ->groupBy(fn ($p) => $p->created_at->toDateString())
            ->map(fn ($g) => ['pedidos' => $g->count(), 'total' => $g->sum('total')])
            ->sortKeys();

        $topProductos = PedidoItem::whereHas('pedido', fn ($q) => $q->where('estado', '!=', 'cancelado')->whereBetween('created_at', [$desde, $hasta]))
            ->selectRaw('nombre, SUM(cantidad) as unidades, SUM(subtotal) as importe')
            ->groupBy('nombre')->orderByDesc('importe')->limit(10)->get();

        $porMetodo = (clone $base)->selectRaw('metodo_pago, COUNT(*) as pedidos, SUM(total) as total')
            ->groupBy('metodo_pago')->orderByDesc('total')->get();

        return view('admin.reportes.ventas', compact('desde', 'hasta', 'totales', 'porDia', 'topProductos', 'porMetodo'));
    }
}
