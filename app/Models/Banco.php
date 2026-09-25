<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    public function cuentasBancarias()
    {
        return $this->hasMany(CuentaBancaria::class);
    }
    
}
