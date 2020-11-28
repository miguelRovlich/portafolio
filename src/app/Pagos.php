<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'Pagos';

    #Probada
    public function abonos()
    {
        return $this->hasMany('App\Abonos', 'id_Acreedor');
    }
}
