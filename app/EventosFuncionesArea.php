<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosFuncionesArea extends Model
{
    protected $table = 'eventos_funciones_area';

    public function tipos(){
        return $this->hasOne('App\TipoEventos','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion')->with('eventos','vendedores');
    }

    public function lugares(){
        return $this->hasMany('App\EventosFuncionesAreaLugar','evento_funcion_area','id')->with('eventos');
    }
}
