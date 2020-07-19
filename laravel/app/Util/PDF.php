<?php

namespace App\Util;
class PDF{

    public static function InventarioDetallado($detalles, $inicio, $fin, $tipo){
        $fecha = date("d/m/Y h:ia");

        $producto = '';
        if($tipo=='ING'){
            if(count($detalles)>0){
                $producto = $detalles[0]->descripcion;
            }
        }
        else{
            if(count($detalles)>0){
                $producto = $detalles[0]->detalle;
            }
        }
        $tipo = $tipo=='ING'?'Ingrediente':'Producto';

        $html="
        <!DOCTYPE html>
        <html>
        <head>
            <title>Detallado</title>
            <style>
            
            html{
                margin:4px;
                font-size: 0.8em;
            }
            body{
                margin:10px;
                padding:10px;
            }
            img{
                width: 100%;
            }
            .al-der{
                text-align: right;
            }
            .subtotal, .total{
                font-weight: bold;
            }
            .total{
            }
            .centrado{
                text-align: center;
            }
            .min-w{
                width: 1px;
            }
            table{
                border-collapse: collapse;
                width: 100%;
                border: thin solid gray;
            }
            table th, table td{
              border: thin solid gray;
            }
            table th{
              font-size: 1.1em;
              padding: 4px;
            }
            </style>
        </head>
        <body>
        
        <table>
        <tbody>
        <th class='min-w'>
            $tipo
        </th>
        <td class=''>
            $producto
        </td>
        <th class='min-w'>
            Fecha Corte
        </th>
        <td class='centrado'>
            $inicio - $fin
        </td>
        <th class='min-w'>
            Fecha Generación
        </th>
        <td class='centrado'>
            $fecha
        </td>
        </tbody>
        </table>
        <table>
        <thead>
            <tr>
                <th>
                    Fecha
                </th>
                <th>
                    Documento
                </th>
                <th>
                    Cantidad
                </th>
                <th>
                    Valor
                </th>
                <th>
                    Tipo
                </th>
            </tr>
        </thead>
        <tbody>";
        foreach($detalles as $detalle){

            $html.="
            <tr>
            <td>
            {$detalle->created_at}
            </td>
            <td class='centrado'>
            {$detalle->des}
            </td>
            <td class='al-der'>
            {$detalle->cantidad}
            </td>
            <td class='al-der'>
            {$detalle->valor}
            </td>
            <td class='al-der'>
            {$detalle->tipo}
            </td>
            </tr>";
        }
        $html.="
            </tbody>
            </table>
            </body>
            </html>
            ";
        return $html;
    }
    
