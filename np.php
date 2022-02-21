<?php
$drawer = $_GET['drawer']?:0;
$stack = $_POST['stack'];
$dedicadas = array();
$stack = json_decode(json_encode($stack), FALSE);
if(!$stack){
    $stack = $_GET['stack'];
    $stack = json_decode($stack);
}

$impresora = '';
$i = 0;
$quitar = array();
$res = '';
$chars = 40;
$res.='<html><body style="font-family: monospace">';
$font = '';
foreach($stack as $instruccion){
    if($instruccion->i == 'chars'){
        $chars = intval($instruccion->v);
    }
    if($instruccion->i == 'impresora'){
    }
    if($instruccion->i == 'texto' || $instruccion->i == 'producto_pedido'){
        $res.='<div '.$font.'>';
        $text = preg_replace("/\r|\n/", "&lb;", $instruccion->v);
        $aux = explode("&lb;", $text);
        $lines = [];
        for ($j=0; $j < count($aux); $j++){
            $row = $aux[$j];
            $row = str_split($row, $chars);
            foreach ($row as $line){
                $res.= str_replace(' ', '&nbsp;', $line).'<br/>';
            }
        }
        $res.='</div>';
    }
    if($instruccion->i == 'producto_pedido'){
    }
    else if($instruccion->i == 'imagen'){
    }
    else if($instruccion->i == 'logo'){
        $res.='<img src="/HSPrint/img/logo.png"/>';
    }
    else if($instruccion->i == 'doble'){
        $font = 'style="font-size: 2em"';
    }
    else if($instruccion->i == 'sencilla'){
        $font = '';
    }
    else if($instruccion->i == 'gaveta'){
    }
    else if($instruccion->i == 'solo-gaveta'){
    }
    $i++;
}

if($drawer == 1){
}
$res.='</body></html>';
echo $res;