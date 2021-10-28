<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description', 'code_one', 'code_two', 'code_three', 'code_four',
    ];

    public function detailvouchers(){
        return $this->belongsToMany('App\DetailVoucher')->withTimesTamps();
    }


    public function quotationpayments(){
        return $this->belongsToMany('App\QuotationPayment')->withTimesTamps();
    }

    public function expensepayments(){
        return $this->belongsToMany('App\ExpensePayment')->withTimesTamps();
    }

    public function anticipos(){
        return $this->belongsToMany('App\Anticipo')->withTimesTamps();
    }

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}