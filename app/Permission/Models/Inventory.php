<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'name',
    ];

    public function quotationproducts(){
        return $this->belongsToMany('App\QuotationProduct')->withTimesTamps();
    }

    public function expensedetails(){
        return $this->belongsToMany('App\ExpenseDetail')->withTimesTamps();
    }

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}