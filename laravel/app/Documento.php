<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = "documento";
    
    public function detalles(){
        return $this->hasMany('App\DetalleDocumento');
    }
    public function pedido(){
        return $this->belongsTo('App\Pedido');
    }
    public function tercero(){
        return $this->belongsTo('App\Tercero');
    }
}