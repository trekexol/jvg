<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'logins';
    
    public function tipoinv()
    {
        return $this->belongsTo('App\InventaryType', 'tipoinv_id');
    }

    public function tiporate()
    {
        return $this->belongsTo('App\RateType', 'tiporate_id');
    }


    public function user()
    {
        return $this->hasMany('App\User');
    }
}
