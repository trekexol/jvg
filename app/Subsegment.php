<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subsegment extends Model
{

    use Notifiable;
    
    public function segments(){
        return $this->belongsTo('App\Permission\Models\Segment','segment_id');
    }

    public function twosubsegment()
    {
        return $this->hasMany('App\TwoSubsegment');
    }
}
