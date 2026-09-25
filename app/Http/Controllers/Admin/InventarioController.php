<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->when($request->filtro === 'bajo', fn ($q) => $q->whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0))
            ->when($request->filtro === 'agotado', fn ($q) => $q->where('stock', '<=', 0))
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('nombre', 'like', "%{$b}%")->orWhere('codigo', 'like', "%{$b}%")))
            ->orderBy('stock')
            ->paginate(20)
            ->withQueryString();

        $resumen = [
            'unidades' => (int) Producto::sum('stock'),
            'valorizado' => (float) Producto::selectRaw('COALESCE(SUM(stock * precio), 0) as v')->value('v'),
            'bajo' => Producto::whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0)->count(),
            'agotados' => Producto::where('stock', '<=', 0)->count(),
        ];

        return view('admin.inventario.index', compact('productos', 'resumen'));
    }

    /** Ajuste rápido: suma o resta unidades al stock. */
    public function ajustar(Request $request, Producto $producto)
    {
        $data = $request->validate(['cantidad' => 'required|integer|not_in:0']);
        $nuevo = max(0, $producto->stock + $data['cantidad']);
        $producto->update(['stock' => $nuevo]);

        return back()->with('success', "Stock de {$producto->codigo} ajustado a {$nuevo} unidades.");
    }
}
