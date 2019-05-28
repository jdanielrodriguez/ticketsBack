<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosDescuento extends Model
{
    protected $table = 'eventos_descuento';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion')->with('eventos');
    }
}
