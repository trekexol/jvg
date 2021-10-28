<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'description', 'status',
    ];

     public function vendors(){
        return $this->belongsToMany('App\Vendor')->withTimesTamps();
    }
    public function transports(){
        return $this->belongsToMany('App\Transport')->withTimesTamps();
    }

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}