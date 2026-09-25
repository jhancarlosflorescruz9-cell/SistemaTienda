@extends('layouts.tienda.app')

@section('title', $producto->nombre)

@section('content')
    @php $promo = $producto->promocionVigente(); @endphp
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <nav class="text-xs text-slate-500 mb-3">
            <a href="{{ route('tienda.inicio') }}" class="hover:text-marca">Inicio</a> /
            <a href="{{ route('tienda.categoria', $producto->categoria) }}" class="hover:text-marca">{{ $producto->categoria->nombre }}</a> /
            <span class="text-slate-800">{{ \Illuminate\Support\Str::limit($producto->nombre, 50) }}</span>
        </nav>

        <div class="bg-white rounded-2xl p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">
            <div class="aspect-square rounded-xl overflow-hidden bg-slate-50 relative">
                <x-producto-imagen :producto="$producto" icono="text-[120px]" />
                @if ($producto->porcentaje_descuento > 0)
                    <span class="absolute top-3 left-3 bg-marca text-white font-extrabold px-2 py-1 rounded">-{{ $producto->porcentaje_descuento }}%</span>
                @endif
            </div>

            <div>
                <h1 class="text-xl sm:text-2xl font-bold leading-snug">{{ $producto->nombre }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500 mt-2">
                    @if ($producto->calificacion > 0)
                        <span class="text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)<i class="fa-{{ $i <= round($producto->calificacion) ? 'solid' : 'regular' }} fa-star"></i>@endfor
                            <b class="text-slate-800 ml-1">{{ $producto->calificacion }}</b>
                        </span>
                        <span>·</span>
                    @endif
                    <span>{{ number_format($producto->vendidos) }} vendidos</span>
                    @if ($producto->marca)<span>·</span><span>Marca: <b class="text-slate-700">{{ $producto->marca->nombre }}</b></span>@endif
                </div>

                @if ($promo)
                    <div class="mt-4 bg-marca-claro text-marca rounded-lg px-3 py-2 text-sm font-bold flex flex-wrap items-center gap-2" data-cuenta-regresiva="{{ $promo->fecha_fin->toIso8601String() }}">
                        <i class="fa-solid fa-bolt"></i> {{ $promo->nombre }} · termina en
                        <span data-h>00</span>:<span data-m>00</span>:<span data-s>00</span>
                    </div>
                @endif

                <div class="mt-4 flex items-end gap-3">
                    <span class="text-4xl font-extrabold text-marca"><small class="text-lg">S/</small>{{ number_format($producto->precio_final, 2) }}</span>
                    @if ($producto->porcentaje_descuento > 0)
                        <span class="text-slate-400 line-through mb-1">S/ {{ number_format($producto->precio, 2) }}</span>
                        <span class="bg-marca text-white text-xs font-bold rounded px-1.5 py-0.5 mb-1.5">Ahorras S/ {{ number_format($producto->precio - $producto->precio_final, 2) }}</span>
                    @endif
                </div>

                <ul class="mt-5 space-y-2 text-sm">
                    <li class="flex gap-2"><i class="fa-solid fa-truck text-[#0a8800] w-5 mt-0.5"></i>
                        <span>{!! $producto->envio_gratis ? '<b class="text-[#0a8800]">Envío gratis</b>' : 'Envío gratis desde S/ '.number_format(\App\Services\Carrito::ENVIO_GRATIS_DESDE, 0) !!} · Llega en 3 a 7 días hábiles</span></li>
                    <li class="flex gap-2"><i class="fa-solid fa-rotate-left text-[#0a8800] w-5 mt-0.5"></i><span>Devolución gratuita dentro de 90 días</span></li>
                    <li class="flex gap-2"><i class="fa-solid fa-store text-slate-500 w-5 mt-0.5"></i>
                        <span>Vendido por <b>{{ $producto->tienda->nombre ?? 'SistemaTienda' }}</b>
                            @if ($producto->tienda?->verificada)<i class="fa-solid fa-circle-check text-blue-500" title="Vendedor verificado"></i>@endif
                        </span></li>
                </ul>

                <form method="POST" action="{{ route('tienda.carrito.agregar', $producto) }}" class="mt-6">
                    @csrf
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-sm font-semibold">Cantidad</span>
                        <div class="flex items-center border rounded-full">
                            <button type="button" class="w-9 h-9" onclick="const i=this.nextElementSibling; i.value=Math.max(1, +i.value-1)" aria-label="Menos">−</button>
                            <input type="number" name="cantidad" value="1" min="1" max="{{ min($producto->stock, 99) }}" class="w-12 text-center outline-none [appearance:textfield]">
                            <button type="button" class="w-9 h-9" onclick="const i=this.previousElementSibling; i.value=Math.min(+i.max, +i.value+1)" aria-label="Más">+</button>
                        </div>
                        <span class="text-xs {{ $producto->stock <= 5 ? 'text-red-600 font-bold' : 'text-slate-500' }}">
                            {{ $producto->stock > 0 ? ($producto->stock <= 5 ? '¡Solo quedan '.$producto->stock.'!' : $producto->stock.' disponibles') : 'Agotado' }}
                        </span>
                    </div>
                    @if ($producto->stock > 0)
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button class="flex-1 bg-marca hover:bg-marca-oscuro text-white font-extrabold rounded-full py-3.5 text-lg">
                                <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
                            </button>
                            <button name="comprar_ahora" value="1" class="flex-1 border-2 border-slate-900 font-extrabold rounded-full py-3 text-lg hover:bg-slate-900 hover:text-white">
                                Comprar ahora
                            </button>
                        </div>
                    @else
                        <button disabled class="w-full bg-slate-300 text-white font-extrabold rounded-full py-3.5 text-lg">Agotado</button>
                    @endif
                </form>

                <div class="mt-4 text-xs text-slate-500 flex items-center gap-2"><i class="fa-solid fa-lock"></i> Tus datos y pagos están protegidos</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            <section class="lg:col-span-2 bg-white rounded-2xl p-6">
                <h2 class="font-extrabold text-lg mb-3">Detalles del producto</h2>
                <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $producto->descripcion ?: 'Sin descripción.' }}</div>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm mt-4 border-t pt-4">
                    <dt class="text-slate-500">Código</dt><dd>{{ $producto->codigo }}</dd>
                    <dt class="text-slate-500">Categoría</dt><dd>{{ $producto->categoria->nombre }}</dd>
                    @if ($producto->marca)<dt class="text-slate-500">Marca</dt><dd>{{ $producto->marca->nombre }}</dd>@endif
                </dl>
            </section>

            <section class="bg-white rounded-2xl p-6">
                <h2 class="font-extrabold text-lg mb-3">Reseñas ({{ $resenas->count() }})</h2>
                <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                    @forelse ($resenas as $r)
                        <div class="border-b pb-3 last:border-0">
                            <div class="flex items-center justify-between text-sm">
                                <b>{{ $r->autor }}</b>
                                <span class="text-amber-400 text-xs">@for ($i = 1; $i <= 5; $i++)<i class="fa-{{ $i <= $r->calificacion ? 'solid' : 'regular' }} fa-star"></i>@endfor</span>
                            </div>
                            <p class="text-sm text-slate-600 mt-1">{{ $r->comentario }}</p>
                            <span class="text-[11px] text-slate-400">{{ $r->created_at->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sé el primero en opinar sobre este producto.</p>
                    @endforelse
                </div>
                <details class="mt-4">
                    <summary class="cursor-pointer text-sm font-bold text-marca">Escribir una reseña</summary>
                    <form method="POST" action="{{ route('tienda.resena', $producto) }}" class="space-y-2 mt-3 text-sm">
                        @csrf
                        <input name="autor" required maxlength="120" placeholder="Tu nombre" class="w-full border rounded-md px-3 py-2">
                        <select name="calificacion" class="w-full border rounded-md px-3 py-2">
                            @for ($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ str_repeat('★', $i) }} ({{ $i }})</option>@endfor
                        </select>
                        <textarea name="comentario" rows="3" maxlength="1000" placeholder="¿Qué te pareció?" class="w-full border rounded-md px-3 py-2"></textarea>
                        <button class="w-full bg-slate-900 text-white font-bold rounded-full py-2">Enviar reseña</button>
                    </form>
                </details>
            </section>
        </div>

        @if ($relacionados->isNotEmpty())
            <section class="mt-6">
                <h2 class="text-xl font-extrabold mb-3">También te puede gustar</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach ($relacionados as $p)
                        <x-tienda.tarjeta-producto :producto="$p" />
                    @endforeach
                </div>
            </section>
        @endif
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
