<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptVacation extends Model
{
    public function employees(){
        return $this->belongsTo('App\Permission\Models\Employee','employee_id');
    }

}
