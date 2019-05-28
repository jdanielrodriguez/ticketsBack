<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosVenta extends Model
{
    protected $table = 'eventos_venta';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento');
    }

    public function funciones(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion')->with('eventos');
    }

    public function area(){
        return $this->hasOne('App\EventosFuncionesAreaLugar','id','evento_funcion_area_lugar')->with('eventos');
    }

    public function vendedores(){
        return $this->hasOne('App\EventosVendedor','id','evento_vendedor')->with('eventos');
    }

    public function descuentos(){
        return $this->hasOne('App\EventosDescuento','id','evento_descuento')->with('eventos');
    }
}
