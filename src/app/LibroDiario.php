<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibroDiario extends Model
{
    protected $table = 'LibroDiario';

    #probada
    public function participante()
    {
        return $this->belongsTo('App\Participantes', 'id_Owner')->first();
    }

    #probada
    public function tipoComprobante()
    {
        return $this->belongsTo('App\TipoComprobante', 'id_TipoComprobante')->first();
    }

    public function instruccionPagada()
    {
        return $this->hasOne('App\InstruccionPago', 'id_LibroDiario');
    }

    public function instruccionContabilizada()
    {
        return $this->hasOne('App\InstruccionPago', 'id_LibroDiarioContab');
    }
}
