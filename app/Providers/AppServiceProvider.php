<?php

namespace App\Providers;

use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Resena;
use App\Services\Carrito;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Carrito::class);
    }

    public function boot(): void
    {
        \Carbon\Carbon::setLocale('es');

        // Contadores del topbar del panel (pedidos por despachar y alertas)
        View::composer('layouts.admin.app', function ($view) {
            if (! Schema::hasTable('pedidos')) {
                $view->with(['navPedidos' => 0, 'navAlertas' => 0]);

                return;
            }
            $view->with([
                'navPedidos' => Pedido::whereIn('estado', ['pendiente', 'pagado'])->count(),
                'navAlertas' => Producto::whereColumn('stock', '<=', 'stock_minimo')->count()
                    + Resena::where('aprobada', false)->count(),
            ]);
        });

        // Datos compartidos de la tienda pública
        View::composer('layouts.tienda.app', function ($view) {
            $view->with([
                'carritoCantidad' => app(Carrito::class)->cantidadTotal(),
                'menuCategorias' => Categoria::activas()->whereNull('parent_id')->get(),
            ]);
        });
    }
}
