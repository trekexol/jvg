<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    
    
    
    public function companies(){
        return $this->belongsTo('App\Permission\Models\Company','company_id');
    }
    public function parroquias(){
        return $this->belongsTo('App\Permission\Models\Parroquia','parroquia_id');
    }


    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function parroquia()
    {
        return $this->belongsTo('App\Parroquia', 'parroquia_id');
    }

}
