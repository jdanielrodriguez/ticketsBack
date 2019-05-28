<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosCosto extends Model
{
    protected $table = 'eventos_costo';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento');
    }
}
