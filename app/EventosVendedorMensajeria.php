<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosVendedorMensajeria extends Model
{
    protected $table = 'eventos_vendedor_mensajeria';

    public function emisor(){
        return $this->hasOne('App\Users','id','envia');
    }

    public function receptor(){
        return $this->hasOne('App\Users','id','recibe');
    }
}
