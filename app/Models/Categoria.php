<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $guarded = [];

    protected $casts = ['activo' => 'boolean'];

    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    public function hijas()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)->orderBy('orden')->orderBy('nombre');
    }
}
