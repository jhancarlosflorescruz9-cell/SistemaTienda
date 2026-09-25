{{-- Resumen del pedido: se usa en carrito y checkout --}}
<aside class="space-y-3">
    <div class="bg-white rounded-xl p-5 lg:sticky lg:top-24">
        <h2 class="font-extrabold text-lg mb-3">Resumen del pedido</h2>

        <form method="POST" action="{{ route('tienda.carrito.cupon') }}" class="flex gap-2 mb-4">
            @csrf
            <input name="codigo" value="{{ $resumen['cupon']?->codigo }}" placeholder="Código de cupón"
                class="flex-1 border rounded-full px-4 py-2 text-sm uppercase min-w-0">
            <button class="border-2 border-slate-900 rounded-full px-4 text-sm font-bold hover:bg-slate-900 hover:text-white">Aplicar</button>
        </form>
        @if ($resumen['cupon'])
            <div class="flex items-center justify-between text-xs bg-green-50 text-[#0a8800] rounded-lg px-3 py-2 mb-3">
                <span><i class="fa-solid fa-ticket"></i> Cupón <b>{{ $resumen['cupon']->codigo }}</b> aplicado</span>
                <form method="POST" action="{{ route('tienda.carrito.cupon') }}">@csrf<button class="underline">Quitar</button></form>
            </div>
        @endif

        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt>Subtotal</dt><dd>S/ {{ number_format($resumen['subtotal'], 2) }}</dd></div>
            @if ($resumen['descuento'] > 0)
                <div class="flex justify-between text-[#0a8800]"><dt>Descuento cupón</dt><dd>- S/ {{ number_format($resumen['descuento'], 2) }}</dd></div>
            @endif
            <div class="flex justify-between"><dt>Envío</dt><dd>{!! $resumen['envio'] > 0 ? 'S/ '.number_format($resumen['envio'], 2) : '<b class="text-[#0a8800]">GRATIS</b>' !!}</dd></div>
            <div class="flex justify-between border-t pt-3 text-lg font-extrabold"><dt>Total</dt><dd class="text-marca">S/ {{ number_format($resumen['total'], 2) }}</dd></div>
        </dl>

        @php
            $ahorro = $resumen['lineas']->sum(fn ($l) => ($l->producto->precio - $l->precio) * $l->cantidad) + $resumen['descuento'];
        @endphp
        @if ($ahorro > 0)
            <div class="mt-3 text-sm font-bold text-marca text-center">¡Estás ahorrando S/ {{ number_format($ahorro, 2) }}!</div>
        @endif

        @if (! empty($boton))
            <a href="{{ route('tienda.checkout') }}" class="block text-center mt-4 bg-marca hover:bg-marca-oscuro text-white font-extrabold rounded-full py-3.5 text-lg">
                Pagar ({{ $resumen['lineas']->sum('cantidad') }})
            </a>
        @endif
        <div class="mt-4 text-xs text-slate-500 space-y-1">
            <div><i class="fa-solid fa-shield-halved text-[#0a8800]"></i> Pago seguro y protección de compra</div>
            <div><i class="fa-solid fa-rotate-left text-[#0a8800]"></i> Devoluciones gratis por 90 días</div>
        </div>
    </div>
</aside>
