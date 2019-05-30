<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosFunciones extends Model
{
    protected $table = 'eventos_funciones';

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento');
    }

    public function areas(){
        return $this->hasMany('App\EventosFuncionesArea','evento_funcion','id');
    }
}
