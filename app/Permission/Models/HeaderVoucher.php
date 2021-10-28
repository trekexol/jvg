<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderVoucher extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description', 'code_one', 'code_two', 'code_three', 'code_four',
    ];

    public function detailvouchers(){
        return $this->belongsToMany('App\DetailVoucher')->withTimesTamps();
    }

   

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}