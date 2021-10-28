<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    public function professions(){
        return $this->belongsTo('App\Permission\Models\Profession','id_profession');
    }
}
