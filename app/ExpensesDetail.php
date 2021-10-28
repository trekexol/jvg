<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensesDetail extends Model
{
    protected $fillable = ['id_expense','id_inventory','id_account','id_branch','description','exento','islr','amount'
                            ,'price','rate','id_user','status','created_at','updated_at'];

    

    public function inventories(){
        return $this->belongsTo('App\Permission\Models\Inventory','id_inventory');
    }

    public function expenses(){
        return $this->belongsTo('App\ExpensesAndPurchase','id_expense');
    }
}
