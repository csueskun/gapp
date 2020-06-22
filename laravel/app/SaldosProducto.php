<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class SaldosProducto extends Model
{
    protected $table = "saldos_producto";
    
    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    }
        
    public function ingrediente(){
        return $this->belongsTo('App\Ingrediente', 'ingrediente_id');
    }
        
    

    //<
    //>
}