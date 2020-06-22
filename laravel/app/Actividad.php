<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = "actividad";
    
    public function docente(){
        return $this->belongsTo('App\Docente', 'docente_id');
    }
        
    

    //<
        
    public function semanas(){
        return Actividad::selectRaw('floor(datediff(fecha_fin, fecha_inicio)/7)+1 as total')->where('id',$this->id)->first();
//        return $this->id;
    }
    
    //>
}