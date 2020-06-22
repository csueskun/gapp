<?php

$stack = $_GET['stack'];
$stack = json_decode($stack);

// para produccion:
//require __DIR__.'/../../../../laravel/vendor/mike42/escpos-php/autoload.php';
//para desarrollo:
require __DIR__.'/../../../vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

foreach($stack as $instruccion){
    if($instruccion->instruccion == 'impresora'){
        $connector = new WindowsPrintConnector($instruccion->contenido);
        $printer = new Printer($connector);
    }
    if($instruccion->instruccion == 'texto'){
        $printer -> text($instruccion->contenido);
    }
    else if($instruccion->instruccion == 'imagen'){
        $imagen = EscposImage::load($_SERVER['DOCUMENT_ROOT']."/images/".$instruccion->contenido, false);
        $printer -> bitImage($imagen);
    }
    else if($instruccion->instruccion == 'logo'){
        $imagen = EscposImage::load($_SERVER['DOCUMENT_ROOT']."/images/".'logo_empresa_pos.png');
        $printer -> bitImage($imagen);
    }
    else if($instruccion->instruccion == 'doble'){
        $printer-> setTextSize(2, 2);
    }
    else if($instruccion->instruccion == 'sencilla'){
        $printer-> setTextSize(1, 1);
    }
}

$printer->feed(3);
$printer->cut();
$printer->close();
    