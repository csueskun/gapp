<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoSabor extends Model
{
    protected $table = "producto_sabor";
        
                    
    public function producto(){
        return $this->belongsTo('App\Producto');
    }
    public function sabor(){
        return $this->belongsTo('App\Sabor');
    }

}