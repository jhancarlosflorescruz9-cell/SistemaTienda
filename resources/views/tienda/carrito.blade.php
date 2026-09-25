@extends('layouts.tienda.app')

@section('title', 'Carrito')

@section('content')
    <div class="max-w-6xl mx-auto px-4 mt-6">
        <h1 class="text-2xl font-extrabold mb-4">Carrito ({{ $resumen['lineas']->sum('cantidad') }})</h1>

        @if ($resumen['lineas']->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center">
                <i class="fa-solid fa-cart-shopping text-6xl text-slate-200"></i>
                <p class="mt-4 font-bold text-lg">Tu carrito está vacío</p>
                <a href="{{ route('tienda.inicio') }}" class="inline-block mt-4 bg-marca text-white font-bold rounded-full px-8 py-3">Explorar ofertas</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 space-y-3">
                    @if ($resumen['falta_envio_gratis'] > 0 && $resumen['envio'] > 0)
                        @php $avance = min(100, $resumen['subtotal'] * 100 / \App\Services\Carrito::ENVIO_GRATIS_DESDE); @endphp
                        <div class="bg-white rounded-xl p-4 text-sm">
                            <div><i class="fa-solid fa-truck text-[#0a8800]"></i> Agrega <b>S/ {{ number_format($resumen['falta_envio_gratis'], 2) }}</b> más para obtener <b class="text-[#0a8800]">envío gratis</b></div>
                            <div class="h-2 bg-slate-100 rounded-full mt-2"><div class="h-2 bg-[#0a8800] rounded-full" style="width: {{ $avance }}%"></div></div>
                        </div>
                    @else
                        <div class="bg-green-50 text-[#0a8800] rounded-xl p-3 text-sm font-bold"><i class="fa-solid fa-circle-check"></i> ¡Tu pedido tiene envío gratis!</div>
                    @endif

                    <div class="bg-white rounded-xl divide-y">
                        @foreach ($resumen['lineas'] as $linea)
                            @php $p = $linea->producto; @endphp
                            <div class="p-4 flex gap-4">
                                <a href="{{ route('tienda.producto', $p) }}" class="w-24 h-24 rounded-lg overflow-hidden shrink-0"><x-producto-imagen :producto="$p" icono="text-3xl" /></a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('tienda.producto', $p) }}" class="text-sm line-clamp-2 hover:underline">{{ $p->nombre }}</a>
                                    @if ($p->porcentaje_descuento)<span class="inline-block mt-1 text-[11px] font-bold text-marca border border-marca rounded px-1">-{{ $p->porcentaje_descuento }}%</span>@endif
                                    <div class="flex flex-wrap items-center justify-between gap-2 mt-2">
                                        <div>
                                            <span class="text-marca font-extrabold">S/ {{ number_format($linea->precio, 2) }}</span>
                                            @if ($p->porcentaje_descuento)<span class="text-xs text-slate-400 line-through">S/ {{ number_format($p->precio, 2) }}</span>@endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <form method="POST" action="{{ route('tienda.carrito.actualizar', $p) }}" class="flex items-center border rounded-full">
                                                @csrf @method('PATCH')
                                                <button name="cantidad" value="{{ $linea->cantidad - 1 }}" class="w-8 h-8" aria-label="Menos">−</button>
                                                <span class="w-8 text-center text-sm">{{ $linea->cantidad }}</span>
                                                <button name="cantidad" value="{{ $linea->cantidad + 1 }}" class="w-8 h-8 disabled:text-slate-300" @disabled($linea->cantidad >= $p->stock) aria-label="Más">+</button>
                                            </form>
                                            <form method="POST" action="{{ route('tienda.carrito.actualizar', $p) }}">
                                                @csrf @method('PATCH')
                                                <button name="cantidad" value="0" class="text-slate-400 hover:text-red-600" title="Quitar"><i class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @include('tienda.partials.resumen', ['boton' => true])
            </div>
        @endif
    </div>
@endsection
