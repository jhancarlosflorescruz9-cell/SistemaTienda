<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tablas del módulo de comercio electrónico (tienda virtual tipo marketplace).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('nombre', 120);
            $table->string('slug', 140)->unique();
            $table->string('icono', 60)->default('fa-solid fa-tag');
            $table->string('color', 20)->default('#fb7701');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Vendedores del marketplace (cada tienda publica sus productos)
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('ruc', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->decimal('comision', 5, 2)->default(10); // % que cobra el marketplace
            $table->decimal('calificacion', 2, 1)->default(4.5);
            $table->boolean('verificada')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas')->nullOnDelete();
            $table->string('codigo', 40)->unique();
            $table->string('nombre', 200);
            $table->string('slug', 220)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_oferta', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->string('imagen')->nullable();
            $table->unsignedInteger('vendidos')->default(0);
            $table->decimal('calificacion', 2, 1)->default(0);
            $table->boolean('envio_gratis')->default(false);
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Promociones / ofertas flash con tiempo límite
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->enum('tipo', ['flash', 'temporada', 'liquidacion'])->default('flash');
            $table->unsignedTinyInteger('descuento'); // porcentaje
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('producto_promocion', function (Blueprint $table) {
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('promocion_id')->constrained('promociones')->cascadeOnDelete();
            $table->primary(['producto_id', 'promocion_id']);
        });

        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 40)->unique();
            $table->enum('tipo', ['porcentaje', 'monto'])->default('porcentaje');
            $table->decimal('valor', 10, 2);
            $table->decimal('minimo_compra', 10, 2)->default(0);
            $table->unsignedInteger('usos_maximos')->nullable();
            $table->unsignedInteger('usos')->default(0);
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('email', 150)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('documento', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->string('distrito', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('cupon_id')->nullable()->constrained('cupones')->nullOnDelete();
            $table->string('metodo_pago', 60);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('envio', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->string('direccion_envio');
            $table->string('distrito', 100)->nullable();
            $table->string('tracking', 60)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('nombre', 200);
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('cantidad');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->string('autor', 120);
            $table->unsignedTinyInteger('calificacion');
            $table->text('comentario')->nullable();
            $table->boolean('aprobada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas');
        Schema::dropIfExists('pedido_items');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('cupones');
        Schema::dropIfExists('producto_promocion');
        Schema::dropIfExists('promociones');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('tiendas');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('categorias');
    }
};
