<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoPedido extends Model
{
    protected $table = "producto_pedido";
                    
    public function pedido(){
        return $this->belongsTo('App\Pedido');
    }
    
    public function producto(){
        return $this->belongsTo('App\Producto');
    }

}