<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatrizPago extends Model
{

    protected $table = 'MatrizPago';

    #probada
    public function instruccion()
    {
        return $this->hasMany('App\InstruccionPago', 'id_Matriz');
    }

    #probada
    public function ventanaFacturacion()
    {
        return $this->belongsTo('App\VentanaFacturacion', 'id_VentanaFacturacion')->first();
    }
}
