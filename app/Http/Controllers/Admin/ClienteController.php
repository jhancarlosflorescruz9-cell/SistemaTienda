<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::withCount('pedidos')
            ->withSum(['pedidos as total_comprado' => fn ($q) => $q->where('estado', '!=', 'cancelado')], 'total')
            ->withMax('pedidos as ultima_compra', 'created_at')
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('nombre', 'like', "%{$b}%")->orWhere('email', 'like', "%{$b}%")->orWhere('documento', 'like', "%{$b}%")))
            ->orderByDesc('total_comprado')
            ->paginate(20)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['pedidos' => fn ($q) => $q->latest()->withCount('items')]);

        return view('admin.clientes.show', compact('cliente'));
    }
}
