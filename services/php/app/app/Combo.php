<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = "combo";
    //<
    //>

    public function comboProductos() {
        return $this->hasMany('App\ComboProducto');
    }
}