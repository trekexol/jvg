<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = ['nombres','apellidos'];

    public function estado()
    {
        return $this->belongsTo('App\Estado', 'estado_id');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Municipio', 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo('App\Parroquia', 'parroquia_id');
    }

    public function professions()
    {
        return $this->belongsTo('App\Profession', 'profession_id');
    }


    public function transports()
    {
        return $this->belongsToMany(Transport::class, 'historic_transports'
        ,'employee_id','transport_id')->withPivot('date_begin', 'date_end');;
    }
}
