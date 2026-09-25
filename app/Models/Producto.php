<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = [];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'envio_gratis' => 'boolean',
        'destacado' => 'boolean',
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function promociones()
    {
        return $this->belongsToMany(Promocion::class, 'producto_promocion');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    /** Caché en memoria de la promoción vigente (no se guarda en BD). */
    protected $promoCache = false;

    public function scopePublicados($query)
    {
        return $query->where('activo', true)->where('stock', '>', 0);
    }

    /** Promoción vigente con mayor descuento (se cachea en la instancia). */
    public function promocionVigente(): ?Promocion
    {
        if ($this->promoCache === false) {
            $promos = $this->relationLoaded('promociones')
                ? $this->promociones
                : $this->promociones()->get();
            $this->promoCache = $promos
                ->filter(fn ($p) => $p->estaVigente())
                ->sortByDesc('descuento')
                ->first();
        }

        return $this->promoCache;
    }

    /** Precio que paga el cliente: promoción vigente > precio oferta > precio normal. */
    public function getPrecioFinalAttribute(): float
    {
        $precio = (float) $this->precio;
        $candidatos = [$precio];

        if ($this->precio_oferta && (float) $this->precio_oferta < $precio) {
            $candidatos[] = (float) $this->precio_oferta;
        }
        if ($promo = $this->promocionVigente()) {
            $candidatos[] = round($precio * (100 - $promo->descuento) / 100, 2);
        }

        return min($candidatos);
    }

    public function getPorcentajeDescuentoAttribute(): int
    {
        $precio = (float) $this->precio;

        return $precio > 0 ? (int) round(100 - ($this->precio_final * 100 / $precio)) : 0;
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        return str_starts_with($this->imagen, 'http') ? $this->imagen : asset($this->imagen);
    }
}
