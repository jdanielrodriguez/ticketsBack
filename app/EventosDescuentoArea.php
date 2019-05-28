<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosDescuentoArea extends Model
{
    protected $table = 'eventos_descuento_area';

    public function descuentos(){
        return $this->hasOne('App\EventosDescuento','id','evento_descuento');
    }

    public function eventos(){
        return $this->hasOne('App\EventosFuncionesArea','id','evento_funcion_area')->with('eventos');
    }
}
