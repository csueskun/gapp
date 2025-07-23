<?php



// para produccion:
//require __DIR__.'/../../../../laravel/vendor/mike42/escpos-php/autoload.php';
//para desarrollo:
require __DIR__.'/../../../vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */

$caracteres = 30;

$connector = new WindowsPrintConnector($config->impresora_comanda);
$printer = new Printer($connector);


    $fecha = date("d/m/Y h:ia");
    $fechaPedido = date_create($pedido->created_at);
    $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");

    $fecha = date("d/m/Y h:ia");
    $fechaPedido = date_create($pedido->created_at);
    $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");

    $printer->text ($config->encabezado_comanda);
    $printer->text ("\n");
    $printer->text ("Orden Nro: $pedido->id");
    $printer->text ("\n");
    if($pedido->mesa_id != '0'){
        $printer->text ("Mesa Nro: $pedido->mesa_id");
        $printer->text ("\n");
    }
    $printer->text ("Fecha: $fechaPedido");
    $printer-> setTextSize(2, 2);
    foreach ($pedido->productos_pedido as $producto_pedido) {
        $x_cantidad = ' x'.$producto_pedido->cant;
        if ($producto_pedido->producto->impcomanda == 0) {
            continue;
        }
        if ($producto_pedido->comanda > 0) {
            continue;
        }
        $subtotal = 0;
        $subtotal += $producto_pedido->producto->valor;
        $obs = json_decode($producto_pedido->obs);
        $obs->sabor = isset($obs->sabor) ? $obs->sabor : "";
        $printer->text ("\n");
        $printer->text (str_repeat("-", ($caracteres/2))."\n");
        $printer->text ($producto_pedido->producto->tipo_producto->descripcion." ");
        if ($obs->tipo == "MIXTA") {
            $printer->text ($obs->tamano);
            foreach ($obs->mix as $fraccion) {
                $printer->text ("\n");
                $printer->text ('  1/' . count($obs->mix) . ' ' . $fraccion->nombre);
                foreach ($fraccion->ingredientes as $ingrediente) {
                    $printer->text ("\n");
                    $printer->text ('    SIN ' . $ingrediente);
                }
                foreach ($fraccion->adicionales as $adicional) {
                    $printer->text ("\n");
                    $printer->text ('    EXTRA ' . $adicional->nombre);
                }
            }
            
        } else {
            $printer->text ("\n");
            $printer->text ($producto_pedido->producto->descripcion . " " . $obs->tamano . " " . $obs->sabor.''.$x_cantidad);
        }

        if (!count($obs->mix) && isset($producto_pedido->producto_pedido_adicionales)) {

            foreach ($obs->ingredientes as $ingrediente) {
                $printer->text ("\n");
                $printer->text ('  SIN ' . $ingrediente);
                
            }
            foreach ($producto_pedido->producto_pedido_adicionales as $producto_pedido_adicional) {
                $subtotal += $producto_pedido_adicional->adicional->valor;
                $printer->text ("\n");
                $printer->text ('  EXTRA ' . $producto_pedido_adicional->adicional->ingrediente->descripcion . ' ');
                
            }
        }
    }
    $printer -> setTextSize(1, 1);
    $printer->text ("\n");
    $printer->text (str_repeat("-", $caracteres)."\n");
    if($pedido->obs == null || $pedido->obs == ''){
        $obs = json_decode("{}");
    }
    else{
        $obs = json_decode($pedido->obs);
        if(isset($obs->para_llevar)){
            $printer->text ("** ".$obs->para_llevar);
            $printer->text ("\n");
        }
        if(isset($obs->entregar_en)){
            if($obs->entregar_en=='CAJA'){
                $printer->text ("** ENTREGAR EN CAJA");
            }
            else{
                $printer->text ("** ENTREGAR EN \n".strtoupper($obs->entregar_en));
            }
            $printer->text ("\n");
        }
        if(isset($obs->observacion)){
            $printer->text ("*** ".strtoupper($obs->observacion));
            $printer->text ("\n");
        }
    }
    /*
    if($pedido->mesa_id == '0'){
        $printer->text ("");
        $printer->text ("\n");
    }
    */
    $printer->text ("Fecha IMP: $fecha");
    $printer->text ("\n");
    $printer->text ("\n");
    $printer->text ("\n");
    $printer->text ("\n");
    $printer->cut();
    $printer->close();
    
    
    