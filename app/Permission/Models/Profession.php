<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description',
    ];

    public function nominas(){
        return $this->belongsToMany('App\Nomina')->withTimesTamps();
    }

    public function employees(){
        return $this->belongsToMany('App\Employee')->withTimesTamps();
    }


    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}