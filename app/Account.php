<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function accounthistorial()
    {
        return $this->hasMany('App\AccountHistorial');
    }
}
