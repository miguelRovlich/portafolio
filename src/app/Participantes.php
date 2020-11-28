<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participantes extends Model
{
    
    public $timestamps = false;
    protected $table = 'Participantes';

    #probada
    public function deudor()
    {
        return $this->hasMany('App\InstruccionPago', 'id_Deudor');
    }

    #probada
    public function acreedor()
    {
        return $this->hasMany('App\InstruccionPago', 'id_Acreedor');
    }

    #probada
    public function siiCompra()
    {
        return $this->hasMany('App\SiiCompra', 'id_Participante');
    }

    #probada
    public function siiVenta()
    {
        return $this->hasMany('App\SiiVenta', 'id_Cliente');
    }

    #probada
    public function libroDiario()
    {
        return $this->hasMany('App\LibroDiario', 'id_Owner');
    }

}
