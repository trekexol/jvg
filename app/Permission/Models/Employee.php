<?php

namespace App\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //es: desde aqui
    //en: from here

    protected $fillable = [
        'id', 'id_empleado', 'nombres' ,'apellidos', 'status',
    ];

    public function receiptvacations(){
        return $this->belongsToMany('App\ReceiptVacation')->withTimesTamps();
    }

    public function vendors(){
        return $this->belongsToMany('App\Vendor')->withTimesTamps();
    }
    
    public function nominacalculations(){
        return $this->belongsToMany('App\NominaCalculation')->withTimesTamps();
    }

    public function permissions(){
        return $this->belongsToMany('App\Permission\Models\Permission')->withTimesTamps();
    }
}