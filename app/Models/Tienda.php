<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Vendedor del marketplace. */
class Tienda extends Model
{
    protected $guarded = [];

    protected $casts = ['activo' => 'boolean', 'verificada' => 'boolean'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
