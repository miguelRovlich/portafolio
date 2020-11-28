<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstruccionPago extends Model
{
    
    protected $table = 'InstruccionPago';

    #probada
    public function abonos()
    {
        return $this->hasMany('App\Abonos', 'id_Instruccion');
    }

    #probada
    public function matrizPago()
    {
        return $this->belongsTo('App\MatrizPago', 'id_Matriz');
    }

    #probada
    public function DTE()
    {
        return $this->hasMany('App\DTE', 'id_Instruccion');
    }

    #probada
    public function deudor()
    {
        return $this->belongsTo('App\Participantes', 'id_Deudor');
    }

    #probada
    public function acreedor()
    {
        return $this->belongsTo('App\Participantes', 'id_Acreedor');
    }

    public function libroDiarioPago()
    {
        return $this->belongsTo('App\LibroDiario', 'id_LibroDiario');
    }

    public function libroDiarioContab()
    {
        return $this->belongsTo('App\LibroDiario', 'id_LibroDiarioContab');
    }

    public function siiCompra()
    {
        return $this->belongsTo('App\SiiCompra', 'id_SiiCompra');
    }

    public function siiVenta()
    {
        return $this->belongsTo('App\SiiVenta', 'id_SiiVenta');
    }
    
}
