<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = "tipo_documento";
        
    function aumentarConsecutivo(){
        $this->consecutivo = $this->consecutivo+1;
        $this->save();
    }

}