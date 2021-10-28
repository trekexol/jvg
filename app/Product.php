<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Product extends Model
{
    
    public function segments(){
        return $this->belongsTo('App\Permission\Models\Segment','segment_id');
    }

    public function subsegments(){
        return $this->belongsTo('App\Permission\Models\SubSegment','subsegment_id');
    }
    public function twosubsegments(){
        return $this->belongsTo('App\TwoSubSegment','twosubsegment_id');
    }
    public function threesubsegments(){
        return $this->belongsTo('App\ThreeSubSegment','threesubsegment_id');
    }

    public function unitofmeasures(){
        return $this->belongsTo('App\Permission\Models\UnitOfMeasure','unit_of_measure_id');
    }

    public function inventory(){
        return $this->hasMany('App\Inventory');
    }
    
    public function quotation_products() {
        return $this->belongsTo('App\QuotationProduct', 'id_inventory');   
    }
}
