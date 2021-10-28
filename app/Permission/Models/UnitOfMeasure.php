<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'description', 'status',
    ];


    public function products(){
        return $this->belongsToMany('App\Product')->withTimesTamps();
    }
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}