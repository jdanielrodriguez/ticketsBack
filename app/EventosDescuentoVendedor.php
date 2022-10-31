<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosDescuentoVendedor extends Model
{
    protected $table = 'eventos_descuento_vendedor';

    public function decuentos(){
        return $this->hasOne('App\EventosDescuentoArea','id','evento_descuento_area');
    }

    public function vendedores(){
        return $this->hasOne('App\EventosVendedor','id','evento_vendedor')->with('eventos');
    }
}
