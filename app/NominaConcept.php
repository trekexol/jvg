<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominaConcept extends Model
{
    public function formulasq(){
        return $this->belongsTo('App\Permission\Models\NominaFormula','id_formula_q');
    }
    public function formulasm(){
        return $this->belongsTo('App\Permission\Models\NominaFormula','id_formula_m');
    }
    public function formulass(){
        return $this->belongsTo('App\Permission\Models\NominaFormula','id_formula_s');
    }
}
