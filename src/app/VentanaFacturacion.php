<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentanaFacturacion extends Model
{
    
    protected $table = 'VentanaFacturacion';

    #probada
    public function matrizPago()
    {
        return $this->hasMany('App\MatrizPago', 'id_VentanaFacturacion');
    }

    #probada
    public function facturacionTipo()
    {
        return $this->belongsTo('App\FacturacionTipo', 'id_FacturacionTipo');
    }
}
