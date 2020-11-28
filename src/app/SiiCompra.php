<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiiCompra extends Model
{
    protected $table = 'SiiCompra';

    #probada
    public function DTETipo()
    {
        return $this->belongsTo('App\DTETipo', 'id_DteTipo')->first();
    }

    #probada
    public function proveedor()
    {
        return $this->belongsTo('App\Participantes', 'id_Proveedor')->first();
    }

    public function instruccion()
    {
        return $this->hasOne('App\InstruccionPago', 'id_SiiCompra');
    }
}