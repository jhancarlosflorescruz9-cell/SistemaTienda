<?php

namespace App\Services;

use App\Models\Cupon;
use App\Models\Producto;
use Illuminate\Support\Collection;

/**
 * Carrito de compras guardado en la sesión.
 * Estructura en sesión: ['items' => [producto_id => cantidad], 'cupon' => 'CODIGO']
 */
class Carrito
{
    public const ENVIO_GRATIS_DESDE = 49.00;

    public const COSTO_ENVIO = 8.90;

    private const CLAVE = 'carrito';

    public function agregar(Producto $producto, int $cantidad = 1): void
    {
        $items = $this->idsCantidades();
        $items[$producto->id] = min(($items[$producto->id] ?? 0) + $cantidad, $producto->stock);
        $this->guardarItems($items);
    }

    public function actualizar(int $productoId, int $cantidad): void
    {
        $items = $this->idsCantidades();
        if ($cantidad <= 0) {
            unset($items[$productoId]);
        } else {
            $stock = Producto::whereKey($productoId)->value('stock') ?? 0;
            $items[$productoId] = min($cantidad, $stock);
        }
        $this->guardarItems($items);
    }

    public function vaciar(): void
    {
        session()->forget(self::CLAVE);
    }

    public function cantidadTotal(): int
    {
        return array_sum($this->idsCantidades());
    }

    public function aplicarCupon(?string $codigo): void
    {
        $data = session(self::CLAVE, []);
        $data['cupon'] = $codigo ? strtoupper($codigo) : null;
        session([self::CLAVE => $data]);
    }

    /** Líneas del carrito con productos cargados (se descartan los que ya no existen o no tienen stock). */
    public function lineas(): Collection
    {
        $items = $this->idsCantidades();
        if (! $items) {
            return collect();
        }

        return Producto::with('promociones')->publicados()->whereIn('id', array_keys($items))->get()
            ->map(fn (Producto $p) => (object) [
                'producto' => $p,
                'cantidad' => min($items[$p->id], $p->stock),
                'precio' => $p->precio_final,
                'subtotal' => round($p->precio_final * min($items[$p->id], $p->stock), 2),
            ]);
    }

    /** Totales del carrito, incluyendo cupón y envío. */
    public function resumen(): array
    {
        $lineas = $this->lineas();
        $subtotal = round($lineas->sum('subtotal'), 2);
        $cupon = null;
        $errorCupon = null;
        $descuento = 0.0;

        if ($codigo = session(self::CLAVE.'.cupon')) {
            $cupon = Cupon::where('codigo', $codigo)->first();
            $errorCupon = $cupon ? $cupon->motivoInvalido($subtotal) : 'El cupón no existe.';
            if ($errorCupon) {
                $cupon = null;
            } else {
                $descuento = $cupon->calcularDescuento($subtotal);
            }
        }

        $envioGratis = $lineas->isNotEmpty()
            && ($subtotal >= self::ENVIO_GRATIS_DESDE || $lineas->every(fn ($l) => $l->producto->envio_gratis));
        $envio = $lineas->isEmpty() || $envioGratis ? 0.0 : self::COSTO_ENVIO;

        return [
            'lineas' => $lineas,
            'subtotal' => $subtotal,
            'cupon' => $cupon,
            'codigo_cupon' => $codigo,
            'error_cupon' => $errorCupon,
            'descuento' => $descuento,
            'envio' => $envio,
            'total' => round(max($subtotal - $descuento, 0) + $envio, 2),
            'falta_envio_gratis' => max(0, round(self::ENVIO_GRATIS_DESDE - $subtotal, 2)),
        ];
    }

    private function idsCantidades(): array
    {
        return session(self::CLAVE.'.items', []);
    }

    private function guardarItems(array $items): void
    {
        $data = session(self::CLAVE, []);
        $data['items'] = array_filter($items, fn ($c) => $c > 0);
        session([self::CLAVE => $data]);
    }
}
