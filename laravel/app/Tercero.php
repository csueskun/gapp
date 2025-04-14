<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $table = "tercero";
    
    public function nombrecompleto(){
        return $this->belongsTo('App\Nombrecompleto', 'nombrecompleto');
    }
        
    

    //<
    //>
}