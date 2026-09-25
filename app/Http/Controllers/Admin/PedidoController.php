<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = Pedido::with('cliente')->withCount('items')
            ->when($request->estado, fn ($q, $e) => $q->where('estado', $e))
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('codigo', 'like', "%{$b}%")
                ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$b}%")->orWhere('email', 'like', "%{$b}%"))))
            ->when($request->desde, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($request->hasta, fn ($q, $h) => $q->whereDate('created_at', '<=', $h))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conteos = Pedido::select('estado', DB::raw('count(*) as total'))->groupBy('estado')->pluck('total', 'estado');

        return view('admin.pedidos.index', compact('pedidos', 'conteos'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['cliente', 'items.producto', 'cupon']);

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'estado' => 'required|in:'.implode(',', array_keys(Pedido::ESTADOS)),
            'tracking' => 'nullable|string|max:60',
            'notas' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($pedido, $data) {
            // Al cancelar se devuelve el stock; al reactivar se vuelve a descontar.
            $cancelando = $data['estado'] === 'cancelado' && $pedido->estado !== 'cancelado';
            $reactivando = $pedido->estado === 'cancelado' && $data['estado'] !== 'cancelado';
            if ($cancelando || $reactivando) {
                $signo = $cancelando ? 1 : -1;
                foreach ($pedido->items()->with('producto')->get() as $item) {
                    if ($item->producto) {
                        $item->producto->increment('stock', $signo * $item->cantidad);
                        $item->producto->decrement('vendidos', min($signo * $item->cantidad, $item->producto->vendidos));
                    }
                }
            }
            $pedido->update($data);
        });

        return back()->with('success', 'Pedido '.$pedido->codigo.' actualizado.');
    }
}
