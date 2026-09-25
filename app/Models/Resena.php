<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $guarded = [];

    protected $casts = ['aprobada' => 'boolean'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