    public static function impCuadre($cuadre, $fvs, $inicio, $fin, $descuento, $propina, $totalq){
        
        $fecha = date("d/m/Y h:ia");
        $inicio = date_create($inicio);
        $inicio = date_format($inicio, "d/m/Y");
        $fin = date_create($fin);
        $fin = date_format($fin, "d/m/Y");




        
        $html = '
        
            <html>
                <head>
                    <title>Impresión de Documento</title>
                    <style>
                        html{
                            margin:4px;
                        }
                        body{
                            font-family: monospace;
                            margin:0;
                            padding:0;
                            border: thin solid black;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .al-izq{
                            text-align: left;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 20px;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            /*background-image: url(./images/logo220.png);*/
                            height: 00px;
                            background-repeat: no-repeat;
                        }
                        .letra-sans{
                            font-family: sans-serif;
                        }
                        .letra-sans.mediana{
                            font-family: sans-serif;
                            font-size: 1.2em;
                        }
                        .letra-sans.grande{
                            font-family: sans-serif;
                            font-size: 1.5em;
                        }
                        .color-rojo{
                            color:red;
                        }
                        .color-verde{
                            color:green;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    
                    <h3 class="centrado letra-sans grande">CUADRE DE CAJA</h3>
                    <table>
                        <tr>
                            <td class="letra-sans">Fecha y Hora de Realización:</td>
                            <td class="al-der">'.$fecha.'</td>
                        </tr>
                        <tr>
                            <td class="letra-sans">Fecha de Inicio del Cuadre:</td>
                            <td class="al-der">'.$inicio.'</td>
                        </tr>
                        <tr>
                            <td class="letra-sans">Fecha de Finalización del Cuadre:</td>
                            <td class="al-der">'.$fin.'</td>
                        </tr>
                    </table>
                    <font size="40px"><br/></font>
                    ';
        $propinas = 0;
        foreach($cuadre as $linea){

            if($linea->tipo == 'PRO'){
                $propinas = $linea->total;
            }
            if($linea->tipo == '00'){
                $linea->total += $propinas;
            }
        }

            foreach($cuadre as $linea){      
                
                if($linea->tipo != '00'){
            $html.='<font size="5px"></font><table>
                        <tr><td colspan="3" class="overflow"><hr></td></tr>';
            
                    if ($linea->tipo == 'FV' && count($fvs)>0) {
                        $html.="</table><table>";
                        foreach ($fvs as $fv) {
                            $descripcion = $fv->descripcion. " x ".$fv->cantidad;
                            $descripcion = str_replace(' "unico"', '', $descripcion);
                            $html.="<tr><td class='al-izq'>".$descripcion."</td><td style='width: 180px;font-size: 1.3em;' class='al-der " . ($linea->ie == 'E' ? 'color-rojo' : 'color-verde') . "'>$ " . number_format($fv->total) . "</td></tr>";
                            
                        }
                        $html.="<tr><td class='al-der'></td><td style='width: 180px;' class='al-der'>------------</td></tr>";
                        $html.="</table><table>";
                    }
                $html.='
                        <tr>
                            <td class="letra-sans mediana" style="width: 1px">'.$linea->tipo.'</td>
                            <td class="letra-sans mediana">
                            - '.self::nombreTipoDocumento($linea->tipo).'
                            </td>
                            <td style="font-size: 1.3em;" class="al-der '.($linea->ie=='E'?'color-rojo':'color-verde').'">
                            '.($linea->ie=='E'?'- ':'').'$ '.number_format($linea->total,0).'
                            </td>
                        </tr>';

            $html.='
                    </table>
                                     ';
                }
                else{
                    $html2='
                    <table>
                        <tr><td colspan="2" class="overflow"><hr></td></tr>
                        <tr class="total"><td class="letra-sans mediana">Total</td><td class="al-der '.($linea->total>0?'color-verde':'color-rojo').'">$ '.number_format($linea->total,0).'</td></tr>
                        <tr><td colspan="2" class="overflow"><hr></td></tr>
                    </table>

                ';
                }

                
            }
            $lb = '<font size="5px"></font><table><tr><td colspan="3" class="overflow"><hr></td></tr></table>';
            $linea = $lb;
            foreach ($totalq as $t){
                $linea .= '<table>';
                $linea.= "<tr><td class='al-izq letra-sans mediana'>IVA</td><td style='width: 180px;font-size: 1.3em;' class='al-der color-verde'>$ " . number_format($t->impiva) . "</td></tr>";
                $linea .= '<tr><td colspan="2" class="overflow"><hr></td></tr>';
                $linea.= "<tr><td class='al-izq letra-sans mediana'>IMP.CONSUMO</td><td style='width: 180px;font-size: 1.3em;' class='al-der color-verde'>$ " . number_format($t->impcon) . "</td></tr>";
                $linea .= '<tr><td colspan="2" class="overflow"><hr></td></tr>';
                $linea.= "<tr><td class='al-izq letra-sans mediana'>EFECTIVO</td><td style='width: 180px;font-size: 1.3em;' class='al-der color-verde'>$ " . number_format($t->efectivo) . "</td></tr>";
                $linea .= '<tr><td colspan="2" class="overflow"><hr></td></tr>';
                $linea.= "<tr><td class='al-izq letra-sans mediana'>DEBITO</td><td style='width: 180px;font-size: 1.3em;' class='al-der color-verde'>$ " . number_format($t->debito) . "</td></tr>";
                $linea .= '<tr><td colspan="2" class="overflow"><hr></td></tr>';
                $linea.= "<tr><td class='al-izq letra-sans mediana'>CRÉDITO</td><td style='width: 180px;font-size: 1.3em;' class='al-der color-verde'>$ " . number_format($t->tcredito) . "</td></tr>";
                $linea .= '<tr><td colspan="2" class="overflow"><hr></td></tr>';
                $linea .= '</table>';
            }
            $html.=$linea;
            $html.= $html2;
            $html.='</body></html>';
        
        
        return $html;
    }
    public static function impDocumento($documento){
        
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($documento->created_at);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");
        
        $html = '
        
            <html>
                <head>
                    <title>Impresión de Documento</title>
                    <style>
                        html{
                            margin:4px;
                            font-size: 14px;
                        }
                        body{
                            margin:0;
                            padding:20px 40px;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 1.3em;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            background-image: url(./images/logo_h100.png);
                            height: 100px;
                            background-repeat: no-repeat;
                            background-size: contain;
                        }
                        table.bordered,
                        table.bordered th,
                        table.bordered td{
                            border-collapse: collapse;
                            border: thin solid darkgrey;
                        }
                        table.bordered td{
                            padding: 2px 2px;
                        }
                        p.common{
                            padding: 0px;
                            margin: 0px;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    <br>
                    <p class="common">Documento: <strong>'.$documento->tipodoc.$documento->numdoc.'</strong></p>
                    <p class="common">Fecha: <strong>'.$fechaPedido.'</strong></p>
                    <p class="common">Mesa: <strong>'.$documento->mesa_id.'</strong></p>
                    <p class="common">Tercero: <strong>'.($documento->tercero?($documento->tercero->identificacion.' '.$documento->tercero->nombrecompleto):'VARIOS').'</strong></p>
                    <br>
                    ';
        $html.="
        <table class='bordered'>
        <tr><th>Item</th><th>Detalle</th><th>Cantidad</th><th>Valor</th><th>Total</th></tr>
        ";
                        $ii=1;
        foreach ($documento->detalles as $detalle) {
            $detalle->detalle = str_replace(" 1/", "<br/>&nbsp;1/", $detalle->detalle);
            $detalle->detalle = str_replace(" EXTRA", "<br/>&nbsp;&nbsp;EXTRA", $detalle->detalle);
            $html.='
                        <tr>
                            <td>
                                '.$ii.'
                            </td>
                            <td>
                                '.$detalle->detalle.'
                            </td>
                            <td class="al-der">
                            '.number_format($detalle->cantidad,0).'
                            </td>
                            <td class="al-der">
                                $'.number_format($detalle->valor,0).'
                            </td>
                            <td class="al-der">
                                $'.number_format($detalle->total,0).'
                            </td>
                        </tr>
                                     ';
            $ii++;
        }

        $html.='
                    </table>
                    <br/>
                    <table>
                        <tr><td colspan="2" class="overflow" style="font-family: monospace">---------------------------------------------------------------------------------------</td></tr>
                        <tr class="total"><td>Total</td><td class="al-der">$'.number_format($documento->total,0).'</td></tr>
                        <tr><td colspan="2" class="overflow" style="font-family: monospace">---------------------------------------------------------------------------------------</td></tr>
                    </table>
                </body>
            </html>

                ';
        
//        echo $html;
//        echo "<textarea>".$html."</textarea>";
//        die();
        return $html;
    }
    public static function reporteVentas($config, $reporte, $fecha_inicio, $fecha_fin){

        $fecha = date("d/m/Y h:ia");

        $html = '
        
            <html>
                <head>
                    <title>Impresión de Documento</title>
                    <style>
                        html{
                            margin:4px;
                            font-size: 14px;
                        }
                        body{
                            margin:0;
                            padding:20px 40px;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 1.3em;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            background-image: url(./images/logo_h100.png);
                            height: 100px;
                            background-repeat: no-repeat;
                            background-size: contain;
                        }
                        table.bordered,
                        table.bordered th,
                        table.bordered td{
                            border-collapse: collapse;
                            border: thin solid darkgrey;
                        }
                        table.bordered td{
                            padding: 2px 2px;
                        }
                        table.bordered td{
                            font-size: 0.8em;
                        }
                        p.common{
                            padding: 0px;
                            margin: 0px;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    <h3>Reporte de ventas</h3>
                    <p class="common">Fecha de generación: <strong>'.$fecha.'</strong></p>
                    <p class="common">Fecha inicio: <strong>'.$fecha_inicio.'</strong></p>
                    <p class="common">Fecha fin: <strong>'.$fecha_fin.'</strong></p>
                    <br>
                    ';
        $html.="
        <table class='bordered'>
        <tr>
        <th width='1'>Item</th>
        <th width='50'>Fecha</th>
        <th width='60'>Documento</th>
        <th>Cliente</th>
        <th width='60'>Iva</th>
        <th width='65'>Imp.Consumo</th>
        <th width='65'>Descuento</th>
        <th width='70'>Total</th>
        </tr>
        ";
                        $ii=1;
        $total_iva = 0;
        $total_ico = 0;
        $total_total = 0;
        $total_dcto = 0;
        foreach ($reporte as $factura) {
            $fecha = date_format(date_create($factura->created_at), "d/m/Y");
            $tercero = 'VARIOS';
            $total_iva += $factura->iva?:0;
            $total_ico += $factura->impco?:0;
            $total_total += $factura->total?:0;
            $total_dcto += $factura->descuento?:0;
            if($factura->tercero){
                $tercero = $factura->tercero->identificacion;
                $tercero .= '-';
                $tercero .= $factura->tercero->nombrecompleto;
            }
            $html.='
                        <tr>
                            <td>
                                '.$ii.'
                            </td>
                            <td>
                                '.$fecha.'
                            </td>
                            <td>
                                '."{$factura->tipodoc}-{$factura->numdoc}".'
                            </td>
                            <td>
                                '.strtoupper($tercero).'
                            </td>
                            <td class="al-der">
                                $ '.number_format(($factura->iva?:0), 2).'
                            </td>
                            <td class="al-der">
                                $ '.number_format(($factura->impco?:0), 2).'
                            </td>
                            <td class="al-der">
                                $ '.number_format(($factura->descuento?:0), 2).'
                            </td>
                            <td class="al-der">
                                $ '.number_format(($factura->total?:0), 2).'
                            </td>
                        </tr>
                                     ';
            $ii++;
        }

        $html.='
                        <tr>        
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                <strong style="font-size: 1.5em">Total</strong>
                            </td>
                            <td class="al-der">
                                <strong>$ '.number_format(($total_iva), 2).'</strong>
                            </td>
                            <td class="al-der">
                                <strong>$ '.number_format(($total_ico), 2).'</strong>
                            </td>
                            <td class="al-der">
                                <strong>$ '.number_format(($total_dcto), 2).'</strong>
                            </td>
                            <td class="al-der">
                                <strong>$ '.number_format(($total_total), 2).'</strong>
                            </td>
                        </tr>

                    </table>
                    <br/>
                </body>
            </html>

                ';

//        echo $html;
//        echo "<textarea>".$html."</textarea>";
//        die();
        return $html;
    }
    
    
    public static function impFacturaPedido2($pedido){
        
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($pedido->created_at);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");
        
        $html = '
        
            <html>
                <head>
                    <title>Impresión de Factura</title>
                    <style>
                        html{
                            margin:4px;
                        }
                        body{
                            font-family: monospace;
                            margin:0;
                            padding:0;
                            border: thin solid black;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 20px;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            /*background-image: url(./images/logo220.png);*/
                            height: 0px;
                            background-repeat: no-repeat;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    
                    <h3 class="centrado">BIENVENIDOS</h3>
                    <table>
                        <tr>
                            <td>Fecha y Hora:</td>
                            <td class="al-der">'.$fecha.'</td>
                        </tr>
                        <tr>
                            <td>Fecha y Hora del Pedido:</td>
                            <td class="al-der">'.$fechaPedido.'</td>
                        </tr>
                        <tr>
                            <td>Mesa:</td>
                            <td class="al-der subtotal">'.$pedido->mesa_id.'</td>
                        </tr>
                    </table>
                    ';
                        
        foreach ($pedido->productos_pedido as $producto_pedido) {
            $html.='
                    <table>
                        <tr><td colspan="2" class="overflow">-------------------------------------------------------------</td></tr>
                    </table>
                ';

            $subtotal = 0;
            $subtotal+= $producto_pedido->valor;
            $obs = json_decode($producto_pedido->obs);
            
            if($obs->tipo=="MIXTA"){
                $html.="<table><tr><td>{$producto_pedido->producto->tipo_producto->descripcion} {$obs->tamano}</td><td class='al-der'>$".number_format($producto_pedido->valor, 0)."</td></tr>";
                foreach($obs->mix as $fraccion) {
                    $html.='<tr><td>&nbsp;1/' . count($obs->mix) . ' ' . $fraccion->nombre.'</td><td></td></tr>';
                    foreach($fraccion->ingredientes as $ingrediente) {
                        $html.='<tr><td>&nbsp;&nbsp;SIN '.$ingrediente.'</td><td></td></tr>';
                    }
                    foreach ($fraccion->adicionales as $adicional) {
                        $val_adicional_fraccion = $adicional->valor/count($obs->mix);
                        $val_adicional_fraccion = ceil($val_adicional_fraccion/100)*100;
                        $html.='<tr><td>&nbsp;&nbsp;EXTRA ' . $adicional->nombre.'</td><td class="al-der">$'.$val_adicional_fraccion.'</td></tr>';
                        $subtotal+= $val_adicional_fraccion;
                    }
                }
            }
            else{
                $html.="<font size='5px'><br/></font><table><tr><td>{$producto_pedido->producto->tipo_producto->descripcion} ";
                $html.=$producto_pedido->producto->descripcion." ".$obs->tamano;
                
                        $html.='
                   </td>
                   <td class="al-der">
                       $' . number_format($producto_pedido->valor, 0) . '
                   </td>
               </tr>
                            ';
                foreach ($producto_pedido->producto_pedido_adicionales as $producto_pedido_adicional) {
                    $subtotal+= $producto_pedido_adicional->adicional->valor;
                    $html.='
                            <tr>
                                <td>
                                    EXTRA ' . $producto_pedido_adicional->adicional->ingrediente->descripcion . '
                                </td>
                                <td class="al-der">
                                    $' . number_format($producto_pedido_adicional->adicional->valor, 0) . '
                                </td>
                            </tr>
                                         ';
                }
            }
            
            

//            if(!$obs->tipo=="MIXTA"){
//                
//            }
            $html.='
                        <tr class="subtotal">
                            <td>
                                Subtotal
                            </td>
                            <td class="al-der">
                                $'.number_format($subtotal,0).'
                            </td>
                        </tr>
                    </table>
                                     ';
        }

        $html.='
                    </table>
                    <br/>
                    <table>
                        <tr><td colspan="2" class="overflow">-------------------------------------------------------------</td></tr>
                        <tr class="total"><td>Total</td><td class="al-der">$'.number_format($pedido->total,0).'</td></tr>
                        <tr><td colspan="2" class="overflow">-------------------------------------------------------------</td></tr>
                    </table>
                </body>
            </html>

                ';
        
//        echo $html;
//        echo "<textarea>".$html."</textarea>";
//        die();
        return $html;
    }
    
    public static function impFacturaPedido($productos, $fechapedido, $mesa, $documento){
        
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($fechapedido);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");
        
        $html = '<!DOCTYPE html>
        <html><head>
                    <title>Impresión de Factura</title>
                    <style type="text/css">
                        html{
                            margin:4px;
                        }
                        body{
                            font-family: monospace;
                            margin:0;
                            padding:0;
                            border: thin solid black;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 20px;
                        }
                        .desc{
                            width: 200px;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            /*background-image: url(./images/logo220.png)*/;
                            height: 70px;
                            background-repeat: no-repeat;
                        }
                    </style>
                </head><body>
                    <div class="logo"></div>
                    
                    <h3 class="centrado">BIENVENIDOS</h3>
                    <table>
                        <tr>
                            <td>Número Factura:</td>
                            <td class="al-der">.$documento->numdoc.</td>
                        </tr>
                        <tr>
                            <td>Fecha y Hora:</td>
                            <td class="al-der">'.$fecha.'</td>
                        </tr>
                        <tr>
                            <td>Fecha y Hora del Pedido:</td>
                            <td class="al-der">'.$fechaPedido.'</td>
                        </tr>
                        <tr>
                            <td>Mesa:</td>
                            <td class="al-der subtotal">'.$mesa.'</td>
                        </tr>
                    </table>
                    ';
        $tipo_producto_a = "";
        $total_producto = 0;
        $total = 0;
        foreach ($productos as $producto) {
            $tipo_producto = $producto->tipo_producto;
            
            if($tipo_producto_a!="" && $tipo_producto != $tipo_producto_a){
                $html.='
                    <table><tr class="subtotal">
                        <td>
                            Total ' . $tipo_producto_a . '
                        </td>
                        <td class="al-der">
                            $' . number_format($total_producto, 0) . '
                        </td>
                    </tr></table>';
                $total += $total_producto;
                $total_producto=0;
            }
            
            $html.= "<table>";
            
            $obs = json_decode($producto->obs);
            
                    
            if($tipo_producto != $tipo_producto_a){
                $html.= "
                            <tr><td colspan='2' class='overflow'>-------------------------------------------------------------</td></tr>
                            <tr class='subtotal'><td colspan='2'>$tipo_producto</td></tr>
                        ";
            }
            $total_producto+=$producto->total;
            if($obs->tipo == "MIXTA"){
                $html.= "<tr><td class='desc'>&nbsp;$obs->tamano</td><td class='al-der'>".number_format($producto->valor,0)."</td></tr>";
                $cant_mix = count($obs->mix);
                foreach($obs->mix as $mix){
                    $html.="<tr><td class='desc'>&nbsp;&nbsp;1/$cant_mix $mix->nombre</td><td></td></tr>";
                    foreach($mix->adicionales as $adicional_mix){
                        $val_adicional_fraccion = ceil($adicional_mix->valor/($cant_mix*100))*100;
                        $html.= "<tr><td class='desc'>&nbsp;&nbsp;&nbsp;EXTRA $adicional_mix->nombre</td><td class='al-der'>".number_format($val_adicional_fraccion,0)."</td></tr>";
//                        $total_producto+=$val_adicional_fraccion;
                    }
                }
            }
            else{
                $obs->sabor = isset($obs->sabor)?$obs->sabor:"";
                $obs->tamano = isset($obs->tamano)?$obs->tamano:"";
                $adicionales_producto = json_decode($producto->adicionales);
                $html.= "<tr><td class='desc'>&nbsp;$producto->descripcion $obs->sabor $obs->tamano</td><td class='al-der'>".number_format($producto->valor,0)."</td></tr>";
                if($producto->adicionales!=null){
                    foreach($adicionales_producto as $adicional_producto){
                        $html.= "<tr><td class='desc'>&nbsp;&nbsp;EXTRA $adicional_producto->descripcion</td><td class='al-der'>".number_format($adicional_producto->valor,0)."</td></tr>";
//                        $total_producto+=$adicional_producto->valor;
                    }
                }
            }
            
            $html.= "</table>";
            $tipo_producto_a = $tipo_producto;
            
        }
        $total += $total_producto;
        $html.='
            <table><tr class="subtotal">
                <td>
                    Total ' . $tipo_producto_a . '
                </td>
                <td class="al-der">
                    $' . number_format($total_producto, 0) . '
                </td>
            </tr></table>';
        
        $html.='
                    <br/>
                    <table>
                        <tr><td colspan="2" class="overflow">-------------------------------------------------------------</td></tr>
                        <tr class="total"><td>Total</td><td class="al-der">$'.number_format($total, 0).'</td></tr>
                        <tr><td colspan="2" class="overflow">-------------------------------------------------------------</td></tr>
                    </table>
                </body></html>';
        return $html;
    }
    
    public static function impComandaPedido($pedido){
        
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($pedido->created_at);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");
        
        $html = '
            
            <html>
                <head>
                    <title>Impresión de Comanda</title>
                    <style type="text/css">
                        html{
                            margin:4px;
                        }
                        body{
                            font-family: monospace;
                            margin:0;
                            padding:0;
                            border: thin solid black;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 20px;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            /*background-image: url(/images/logo220.png)*/;
                            height: 70px;
                            background-repeat: no-repeat;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    
                    <h3 class="centrado">ORDEN</h3>
                    <table>
                        <tr>
                            <td>Fecha y Hora:</td>
                            <td class="al-der">'.$fecha.'</td>
                        </tr>
                        <tr>
                            <td>Fecha y Hora del Pedido:</td>
                            <td class="al-der">'.$fechaPedido.'</td>
                        </tr>
                        <tr>
                            <td>Mesa:</td>
                            <td class="al-der subtotal">'.$pedido->mesa_id.'</td>
                        </tr>
                    </table>
                    <table>
                    ';
                        
        foreach ($pedido->productos_pedido as $producto_pedido) {
            if($producto_pedido->producto->impcomanda == 0){
                continue;
            }

            $x_cantidad = ' x'.$producto_pedido->cant;

            $subtotal = 0;
            $subtotal+= $producto_pedido->producto->valor;
            $obs = json_decode($producto_pedido->obs);
            $obs->sabor = isset($obs->sabor)?$obs->sabor:"";
            $html.='
                        <tr><td class="overflow">-------------------------------------------------------------</td></tr>
                        <tr>
                            <td>
                                '.$producto_pedido->producto->tipo_producto->descripcion." ";
            if($obs->tipo == "MIXTA"){
                $html.=$obs->tamano;
                foreach ($obs->mix as $fraccion) {
                    $html.='<br/>&nbsp;';
                    $html.='1/' . count($obs->mix) . ' ' . $fraccion->nombre;
                    foreach ($fraccion->ingredientes as $ingrediente) {
                        $html.='<br/>&nbsp;&nbsp;SIN ' . $ingrediente;
                    }
                    foreach ($fraccion->adicionales as $adicional) {
//                        $val_adicional_fraccion = $adicional->valor/count($obs->mix);
//                        $val_adicional_fraccion = ceil($val_adicional_fraccion/100)*100;
//                        $html.=' EXTRA ' . $adicional->nombre.' $'.$val_adicional_fraccion;
                        $html.='<br/>&nbsp;&nbsp;EXTRA ' . $adicional->nombre;
                        
                    }
                    $html.='';
                }
            }
            else{
                $html.=$producto_pedido->producto->descripcion." ".$obs->tamano." ".$obs->sabor.' '.$x_cantidad;
            }
            $html.='
                                     ';
            
//            if(isset($producto_pedido->obs)){
//                $html.='
//                        <tr class="obs">
//                            <td>
//                                ' . $producto_pedido->obs . '
//                            </td>
//                        </tr>
//                                     ';
//            }

            if(!count($obs->mix) && isset($producto_pedido->producto_pedido_adicionales)){
                $html.='
                                ';
                foreach ($obs->ingredientes as $ingrediente) {
                    $html.='<br/>&nbsp;&nbsp;SIN ' . $ingrediente;
                }
                foreach ($producto_pedido->producto_pedido_adicionales as $producto_pedido_adicional) {
                    $subtotal+= $producto_pedido_adicional->adicional->valor;
                    $html.='<br/>&nbsp;&nbsp;EXTRA ' . $producto_pedido_adicional->adicional->ingrediente->descripcion.' ';
                }
                $html.='
                            </td>
                        </tr>
                                     ';
            }
            
        }

        $html.='
                        <tr><td class="overflow">-------------------------------------------------------------</td></tr>
                    </table>

                </body>
            </html>

                ';
        
//        echo $html;
//        die();
        return $html;
    }

    public static function reporteMesero($reporte, $fecha_inicio, $fecha_fin, $mesero){
        $css = '';
        $fecha = date("d/m/Y h:ia");
        if($mesero != '0'){
            $css = "th:nth-child(1),td:nth-child(1){display: none;}";
        }
        $html = '
        
            <html>
                <head>
                    <title>Impresión de Documento</title>
                    <style>
                        html{
                            margin:4px;
                            font-size: 14px;
                        }
                        body{
                            margin:0;
                            padding:20px 40px;
                        }
                        img{
                            width: 100%;
                        }
                        .al-der{
                            text-align: right;
                        }
                        .subtotal, .total{
                            font-weight: bold;
                        }
                        .total{
                            font-size: 1.3em;
                        }
                        .centrado{
                            text-align: center;
                        }
                        table{
                            width: 100%;
                        }
                        div.logo{
                            background-image: url(./images/logo_h100.png);
                            height: 100px;
                            background-repeat: no-repeat;
                            background-size: contain;
                        }
                        table.bordered,
                        table.bordered th,
                        table.bordered td{
                            border-collapse: collapse;
                            border: thin solid darkgrey;
                        }
                        table.bordered td{
                            padding: 2px 2px;
                        }
                        table.bordered td{
                            font-size: 0.8em;
                        }
                        p.common{
                            padding: 0px;
                            margin: 0px;
                        }
                        '.$css.'
                    </style>
                </head>
                <body>
                    <div class="logo"></div>
                    <h3>Reporte de ventas</h3>
                    <p class="common">Fecha de generación: <strong>'.$fecha.'</strong></p>
                    <p class="common">Fecha inicio: <strong>'.$fecha_inicio.'</strong></p>
                    <p class="common">Fecha fin: <strong>'.$fecha_fin.'</strong></p>
                    ';
        if($mesero != '0'){
            $html.="<p class=\"common\">Usuario: <strong>$mesero</strong></p>";
            $html.="<br>";
        }
        $html.="<br>
        <table class='bordered'>
        <tr>
        <th>Usuario</th>
        <th width='55'>Factura</th>
        <th width='45'>Fecha</th>
        <th>Producto</th>
        <th width='50'>Cantidad</th>
        <th width='50'>Valor</th>
        <th width='65'>Total</th>
        </tr>
        ";
        $ii=1;
        $total_iva = 0;
        $total_ico = 0;
        $total_total = 0;
        $total_dcto = 0;

        foreach ($reporte as $row) {
            $fecha = date_format(date_create($row->fecha), "d/m/Y");
//            $tercero = 'VARIOS';
//            $total_iva += $factura->iva?:0;
//            $total_ico += $factura->impco?:0;
            $total_total += $row->total?:0;
//            $total_dcto += $factura->descuento?:0;
//            if($factura->tercero){
//                $tercero = $factura->tercero->identificacion;
//                $tercero .= '-';
//                $tercero .= $factura->tercero->nombrecompleto;
//            }
            $html.='
                        <tr>
                            <td>
                                '.$row->usuario.'
                            </td>
                            <td>
                                '.$row->factura.'
                            </td>
                            <td>
                                '.$fecha.'
                            </td>
                            <td>
                                '.strtoupper($row->producto).'
                            </td>
                            <td class="al-der">
                                '.number_format(($row->cantidad), 0).'
                            </td>
                            <td class="al-der">
                                '.number_format(($row->valor), 2).'
                            </td>
                            <td class="al-der">
                                '.number_format(($row->total), 2).'
                            </td>
                        </tr>
                                     ';
            $ii++;
        }


        $html.='
                        <tr>        
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                <strong style="font-size: 1.5em">Total</strong>
                            </td>
                            <td class="al-der">
                            </td>
                            <td class="al-der">
                            </td>
                            <td class="al-der">
                                <strong>$ '.number_format(($total_total), 2).'</strong>
                            </td>
                        </tr>

                    </table>
                    <br/>
                </body>
            </html>

                ';

//        echo $html;
//        echo "<textarea>".$html."</textarea>";
//        die();
        return $html;
    }
    
    public static function nombreTipoDocumento($tipo){
        $docs = array("FV"=>"Factura de Venta", "FC"=>"Factura de Compra", "PN"=>"Pago de Nómina",
            "BI"=>"Base Inicial", "NI"=>"Nota de Inventario", "CO"=>"Consumo", "DES"=>"Descuento", "PRO"=>"Propina");
        return $docs[$tipo];
    }
}