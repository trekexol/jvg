<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensePayment extends Model
{
    public function accounts(){
        return $this->belongsTo('App\Permission\Models\Account','id_account');
    }
}
