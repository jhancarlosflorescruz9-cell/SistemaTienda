<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Cupon;
use App\Models\Marca;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Resena;
use App\Models\Tienda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Datos de demostración para la tienda virtual.
 * Ejecutar: php artisan db:seed --class=TiendaSeeder
 */
class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        if (Producto::exists()) {
            $this->command?->warn('Ya existen productos; se omite TiendaSeeder.');

            return;
        }

        mt_srand(2026);

        $categorias = collect([
            ['Moda mujer', 'fa-solid fa-person-dress', '#e11d48'],
            ['Moda hombre', 'fa-solid fa-shirt', '#2563eb'],
            ['Electrónica', 'fa-solid fa-headphones', '#7c3aed'],
            ['Celulares y accesorios', 'fa-solid fa-mobile-screen', '#0891b2'],
            ['Hogar y cocina', 'fa-solid fa-kitchen-set', '#ea580c'],
            ['Belleza y cuidado', 'fa-solid fa-spa', '#db2777'],
            ['Deportes', 'fa-solid fa-dumbbell', '#16a34a'],
            ['Juguetes', 'fa-solid fa-puzzle-piece', '#f59e0b'],
            ['Mascotas', 'fa-solid fa-paw', '#a16207'],
            ['Automotriz', 'fa-solid fa-car', '#475569'],
        ])->mapWithKeys(fn ($c, $i) => [$c[0] => Categoria::create([
            'nombre' => $c[0], 'slug' => Str::slug($c[0]), 'icono' => $c[1], 'color' => $c[2], 'orden' => $i, 'activo' => true,
        ])]);

        $marcas = collect(['Genérico', 'Xiaomi', 'Baseus', 'Anker', 'Oster', 'Nike', 'Adidas', 'Maybelline', 'Lego', 'Pedigree'])
            ->mapWithKeys(fn ($m) => [$m => Marca::create(['nombre' => $m, 'activo' => true])]);

        $tiendas = collect([
            ['TecnoImport Perú', '20601234567', 12, true, 4.8],
            ['Casa Bonita Store', '20609876543', 10, true, 4.6],
            ['Moda Express', '10456789012', 15, false, 4.3],
            ['Fit & Sport Lima', '20555666777', 10, true, 4.7],
        ])->map(fn ($t) => Tienda::create([
            'nombre' => $t[0], 'ruc' => $t[1], 'comision' => $t[2], 'verificada' => $t[3], 'calificacion' => $t[4],
            'email' => Str::slug($t[0]).'@correo.pe', 'telefono' => '9'.mt_rand(10000000, 99999999), 'activo' => true,
        ]));

        // [nombre, categoría, marca, precio, precio_oferta, envío gratis]
        $productos = [
            ['Vestido midi floral manga corta', 'Moda mujer', 'Genérico', 69.90, 39.90, false],
            ['Blusa de lino oversize', 'Moda mujer', 'Genérico', 49.90, 29.90, false],
            ['Jean mom fit tiro alto', 'Moda mujer', 'Genérico', 89.90, null, true],
            ['Cartera tote de cuero sintético', 'Moda mujer', 'Genérico', 79.90, 45.00, false],
            ['Zapatillas urbanas mujer blancas', 'Moda mujer', 'Adidas', 229.00, 179.00, true],
            ['Polo básico algodón pima pack x3', 'Moda hombre', 'Genérico', 79.90, 54.90, true],
            ['Casaca cortavientos impermeable', 'Moda hombre', 'Genérico', 139.90, 89.90, false],
            ['Zapatillas running Air Zoom', 'Moda hombre', 'Nike', 399.00, 299.00, true],
            ['Reloj deportivo digital resistente al agua', 'Moda hombre', 'Genérico', 59.90, 24.90, false],
            ['Audífonos inalámbricos Bluetooth 5.3 con estuche', 'Electrónica', 'Xiaomi', 129.90, 59.90, true],
            ['Parlante portátil 20W resistente al agua', 'Electrónica', 'Anker', 199.00, 149.00, true],
            ['Smartwatch con monitor de ritmo cardíaco', 'Electrónica', 'Xiaomi', 189.90, 99.90, true],
            ['Tira LED RGB 5 m con control remoto', 'Electrónica', 'Genérico', 39.90, 19.90, false],
            ['Mini proyector portátil HD', 'Electrónica', 'Genérico', 349.00, 229.00, true],
            ['Teclado mecánico retroiluminado', 'Electrónica', 'Genérico', 159.90, 109.90, false],
            ['Cargador rápido USB-C 65W GaN', 'Celulares y accesorios', 'Baseus', 119.00, 79.00, true],
            ['Power bank 20000 mAh carga rápida', 'Celulares y accesorios', 'Anker', 149.00, 109.00, true],
            ['Case antigolpes transparente', 'Celulares y accesorios', 'Genérico', 19.90, 9.90, false],
            ['Soporte de celular para auto magnético', 'Celulares y accesorios', 'Baseus', 49.90, 29.90, false],
            ['Cable USB-C trenzado 2 m pack x2', 'Celulares y accesorios', 'Baseus', 34.90, 19.90, false],
            ['Freidora de aire 5 L digital', 'Hogar y cocina', 'Oster', 399.00, 279.00, true],
            ['Licuadora de alta potencia 1.5 L', 'Hogar y cocina', 'Oster', 259.00, 199.00, true],
            ['Organizador de cocina giratorio', 'Hogar y cocina', 'Genérico', 45.90, 25.90, false],
            ['Set de sartenes antiadherentes x3', 'Hogar y cocina', 'Genérico', 149.90, 89.90, true],
            ['Lámpara de noche con sensor de movimiento', 'Hogar y cocina', 'Genérico', 29.90, 14.90, false],
            ['Juego de sábanas microfibra 2 plazas', 'Hogar y cocina', 'Genérico', 69.90, 42.90, false],
            ['Máscara de pestañas a prueba de agua', 'Belleza y cuidado', 'Maybelline', 45.90, 32.90, false],
            ['Secadora de cabello iónica 2000W', 'Belleza y cuidado', 'Genérico', 119.90, 69.90, true],
            ['Set de brochas de maquillaje x12', 'Belleza y cuidado', 'Genérico', 39.90, 18.90, false],
            ['Sérum facial vitamina C 30 ml', 'Belleza y cuidado', 'Genérico', 59.90, 34.90, false],
            ['Mat de yoga antideslizante 6 mm', 'Deportes', 'Genérico', 69.90, 39.90, false],
            ['Mancuernas ajustables 20 kg par', 'Deportes', 'Genérico', 259.00, 199.00, true],
            ['Botella térmica deportiva 1 L', 'Deportes', 'Genérico', 39.90, 22.90, false],
            ['Bandas de resistencia set x5', 'Deportes', 'Genérico', 35.90, 16.90, false],
            ['Pelota de fútbol profesional N°5', 'Deportes', 'Adidas', 129.00, 99.00, false],
            ['Bloques de construcción ciudad 500 piezas', 'Juguetes', 'Lego', 189.00, 159.00, true],
            ['Auto a control remoto todo terreno', 'Juguetes', 'Genérico', 99.90, 59.90, false],
            ['Pizarra mágica LCD para niños', 'Juguetes', 'Genérico', 24.90, 12.90, false],
            ['Peluche oso gigante 80 cm', 'Juguetes', 'Genérico', 79.90, 49.90, false],
            ['Alimento para perro adulto 15 kg', 'Mascotas', 'Pedigree', 169.00, 145.00, true],
            ['Cama acolchada para mascotas talla M', 'Mascotas', 'Genérico', 69.90, 44.90, false],
            ['Rascador para gatos con torre', 'Mascotas', 'Genérico', 119.90, 79.90, true],
            ['Aspiradora portátil para auto', 'Automotriz', 'Baseus', 99.90, 64.90, false],
            ['Cámara de retroceso con visión nocturna', 'Automotriz', 'Genérico', 129.90, 89.90, true],
            ['Organizador para asiento trasero', 'Automotriz', 'Genérico', 39.90, 21.90, false],
        ];

        $creados = collect($productos)->map(function ($p, $i) use ($categorias, $marcas, $tiendas) {
            $cat = $categorias[$p[1]];

            return Producto::create([
                'categoria_id' => $cat->id,
                'marca_id' => $marcas[$p[2]]->id,
                'tienda_id' => $i % 5 === 0 ? null : $tiendas[$i % $tiendas->count()]->id,
                'codigo' => 'PRD-'.str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'nombre' => $p[0],
                'slug' => Str::slug($p[0]),
                'descripcion' => "{$p[0]} de excelente calidad.\n\n• Material resistente y duradero\n• Garantía de 6 meses con el vendedor\n• Producto nuevo en empaque original\n\nIdeal para el uso diario. Stock limitado.",
                'precio' => $p[3],
                'precio_oferta' => $p[4],
                'stock' => $i % 9 === 4 ? mt_rand(1, 4) : mt_rand(15, 250),
                'stock_minimo' => 5,
                'vendidos' => mt_rand(20, 4800),
                'envio_gratis' => $p[5],
                'destacado' => $i % 4 === 0,
                'activo' => true,
            ]);
        });

        // Oferta flash vigente + promoción de temporada
        Promocion::create([
            'nombre' => 'Ofertas relámpago', 'tipo' => 'flash', 'descuento' => 60,
            'fecha_inicio' => now()->subHours(2), 'fecha_fin' => now()->addHours(22), 'activo' => true,
        ])->productos()->sync($creados->shuffle()->take(10)->pluck('id'));

        Promocion::create([
            'nombre' => 'Especial Día del Hogar', 'tipo' => 'temporada', 'descuento' => 25,
            'fecha_inicio' => now()->addDays(5), 'fecha_fin' => now()->addDays(12), 'activo' => true,
        ])->productos()->sync($creados->where('categoria_id', $categorias['Hogar y cocina']->id)->pluck('id'));

        Cupon::create(['codigo' => 'BIENVENIDO10', 'tipo' => 'porcentaje', 'valor' => 10, 'minimo_compra' => 0, 'activo' => true]);
        Cupon::create(['codigo' => 'ENVIO15', 'tipo' => 'monto', 'valor' => 15, 'minimo_compra' => 99, 'usos_maximos' => 500, 'fecha_fin' => now()->addMonth(), 'activo' => true]);
        Cupon::create(['codigo' => 'CYBER30', 'tipo' => 'porcentaje', 'valor' => 30, 'minimo_compra' => 200, 'usos_maximos' => 100, 'fecha_fin' => now()->subDays(3), 'activo' => true]);

        // Clientes y pedidos de los últimos 30 días
        $nombres = ['María Quispe', 'José Rodríguez', 'Lucía Fernández', 'Carlos Huamán', 'Ana Torres', 'Luis Mendoza',
            'Rosa Flores', 'Jorge Castillo', 'Carmen Vargas', 'Pedro Ramírez', 'Sofía Chávez', 'Diego Rojas'];
        $distritos = ['Miraflores', 'San Isidro', 'Surco', 'San Borja', 'Los Olivos', 'Arequipa', 'Trujillo', 'Chiclayo', 'Cusco', 'Piura'];
        $clientes = collect($nombres)->map(fn ($n, $i) => Cliente::create([
            'nombre' => $n,
            'email' => Str::slug($n, '.').'@gmail.com',
            'telefono' => '9'.mt_rand(10000000, 99999999),
            'documento' => (string) mt_rand(40000000, 79999999),
            'direccion' => 'Av. Principal '.mt_rand(100, 2500),
            'distrito' => $distritos[$i % count($distritos)],
        ]));

        $metodos = ['Tarjeta de crédito/débito', 'Yape / Plin', 'Transferencia bancaria', 'Pago contra entrega'];
        for ($n = 0; $n < 60; $n++) {
            $fecha = now()->subDays(mt_rand(0, 29))->setTime(mt_rand(8, 22), mt_rand(0, 59));
            $cliente = $clientes->random();
            $items = $creados->random(mt_rand(1, 4));
            $subtotal = 0;
            $lineas = [];
            foreach ($items as $prod) {
                $cant = mt_rand(1, 3);
                $precio = (float) ($prod->precio_oferta ?? $prod->precio);
                $subtotal += $precio * $cant;
                $lineas[] = ['producto_id' => $prod->id, 'nombre' => $prod->nombre, 'precio' => $precio, 'cantidad' => $cant, 'subtotal' => $precio * $cant];
            }
            $envio = $subtotal >= 49 ? 0 : 8.90;
            $dias = now()->diffInDays($fecha, true);
            $estado = match (true) {
                $n % 17 === 0 => 'cancelado',
                $dias > 7 => 'entregado',
                $dias > 3 => 'enviado',
                $dias > 1 => 'pagado',
                default => ['pendiente', 'pagado'][mt_rand(0, 1)],
            };

            $pedido = Pedido::create([
                'codigo' => 'PED-'.$fecha->format('ymd').'-'.strtoupper(Str::random(5)),
                'cliente_id' => $cliente->id,
                'metodo_pago' => $metodos[array_rand($metodos)],
                'subtotal' => round($subtotal, 2), 'descuento' => 0, 'envio' => $envio,
                'total' => round($subtotal + $envio, 2),
                'estado' => $estado,
                'direccion_envio' => $cliente->direccion, 'distrito' => $cliente->distrito,
                'tracking' => in_array($estado, ['enviado', 'entregado']) ? 'OLVA-'.mt_rand(100000, 999999) : null,
            ]);
            $pedido->forceFill(['created_at' => $fecha, 'updated_at' => $fecha])->saveQuietly();
            $pedido->items()->createMany($lineas);
        }

        // Reseñas
        $comentarios = ['Excelente calidad, llegó antes de lo esperado.', 'Muy buen producto por el precio.', 'Tal cual la descripción, lo recomiendo.',
            'Buen producto, aunque el empaque llegó un poco dañado.', 'Me encantó, volveré a comprar.', 'Cumple su función, nada extraordinario.'];
        foreach ($creados as $prod) {
            $cantidad = mt_rand(0, 4);
            for ($r = 0; $r < $cantidad; $r++) {
                Resena::create([
                    'producto_id' => $prod->id,
                    'autor' => $nombres[array_rand($nombres)],
                    'calificacion' => mt_rand(3, 5),
                    'comentario' => $comentarios[array_rand($comentarios)],
                    'aprobada' => mt_rand(0, 5) > 0,
                ]);
            }
            $prom = $prod->resenas()->where('aprobada', true)->avg('calificacion');
            $prod->update(['calificacion' => $prom ? round($prom, 1) : round(mt_rand(40, 50) / 10, 1)]);
        }
    }
}
