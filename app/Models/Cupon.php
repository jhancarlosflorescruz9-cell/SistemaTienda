<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $guarded = [];

    protected $casts = [
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'valor' => 'decimal:2',
        'minimo_compra' => 'decimal:2',
    ];

    /** Devuelve null si el cupón es válido para el subtotal, o el motivo del rechazo. */
    public function motivoInvalido(float $subtotal): ?string
    {
        if (! $this->activo) {
            return 'El cupón no está activo.';
        }
        if ($this->fecha_fin && $this->fecha_fin->endOfDay()->isPast()) {
            return 'El cupón ha vencido.';
        }
        if ($this->usos_maximos !== null && $this->usos >= $this->usos_maximos) {
            return 'El cupón alcanzó su límite de usos.';
        }
        if ($subtotal < (float) $this->minimo_compra) {
            return 'Compra mínima para este cupón: S/ '.number_format($this->minimo_compra, 2);
        }

        return null;
    }

    public function calcularDescuento(float $subtotal): float
    {
        $descuento = $this->tipo === 'porcentaje'
            ? $subtotal * (float) $this->valor / 100
            : (float) $this->valor;

        return round(min($descuento, $subtotal), 2);
    }
}
