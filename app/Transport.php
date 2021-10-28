<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{

    protected $fillable = ['type','placa'];


    public function modelos(){
        return $this->belongsTo('App\Permission\Models\Modelo','modelo_id');
    }
    public function colors(){
        return $this->belongsTo('App\Permission\Models\Color','color_id');
    }
    public function users(){
        return $this->belongsTo('App\Permission\Models\User','user_id');
    }



    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'historic_transports','employee_id','transport_id');
    }
}
