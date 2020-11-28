<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturacionTipo extends Model
{
    protected $table = 'FacturacionTipo';

    public function ventanaFacturacion()
    {
        return $this->hasMany('App\VentanaFacturacion', 'id_FacturacionTipo');
    }
}
