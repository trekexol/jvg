<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class ComisionType extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description',
    ];

    public function vendors(){
        return $this->belongsToMany('App\Vendor')->withTimesTamps();
    }


    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}