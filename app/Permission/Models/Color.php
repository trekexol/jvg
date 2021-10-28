<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description',
    ];

    public function transports(){
        return $this->belongsToMany('App\Transport')->withTimesTamps();
    }
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}