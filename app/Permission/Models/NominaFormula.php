<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class NominaFormula extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description',
    ];

    public function nominaformulas(){
        return $this->belongsToMany('App\NominaFormula')->withTimesTamps();
    }
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}