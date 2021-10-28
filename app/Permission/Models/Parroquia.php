<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id' , 'descripcion', 
    ];

    public function branches(){
        return $this->belongsToMany('App\Branch')->withTimesTamps();
    }
    public function vendors(){
        return $this->belongsToMany('App\Vendor')->withTimesTamps();
    }


    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}