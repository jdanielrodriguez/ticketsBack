<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CajaDetalle extends Model
{
    protected $table = 'caja_detalle';

    public function cuponDescuento(){
        return $this->hasOne('App\Users','id','cupon_descuento');
    }

    public function comprador(){
        return $this->hasOne('App\Users','id','comprador');
    }

    public function caja(){
        return $this->hasOne('App\Caja','id','caja');
    }

    public function lugar(){
        return $this->hasOne('App\EventosFuncionesAreaLugar','id','evento_funcion_area_lugar');
    }
}
