<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{

    protected $table = 'Roles';

    #probada
    public function users()
    {
        return $this->belongsToMany('App\User', 'User_Role', 'id_Role', 'id_User')
            ->withPivot('id_Participante')
            ->withTimestamps();
    }
}
