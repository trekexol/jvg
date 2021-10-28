<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'name' , 'description', 
    ];

    public function branches(){
        return $this->belongsToMany('App\Branch')->withTimesTamps();
    }

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}