<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DTE extends Model
{
    protected $table = 'DTE';

    #probada
    public function instruccion()
    {
        return $this->belongsTo('App\InstruccionPago', 'id_Instruccion');
    }

    #probada
    public function dteTipo()
    {
        return $this->belongsTo('App\DTETipo', 'id_Tipo');
    }
}
