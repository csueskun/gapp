<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoPedidoIngrediente extends Model
{
    protected $table = "producto_pedido_ingrediente";
                    
    public function producto_pedido(){
        return $this->belongsTo('App\ProductoPedido');
    }
    public function ingrediente(){
        return $this->belongsTo('App\Ingrediente');
    }

}