<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anuncios extends Model
{
    protected $table = 'anuncios';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento');
    }
}
