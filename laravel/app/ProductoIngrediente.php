<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoIngrediente extends Model
{
    protected $table = "producto_ingrediente";
        
                    
    public function producto(){
        return $this->belongsTo('App\Producto');
    }
    public function ingrediente(){
        return $this->belongsTo('App\Ingrediente');
    }

}