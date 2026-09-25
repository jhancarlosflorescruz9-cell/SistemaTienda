@extends('layouts.tienda.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">

        {{-- Banner principal --}}
        <section class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2 rounded-2xl bg-gradient-to-r from-[#fb7701] to-[#ff3d00] text-white p-6 sm:p-10 relative overflow-hidden">
                <div class="relative z-10 max-w-md">
                    <span class="inline-block bg-white/20 rounded-full px-3 py-1 text-xs font-bold mb-3">TEMPORADA DE OFERTAS</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Hasta 70% de descuento en miles de productos</h1>
                    <p class="mt-2 text-white/90">Precios de fábrica, envío rápido y devoluciones gratis.</p>
                    <a href="{{ route('tienda.buscar', ['orden' => 'vendidos']) }}" class="inline-block mt-5 bg-white text-marca font-extrabold px-6 py-3 rounded-full hover:scale-105 transition">Comprar ahora</a>
                </div>
                <i class="fa-solid fa-bag-shopping absolute -right-6 -bottom-8 text-[200px] text-white/10"></i>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-1 gap-3">
                <div class="rounded-2xl bg-[#0a8800] text-white p-5 flex flex-col justify-center">
                    <i class="fa-solid fa-truck-fast text-2xl"></i>
                    <div class="font-extrabold mt-2">Envío gratis</div>
                    <div class="text-sm text-white/90">En compras desde S/ {{ number_format(\App\Services\Carrito::ENVIO_GRATIS_DESDE, 0) }}</div>
                </div>
                <div class="rounded-2xl bg-slate-900 text-white p-5 flex flex-col justify-center">
                    <i class="fa-solid fa-ticket text-2xl text-marca"></i>
                    <div class="font-extrabold mt-2">10% en tu 1ª compra</div>
                    <div class="text-sm text-white/80">Cupón: <b class="text-marca">BIENVENIDO10</b></div>
                </div>
            </div>
        </section>

        {{-- Categorías --}}
        <section class="mt-6 bg-white rounded-2xl p-4">
            <div class="flex gap-4 sm:gap-6 overflow-x-auto no-scrollbar justify-start lg:justify-between">
                @foreach ($categorias as $cat)
                    <a href="{{ route('tienda.categoria', $cat) }}" class="flex flex-col items-center gap-2 shrink-0 w-20 group">
                        <span class="w-16 h-16 rounded-full flex items-center justify-center text-2xl group-hover:scale-110 transition" style="background: {{ $cat->color }}1f; color: {{ $cat->color }}">
                            <i class="{{ $cat->icono }}"></i>
                        </span>
                        <span class="text-xs text-center leading-tight group-hover:text-marca">{{ $cat->nombre }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Ofertas flash --}}
        @if ($flash && $productosFlash->isNotEmpty())
            <section class="mt-6 bg-white rounded-2xl p-4">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-extrabold text-marca"><i class="fa-solid fa-bolt"></i> {{ $flash->nombre }}</h2>
                        <div class="flex items-center gap-1 text-sm font-bold" data-cuenta-regresiva="{{ $flash->fecha_fin->toIso8601String() }}">
                            <span class="text-slate-500 font-semibold mr-1">Termina en</span>
                            <span class="bg-slate-900 text-white rounded px-1.5 py-0.5" data-h>00</span>:
                            <span class="bg-slate-900 text-white rounded px-1.5 py-0.5" data-m>00</span>:
                            <span class="bg-slate-900 text-white rounded px-1.5 py-0.5" data-s>00</span>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-marca">Hasta -{{ $flash->descuento }}%</span>
                </div>
                <div class="flex gap-3 overflow-x-auto no-scrollbar pb-1">
                    @foreach ($productosFlash as $producto)
                        <div class="w-40 sm:w-44 shrink-0 border border-slate-100 rounded-lg">
                            <x-tienda.tarjeta-producto :producto="$producto" flash />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Más vendidos --}}
        <section class="mt-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-extrabold"><i class="fa-solid fa-fire text-red-500"></i> Los más vendidos</h2>
                <a href="{{ route('tienda.buscar', ['orden' => 'vendidos']) }}" class="text-sm font-semibold hover:text-marca">Ver todo <i class="fa-solid fa-chevron-right text-xs"></i></a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach ($masVendidos as $producto)
                    <x-tienda.tarjeta-producto :producto="$producto" />
                @endforeach
            </div>
        </section>

        {{-- Recomendados --}}
        <section class="mt-8">
            <h2 class="text-xl font-extrabold text-center mb-4">Recomendados para ti</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach ($recomendados as $producto)
                    <x-tienda.tarjeta-producto :producto="$producto" />
                @endforeach
            </div>
            @if ($recomendados->hasMorePages())
                <div class="text-center mt-6">
                    <a href="{{ $recomendados->nextPageUrl() }}" class="inline-block border-2 border-slate-900 font-bold rounded-full px-8 py-2.5 hover:bg-slate-900 hover:text-white">Ver más productos</a>
                </div>
            @endif
        </section>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('[data-cuenta-regresiva]').forEach(function (el) {
            const fin = new Date(el.dataset.cuentaRegresiva).getTime();
            const pad = n => String(n).padStart(2, '0');
            (function tick() {
                const r = Math.max(0, fin - Date.now());
                el.querySelector('[data-h]').textContent = pad(Math.floor(r / 3600000));
                el.querySelector('[data-m]').textContent = pad(Math.floor(r / 60000) % 60);
                el.querySelector('[data-s]').textContent = pad(Math.floor(r / 1000) % 60);
                if (r > 0) setTimeout(tick, 1000);
            })();
        });
    </script>
@endsection
