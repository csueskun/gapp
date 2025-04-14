<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class SeguimientoExtension extends Model
{
    protected $table = "seguimiento_extension";
    
    public function actividad_extension(){
        return $this->belongsTo('App\ActividadExtension', 'actividad_extension_id');
    }
        
    

    //<
    //>
}