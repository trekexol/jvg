<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    public function municipio()
    {
        return $this->belongsTo('App\Municipio', 'municipio_id');
    }
}
