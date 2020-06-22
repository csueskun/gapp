<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ComboProducto extends Model
{
    protected $table = "combo_producto";
    
    public function producto(){
        return $this->belongsTo('App\Producto');
    }
        
    public function combo(){
        return $this->belongsTo('App\Combo');
    }
        
    

    //<
    //>
}