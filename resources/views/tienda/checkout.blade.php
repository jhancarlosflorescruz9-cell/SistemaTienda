@extends('layouts.tienda.app')

@section('title', 'Finalizar compra')

@section('content')
    @php $input = 'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-marca'; @endphp
    <div class="max-w-6xl mx-auto px-4 mt-6">
        <h1 class="text-2xl font-extrabold mb-4"><i class="fa-solid fa-lock text-[#0a8800] text-lg"></i> Finalizar compra</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <form id="formCheckout" method="POST" action="{{ route('tienda.checkout.store') }}" class="lg:col-span-2 space-y-4">
                @csrf
                <section class="bg-white rounded-xl p-5">
                    <h2 class="font-extrabold mb-4">1. Datos de contacto y envío</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input name="nombre" value="{{ old('nombre') }}" required placeholder="Nombre completo *" class="{{ $input }} sm:col-span-2">
                        <input name="email" type="email" value="{{ old('email') }}" required placeholder="Correo electrónico *" class="{{ $input }}">
                        <input name="telefono" value="{{ old('telefono') }}" required placeholder="Celular *" class="{{ $input }}">
                        <input name="documento" value="{{ old('documento') }}" placeholder="DNI / RUC (opcional)" class="{{ $input }}">
                        <input name="distrito" value="{{ old('distrito') }}" required placeholder="Distrito / Ciudad *" class="{{ $input }}">
                        <input name="direccion" value="{{ old('direccion') }}" required placeholder="Dirección (calle, número, referencia) *" class="{{ $input }} sm:col-span-2">
                        <textarea name="notas" rows="2" placeholder="Notas para la entrega (opcional)" class="{{ $input }} sm:col-span-2">{{ old('notas') }}</textarea>
                    </div>
                </section>

                <section class="bg-white rounded-xl p-5">
                    <h2 class="font-extrabold mb-4">2. Método de pago</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($metodos as $metodo => $icono)
                            <label class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer has-[:checked]:border-marca has-[:checked]:bg-marca-claro">
                                <input type="radio" name="metodo_pago" value="{{ $metodo }}" required @checked(old('metodo_pago', array_key_first($metodos)) === $metodo) class="accent-[#fb7701]">
                                <i class="{{ $icono }} text-xl w-6 text-center"></i>
                                <span class="text-sm font-semibold">{{ $metodo }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500 mt-3 mb-0"><i class="fa-solid fa-circle-info"></i> Modo demostración: no se realizan cobros reales.</p>
                </section>

                <section class="bg-white rounded-xl p-5">
                    <h2 class="font-extrabold mb-3">3. Revisa tus productos</h2>
                    <div class="divide-y">
                        @foreach ($resumen['lineas'] as $linea)
                            <div class="flex items-center gap-3 py-2">
                                <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0"><x-producto-imagen :producto="$linea->producto" icono="text-xl" /></div>
                                <div class="flex-1 min-w-0 text-sm line-clamp-2">{{ $linea->producto->nombre }}</div>
                                <div class="text-sm text-slate-500">x{{ $linea->cantidad }}</div>
                                <div class="text-sm font-bold w-24 text-right">S/ {{ number_format($linea->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </form>

            <div>
                @include('tienda.partials.resumen')
                <button form="formCheckout" class="w-full mt-3 bg-marca hover:bg-marca-oscuro text-white font-extrabold rounded-full py-4 text-lg">
                    Realizar pedido · S/ {{ number_format($resumen['total'], 2) }}
                </button>
            </div>
        </div>
    </div>
@endsection
