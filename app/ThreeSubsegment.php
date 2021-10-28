<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreeSubsegment extends Model
{
    public function subsegments(){
        return $this->belongsTo('App\TwoSubsegment','twosubsegment_id');
    }
}
