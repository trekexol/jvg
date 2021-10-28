<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Inventory extends Model
{

    /*protected $connection = 'arya';

    public function changeConnection($conn){
        $this->connection = $conn;
    }*/

    public function products(){
        return $this->belongsTo('App\Permission\Models\Product','product_id');
    }

    
    
}
