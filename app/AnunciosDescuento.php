<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnunciosDescuento extends Model
{
    protected $table = 'anuncios_descuento';

    public function usuarios(){
        return $this->hasOne('App\Anuncios','id','anuncio');
    }

    public function eventos(){
        return $this->hasOne('App\EventosDescuentoArea','id','evento_descuento_area')->with('eventos');
    }
}
