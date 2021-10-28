<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    public function inventories(){
        return $this->belongsTo('App\Permission\Models\Inventory','id_inventory');
    }
    public function product() {
        return $this->hasMany('App\Product');   
    }

    public function quotations(){
        return $this->belongsTo('App\Quotation','id_quotation');
    }
}
