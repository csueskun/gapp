<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class SeguimientoInvestigacion extends Model
{
    protected $table = "seguimiento_investigacion";
    
    public function actividad_investigativa(){
        return $this->belongsTo('App\ActividadInvestigativa', 'actividad_investigativa_id');
    }
        
    

    //<
    //>
}