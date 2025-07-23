<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
        
                    
    public function tipo_producto(){
        return $this->belongsTo('App\TipoProducto');
    }
                    
    public function ingredientes(){
        return $this->belongsToMany('App\Ingrediente', 'producto_ingrediente')->withPivot("tamano", "cantidad");
    }
                    
    public function sabores(){
        return $this->belongsToMany('App\Sabor', 'producto_sabor');
    }
                    
    public function adicionales(){
        return $this->belongsToMany('App\Ingrediente', 'adicional');
    }

    public function tamanos(){
        return $this->hasMany('App\ProductoTamano');
    }
}