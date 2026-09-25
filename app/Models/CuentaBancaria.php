<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
