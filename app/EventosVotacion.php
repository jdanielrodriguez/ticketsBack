<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosVotacion extends Model
{
    protected $table = 'eventos_votacion';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion')->with('eventos');
    }
}
