<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosVendedor extends Model
{
    protected $table = 'eventos_vendedor';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function administrador(){
        return $this->hasOne('App\Users','id','usuario_admin');
    }

    public function eventos(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion')->with('eventos');
    }
}
