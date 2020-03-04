<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'caja';

    public function usuarios(){
        return $this->hasOne('App\Users','id','usuario');
    }

    public function creador(){
        return $this->hasOne('App\Users','id','creador');
    }
    
    public function funcion(){
        return $this->hasOne('App\EventosFunciones','id','evento_funcion');
    }

}
