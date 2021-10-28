<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'name', 'status',
    ];

    public function expensesandpurchases(){
        return $this->belongsToMany('App\ExpensesAndPurchase')->withTimesTamps();
    }

    
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}