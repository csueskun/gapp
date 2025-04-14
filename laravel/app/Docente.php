<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = "docente";
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'usuario_id');
    }
        
    

    //<
    
    public function nombreCompleto(){
        return $this->nombres.' '.$this->apellidos;
    }
    
    public function presentacion(){
        return $this->codigo.' Docente '.$this->escalafon($this->escalafon);
    }
    
    
    public function escalafon($e){
        switch($e){
            case('1'): return 'Instructor'; break;
            case('2'): return 'Auxiliar'; break;
            case('3'): return 'Asistente'; break;
            case('4'): return 'Asociado'; break;
            case('5'): return 'Titular'; break;
            default: return $e;
        }
    }
    
    
    
    //>
}