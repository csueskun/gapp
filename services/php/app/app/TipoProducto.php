<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = "tipo_producto";
        
    public function productos() {
        return $this->hasMany('App\Producto');
    }
    public function adicionales(){
        return $this->belongsToMany('App\Ingrediente', 'adicional')->withPivot("id","valor","tamano","cantidad");
    }
}