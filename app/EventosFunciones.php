<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosFunciones extends Model
{
    protected $table = 'eventos_funciones';

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento')->with('usuarios','tipos','categorias');
    }

    public function areas(){
        return $this->hasMany('App\EventosFuncionesArea','evento_funcion','id')->with('lugares');
    }

    public function imagenes(){
        return $this->hasMany('App\EventosImgs','evento','id');
    }

    public function vendedores(){
        return $this->hasMany('App\EventosVendedor','evento_funcion','id')->with('usuarios','administrador');
    }
}
