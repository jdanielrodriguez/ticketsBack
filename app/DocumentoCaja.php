<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoCaja extends Model
{
    protected $table = 'documento_caja';

    public function creador(){
        return $this->hasOne('App\Users','id','creador');
    }

    public function polizaDetalle(){
        return $this->hasOne('App\PolizasDetalle','id','poliza_detalle');
    }

    public function caja(){
        return $this->hasOne('App\Caja','id','caja');
    }
}
