<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankMovement extends Model
{
    public function account()
    {
        return $this->hasMany('App\Account');
    }
    
    public function accounts(){
        return $this->belongsTo('App\Permission\Models\Account','id_account');
    }

    public function clients(){
        return $this->belongsTo('App\Permission\Models\Client','id_client');
    }

    public function vendors(){
        return $this->belongsTo('App\Permission\Models\Vendor','id_vendor');
    }

   
}
