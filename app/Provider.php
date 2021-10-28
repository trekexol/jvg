<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function anticipo(){
        return $this->hasMany('App\Anticipo');
    }
}
