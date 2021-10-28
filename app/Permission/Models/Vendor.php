<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'name', 'status',
    ];

    public function quotations(){
        return $this->belongsToMany('App\Quotation')->withTimesTamps();
    }

    public function clients(){
        return $this->belongsToMany('App\Client')->withTimesTamps();
    }
    
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}