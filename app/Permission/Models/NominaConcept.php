<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class NominaConcept extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'description', 'abbreviation', 'sign'
    ];

    public function nominacalculations(){
        return $this->belongsToMany('App\NominaCalculation')->withTimesTamps();
    }
    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}