<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Tienda;

/*
|--------------------------------------------------------------------------
| Tienda virtual (pública)
|--------------------------------------------------------------------------
*/
// La raíz abre el inicio de sesión del sistema (como al principio)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::prefix('tienda')->name('tienda.')->group(function () {
    Route::get('/', [Tienda\TiendaController::class, 'inicio'])->name('inicio');
    Route::get('/buscar', [Tienda\TiendaController::class, 'catalogo'])->name('buscar');
    Route::get('/categoria/{categoria:slug}', [Tienda\TiendaController::class, 'catalogo'])->name('categoria');
    Route::get('/producto/{producto:slug}', [Tienda\TiendaController::class, 'producto'])->name('producto');
    Route::post('/producto/{producto}/resena', [Tienda\TiendaController::class, 'resena'])->name('resena')->middleware('throttle:5,1');

    Route::get('/carrito', [Tienda\CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/{producto}', [Tienda\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/{producto}', [Tienda\CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::post('/carrito-cupon', [Tienda\CarritoController::class, 'cupon'])->name('carrito.cupon');

    Route::get('/checkout', [Tienda\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [Tienda\CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
    Route::get('/pedido/{codigo}/confirmado', [Tienda\CheckoutController::class, 'confirmacion'])->name('confirmacion');
    Route::get('/seguimiento', [Tienda\CheckoutController::class, 'seguimiento'])->name('seguimiento');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Panel de comercio electrónico
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        // Pedidos
        Route::get('pedidos', [Admin\PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('pedidos/{pedido}', [Admin\PedidoController::class, 'show'])->name('pedidos.show');
        Route::put('pedidos/{pedido}', [Admin\PedidoController::class, 'update'])->name('pedidos.update');

        // Catálogo
        Route::resource('productos', Admin\ProductoController::class)->except('show');
        Route::resource('categorias', Admin\CategoriaController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('marcas', Admin\MarcaController::class)->only(['index', 'store', 'update', 'destroy']);

        // Marketing
        Route::resource('promociones', Admin\PromocionController::class)->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['promociones' => 'promocion']);
        Route::resource('cupones', Admin\CuponController::class)->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['cupones' => 'cupon']);

        // Marketplace
        Route::resource('vendedores', Admin\TiendaController::class)->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['vendedores' => 'tienda']);

        // Clientes y reseñas
        Route::get('clientes', [Admin\ClienteController::class, 'index'])->name('clientes.index');
        Route::get('clientes/{cliente}', [Admin\ClienteController::class, 'show'])->name('clientes.show');
        Route::get('resenas', [Admin\ResenaController::class, 'index'])->name('resenas.index');
        Route::patch('resenas/{resena}', [Admin\ResenaController::class, 'update'])->name('resenas.update');
        Route::delete('resenas/{resena}', [Admin\ResenaController::class, 'destroy'])->name('resenas.destroy');

        // Inventario y reportes
        Route::get('inventario', [Admin\InventarioController::class, 'index'])->name('inventario.index');
        Route::post('inventario/{producto}/ajustar', [Admin\InventarioController::class, 'ajustar'])->name('inventario.ajustar');
        Route::get('reportes/ventas', [Admin\ReporteController::class, 'ventas'])->name('reportes.ventas');
    });

    Route::get('/configuracion/menu', function () { return view('admin.configuracion.configuracion_menu');})->middleware('auth')->name('configuracion.menu');
    //Ruta general de configuraciones
    Route::view('/configuracion', 'admin.configuracion.configuracion_menu')->name('configuracion.index');
    //Lista de bancos
    Route::get('/configuracion/banco', [App\Http\Controllers\BancoController::class, 'index'])->name('configuracion.banco')->middleware('auth');
    Route::post('/configuracion/banco', [App\Http\Controllers\BancoController::class, 'store'])->name('configuracion.banco.store')->middleware('auth');
    Route::put('/configuracion/banco/{banco}', [App\Http\Controllers\BancoController::class, 'update'])->name('configuracion.banco.update')->middleware('auth');
    Route::delete('/configuracion/banco/{banco}', [App\Http\Controllers\BancoController::class, 'destroy'])->name('configuracion.banco.destroy')->middleware('auth');
    //Lista de monedas
    Route::get('/configuracion/moneda', [App\Http\Controllers\MonedaController::class, 'index'])->name('configuracion.moneda')->middleware('auth');
    Route::post('/configuracion/moneda', [App\Http\Controllers\MonedaController::class, 'store'])->name('configuracion.moneda.store')->middleware('auth');
    Route::put('/configuracion/moneda/{moneda}', [App\Http\Controllers\MonedaController::class, 'update'])->name('configuracion.moneda.update')->middleware('auth');
    Route::delete('/configuracion/moneda/{moneda}', [App\Http\Controllers\MonedaController::class, 'destroy'])->name('configuracion.moneda.destroy')->middleware('auth');
    //Lista de cuenta bancarias
    Route::get('/configuracion/cuentabancaria', [App\Http\Controllers\CuentaBancariaController::class, 'index'])->name('configuracion.cuentabancaria')->middleware('auth');
    Route::post('/configuracion/cuentabancaria', [App\Http\Controllers\CuentaBancariaController::class, 'store'])->name('configuracion.cuentabancaria.store')->middleware('auth');
    Route::put('/configuracion/cuentabancaria/{cuentabancaria}', [App\Http\Controllers\CuentaBancariaController::class, 'update'])->name('configuracion.cuentabancaria.update')->middleware('auth');
    Route::delete('/configuracion/cuentabancaria/{cuentabancaria}', [App\Http\Controllers\CuentaBancariaController::class, 'destroy'])->name('configuracion.cuentabancaria.destroy')->middleware('auth');
    // Lista de tarjetas
    Route::get('/configuracion/tarjeta', [App\Http\Controllers\TarjetaController::class, 'index'])->name('configuracion.tarjeta')->middleware('auth');
    Route::post('/configuracion/tarjeta', [App\Http\Controllers\TarjetaController::class, 'store'])->name('configuracion.tarjeta.store')->middleware('auth');
    Route::put('/configuracion/tarjeta/{tarjeta}', [App\Http\Controllers\TarjetaController::class, 'update'])->name('configuracion.tarjeta.update')->middleware('auth');
    Route::delete('/configuracion/tarjeta/{tarjeta}', [App\Http\Controllers\TarjetaController::class, 'destroy'])->name('configuracion.tarjeta.destroy')->middleware('auth');
    // Lista de plataformas
    Route::get('/configuracion/plataforma', [App\Http\Controllers\PlataformaController::class, 'index'])->name('configuracion.plataforma')->middleware('auth');
    Route::post('/configuracion/plataforma', [App\Http\Controllers\PlataformaController::class, 'store'])->name('configuracion.plataforma.store')->middleware('auth');
    Route::put('/configuracion/plataforma/{plataforma}', [App\Http\Controllers\PlataformaController::class, 'update'])->name('configuracion.plataforma.update')->middleware('auth');
    Route::delete('/configuracion/plataforma/{plataforma}', [App\Http\Controllers\PlataformaController::class, 'destroy'])->name('configuracion.plataforma.destroy')->middleware('auth');
    // Métodos de pago
    Route::get('/configuracion/metodopago', [App\Http\Controllers\MetodoPagoController::class, 'index'])->name('configuracion.metodopago')->middleware('auth');
    Route::post('/configuracion/metodopago', [App\Http\Controllers\MetodoPagoController::class, 'store'])->name('configuracion.metodopago.store')->middleware('auth');
    Route::put('/configuracion/metodopago/{metodopago}', [App\Http\Controllers\MetodoPagoController::class, 'update'])->name('configuracion.metodopago.update')->middleware('auth');
    Route::delete('/configuracion/metodopago/{metodopago}', [App\Http\Controllers\MetodoPagoController::class, 'destroy'])->name('configuracion.metodopago.destroy')->middleware('auth');
    //metodo gasto 
    Route::post('/configuracion/metodopago/gasto', [App\Http\Controllers\MetodoGastoController::class, 'store'])->name('configuracion.metodogasto.store')->middleware('auth');
    Route::put('/configuracion/metodopago/gasto/{metodoGasto}', [App\Http\Controllers\MetodoGastoController::class, 'update']) ->name('configuracion.metodogasto.update')->middleware('auth');
    Route::delete('/configuracion/metodopago/gasto/{metodoGasto}', [App\Http\Controllers\MetodoGastoController::class, 'destroy'])->name('configuracion.metodogasto.destroy')->middleware('auth');
    //Lista de atributos
    Route::get('/configuracion/atributo', [App\Http\Controllers\AtributoController::class, 'index'])->name('configuracion.atributo')->middleware('auth');
    Route::post('/configuracion/atributo', [App\Http\Controllers\AtributoController::class, 'store'])->name('configuracion.atributo.store')->middleware('auth');
    Route::put('/configuracion/atributo/{atributo}', [App\Http\Controllers\AtributoController::class, 'update'])->name('configuracion.atributo.update')->middleware('auth');
    Route::delete('/configuracion/atributo/{atributo}', [App\Http\Controllers\AtributoController::class, 'destroy'])->name('configuracion.atributo.destroy')->middleware('auth');
    //Lista de detracciones
    Route::get('/configuracion/detraccion', [App\Http\Controllers\DetraccionController::class, 'index'])->name('configuracion.detraccion')->middleware('auth');
    Route::post('/configuracion/detraccion', [App\Http\Controllers\DetraccionController::class, 'store'])->name('configuracion.detraccion.store')->middleware('auth');
    Route::put('/configuracion/detraccion/{detraccion}', [App\Http\Controllers\DetraccionController::class, 'update'])->name('configuracion.detraccion.update')->middleware('auth');
    Route::delete('/configuracion/detraccion/{detraccion}', [App\Http\Controllers\DetraccionController::class, 'destroy'])->name('configuracion.detraccion.destroy')->middleware('auth');
    //Lista de unidades
    Route::get('/configuracion/unidad', [App\Http\Controllers\UnidadController::class, 'index'])->name('configuracion.unidad')->middleware('auth');
    Route::post('/configuracion/unidad', [App\Http\Controllers\UnidadController::class, 'store'])->name('configuracion.unidad.store')->middleware('auth');
    Route::put('/configuracion/unidad/{unidad}', [App\Http\Controllers\UnidadController::class, 'update'])->name('configuracion.unidad.update')->middleware('auth');
    Route::delete('/configuracion/unidad/{unidad}', [App\Http\Controllers\UnidadController::class, 'destroy'])->name('configuracion.unidad.destroy')->middleware('auth');

});
