<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "pedido";
                     
    public function productos(){
        return $this->belongsToMany('App\Producto', 'producto_pedido')->withPivot("id", "obs", "preparado", "created_at");
    }
                 
    public function usuario(){
        return $this->belongsTo('App\Users', 'user_id');
    }

    // 1 activo
    // 2 pagado
    // 3 facturado no pagado
    // 4
    // 5 
}