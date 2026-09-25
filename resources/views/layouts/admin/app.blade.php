<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    @yield('title', 'SistemaTienda')
    </title>
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @yield('css')
    <style>
        :root {
            --active-pink: #0407e2;
            --text-main: #1e2a4a;
            --text-soft: #56618a;
            --sidebar-bg: #ffffff;
            --hover-bg: #f0f6ff;
        }
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
        }
        .sidebar-left {
            position: fixed;
            left: 0;
            top: 0;
            width: 270px;
            height: 100vh;
            background: var(--sidebar-bg);
            box-shadow: 2px 0 18px rgba(30, 42, 74, .06);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-header img {
            max-width: 56px;
            max-height: 56px;
            object-fit: contain;
        }

        .nano {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .nano::-webkit-scrollbar {
            width: 4px;
        }

        .nano::-webkit-scrollbar-thumb {
            background: #e5e7f5;
            border-radius: 10px;
        }

        .nano-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-main {
            list-style: none;
            padding: 6px 14px;
            margin: 0;
            flex: 1;
        }

        .nav-main > li {
            list-style: none;
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            color: var(--text-main);
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 500;
            transition: background .15s ease, color .15s ease;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: var(--text-soft);
            flex-shrink: 0;
        }

        .nav-link .chevron {
            margin-left: auto;
            font-size: 12px;
            color: #b7bfd9;
        }

        .nav-link .badge-beta {
            margin-left: auto;
            margin-right: 6px;
            background: #ffb020;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: .3px;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: var(--active-pink);
        }

        .nav-link:hover i,
        .nav-link:hover .chevron {
            color: var(--active-pink);
        }

        /* Active / selected item -> pink */
        .nav-active > .nav-link,
        .nav-link.is-active {
            background: var(--active-pink);
            color: #fff;
        }

        .nav-active > .nav-link i,
        .nav-link.is-active i,
        .nav-active > .nav-link .chevron,
        .nav-link.is-active .chevron {
            color: #fff;
        }

        .nav-parent > .nav-link {
            font-weight: 500;
        }

        .nav-children {
            display: none;
            list-style: none;
            padding-left: 18px;
            margin: 2px 0 4px 0;
        }

        .nav-parent.is-open > .nav-children {
            display: block;
        }

        .nav-parent .chevron {
            transition: transform .2s ease;
        }

        .nav-parent.is-open > .nav-link .chevron {
            transform: rotate(180deg);
        }

        .nav-children .nav-link.is-current {
            color: var(--active-pink);
            font-weight: 600;
            background: var(--hover-bg);
        }

        .nav-children .nav-link {
            font-size: 13.5px;
            padding: 8px 14px;
        }

        .sidebar-footer {
            border-top: 1px solid #eef0f7;
            padding: 16px 14px 22px 14px;
            flex-shrink: 0;
        }

        .sidebar-footer .nav-link {
            color: var(--text-soft);
        }
        .topbar {
            position: fixed;
            top: 0;
            left: 270px;
            right: 0;
            height: 70px;
            background: #fff;
            box-shadow: 0 2px 12px rgba(30, 42, 74, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            gap: 14px;
            z-index: 900;
            transition: left .25s ease;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-soft);
            font-size: 16px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background: var(--hover-bg);
            color: var(--active-pink);
        }

        .topbar-shortcuts {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .topbar-shortcuts::-webkit-scrollbar {
            display: none;
        }

        .shortcut-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            min-width: 54px;
            padding: 6px 10px;
            border-radius: 10px;
            background: #f4f5fb;
            color: var(--text-soft);
            text-decoration: none;
            font-size: 10.5px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .shortcut-btn i {
            font-size: 15px;
            color: var(--text-main);
        }

        .shortcut-btn:hover {
            background: var(--hover-bg);
            color: var(--active-pink);
        }

        .shortcut-btn:hover i {
            color: var(--active-pink);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .demo-badge {
            background: linear-gradient(135deg, #0407e2,#0407e2);
            color: #fff;
            border-radius: 12px;
            padding: 6px 16px;
            text-align: center;
            line-height: 1.25;
            flex-shrink: 0;
        }

        .demo-badge strong {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
        }

        .demo-badge span {
            display: block;
            font-size: 10px;
            opacity: .9;
        }

        .icon-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            font-size: 17px;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: var(--hover-bg);
        }

        .icon-btn .count {
            position: absolute;
            top: 2px;
            right: 2px;
            min-width: 16px;
            height: 16px;
            padding: 0 3px;
            border-radius: 20px;
            background: #3f6fff;
            color: #fff;
            font-size: 9.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn.notif .count {
            background: #1bc405;
        }

        .admin-block {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .admin-text {
            text-align: right;
            line-height: 1.25;
        }

        .admin-text strong {
            display: block;
            font-size: 13px;
            color: var(--text-main);
        }

        .admin-text span {
            display: block;
            font-size: 11px;
            color: var(--text-soft);
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #eef0f7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-soft);
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20, 24, 42, .45);
            z-index: 999;
        }

        .main-content {
            margin-left: 270px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
            padding: 25px;
            transition: margin-left .25s ease;
        }
        body.sidebar-collapsed .sidebar-left {
            left: -270px;
        }

        body.sidebar-collapsed .topbar {
            left: 0;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }

        .sidebar-toggle i {
            transition: transform .25s ease;
        }

        body.sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        @media (max-width: 991.98px) {
            .sidebar-left {
                box-shadow: 4px 0 24px rgba(20, 24, 42, .18);
            }

            body:not(.sidebar-collapsed) .sidebar-overlay {
                display: block;
            }

            .topbar {
                left: 0;
                padding: 0 14px;
                gap: 8px;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .topbar-shortcuts {
                display: none;
            }

            .admin-text {
                display: none;
            }

            .demo-badge span {
                display: none;
            }

            .demo-badge {
                padding: 6px 10px;
            }

            .demo-badge strong {
                font-size: 10.5px;
            }
        }
        
    </style>
</head>

<body>
    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <aside id="sidebar-left" class="sidebar-left">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('image/logo.jpeg') }}" alt="Logo">
            </a>
        </div>
        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main-wrapper">
                    @php
                        // [icono, título, patrón de ruta activa, [[texto, url, patrón]...]]
                        $menu = [
                            ['fa-solid fa-receipt', 'Pedidos', 'admin.pedidos.*', [
                                ['Todos los pedidos', route('admin.pedidos.index'), null],
                                ['Por confirmar', route('admin.pedidos.index', ['estado' => 'pendiente']), null],
                                ['Por despachar', route('admin.pedidos.index', ['estado' => 'pagado']), null],
                                ['En camino', route('admin.pedidos.index', ['estado' => 'enviado']), null],
                            ]],
                            ['fa-solid fa-box-open', 'Productos', 'admin.productos.*|admin.categorias.*|admin.marcas.*', [
                                ['Productos', route('admin.productos.index'), 'admin.productos.index'],
                                ['Nuevo producto', route('admin.productos.create'), 'admin.productos.create'],
                                ['Categorías', route('admin.categorias.index'), 'admin.categorias.*'],
                                ['Marcas', route('admin.marcas.index'), 'admin.marcas.*'],
                            ]],
                            ['fa-solid fa-bolt', 'Marketing', 'admin.promociones.*|admin.cupones.*', [
                                ['Ofertas flash y promociones', route('admin.promociones.index'), 'admin.promociones.*'],
                                ['Cupones de descuento', route('admin.cupones.index'), 'admin.cupones.*'],
                            ]],
                            ['fa-solid fa-store', 'Marketplace', 'admin.vendedores.*', [
                                ['Vendedores', route('admin.vendedores.index'), 'admin.vendedores.*'],
                            ]],
                            ['fa-regular fa-address-card', 'Clientes', 'admin.clientes.*|admin.resenas.*', [
                                ['Clientes', route('admin.clientes.index'), 'admin.clientes.*'],
                                ['Reseñas', route('admin.resenas.index'), 'admin.resenas.*'],
                            ]],
                            ['fa-solid fa-warehouse', 'Inventario', 'admin.inventario.*', [
                                ['Stock', route('admin.inventario.index'), null],
                                ['Stock bajo', route('admin.inventario.index', ['filtro' => 'bajo']), null],
                                ['Agotados', route('admin.inventario.index', ['filtro' => 'agotado']), null],
                            ]],
                            ['fa-regular fa-file', 'Reportes', 'admin.reportes.*', [
                                ['Reporte de ventas', route('admin.reportes.ventas'), 'admin.reportes.ventas'],
                            ]],
                        ];
                    @endphp
                    <ul class="nav-main">
                        <li class="{{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fa-regular fa-compass"></i>
                                <span>DASHBOARD</span>
                            </a>
                        </li>

                        @foreach ($menu as [$icono, $titulo, $patron, $hijos])
                            @php $abierto = request()->routeIs(...explode('|', $patron)); @endphp
                            <li class="nav-parent {{ $abierto ? 'is-open' : '' }}">
                                <a class="nav-link {{ $abierto ? 'is-active' : '' }}" href="#" data-toggle-submenu>
                                    <i class="{{ $icono }}"></i>
                                    <span>{{ $titulo }}</span>
                                    <i class="fas fa-chevron-down chevron"></i>
                                </a>
                                <ul class="nav-children">
                                    @foreach ($hijos as [$texto, $url, $activo])
                                        @php $esActual = $activo ? request()->routeIs($activo) : request()->fullUrl() === $url; @endphp
                                        <li><a class="nav-link {{ $esActual ? 'is-current' : '' }}" href="{{ $url }}">{{ $texto }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach

                        <li>
                            <a class="nav-link" href="{{ route('tienda.inicio') }}" target="_blank">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Ver tienda virtual</span>
                            </a>
                        </li>
                    </ul>

                </nav>

            </div>
        </div>
        
        <div class="sidebar-footer">
            <a class="nav-link {{ request()->routeIs('configuracion.menu') ? 'is-active' : '' }}"
            href="{{ route('configuracion.menu') }}">
                <i class="fa-solid fa-gear"></i>
                <span>Configuración y más</span>
            </a>
        </div>

    </aside>
    <header class="topbar">
        <div class="topbar-left">
            <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Mostrar/ocultar menú">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="topbar-shortcuts">
                <a href="{{ route('admin.productos.create') }}" class="shortcut-btn" title="Nuevo producto">
                    <i class="fa-solid fa-square-plus" style="font-size: 23px;"></i>
                    PROD
                </a>
                <a href="{{ route('admin.pedidos.index') }}" class="shortcut-btn" title="Pedidos">
                    <i class="fa-solid fa-receipt" style="font-size: 23px;"></i>
                    PED
                </a>
                <a href="{{ route('admin.promociones.index') }}" class="shortcut-btn" title="Ofertas flash">
                    <i class="fa-solid fa-bolt" style="font-size: 23px;"></i>
                    FLASH
                </a>
                <a href="{{ route('tienda.inicio') }}" target="_blank" class="shortcut-btn" title="Ver tienda">
                    <i class="fa-solid fa-store" style="font-size: 23px;"></i>
                    TIENDA
                </a>
            </div>
        </div>
        <div class="topbar-right">
            <div class="demo-badge">
                <strong>Modo: DEMO</strong>
                <span>Tienda en línea</span>
            </div>
            <a href="{{ route('admin.pedidos.index', ['estado' => 'pagado']) }}" class="icon-btn" title="Pedidos por despachar">
                <i class="fas fa-cart-shopping"></i>
                <span class="count">{{ $navPedidos }}</span>
            </a>

            <a href="{{ route('admin.inventario.index', ['filtro' => 'bajo']) }}" class="icon-btn notif" title="Stock bajo y reseñas pendientes">
                <i class="fas fa-bell"></i>
                <span class="count">{{ $navAlertas }}</span>
            </a>

            <div class="admin-block" style="position: relative; cursor: pointer;" onclick="document.getElementById('logoutMenu').classList.toggle('d-none')">
                <div class="admin-text">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                <div class="admin-avatar">
                    <i class="fas fa-circle-user"></i>
                </div>
                <div id="logoutMenu" class="d-none"
                    style="position: absolute; top: 50px; right: 0; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,.15); min-width: 160px; z-index: 9999;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            style="width: 100%; border: none; background: none; padding: 12px 16px; text-align: left; cursor: pointer;">
                            <i class="fas fa-right-from-bracket"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </header>
    <main class="main-content">

        @yield('content')

    </main>

    @yield('js')
    <script>
        function abrirModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function cerrarModal(id) { document.getElementById(id).classList.add('hidden'); }
        function confirmarEliminar(url) {
            document.getElementById('formEliminar').action = url;
            document.getElementById('modalEliminar').classList.remove('hidden');
        }
    </script>

    <script>
        (function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay   = document.getElementById('sidebarOverlay');
            if (window.innerWidth <= 991.98) {
                document.body.classList.add('sidebar-collapsed');
            }
            toggleBtn.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-collapsed');
            });
            overlay.addEventListener('click', function () {
                document.body.classList.add('sidebar-collapsed');
            });
            document.querySelectorAll('[data-toggle-submenu]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    link.parentElement.classList.toggle('is-open');
                });
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth > 991.98) {
                    document.body.classList.remove('sidebar-collapsed');
                } else {
                    document.body.classList.add('sidebar-collapsed');
                }
            });
        })();
    </script>
    {{-- ================= MENSAJE FLOTANTE DE ÉXITO ================= --}}
    {{-- Colócalo dentro de tu layout principal (justo después de abrir <body> o
        dentro del topbar), y en tus controladores usa:
        return redirect()->back()->with('success', 'Banco editado con éxito'); --}}

    @if (session('success'))
        <div id="alertaExito"
            class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex items-center gap-2
                    bg-green-50 border border-green-100 text-green-600 text-sm font-medium
                    px-5 py-2.5 rounded-lg shadow-sm">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>

        <script>
            setTimeout(function () {
                const alerta = document.getElementById('alertaExito');
                if (alerta) {
                    alerta.style.transition = 'opacity .4s ease';
                    alerta.style.opacity = '0';
                    setTimeout(function () { alerta.remove(); }, 400);
                }
            }, 3000);
        </script>
    @endif
    @if (session('error') || $errors->any())
        <div id="alertaError"
            class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex items-start gap-2 max-w-lg
                    bg-red-50 border border-red-100 text-red-600 text-sm font-medium
                    px-5 py-2.5 rounded-lg shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            <div>
                @if (session('error'))<div>{{ session('error') }}</div>@endif
                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="ml-2 text-red-400"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <script>setTimeout(function () { var a = document.getElementById('alertaError'); if (a) a.remove(); }, 7000);</script>
    @endif

    {{-- ================= MODAL DE CONFIRMACIÓN: ELIMINAR ================= --}}
    {{-- Inclúyelo una vez dentro de tu vista (o layout) y usa la función JS
     confirmarEliminar('URL_DEL_REGISTRO') en el onclick de cada botón "Eliminar". --}}
    <div id="modalEliminar" class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
        {{-- fondo sutil --}}
        <div class="absolute inset-0 bg-black/10"
            onclick="document.getElementById('modalEliminar').classList.add('hidden')"></div>
        {{-- contenido --}}
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-slate-800">Eliminar</h2>
                <button type="button"
                        onclick="document.getElementById('modalEliminar').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="flex items-center gap-3 mb-8">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg"></i>
                <p class="text-sm text-slate-600">¿Desea eliminar el registro?</p>
            </div>

            <form id="formEliminar" action="#" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button"
                            onclick="document.getElementById('modalEliminar').classList.add('hidden')"
                            class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
                        Eliminar
                    </button>
                </div>
            </form>

        </div>
    </div>

    
    
</body>
</html>