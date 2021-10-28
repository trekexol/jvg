<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function vendors(){
        return $this->belongsTo('App\Permission\Models\Vendor','id_vendor');
    }
}
