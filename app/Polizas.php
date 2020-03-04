<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Polizas extends Model
{
    protected $table = 'polizas';

    public function creador(){
        return $this->hasOne('App\Users','id','creador');
    }
}
