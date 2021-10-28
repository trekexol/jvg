<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anticipo extends Model
{
    public function clients(){
        return $this->belongsTo('App\Permission\Models\Client','id_client');
    }

    public function accounts(){
        return $this->belongsTo('App\Permission\Models\Account','id_account');
    }

    public function providers(){
        return $this->belongsTo('App\Provider','id_provider');
    }

    public function quotations(){
        return $this->belongsTo('App\Quotation','id_quotation');
    }

    public function expenses(){
        return $this->belongsTo('App\ExpensesAndPurchase','id_expense');
    }
}
