<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleDocumento extends Model
{
    protected $table = "detalle_documento";
        
    public function producto(){
        return $this->belongsTo('App\Producto');
    }         
    public function ingrediente(){
        return $this->belongsTo('App\Ingrediente');
    }         

}