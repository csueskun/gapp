<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoTamano extends Model
{
    protected $table = "producto_tamano";
        
                    
    public function producto(){
        return $this->belongsTo('App\Producto');
    }

}