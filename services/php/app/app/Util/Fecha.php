<?php

namespace App\Util;
class Fecha{
    
    public static $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    public static $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    public static function StringAFormatoLargo($fecha){
        $fecha = date_create($fecha);
        $imp = self::$dias[date_format($fecha,'w')];
        $imp.= " ".date_format($fecha,'d');
        $imp.= " de ".self::$meses[date_format($fecha,'n')-1];
        $imp.= " de ".date_format($fecha,'Y');
        $imp.= " ".date_format($fecha,'h:i A');
        return $imp;
    }
}