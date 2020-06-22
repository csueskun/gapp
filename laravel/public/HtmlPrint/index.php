<?php
$stack = $_GET['stack'];
$drawer = $_GET['drawer'];
$stack = json_decode($stack);
$dedicadas = array();

require 'escpos-php/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

echo '<style>html{font-family: monospace; font-size: 30px} .doble{font-size: 60px}</style>';
$impresora = '';
$t = 1;
foreach($stack as $instruccion){
    if($instruccion->instruccion == 'impresora'){
        $impresora = $instruccion->contenido;
        $connector = new WindowsPrintConnector($impresora);
        $printer = new Printer($connector);
    }
    if($instruccion->instruccion == 'texto'){
//        $printer -> text($instruccion->contenido);
        imp($instruccion->contenido, $t);
    }
    if($instruccion->instruccion == 'producto_pedido'){
        if($instruccion->impresora != null && $instruccion->impresora != $impresora){
            if (!in_array($instruccion->impresora, $dedicadas)){
                $dedicadas[] = $instruccion->impresora; 
            }
        }
        else{
//            $printer -> text($instruccion->contenido);
            imp($instruccion->contenido);
        }
    }
    else if($instruccion->instruccion == 'imagen'){
        $imagen = EscposImage::load('./img/'.$instruccion->contenido, false);
//        $printer -> bitImage($imagen);
    }
    else if($instruccion->instruccion == 'logo'){
        $imagen = EscposImage::load('./img/logo.png', false);
//        $printer -> bitImage($imagen);
    }
    else if($instruccion->instruccion == 'doble'){
//        $printer-> setTextSize(2, 2);
        $t=2;
    }
    else if($instruccion->instruccion == 'sencilla'){
//        $printer-> setTextSize(1, 1);
        $t=1;
    }
}

foreach($dedicadas as $impresora_dedicada){
    $connector = new WindowsPrintConnector($impresora_dedicada);
    $printer = new Printer($connector);

    foreach($stack as $instruccion){

        if($instruccion->instruccion == 'texto'){
//            $printer -> text($instruccion->contenido);
            imp($instruccion->contenido);
        }
        if($instruccion->instruccion == 'producto_pedido'){
            if($instruccion->impresora != null && $instruccion->impresora == $impresora_dedicada){
//                $printer -> text($instruccion->contenido);
                imp($instruccion->contenido);
            }
        }
        else if($instruccion->instruccion == 'doble'){
//            $printer-> setTextSize(2, 2);
        }
        else if($instruccion->instruccion == 'sencilla'){
//            $printer-> setTextSize(1, 1);
        }
    }
//    $printer->feed(3);
//    $printer->cut();
//    $printer->close();
}

if($drawer == 1){
    echo '------ Gaveta ------';
    echo '<br/>';
//    $printer -> pulse();
//    $printer -> pulse(1);
//    $printer -> pulse(0, 100, 100);
//    $printer -> pulse(0, 300, 300);
//    $printer -> pulse(1, 100, 100);
//    $printer -> pulse(1, 300, 300);
}
if(count($stack)>0){
//    $printer->feed(3);
//    $printer->cut();
//    $printer->close();
    echo '------ Cut ------';
    echo '<br/>';
}
$printer->close();

function imp($str, $t = 1){
    $clase = '';
    if($t == 2){
        $clase = 'doble';
    }
    echo "<span class='$clase'>";
    $lineas = explode('\n',$str);
    foreach ($lineas as $linea){
        $linea = removeAccents($linea);
        $j = 0;
        for($i=0;$i<strlen($linea);$i++){
            if($j*$t == 33){
                $j=0;
                echo '<br/>';
            }
            if(substr($linea, $i, 1)==' '){
                echo '&nbsp;';
            }
            else{
                echo substr($linea, $i, 1);
            }
            $j++;
        }
        echo '<br/>';
    }
    echo '</span>';
}

function removeAccents($s){
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
        'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
        'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
        'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    return strtr( $s, $unwanted_array );
}

