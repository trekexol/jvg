<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IslrConcept extends Model
{
    public function expenses() {
        return $this->hasMany('App\ExpensesAndPurchase');   
    }
}
