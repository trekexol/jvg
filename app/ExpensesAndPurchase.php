<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensesAndPurchase extends Model
{
    public function providers(){
        return $this->belongsTo('App\Permission\Models\Provider','id_provider');
    }

    public function expensedetails() {
        return $this->hasMany('App\ExpenseDetail');   
    }

    public function islr_concepts(){
        return $this->belongsTo('App\IslrConcept','id_islr_concept');
    }

    public function anticipos() {
        return $this->hasMany('App\Anticipo');   
    }
}
