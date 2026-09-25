@props(['producto', 'flash' => false])
@php
    $vendidos = $producto->vendidos >= 1000 ? number_format($producto->vendidos / 1000, 1).'k' : $producto->vendidos;
@endphp
<div class="group bg-white rounded-lg overflow-hidden hover:shadow-lg transition relative flex flex-col">
    <a href="{{ route('tienda.producto', $producto) }}" class="block aspect-square relative overflow-hidden bg-slate-50">
        <div class="w-full h-full group-hover:scale-105 transition duration-300">
            <x-producto-imagen :producto="$producto" icono="text-5xl" />
        </div>
        @if ($producto->porcentaje_descuento > 0)
            <span class="absolute top-2 left-2 bg-marca text-white text-xs font-extrabold px-1.5 py-0.5 rounded">-{{ $producto->porcentaje_descuento }}%</span>
        @endif
        @if ($producto->stock <= 5)
            <span class="absolute bottom-0 inset-x-0 bg-red-600/90 text-white text-[11px] font-bold text-center py-0.5">¡Solo quedan {{ $producto->stock }}!</span>
        @endif
    </a>
    <div class="p-2.5 flex flex-col flex-1">
        <a href="{{ route('tienda.producto', $producto) }}" class="text-[13px] leading-snug line-clamp-2 min-h-[2.5rem] hover:underline">{{ $producto->nombre }}</a>
        @if ($flash || $producto->promocionVigente())
            <div class="text-[11px] text-marca font-bold mt-1"><i class="fa-solid fa-bolt"></i> Oferta relámpago</div>
        @elseif ($producto->envio_gratis)
            <div class="text-[11px] text-[#0a8800] font-bold mt-1"><i class="fa-solid fa-truck"></i> Envío gratis</div>
        @endif
        <div class="mt-auto pt-1.5 flex items-end justify-between gap-2">
            <div class="min-w-0">
                <span class="text-marca font-extrabold text-lg leading-none"><small class="text-xs">S/</small>{{ number_format($producto->precio_final, 2) }}</span>
                @if ($producto->porcentaje_descuento > 0)
                    <span class="text-[11px] text-slate-400 line-through ml-1">S/{{ number_format($producto->precio, 2) }}</span>
                @endif
                <div class="text-[11px] text-slate-500 mt-0.5">
                    @if ($producto->calificacion > 0)<i class="fa-solid fa-star text-amber-400"></i> {{ $producto->calificacion }} · @endif
                    {{ $vendidos }} vendidos
                </div>
            </div>
            <form method="POST" action="{{ route('tienda.carrito.agregar', $producto) }}">
                @csrf
                <button class="w-8 h-8 rounded-full border-2 border-slate-800 flex items-center justify-center hover:bg-marca hover:border-marca hover:text-white transition" title="Agregar al carrito" aria-label="Agregar al carrito">
                    <i class="fa-solid fa-cart-plus text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</div>
