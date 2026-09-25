<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $guarded = [];

    public const ESTADOS = [
        'pendiente' => ['Pendiente', 'bg-amber-100 text-amber-700'],
        'pagado' => ['Pagado', 'bg-blue-100 text-blue-700'],
        'enviado' => ['Enviado', 'bg-indigo-100 text-indigo-700'],
        'entregado' => ['Entregado', 'bg-green-100 text-green-700'],
        'cancelado' => ['Cancelado', 'bg-red-100 text-red-700'],
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cupon()
    {
        return $this->belongsTo(Cupon::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado][0] ?? $this->estado;
    }

    public function getEstadoClaseAttribute(): string
    {
        return self::ESTADOS[$this->estado][1] ?? 'bg-slate-100 text-slate-700';
    }

    public static function generarCodigo(): string
    {
        do {
            $codigo = 'PED-'.now()->format('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (self::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
