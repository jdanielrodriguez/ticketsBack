<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosFuncionesAreaLugar extends Model
{
    protected $table = 'eventos_funciones_area_lugar';

    public function eventos(){
        return $this->hasOne('App\EventosFuncionesArea','id','evento_funcion_area')->with('eventos');
    }
}
