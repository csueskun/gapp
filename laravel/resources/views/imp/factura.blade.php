<?php


    class x{
        
        public static function impLinea($izq, $der,$min){
            $der_ = mb_strlen ($der); 
            $izq_ = mb_strlen ($izq); 
            if($der_ + $izq_ > $min){
                $min = $min*2;
            }
            $min = $min-$der_-$izq_;
            for ($i=0;$i<$min;$i++){
                $izq .= " ";
            }
            return $izq.$der;
        }
    }


// para produccion:
//require __DIR__.'/../../../../laravel/vendor/mike42/escpos-php/autoload.php';
//para desarrollo:
require __DIR__.'/../../../vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */

$caracteres = 32;

$connector = new WindowsPrintConnector($config->impresora);
$printer = new Printer($connector);

$imagen = EscposImage::load(($_SERVER['DOCUMENT_ROOT'])."\\images\\logo_empresa_pos.png", false);
$printer -> bitImage($imagen);

    $pedido = $documento->pedido;
    $fecha = date("d/m/Y h:ia");
    $fechaPedido = date_create($pedido->created_at);
    $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");
    
    $printer->text ($config->encabezado_pos);
    $printer->text ("\n");
    $printer->text ("Factura Nro: $documento->numdoc");
    $printer->text ("\n");
    $printer->text ("Mesa Nro: $pedido->mesa_id");
    $printer->text ("\n");
    $printer->text ("Fecha: $fechaPedido");
    
    $tipo_producto_a = "";
    $total_producto = 0;
    $total = 0;
    foreach ($productos as $producto) {
    $tipo_producto = $producto->tipo_producto;

    if ($tipo_producto_a != "" && $tipo_producto != $tipo_producto_a) {
        $printer->text ("\n");
        $printer->text (x::impLinea('Total ' . $tipo_producto_a , ' $' . number_format($total_producto, 0),$caracteres));
        $total += $total_producto;
        $total_producto = 0;
    }

    $printer->text ("\n");
    

    $obs = json_decode($producto->obs);


    if ($tipo_producto != $tipo_producto_a) {
        $printer->text (str_repeat("-", $caracteres)."\n");
        $printer->text ("$tipo_producto");
        $printer->text ("\n");
    }
    $total_producto += $producto->total;
    if ($obs->tipo == "MIXTA") {
        $printer->text (x::impLinea(" $obs->tamano x$producto->cant", number_format($producto->cant * $producto->valor, 0),$caracteres ));
        $cant_mix = count($obs->mix);
        foreach ($obs->mix as $mix) {
            $printer->text ("\n");
            $printer->text("  1/$cant_mix $mix->nombre");
            foreach ($mix->adicionales as $adicional_mix) {
                $val_adicional_fraccion = ceil($adicional_mix->valor / ($cant_mix * 100)) * 100;
                $printer->text ("\n");
                $printer->text(x::impLinea("    EXTRA $adicional_mix->nombre", number_format($val_adicional_fraccion, 0),$caracteres ));
            }
        }
        $printer->text ("\n");
    } else {
        $obs->sabor = isset($obs->sabor) ? $obs->sabor : "";
        $obs->tamano = isset($obs->tamano) ? $obs->tamano : "";
        $adicionales_producto = json_decode($producto->adicionales);
        $printer->text (x::impLinea(" $producto->descripcion ".($obs->sabor!=""?$obs->sabor." ":"")."$obs->tamano x$producto->cant", number_format($producto->cant * $producto->valor, 0),$caracteres));
        if ($producto->adicionales != null) {
            foreach ($adicionales_producto as $adicional_producto) {
                $printer->text (x::impLinea("  EXTRA $adicional_producto->descripcion", number_format($adicional_producto->valor, 0),$caracteres));
            }
        }
    }
    $tipo_producto_a = $tipo_producto;
}
$total += $total_producto;

$printer->text ("\n");
$printer->text (x::impLinea('Total ' . $tipo_producto_a, ' $' . number_format($total_producto, 0),$caracteres));
$printer->text ("\n");
$printer->text (str_repeat("-", $caracteres));
$printer->text ("\n");
$printer->text (x::impLinea('Total' , ' $'.number_format($total, 0),$caracteres));
$printer->text ("\n");
$printer->text (str_repeat("-", $caracteres));
$printer->text ("\n");
if(isset($config->pie_pos) && $config->pie_pos != ''){
    $printer->text ($config->pie_pos);
    $printer->text ("\n");
}
$printer->text ("Fecha IMP: $fecha");
$printer->text ("\n");
$printer->text ("www.h-software.co");
$printer->text ("\n");
$printer->text ("\n");
$printer->text ("\n");
$printer->cut();
$printer->close();