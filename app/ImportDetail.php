<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImportDetail extends Model
{
    public function expensedetails(){
        return $this->belongsTo('App\ExpensesAndPurchase','id_purchases');
    }

}
