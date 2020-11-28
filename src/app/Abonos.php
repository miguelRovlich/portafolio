<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Abonos extends Model
{
    protected $table = 'Abonos';

    #probada
    public function pago()
    {
        return $this->belongsTo('App\Pagos', 'id_Pago');
    }

    #probada
    public function instruccion()
    {
        return $this->belongsTo('App\InstruccionPago', 'id_Instruccion');
    }
    
}
