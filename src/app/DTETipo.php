<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DTETipo extends Model
{
    
    protected $table = 'DTETipo';

    #probada
    public function DTE()
    {
        return $this->hasMany('App\DTE', 'id_Tipo');
    }

    #Probada
    public function siiCompra()
    {
        return $this->hasMany('App\SiiCompra', 'id_DteTipo');
    }

    #Probada
    public function siiVenta()
    {
        return $this->hasMany('App\SiiVenta', 'id_DteTipo');
    }
}
