@extends('layouts.tienda.app')

@section('title', $categoria->nombre ?? (request('q') ? 'Resultados para "'.request('q').'"' : 'Todos los productos'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <nav class="text-xs text-slate-500 mb-3">
            <a href="{{ route('tienda.inicio') }}" class="hover:text-marca">Inicio</a> /
            <span class="text-slate-800">{{ $categoria->nombre ?? (request('q') ? 'Búsqueda' : 'Todos los productos') }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-4">
            {{-- Filtros --}}
            <aside class="lg:w-60 shrink-0">
                <form method="GET" class="bg-white rounded-xl p-4 space-y-4 text-sm lg:sticky lg:top-24">
                    @if (request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                    <div>
                        <h3 class="font-bold mb-2">Categorías</h3>
                        <ul class="space-y-1.5">
                            @foreach ($categorias as $cat)
                                <li><a href="{{ route('tienda.categoria', $cat) }}" class="flex items-center gap-2 {{ $categoria?->id === $cat->id ? 'text-marca font-bold' : 'hover:text-marca' }}">
                                    <i class="{{ $cat->icono }} w-4 text-center text-xs"></i> {{ $cat->nombre }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold mb-2">Precio (S/)</h3>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min" value="{{ request('min') }}" placeholder="Mín" min="0" class="w-full border rounded-md px-2 py-1.5">
                            <span>-</span>
                            <input type="number" name="max" value="{{ request('max') }}" placeholder="Máx" min="0" class="w-full border rounded-md px-2 py-1.5">
                        </div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="envio_gratis" value="1" @checked(request()->boolean('envio_gratis')) class="accent-[#fb7701] w-4 h-4">
                        <span><i class="fa-solid fa-truck text-[#0a8800]"></i> Envío gratis</span>
                    </label>
                    <input type="hidden" name="orden" value="{{ request('orden') }}">
                    <button class="w-full bg-marca hover:bg-marca-oscuro text-white font-bold rounded-full py-2">Aplicar filtros</button>
                </form>
            </aside>

            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-xl px-4 py-3 mb-3 flex flex-wrap items-center justify-between gap-2">
                    <h1 class="font-extrabold text-lg">
                        {{ $categoria->nombre ?? (request('q') ? 'Resultados para "'.request('q').'"' : 'Todos los productos') }}
                        <span class="text-sm font-normal text-slate-500">({{ $productos->total() }})</span>
                    </h1>
                    <div class="flex gap-1 text-sm overflow-x-auto no-scrollbar">
                        @foreach (['vendidos' => 'Más vendidos', 'nuevos' => 'Nuevos', 'valorados' => 'Mejor valorados', 'precio_asc' => 'Precio ↑', 'precio_desc' => 'Precio ↓'] as $clave => $label)
                            <a href="{{ request()->fullUrlWithQuery(['orden' => $clave, 'page' => null]) }}"
                               class="px-3 py-1 rounded-full whitespace-nowrap {{ request('orden', 'vendidos') === $clave ? 'bg-slate-900 text-white' : 'hover:bg-slate-100' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>

                @if ($productos->isEmpty())
                    <div class="bg-white rounded-xl p-12 text-center">
                        <i class="fa-solid fa-magnifying-glass text-5xl text-slate-300"></i>
                        <p class="mt-4 font-bold">No encontramos productos</p>
                        <p class="text-sm text-slate-500">Prueba con otras palabras o quita algunos filtros.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach ($productos as $producto)
                            <x-tienda.tarjeta-producto :producto="$producto" />
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $productos->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
