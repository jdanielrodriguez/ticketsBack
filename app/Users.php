<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users';

    protected $hidden = [
        'password'
    ];

    public function roles(){
        return $this->hasOne('App\Roles','id','rol');
    }
}
