<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    public function subsegment()
    {
        return $this->hasMany('App\SegmentSub');
    }
}
