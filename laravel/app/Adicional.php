<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adicional extends Model
{
    protected $table = "adicional";
        
    public function producto(){
        return $this->belongsTo('App\Producto');
    }
    public function tipo_producto(){
        return $this->belongsTo('App\TipoProducto');
    }
    public function ingrediente(){
        return $this->belongsTo('App\Ingrediente');
    }
    
}