<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = "materia";
    
    public function departamento(){
        return $this->belongsTo('App\Departamento', 'departamento_id');
    }
        
    

    //<
    //>
}