<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Compra más, paga menos') | {{ config('tienda.nombre', 'SistemaTienda') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { marca: { DEFAULT: '#fb7701', oscuro: '#e06a00', claro: '#fff3e8' } },
                    fontFamily: { sans: ['"Nunito Sans"', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { scrollbar-width: none; }
    </style>
    @yield('css')
</head>
<body class="bg-[#f5f5f5] text-slate-800 font-sans antialiased">

    {{-- Barra de beneficios --}}
    <div class="bg-[#0a8800] text-white text-xs sm:text-sm">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-center gap-6 overflow-x-auto no-scrollbar whitespace-nowrap">
            <span><i class="fa-solid fa-truck-fast"></i> Envío gratis desde S/ {{ number_format(\App\Services\Carrito::ENVIO_GRATIS_DESDE, 0) }}</span>
            <span class="hidden sm:inline"><i class="fa-solid fa-rotate-left"></i> Devoluciones gratis por 90 días</span>
            <span class="hidden md:inline"><i class="fa-solid fa-shield-halved"></i> Pago 100% seguro</span>
            <span class="hidden lg:inline"><i class="fa-solid fa-ticket"></i> Usa <b>BIENVENIDO10</b> en tu primera compra</span>
        </div>
    </div>

    {{-- Header --}}
    <header class="bg-white sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-3 sm:gap-6">
            <a href="{{ route('tienda.inicio') }}" class="flex items-center gap-2 shrink-0">
                <span class="w-9 h-9 rounded-xl bg-marca text-white flex items-center justify-center"><i class="fa-solid fa-bag-shopping"></i></span>
                <span class="hidden sm:block font-extrabold text-lg tracking-tight">Sistema<span class="text-marca">Tienda</span></span>
            </a>

            {{-- Categorías (desktop) --}}
            <div class="relative group hidden lg:block">
                <button class="flex items-center gap-2 font-semibold text-sm hover:text-marca py-5">
                    <i class="fa-solid fa-bars"></i> Categorías <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <div class="absolute left-0 top-full hidden group-hover:block bg-white rounded-b-xl shadow-xl w-64 py-2">
                    @foreach ($menuCategorias as $cat)
                        <a href="{{ route('tienda.categoria', $cat) }}" class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-marca-claro hover:text-marca">
                            <i class="{{ $cat->icono }} w-5 text-center" style="color: {{ $cat->color }}"></i> {{ $cat->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('tienda.buscar') }}" method="GET" class="flex-1">
                <div class="flex items-center border-2 border-slate-900 rounded-full overflow-hidden h-10 bg-white">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar productos..."
                        class="flex-1 px-4 text-sm outline-none min-w-0">
                    <button class="bg-slate-900 text-white h-full px-4 sm:px-5 rounded-full m-0.5" aria-label="Buscar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <nav class="flex items-center gap-1 sm:gap-3 shrink-0 text-sm">
                <a href="{{ route('tienda.seguimiento') }}" class="hidden md:flex flex-col items-center px-2 hover:text-marca">
                    <i class="fa-solid fa-box"></i><span class="text-[11px]">Mis pedidos</span>
                </a>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="hidden md:flex flex-col items-center px-2 hover:text-marca">
                    <i class="fa-regular fa-user"></i><span class="text-[11px]">{{ auth()->check() ? 'Panel' : 'Ingresar' }}</span>
                </a>
                <a href="{{ route('tienda.carrito') }}" class="relative flex flex-col items-center px-2 hover:text-marca">
                    <i class="fa-solid fa-cart-shopping text-lg"></i><span class="text-[11px] hidden sm:block">Carrito</span>
                    @if ($carritoCantidad)
                        <span class="absolute -top-1 right-0 bg-marca text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $carritoCantidad }}</span>
                    @endif
                </a>
            </nav>
        </div>

        {{-- Categorías (móvil) --}}
        <div class="lg:hidden border-t border-slate-100 overflow-x-auto no-scrollbar">
            <div class="flex gap-4 px-4 py-2 text-sm whitespace-nowrap">
                @foreach ($menuCategorias as $cat)
                    <a href="{{ route('tienda.categoria', $cat) }}" class="{{ request()->route('categoria')?->id === $cat->id ? 'text-marca font-bold' : 'text-slate-600' }}">{{ $cat->nombre }}</a>
                @endforeach
            </div>
        </div>
    </header>

    {{-- Mensajes --}}
    @if (session('success') || session('error') || $errors->any())
        <div id="toast" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-md">
            @if (session('success'))
                <div class="bg-slate-900/90 text-white text-sm rounded-xl px-4 py-3 shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-400"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div class="bg-red-600 text-white text-sm rounded-xl px-4 py-3 shadow-lg mt-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') ?? $errors->first() }}
                </div>
            @endif
        </div>
        <script>setTimeout(() => document.getElementById('toast')?.remove(), 4000);</script>
    @endif

    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            <div class="col-span-2 md:col-span-1">
                <div class="font-extrabold text-white text-lg mb-2">Sistema<span class="text-marca">Tienda</span></div>
                <p class="text-slate-400">Miles de productos a precios de fábrica, de vendedores verificados, con envío a todo el Perú.</p>
            </div>
            <div>
                <h3 class="text-white font-bold mb-3">Ayuda</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('tienda.seguimiento') }}" class="hover:text-white">Rastrear mi pedido</a></li>
                    <li><span>Envíos y entregas</span></li>
                    <li><span>Devoluciones y reembolsos</span></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-bold mb-3">Vende con nosotros</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}" class="hover:text-white">Portal de vendedores</a></li>
                    <li><span>Programa de afiliados</span></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-bold mb-3">Pagos seguros</h3>
                <div class="flex flex-wrap gap-2 text-2xl text-slate-400">
                    <i class="fa-brands fa-cc-visa"></i><i class="fa-brands fa-cc-mastercard"></i><i class="fa-brands fa-cc-amex"></i>
                    <i class="fa-solid fa-mobile-screen-button"></i><i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 text-center text-xs text-slate-500 py-4">© {{ date('Y') }} SistemaTienda · Todos los derechos reservados</div>
    </footer>

    @yield('js')
</body>
</html>
