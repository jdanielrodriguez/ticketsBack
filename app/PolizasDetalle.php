<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolizasDetalle extends Model
{
    protected $table = 'polizas_detalle';

    public function creador(){
        return $this->hasOne('App\Users','id','creador');
    }

    public function poliza(){
        return $this->hasOne('App\Polizas','id','poliza');
    }
}
