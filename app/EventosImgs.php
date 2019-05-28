<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosImgs extends Model
{
    protected $table = 'eventos_imgs';

    public function eventos(){
        return $this->hasOne('App\Eventos','id','evento');
    }
}
