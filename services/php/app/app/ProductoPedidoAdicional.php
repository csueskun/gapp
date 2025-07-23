<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoPedidoAdicional extends Model
{
    protected $table = "producto_pedido_adicional";
        
                    
    public function producto_pedido(){
        return $this->belongsTo('App\ProductoPedido');
    }
    public function adicional(){
        return $this->belongsTo('App\Adicional');
    }

}