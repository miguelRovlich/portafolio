<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    
    protected $table = 'TipoComprobante';

    #probada
    public function libroDiario()
    {
        return $this->hasMany('App\LibroDiario', 'id_TipoComprobante');
    }
}
