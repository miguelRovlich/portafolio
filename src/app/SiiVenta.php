<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiiVenta extends Model
{
    
    protected $table = 'SiiVenta';

    #probada
    public function DTETipo()
    {
        return $this->belongsTo('App\DTETipo', 'id_DteTipo')->first();
    }

    #probada
    public function cliente()
    {
        return $this->belongsTo('App\Participantes', 'id_Cliente')->first();
    }

    public function instruccion()
    {
        return $this->hasOne('App\InstruccionPago', 'id_SiiVenta');
    }
}