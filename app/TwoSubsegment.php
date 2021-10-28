<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoSubsegment extends Model
{
    public function subsegments(){
        return $this->belongsTo('App\Subsegment','subsegment_id');
    }

    public function threesubsegment()
    {
        return $this->hasMany('App\ThreeSubsegment');
    }
}
