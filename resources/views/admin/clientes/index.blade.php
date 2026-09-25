@extends('layouts.admin.app')

@section('title', 'Clientes')

@section('content')
    <x-admin.header titulo="Clientes de la tienda" icono="fa-regular fa-address-card" seccion="Clientes" />

    <div class="bg-white rounded-3xl shadow-md p-6">
        <form method="GET" class="flex gap-2 mb-4">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre, correo o documento"
                class="flex-1 rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <button class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold py-2">Cliente</th>
                        <th class="font-semibold">Teléfono</th>
                        <th class="font-semibold">Distrito</th>
                        <th class="font-semibold text-center">Pedidos</th>
                        <th class="font-semibold text-right">Total comprado</th>
                        <th class="font-semibold">Última compra</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr class="border-b border-slate-100">
                            <td class="py-2">
                                <div class="text-blue-800 font-medium">{{ $cliente->nombre }}</div>
                                <div class="text-xs text-slate-500">{{ $cliente->email }}</div>
                            </td>
                            <td class="py-2 text-slate-600">{{ $cliente->telefono }}</td>
                            <td class="py-2 text-slate-600">{{ $cliente->distrito }}</td>
                            <td class="py-2 text-center text-slate-600">{{ $cliente->pedidos_count }}</td>
                            <td class="py-2 text-right font-semibold">S/ {{ number_format($cliente->total_comprado ?? 0, 2) }}</td>
                            <td class="py-2 text-slate-600">{{ $cliente->ultima_compra ? \Carbon\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : '—' }}</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('admin.clientes.show', $cliente) }}" class="px-4 py-1.5 rounded-md bg-[#0407e2] text-white text-xs font-semibold no-underline">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $clientes->links() }}</div>
    </div>
@endsection
