<?php

namespace App\Util;
class POS{
    public static function CuentaDividida($data, $config){
        $width = $config->num_impresora;
        $print = [];
        $print[] = ["i"=> "texto", "v"=> 'Resumen de cuenta'];
        $m = [STR_PAD_RIGHT, STR_PAD_RIGHT, STR_PAD_LEFT];
        for ($i=0; $i < count($data); $i++) { 
            $cuenta = $data[$i];
            try {
                $pedido = $cuenta['pedido'];
            } catch (\Throwable $th) {
                continue;
            }
            $print[] = ["i"=>"texto","v"=>str_repeat('-', $width)];
            $print[] = ["i"=>"texto","v"=>$cuenta['alias']];
            $cuentaMaxLen = strlen($cuenta['total']);
            $cuentaMaxLen2 = 0;
            for ($j=0; $j < count($pedido); $j++) { 
                $pedidoItem = $pedido[$j];
                $pedidoItemLen = strlen($pedidoItem['subtotal']);
                $pedidoItemLen2 = strlen($pedidoItem['cantidad']);
                if($cuentaMaxLen<$pedidoItemLen){
                    $cuentaMaxLen = $pedidoItemLen;
                }
                if($cuentaMaxLen2<$pedidoItemLen2){
                    $cuentaMaxLen2 = $pedidoItemLen2;
                }
            }
            $w = [
                $cuentaMaxLen2+1, $width-2-$cuentaMaxLen-$cuentaMaxLen2, $cuentaMaxLen+1
            ];
            for ($j=0; $j < count($pedido); $j++) {
                $pedidoItem = $pedido[$j];
                $itemPrint = PosPrint::getStackFromTable(
                    [[
                        $pedidoItem['cantidad'],
                        $pedidoItem['nombre'],
                        $pedidoItem['subtotal']
                    ]],
                    $w, [STR_PAD_RIGHT, STR_PAD_RIGHT, STR_PAD_LEFT]
                );
                $print = array_merge($print, $itemPrint);
            }
            $totales = [
                ['', 'TOTAL', $cuenta['total']]
            ];
            if($cuenta['propina']){
                $totales[0][1] = 'SUBTOTAL';
                $totales[] = ['', 'PROPINA', $cuenta['propina']];
            }
            $print = array_merge($print, PosPrint::getStackFromTable($totales,
                $w, [STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_LEFT]
            ));
        }
        return $print;
    }
    public static function ReportePedidosPos($inicio, $fin, $data, $config){
        $width = $config->num_impresora;
        $values = [
            ['Reporte de pedidos', ''],
            ['Fecha inicio', date_format(date_create($inicio), 'd/m/Y H:i')],
            ['Fecha fin', date_format(date_create($fin), 'd/m/Y H:i')],
            ['Fecha impresión', date("d/m/Y H:i")],
            ['', ''],
        ];
        $print = PosPrint::getStackFromTable(
            $values, [19, $width-19], [STR_PAD_RIGHT, STR_PAD_LEFT]);
        
        $values = [];
        $max = [0, 0, 0];
        $sumTotal = 0;
        $sumCantidad = 0;
        foreach ($data as $row) {
            $sumTotal += $row->total;
            $sumCantidad += $row->cantidad;
            $total = number_format($row->total, 0);
            $cantidad = number_format($row->cantidad, 0);
            $values[] = [
                $row->tipo,
                $row->cantidad,
                $total
            ];
            if($max[1]<strlen($cantidad)){
                $max[1] = strlen($cantidad);
            }
            if($max[2]<strlen($total)){
                $max[2] = strlen($total);
            }
        }
        $values[] = ['Total', number_format($sumCantidad, 0), number_format($sumTotal, 0)];
        $max[1] += 1;
        $max[2] += 1;
        $max[0] = $width - $max[1] - $max[2];
        $align = [STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_LEFT];
        $header = [['Categoria', 'Cantidad', 'Valor']];
        $print = array_merge($print, PosPrint::getStackFromTable(
            $header, [$width - 10 - $max[2], 10, $max[2]], $align));
        $print = array_merge($print, PosPrint::getStackFromTable(
            [[str_repeat('-',$width)]], [$width], [STR_PAD_RIGHT]));
        $print = array_merge($print, PosPrint::getStackFromTable($values, $max, $align));
        return $print;
    }
    public static function comandaPosStack($pedido,$config,$re=false){
        $stack = [];
        $texto = '';

        $caracteres = $config->num_impresora_comanda;
        $stack[] = ["i"=>"chars","v"=>$caracteres];
        $stack[] = ["i"=>"impresora","v"=>$config->impresora_comanda, "comanda"=>true];
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($pedido->created_at);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");

        $texto.= ($config->encabezado_comanda)."\n";

        if($re){
            $stack[] = ["i"=>"texto","v"=>'*** COMANDA COMPLETA ***'."\n"];
            $stack[] = ["i"=>"texto","v"=>'(Verificar con el cajero)'."\n"];
        }
        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = ("Orden Nro: $pedido->id")."\n";
        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = ("Fecha: $fechaPedido")."\n";
        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = ("Mesero: {$pedido->usuario->nombres} {$pedido->usuario->apellidos}")."\n";
        $stack[] = ["i"=>"texto","v"=>$texto];

        $stack[] = ["i"=>"doble","v"=>2];
        if($pedido->mesa_id==0){
            $texto = "Domicilio\n";
        }
        else{
            $mesa = "Mesa Nro: $pedido->mesa_id";
            if(isset($pedido->obs) && $pedido->obs != ''){
                $obs = json_decode($pedido->obs, false);
                if(isset($obs->mesa_alias) && $obs->mesa_alias != null){
                    $mesa = "Mesa Nro: $obs->mesa_alias";
                }
            }
            $texto = $mesa."\n";
        }
        $stack[] = ["i"=>"texto","v"=>$texto];

        if($pedido->turno){
            $texto = ("Turno: $pedido->turno");
            $stack[] = ["i"=>"texto","v"=>$texto."\n"];
        }
        $stack[] = ["i"=>"sencilla","v"=>1];
        // $stack[] = ["i"=>"texto","v"=>str_repeat("-", $caracteres)."\n"];
        $texto = '';
        
        $pp_aux = [];
        foreach ($pedido->productos_pedido as $producto_pedido) {
            if(!$re){
                if ($producto_pedido->producto->impcomanda == 0) {
                    continue;
                }
                if ($producto_pedido->comanda > 0) {
                    continue;
                }
            }
            $pp_aux[] = $producto_pedido;
        }
        $pedido->productos_pedido = $pp_aux;

        
        $combos = self::buildCombos($pedido->productos_pedido);
        if(count($combos)>0){
            $pedido->productos_pedido = array_merge($pedido->productos_pedido, $combos);
        }
        $pedido->productos_pedido = self::reagruparProductosPedido($pedido->productos_pedido);
        $first = true;
        foreach ($pedido->productos_pedido as $producto_pedido) {
            if(isset($producto_pedido->combo) && $producto_pedido->combo!=''){
                continue;
            }
            try {
                $impresora_dedicada = $producto_pedido->producto->tipo_producto->impresora != null?($producto_pedido->producto->tipo_producto->impresora):$config->impresora_comanda;
            } catch (\Throwable $th) {
                $impresora_dedicada = $config->impresora_comanda;
            }
            $x_cantidad = ' x'.$producto_pedido->cant;
            $subtotal = 0;
            $subtotal += $producto_pedido->producto->valor;
            if($producto_pedido->nombre_combo){
                $obs = new \stdClass;
                $obs->tipo = 'COMBO';
                $obs->tamano = 'unico';
                $obs->sabor = '';
                $obs->mix = [];
            }
            else{
                $obs = json_decode($producto_pedido->obs);
                $obs->sabor = isset($obs->sabor) ? $obs->sabor : "";
            }
            // $texto.= (str_repeat("-", ($caracteres))."\n");
            //No mostrar tipo en la comanda
            // $tipoProducto = $producto_pedido->producto->tipo_producto->descripcion;
            // $texto.= (isset($producto_pedido->combo) && $producto_pedido->combo && $producto_pedido->combo != null)?"COMBO ": "";

            if ($obs->tipo == "COMBO") {
                try {
                    $producto_pedido->cantidad = intval($producto_pedido->cantidad);
                    //if($producto_pedido->cantidad > 1){
                        $texto.= '' . $producto_pedido->cantidad . 'x ';
                    //}
                } catch (\Throwable $th) {
                    $producto_pedido->cantidad = 1;
                }

                $texto.= $producto_pedido->producto->descripcion;
                foreach ($producto_pedido->obs as $observaciones){
                    if(isset($observaciones['sin_ingrediente']) || isset($observaciones['sabor'])){
                       // $texto.= ("\n");
                       // $texto.= $observaciones['producto'];
                    }
                    if(isset($observaciones['sin_ingrediente'])){
                        $texto.= ("\n");
                        $texto.= '  SIN ';
                        foreach ($observaciones['sin_ingrediente'] as $sin){
                            $texto.= $sin->descripcion.', ';
                        }
                        $texto.= '----';
                        $texto = str_replace(', ----', '', $texto);

                    }
                    if(isset($observaciones['sabor'])){
                        $texto.= ("\n");
                        $texto.= ' SABOR '.$observaciones['sabor'];
                    }
                }
                
            }
            elseif ($obs->tipo == "MIXTA") {
                $texto.= self::normalizeSizes($obs->tamano=='unico'?'':$obs->tamano).$x_cantidad;
                $jj=0;
                foreach ($obs->mix as $fraccion) {
                    $texto.= ("\n");
                    try {
                        $texto.=$obs->dist[$jj];
                    } catch (\Throwable $th) {
                        $texto.=' 1/' . count($obs->mix);
                    }
                    $jj++;
                    $texto.= (' ' . $fraccion->nombre);

                    $esCompuesto = isset($fraccion->compuesto) && $fraccion->compuesto != '' && $fraccion->compuesto != '0' && count($fraccion->compuesto)>0;
                    if($esCompuesto){
                        $primero = true;
                        foreach ($fraccion->compuesto as $in){
                            $texto .= $primero?"\n".'CON '.$in->descripcion:','.$in->descripcion;
                            $primero = false;
                        }
                    }
                    else{
                        foreach ($fraccion->sin_ingredientes as $sin_ingrediente) {
                            $texto.= ("\n");
                            $texto.= ' SIN ' . $sin_ingrediente->descripcion;

                        }
                    }

                    foreach ($fraccion->adicionales as $adicional) {
                        $texto.= ("\n");
                        $texto.= ('  EXTRA ' . $adicional->nombre);
                    }
                }

            } else {
                if($obs->tamano == 'unico'){
                    $texto.= $producto_pedido->producto->descripcion . " " .  $obs->sabor.''.$x_cantidad;
                }
                else{
                    $texto.= self::normalizeSizes($obs->tamano . " " .$producto_pedido->producto->descripcion . " " .  $obs->sabor.''.$x_cantidad);
                }

            }

            if (!count($obs->mix) && isset($producto_pedido->producto_pedido_adicionales)) {
                if(isset($obs->obs) && is_array($obs->obs)){
                    foreach($obs->obs as $obsobs){
                        $texto.= ("\n");
                        $texto.= $obsobs;
                    }
                }

                $esCompuesto = isset($obs->compuesto) && $obs->compuesto != '' && $obs->compuesto != '0' && count($obs->compuesto)>0;
                if($esCompuesto){
                    $primero = true;
                    foreach ($obs->compuesto as $in){
                        $texto .= $primero?"\n".'CON '.$in->descripcion:','.$in->descripcion;
                        $primero = false;
                    }
                }
                else{
                    foreach ($obs->sin_ingredientes as $sin_ingrediente) {
                        if(isset($sin_ingrediente->intercambio) && is_numeric($sin_ingrediente->intercambio)){
                            continue;
                        }

                        $texto.= ("\n");
                        $texto.= preg_replace('!\s+!', ' ', (' SIN ' . $sin_ingrediente->descripcion));
                    }
                }

                foreach ($producto_pedido->producto_pedido_adicionales as $producto_pedido_adicional) {
                    if($producto_pedido_adicional->cambio > 0){
                        continue;
                    }
                    $subtotal += $producto_pedido_adicional->adicional->valor;
                    $texto.= ("\n");
                    $texto.= preg_replace('!\s+!', ' ', (' EXTRA ' . $producto_pedido_adicional->adicional->ingrediente->descripcion . ' '));
                }
            }
            $texto = str_replace('  x', ' x', $texto);
            if(isset($obs->intercambios) && $obs->intercambios && $obs->intercambios != ''){
                $text_intercambio = '';
                foreach($obs->intercambios as $intercambio){

                    $text_intercambio.=str_replace(' por ', '->', $intercambio).", ";
                }
                if(count($obs->intercambios)>0){
                    $text_intercambio.='$=$';
                    $text_intercambio = str_replace(', $=$', '', $text_intercambio);
                    $texto.= " [".$text_intercambio."]";
                }
            }
            if(isset($obs->obs) && $obs->obs && $obs->obs != ''){
                $texto.= " *".$obs->obs;
            }
            // return $texto;
            if($first){
                $stack[] = ["i"=>"sencilla","v"=>1];
                $stack[] = ["i"=>"texto","v"=>str_repeat("-", $caracteres)."\n"];
                $first = false;
            }
            $texto = preg_replace('!\s+!', ' ', $texto)."\n";
            $stack[] = ["i"=>"doble","v"=>2];
            $stack[] = ["i"=>"producto_pedido","v"=>$texto, "impresora"=>$impresora_dedicada];
            $stack[] = ["i"=>"sencilla","v"=>1];
            $stack[] = ["i"=>"producto_pedido","v"=>str_repeat("-", $caracteres)."\n", "impresora"=>$impresora_dedicada];
            // $stack[] = ["i"=>"texto","v"=>str_repeat("-", $caracteres)."\n"];
            $texto = '';
        }
        // $stack[] = ["i"=>"sencilla","v"=>1];
        // $texto.= ("\n");
        // $texto.= (str_repeat("-", $caracteres)."\n");
        // $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = '';
        $stack[] = ["i"=>"doble","v"=>2];
        $cliente = '';
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);         
            if(isset($obs->para_llevar)){
                if($obs->para_llevar){
                    $stack[] = ["i"=>"doble","v"=>2];
                    $stack[] = self::textoI('PARA LLEVAR');
                    $stack[] = ["i"=>"sencilla","v"=>1];
                    $texto.= ("\n");
                }
            }     
            if(isset($obs->entregar_en) && $obs->entregar_en != ''){
                if(strtoupper($obs->entregar_en)!='DOMICILIO'&&strtoupper($obs->entregar_en)!='MESA'){
                    $texto .= 'ENTREGAR EN '.strtoupper($obs->entregar_en);
                    if(isset($obs->entregar_obs) && $obs->entregar_obs != ''){
    
                        $texto.= (": ".strtoupper($obs->entregar_obs));
                        }
                    $texto.= ("\n");
                }
            }          
            if(isset($obs->observacion)){
                $texto.= (strtoupper($obs->observacion));
                $texto.= ("\n");
            }
            if(isset($obs->cliente)&&$obs->cliente!=''){
                $texto .= ("Cliente: $obs->cliente\n");
                if(isset($obs->telefono)&&$obs->telefono!=''){
                    $texto .= ("Teléf: $obs->telefono\n");
                }
            }
            if(isset($obs->domicilio)&&$obs->domicilio!=''){
                $texto .= ("Domicilio: $obs->domicilio\n");
            }
        }
        $stack[] = ["i"=>"texto","v"=>$texto];
        $stack[] = ["i"=>"sencilla","v"=>1];
        $texto = $cliente;
        $texto.= "Fecha Imp: $fecha";
        $stack[] = ["i"=>"texto","v"=>$texto];
        return $stack;
    }
    public static function printDocumento($config, $documento, $caja){
        $stack = [];
        $fecha = date_create($documento->created_at);
        $fecha = date_format($fecha, "d/m/Y h:ia");

        $texto = '';
        $pedido = $documento->pedido;
        if($caja == 2){
            $caracteres = $config->num_impresora2;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora2];
        }
        elseif($caja == 3){
            $caracteres = $config->num_impresora3;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora3];
        }
        else{
            $caracteres = $config->num_impresora;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora];
        }
        $stack[] = ["i"=> "chars", "v"=> $caracteres];
        $stack[] = ["i"=> "logo", "v"=> 0];

        $text=[];
        $text[] = ['Fecha:', $fecha];
        $text[] = ['Documento:', $documento->tipodoc.$documento->numdoc];
        $text[] = ['Mesa:', $documento->mesa_id];
        $text[] = ['Tercero:', $documento->tercero?($documento->tercero->identificacion.' '.$documento->tercero->nombrecompleto):'VARIOS'];
        $text[] = [' ', ' '];
        $text[] = ['Detalle', 'Total'];
        $stack = array_merge($stack, self::arrayToTextStack($text, $caracteres));
        $stack[] = self::textoI(str_repeat('-',$caracteres));
        $text=[];
        foreach ($documento->detalles as $detalle) {
            $detalle->detalle = str_replace(" 1/", "<br/>&nbsp;1/", $detalle->detalle);
            $detalle->detalle = str_replace(" EXTRA", "<br/>&nbsp;&nbsp;EXTRA", $detalle->detalle);
            $text[] = [$detalle->detalle, '$'.number_format($detalle->total,0)];
        }
        $stack = array_merge($stack, self::arrayToTextStack($text, $caracteres));
        $stack[] = self::textoI(str_repeat('-',$caracteres));
        $stack[] = ["i"=>"texto","v"=>self::impLinea('Total', '$'.number_format($documento->total,0), $caracteres)];
        
        return $stack;
    }

    public static function facturaPosStack($documento,$productos,$config,$pre=false,$propina=10,$val_propina=0,$descuento=10){
        $post = !$pre;
        $stack = [];

        $texto = '';
        $pedido = $documento->pedido;
        if($pedido->caja_id == 2){
            $caracteres = $config->num_impresora2;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora2];
        }
        elseif($pedido->caja_id == 3){
            $caracteres = $config->num_impresora3;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora3];
        }
        else{
            $caracteres = $config->num_impresora;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora];
        }
        if($post){
            $stack[] = ["i"=> "logo", "v"=> 0];
        }
        $stack[] = ["i"=> "chars", "v"=> $caracteres];

        $observaciones = self::getObservationArray($pedido->obs);
        $fecha = date("d/m/Y h:ia");
        $fechaPedido = date_create($pedido->created_at);
        $fechaPedido = date_format($fechaPedido, "d/m/Y h:ia");

        $texto.= ($config->encabezado_pos);
        $texto.= ("\n");
        if($post){
            $texto.= ("Nro: FV $config->fvcodprefijo $documento->numdoc");
            $texto.= ("\n");
        }
        else{
            $texto.= "RESUMEN DE CUENTA\n";
        }
        $texto.= ("Fecha: $fechaPedido");
        $texto.= ("\n");
        if(isset($observaciones->cliente)&&$observaciones->cliente!=''){
            $texto.= "Cliente: $observaciones->cliente\n";
            if(isset($observaciones->identificacion)&&$observaciones->identificacion!=''){
                $texto.= "CC: $observaciones->identificacion\n";
            }
        }
        else{
            $texto.= "Cliente: VARIOS\n";
        }
        if(isset($observaciones->domicilio)&&$observaciones->domicilio!=''){
            $texto.= "Direc.: $observaciones->domicilio\n";
        }
        if(isset($observaciones->tel)&&$observaciones->tel!=''){
            $texto.= "Teléf.: $observaciones->tel\n";
        }

        if($pedido->caja_id == 2){
            $texto.= "Caja: Nro 2\n";
        }
        else{
            $texto.= "Caja: Nro 1\n";
        }

        if($pedido->mesa_id==0){
            $texto.= "Domicilio\n";
        }
        else{
            $mesa = "Mesa Nro: $pedido->mesa_id";
            if(isset($observaciones->mesa_alias) && $observaciones->mesa_alias != null){
                $mesa = "Mesa Nro: $observaciones->mesa_alias";
            }
            $texto.= $mesa."\n";
        }
        $texto.= ("Mesero: {$pedido->usuario->nombres} {$pedido->usuario->apellidos}");
        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = '';
        if($pedido->turno){
            if(!$post){
                $stack[] = ["i"=>"doble","v"=>2];
            }
            $stack[] = ["i"=>"texto","v"=>"\nTurno: {$pedido->turno}"];
            if(!$post){
                $stack[] = ["i"=>"sencilla","v"=>1];
            }
        }
        $tipo_producto_a = "";
        $total_producto = 0;
        $total = 0;
        $cantidad_productos = 0;
        $iva_grupos = [];
        $ico_grupos = [];
        $columns = [3, 6, 7]; //AYUDA: ancho en chars de las columnas UNIDAD VALOR Y TOTAL
        $combos = self::buildCombos($productos);
        if(count($combos)>0){
            $productos = array_merge($productos, $combos);
        }
        $productos = self::reagruparProductosPedidoFactura($productos);
        $subtotales = $config->subtotales_factura;
        if(count($productos)){
            $texto .= ("\n");
            $texto .= self::impLinea(
                'PRODUCTO', 
                str_pad('UND', $columns[0], ' ', STR_PAD_LEFT).' '.str_pad('VALOR', $columns[1], ' ', STR_PAD_LEFT).' '.str_pad('TOTAL', $columns[2], ' ', STR_PAD_LEFT), 
                $caracteres);
        }
        foreach ($productos as $producto) {
            if(isset($producto->combo) && $producto->combo!=''){
                continue;
            }
            $cantidad_productos += $producto->cant;
            $tipo_producto = $producto->tipo_producto;

            if ($tipo_producto_a != "" && $tipo_producto != $tipo_producto_a && $subtotales) {
                $texto .= ("\n");
                $texto .= (self::impLinea('Total ' . $tipo_producto_a, ' $' . number_format($total_producto, 0), $caracteres));
                $total += $total_producto;
                $total_producto = 0;
            }
            $texto .= ("\n");

            try {
                $obs = json_decode($producto->obs);
            } catch (\Throwable $th) {
                $obs = (object) $producto->obs;
                $obs->tipo = 'COMBO';
                $obs->tamano = 'unico';
                $obs->sabor = '';
                $obs->mix = [];
            }

            if ($tipo_producto != $tipo_producto_a) {
                $texto .= (str_repeat("-", $caracteres) . "\n");
            }
            $total_producto += $producto->total;
            $t_ = str_pad(number_format($producto->cant * $producto->valor, 0), $columns[2], ' ', STR_PAD_LEFT);
            $s_ = str_pad(number_format($producto->valor, 0), $columns[1], ' ', STR_PAD_LEFT);
            $c_ = str_pad($producto->cant, $columns[0], ' ', STR_PAD_LEFT);
            if ($obs->tipo == "MIXTA") {
                $aux = "$tipo_producto $obs->tamano";
                $texto .= self::impLinea(
                    self::normalizeSizes($aux), " ".$c_." ".$s_." ".$t_, $caracteres);

                $cant_mix = count($obs->mix);
                $jj = 0;
                foreach ($obs->mix as $mix) {
                    $texto .= ("\n");
                    try {
                        $texto.=$obs->dist[$jj];
                    } catch (\Throwable $th) {
                        $texto.=' 1/' . $cant_mix;
                    }
                    $jj++;
                    $texto .= " $mix->nombre";
                    foreach ($mix->adicionales as $adicional_mix) {
                        $val_adicional_fraccion = ceil($adicional_mix->valor / ($cant_mix * 100)) * 100;
                        $texto .= (self::impLinea("    EXTRA $adicional_mix->nombre", number_format($val_adicional_fraccion, 0), $caracteres));
                    }
                }
                $texto = self::normalizeSizes($texto);
            } else {
                // if ($tipo_producto_a == "" || $tipo_producto != $tipo_producto_a){
                //     $texto .= "$tipo_producto";
                //     $texto .= ("\n");
                // }
                $obs->sabor = isset($obs->sabor) ? $obs->sabor : "";
                $obs->tamano = isset($obs->tamano) ? $obs->tamano : "";
                $adicionales_producto = json_decode($producto->adicionales);
                $aux = " $producto->descripcion $obs->sabor $obs->tamano ";
                $texto .= self::impLinea(
                    self::normalizeSizes($aux), " ".$c_." ".$s_." ".$t_, $caracteres);

                if ($producto->adicionales != null) {
                    foreach ($adicionales_producto as $adicional_producto) {
                        $texto .= (self::impLinea("  EXTRA $adicional_producto->d", number_format($adicional_producto->v, 0), $caracteres));
                    }
                }
                $texto = self::normalizeSizes($texto);
            }
            $iva = (floatval($producto->iva)?:0)/100;
            $ico = (floatval($producto->impco)?:0)/100;
            $base = floatval($producto->total)/(1+$iva+$ico);
            $iva_key = strval($producto->iva);
           $iva_key = preg_replace('/.00$/', '',$iva_key);
            if(isset($iva_grupos[$iva_key])){
                $iva_grupos[$iva_key] += $base;
            }
            else{
                $iva_grupos[$iva_key] = $base;
            }
            $impco_key = strval($producto->impco);
            $impco_key = preg_replace('/.00$/', '', $impco_key);
            if(isset($ico_grupos[$impco_key])){
                $ico_grupos[$impco_key] += $base;
            }
            else{
                $ico_grupos[$impco_key] = $base;
            }
            $tipo_producto_a = $tipo_producto;
        }
        $total += $total_producto;
        $texto = self::normalizeSizes($texto);
        if($subtotales){
            $texto .= ("\n");
            $texto .= (self::impLinea('Total ' . $tipo_producto_a, ' $' . number_format($total_producto, 0), $caracteres));
        }
        $texto .= ("\n");
        $texto .= (str_repeat("-", $caracteres));
        $texto .= ("\n");
        $stack[] = ["i"=>"texto","v"=>$texto];

        if($post){
            $texto = '';
            if(is_numeric($documento->descuento)){
                $texto .= self::impLinea('Subtotal', ' $' . number_format($total, 0), $caracteres);
                $texto .= (self::impLinea('Descuento', ' -$' . number_format($documento->descuento, 0), floor($caracteres)));
                $stack[] = ["i"=>"texto","v"=>$texto];
                $total -= $documento->descuento;
            }
            $stack[] = ["i"=>"doble","v"=>2];
            $stack[] = ["i"=>"texto","v"=>'Total'];
            $stack[] = ["i"=>"sencilla","v"=>1];
            $total_val = number_format($total, 0);
        }
        else{
            $texto = (self::impLinea('Subtotal', ' $' . number_format($total, 0), floor($caracteres)));
            if($descuento>0){
                $texto .= (self::impLinea('-Descuento', ' $' . number_format($total*$descuento/100, 0), floor($caracteres)));
            }
            $texto .= (self::impLinea('+Propina sugerida', ' $' . number_format($val_propina, 0), floor($caracteres)));
            $stack[] = ["i"=>"texto","v"=>$texto];
            $stack[] = ["i"=>"doble","v"=>2];
            $stack[] = ["i"=>"texto","v"=>'Total'];
            $stack[] = ["i"=>"sencilla","v"=>1];
            $total_val = number_format($val_propina+$total*(1-$descuento/100));
        }
        $stack[] = ["i"=>"texto","v"=>str_pad('$', $caracteres - ceil((5+strlen($total_val))*1.5), " ", STR_PAD_LEFT)];
        $stack[] = ["i"=>"doble","v"=>2];
        $stack[] = ["i"=>"texto","v"=>$total_val];

        $stack[] = ["i"=>"sencilla","v"=>1];
        $texto = ("\n");
        $texto.= (str_repeat("-", $caracteres));
        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = ("\n");
        $texto.= 'CANTIDAD DE PRODUCTOS: '.$cantidad_productos;
        $texto.= ("\n");
        $texto.= (str_repeat("-", $caracteres));
        $stack[] = ["i"=>"texto","v"=>$texto];

//        IMPUESTO AL CONSUMO E IVA
        foreach($iva_grupos as $key=>$value){
            if($key!="0.00" && $key!=""){
                $texto = ("\n");
                $texto.= self::impLinea("Valor Base", ' $'.number_format($value, 2), $caracteres);
                $texto.= self::impLinea("Iva         $key%", ' $'.number_format($value*(floatval($key))/100, 2), $caracteres, false);
                $texto.= ("\n");
                $texto.= (str_repeat("-", $caracteres));
                $stack[] = ["i"=>"texto","v"=>$texto];
            }
        }
        foreach($ico_grupos as $key=>$value){
            if($key!="0.00" && $key!=""){
                $key_ = substr($key, 0, 5);
                $texto = ("\n");
                $texto.= self::impLinea("Valor Base", ' $'.number_format($value, 2), $caracteres);
                $texto.= self::impLinea("Imp.Consumo $key_%", ' $'.number_format($value*(floatval($key))/100, 2), $caracteres, false);
                $texto.= ("\n");
                $texto.= (str_repeat("-", $caracteres));
                $stack[] = ["i"=>"texto","v"=>$texto];
            }
        }
//        MEDIOS DE PAGO
        if($post){
            $texto = "";
            $impMedios = false;
            if($documento->paga_efectivo != null && $documento->paga_efectivo != ''){
                $texto.= self::impLinea("Efectivo", ' $'.number_format($documento->paga_efectivo, 0), $caracteres);
                $impMedios = true;
            }
            if($documento->paga_debito != null && $documento->paga_debito != ''){
                $texto.= self::impLinea("Débito", ' $'.number_format($documento->paga_debito, 0), $caracteres);
                $impMedios = true;
            }
            if($documento->paga_credito != null && $documento->paga_credito != ''){
                $texto.= self::impLinea("Crédito", ' $'.number_format($documento->paga_credito, 0), $caracteres);
                $impMedios = true;
            }
            if($documento->paga_transferencia != null && $documento->paga_transferencia != ''){
                $texto.= self::impLinea("Transferencia", ' $'.number_format($documento->paga_transferencia, 0), $caracteres);
                $impMedios = true;
            }
            if($documento->paga_plataforma != null && $documento->paga_plataforma != ''){
                $texto.= self::impLinea("Plataforma", ' $'.number_format($documento->paga_plataforma, 0), $caracteres);
                $impMedios = true;
            }
            if($documento->paga_puntos != null && $documento->paga_puntos != ''){
                $texto.= self::impLinea("Plataforma", ' $'.number_format($documento->paga_puntos, 0), $caracteres);
                $impMedios = true;
            }
            if($impMedios){
                $texto = 'Forma de pago:'.("\n").$texto;
                $texto.= ("\n");
                $stack[] = ["i"=>"texto","v"=>$texto];
            }
        }

        $texto = "";

//        if(isset($observaciones->para_llevar)){
//            if($observaciones->para_llevar!=''){
//                $texto.= ($observaciones->para_llevar);
//                $texto.= ("\n");
//            }
//        }

        if(isset($observaciones->entregar_en) && $observaciones->entregar_en != ''){
            if(isset($observaciones->entregar_obs) && $observaciones->entregar_obs != ''){
                $texto .= 'ENTREGAR EN '.strtoupper($observaciones->entregar_en);
                $texto.= (": ".strtoupper($observaciones->entregar_obs));
                $texto.= ("\n");
            }
        }
        if(isset($observaciones->observacion)){
            $texto.= (strtoupper($observaciones->observacion));
            $texto.= ("\n");
        }

        $stack[] = ["i"=>"texto","v"=>$texto];
        $texto = '';
        $stack[] = ["i"=>"sencilla","v"=>1];
        if(isset($config->pie_pos) && $config->pie_pos != ''){
            $texto.= ($config->pie_pos);
            $texto.= ("\n");
        }
        if($pre && isset($config->pie_prefactura) && $config->pie_prefactura != ''){
            $texto.= ($config->pie_prefactura);
            $texto.= ("\n");
        }
        $texto.= ("Fecha Imp: $fecha");
        $texto.= ("\n");
        $texto.= ("Impreso por www.h-software.co");
        $stack[] = ["i"=>"texto","v"=>$texto];

        return $stack;
    }


    public static function gaveta($config)
    {
        $stack = [];
        $stack[] = ["i"=> "impresora", "v"=> $config->impresora];
        return $stack;
    }

    public static function reporteTipodoc($nombre, $config, $reporte, $fecha_inicio, $fecha_fin, $descuento=[], $caja_id)
    {
        $max_c = $config->num_impresora;
        $stack = [];
        $stack[] = self::impresoraI($config->impresora);
        $stack[] = self::textoD("Reporte: ",$nombre,$max_c);
        if($caja_id == '0'){
            $stack[] = self::textoD("Caja nro: ",'Todas',$max_c);
        }
        else{
            $stack[] = self::textoD("Caja nro: ",$caja_id,$max_c);
        }
        if($fecha_fin == $fecha_inicio){
            $stack[] = self::textoD("Fecha: ",$fecha_fin,$max_c);
        }
        else{
            $stack[] = self::textoD("Fecha inicio: ",$fecha_inicio,$max_c);
            $stack[] = self::textoD("Fecha fin: ",$fecha_fin,$max_c);
        }
        $stack[] = self::textoI(str_repeat('-',$max_c));
        $total = 0;
        foreach ($reporte as $r){
            $total+=$r->v;
            $r->des = str_replace(' EXTRA ', "\nEXTRA ", $r->des);
//            $stack[] = ["i"=>"texto","v"=>$r->des];
//            $stack[] = self::textoD($r->des, $r->v, $max_c);
            $stack[] = self::textoD(number_format($r->x,0)." $r->des ",number_format($r->v,0),$max_c);
        }
        if(count($descuento)>0){
            $stack[] = self::textoI("\n". str_repeat('-',$max_c));
        }
        foreach ($descuento as $d){
            $total-=$d->v;
            $stack[] = self::textoD("DESCUENTOS: "."", "-".number_format($d->v,0),$max_c);
        }
        $stack[] = ["i"=>"doble","v"=>2];
        // $stack[] = self::textoD("","$".number_format($total,0),floor($max_c/2));
        $stack[] = self::textoD('', "$".number_format($total,0), floor($max_c/1.5));
        // $stack[] = ["i"=>"texto","v"=>$linea];
        $stack[] = ["i"=>"sencilla","v"=>1];
        $stack[] = self::textoI("\n");
        $stack[] = self::sencillaI();
        $stack[] = self::textoI(str_repeat('-',$max_c));
        $stack[] = self::textoD('Impreso: ',date("d/m/Y h:ia"),$max_c);
        $stack[] = self::textoI(str_repeat('-',$max_c));
        return $stack;
    }

    public static function cuadrePos($config, $cuadre, $fvs, $fv_count, $fecha_inicio, $fecha_fin, $descuento, $propina, $totalq, $caja, $printer, $anulados){
        $stack = [];

        try {
            $fv_count = $fv_count[0];
            $minFv = $fv_count->min;
            $maxFv = $fv_count->max;
            $fvcount = $fv_count->count;
        } catch (\Throwable $th) {
            $minFv = '';
            $maxFv = '';
            $fvcount = '';
        }

        
        if($printer == 2){
            $caracteres = $config->num_impresora2;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora2];
        }
        elseif($printer == 3){
            $caracteres = $config->num_impresora3;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora3];
        }
        else{
            $caracteres = $config->num_impresora;
            $stack[] = ["i"=> "impresora", "v"=> $config->impresora];
        }

        $stack[] = ["i"=>"chars","v"=>$caracteres];
        $stack[] = ["i"=>"logo","v"=>0];

        
        if($caja == '0'){
            $caja='Todas';
        }
        $linea = self::impLinea('Caja Número', $caja, $caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea];

        $linea = self::impLinea('Fecha', date("d/m/Y h:ia"), $caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea];

        $linea = self::impLinea('Inicio cuadre', $fecha_inicio, $caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea];

        $linea = self::impLinea('Fin cuadre', $fecha_fin, $caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea];

        $total = 0;
        foreach ($cuadre as $documento){
            // if(!($documento->tipo == 'FV' || $documento->tipo == 'BI')){
            //     continue;
            // }
            $stack[] = ["i"=>"texto","v"=>str_repeat('-', $caracteres)];

            if($documento->tipo == '00'){
                continue;
            }
            $pre = ' $ ';
            if(in_array($documento->tipo, ['FC', 'PN', 'CE', 'RT'])){
                $pre = '- $ ';
                $total-=$documento->total;
            }
            else{
                $total+=$documento->total;
            }
            if($documento->tipo == 'FV'){

                foreach ($fvs as $fv) {
                    $linea = self::impLinea(
                        ' '.self::normalizeSizes($fv->descripcion.' x '.$fv->cantidad),
                        $pre.number_format($fv->total),
                        $caracteres, true, true
                    );
                    $stack[] = ["i"=>"texto","v"=>$linea];
                }
                if(count($fvs)){
                    $stack[] = ["i"=>"texto","v"=>self::impLinea('','-----------',$caracteres)];
                }
            }
            $linea = self::impLinea(
                $documento->tipo.' '.self::nameDocument($documento->tipo),
                $pre.number_format($documento->total,0),
                $caracteres
            );
            $stack[] = ["i"=>"texto","v"=>$linea];
            
            if($documento->tipo=='FV'){
                $linea = self::impLinea(
                    'Cant. documentos:',
                    $fvcount,
                    $caracteres
                );
                $stack[] = ["i"=>"texto","v"=>$linea];
                if($minFv){
                    $linea = self::impLinea(
                        'Desde número:',
                        $minFv,
                        $caracteres
                    );
                    $stack[] = ["i"=>"texto","v"=>$linea];
                }
                if($maxFv){
                    $linea = self::impLinea(
                        'Hasta número:',
                        $maxFv,
                        $caracteres
                    );
                    $stack[] = ["i"=>"texto","v"=>$linea];
                }
            }

        }
        foreach ($descuento as $d){
            $total-=$d->v;
            $stack[] = self::textoD("DESCUENTOS: "."", "- $ ".number_format($d->v,0),$caracteres);
        }

        if(count($descuento)>0){
            $stack[] = self::textoI(str_repeat('-',$caracteres));
        }
        foreach ($propina as $d){
            $total+=$d->v;
            $stack[] = self::textoD("PROPINAS: "."", "$ ".number_format($d->v,0),$caracteres);
        }

        if(count($propina)>0){
            $stack[] = self::textoI(str_repeat('-',$caracteres));
        }

        if($total<0){
            $pre = ' -$';
            $total*=-1;
        }
        else{
            $pre = '$ ';
        }
        
        $linea = '';
        foreach ($totalq as $t){
            $linea.= self::impLinea('Resumen Impuestos','',$caracteres);
            $linea.= self::impLinea('IVA','$'.number_format($t->impiva,0),$caracteres);
            $linea.= self::impLinea('IMP.CONSUMO','$'.number_format($t->impcon,0),$caracteres);
            $linea.= self::impLinea('Formas de Pago','',$caracteres);
            $linea.= self::impLinea('EFECTIVO','$'.number_format($t->efectivo,0),$caracteres);
            $linea.= self::impLinea('DÉBITO','$'.number_format($t->debito,0),$caracteres);
            $linea.= self::impLinea('CRÉDITO','$'.number_format($t->tcredito,0),$caracteres);
            $linea.= self::impLinea('TRANSFERENCIA','$'.number_format($t->transferencia,0),$caracteres);
            $linea.= self::impLinea('PLATAFORMA','$'.number_format($t->plataforma,0),$caracteres);
            $linea.= self::impLinea('PUNTOS','$'.number_format($t->puntos,0),$caracteres);
            $stack[] = ["i"=>"texto","v"=>$linea];
        }
        $linea = '';
        $first = true;
        foreach ($anulados as $anulado) {
            if($first){
                $linea = self::impLinea('ANULADOS:', $anulado->tipodoc.$anulado->codprefijo.$anulado->numdoc, $caracteres);
                $first = false;
            }
            else{
                $linea = self::impLinea('', $anulado->tipodoc.$anulado->codprefijo.$anulado->numdoc, $caracteres);
            }
            $stack[] = ["i"=>"texto","v"=>$linea];
        }

        $linea = self::impLinea('TOTAL',$pre.number_format($total,0),$caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea];

        return $stack;

    }

    public static function inventarioPos($config, $inventario){
        $stack = [];

        $caracteres = $config->num_impresora;

        $stack[] = ["i"=>"chars","v"=>$caracteres];
        $stack[] = ["i"=>"impresora","v"=>$config->impresora];
        $stack[] = ["i"=>"logo","v"=>0];

        $linea = self::impLinea('Fecha', date("d/m/Y h:ia"), $caracteres);
        $stack[] = ["i"=>"texto","v"=>$linea."\n"];

        $total = 0;
        $texto = "\n";
        $i = 0;
        $min = [];
        $max = [0, 0, 0, 0, 0];
        $headers = ['PRODUCTO', 'INICIAL', 'ENTRADA', 'SALIDA', 'TOTAL'];
        $headers_space = floor(($caracteres - 4)/5);
        $extra = $caracteres - ($headers_space * 5) + 4;
        for($j = 0; $j<count($headers); $j++){
            $h = $headers[$j];
            if(strlen($h)>$headers_space){
                if($j>0){
                    $h = substr($h,0,$headers_space); 
                }
                else{
                    $h = substr($h,0,$headers_space + $extra); 
                }
            }
            else{
                $h = str_pad($h, $headers_space, " ", STR_PAD_RIGHT);
            }
            if($j>0){
                $texto.=' ';
            }
            $texto.=$h;
        }
        $texto.="\n";
        $texto.= str_repeat("-", $caracteres);
        foreach ($inventario as $item){
            $des = $item->descripcion;
            $i++;
            if($i>3){
            //break;
            }
            $inicial = $item->entradant - $item->salidant1 - $item->salidant2;
            $salida = $item->salidas + $item->salida2;

            $inicial = floatval($inicial);
            $item->entradas1 = floatval($item->entradas1);
            $salida = floatval($salida);
            $item->total = floatval($item->total);

            if(strlen($inicial)>$max[1]){
                $max[1] = strlen($inicial);
            }
            if(strlen($item->entradas1)>$max[2]){
                $max[2] = strlen($item->entradas1);
            }
            if(strlen($salida)>$max[3]){
                $max[3] = strlen($salida);
            }
            if(strlen($item->total)>$max[4]){
                $max[4] = strlen($item->total);
            }

            $min[] = [strtoupper($des), $inicial, $item->entradas1, $salida, $item->total];
            // $texto.= "\n" . strtoupper($des). " " . $inicial. " " . $item->entradas1. " " . $salida. " ";
            // $texto.= $item->total;
        }
        $max[0] = $caracteres - 4 - $max[1] - $max[2] - $max[3] - $max[4];
        foreach ($min as $item){
            $des = $item[0];
            if(strlen($des)>$max[0]){
                $des = substr($des,0,$max[0]);
            }
            else{
                $des = str_pad($des, $max[0], " ", STR_PAD_RIGHT);
            }
            if (strpos($des, 'Ñ') !== false) {
                $des .= ' ';
            }
            $texto .= "\n" . $des. " " . str_pad($item[1], $max[1], " ", STR_PAD_LEFT) . " " . str_pad($item[2], $max[2], " ", STR_PAD_LEFT) . " ";
            $texto .= str_pad($item[3], $max[3], " ", STR_PAD_LEFT) . " ". str_pad($item[4], $max[4], " ", STR_PAD_LEFT);
        }
        $stack[] = ["i"=>"texto","v"=>$texto];

        return $stack;

    }

    public static function impLinea($izq, $der,$min, $trim = true, $jump = false){
        if($trim){
            $izq = preg_replace('!\s+!', ' ', $izq);
//            $der = preg_replace('!\s+!', ' ', $der);
        }
        $der_ = mb_strlen ($der);
        $izq_ = mb_strlen ($izq);
        $min_ini = $min;
        while($min < ($der_ + $izq_) ){
            if($der_ + $izq_ > $min){
                $min = $min + $min_ini;
            }
        }

        $min = $min-$der_-$izq_;
        for ($i=0;$i<$min;$i++){
            $izq .= " ";
        }
        if($jump){
            return $izq.$der."\n";
        }
        return $izq.$der;
    }

    public static function nameDocument($tipo){
        $docs = array(
            "FV"=>"Factura Venta", "FC"=>"Factura Compra", "PN"=>"Pago Nómina",
            "BI"=>"Base Inicial", "NI"=>"Nota Inventario", "CO"=>"Consumo",
            "CI"=>"Comprob. Ingreso", "RC"=>"Recibo Cartera",
            "CE"=>"Comprob. Egreso", "RT"=>"Recibo Tesorería"
        );
        if(isset($docs[$tipo])){
            return $docs[$tipo];
        }
        return "";
    }

    public static function sizes($t){
        $sizes = array('"grande"'=>"Grande");
        if(isset($sizes[$t])){
            return $sizes[$t];
        }
        return "";
    }

    public static function normalizeSizes($s){
        $s = '#-#'.$s;
        $s = str_replace('#-# ', '', $s);
        $s = str_replace('#-#', '', $s);
        $conv = array(
            "\"grande\"" => "GRA.",
            "'grande'" => "GRA.",
            "grande" => "GRA.",
            "\"extrag\"" => "XGR.",
            "'extrag'" => "XGR.",
            "extrag" => "XGR.",
            " unico" => "",
            " \"unico\"" => "",
            " 'unico'" => "",
            "unico" => "",
            "\"mediano\"" => "MED.",
            "'mediano'" => "MED.",
            "mediano" => "MED.",
            "\"porcion\"" => "POR.",
            "'porcion'" => "POR.",
            "porcion" => "POR.",
            "\"pequeno\"" => "PEQ.",
            "'pequeno'" => "PEQ.",
            "pequeno" => "PEQ.",
        );
        return strtr($s, $conv);
    }

    public static function reagruparProductosPedido($pp){
        $new = [];
        $ordenado = [];
        for($i = 0; $i<count($pp); $i++){
            $p = $pp[$i];
            if(in_array($i, $ordenado)){
                continue;
            }
            $tipo = $p->tipo_producto ;
            $new[] = $p;
            $ordenado[] = $i;
            if(($i+1) < count($pp)){
                for($j = $i+1; $j<(count($pp)-1); $j++){
                    $p2 = $pp[$j];
                    if(in_array($j, $ordenado)){
                        continue;
                    }
                    try {
                        if($p->producto_id == $p2->producto_id && $p->obs == $p2->obs){
                            $p->cant += $p2->cant;
                            $ordenado[] = $j;
                            continue;
                        }
                    } catch (\Throwable $th) {
                    }
                    if($tipo != $p2->tipo_producto){
                        $new[] = $p2;
                        $ordenado[] = $j;
                    }
                }
            }
        }
        return $new;
    }

    public static function buildCombos($pp){
        $combos = [];
        $added_combos = [];
        $combos_obs = [];
        for($i = 0; $i<count($pp); $i++){
            $p = $pp[$i];
            $combo_info = $p->combo;
            if($combo_info && $combo_info != ''){
                $combo_info = json_decode($combo_info);
                $combo_info = json_decode($combo_info);
                $pp_obs = json_decode($p->obs);
                $combo_obs = [];
                $add_obs = false;
                if($pp_obs->sin_ingredientes){
                    $combo_obs['sin_ingrediente'] = $pp_obs->sin_ingredientes;
                    $add_obs = true;
                }
                if($pp_obs->sabor){
                    $combo_obs['sabor'] = $pp_obs->sabor;
                    $add_obs = true;
                }
                if($add_obs){
                    $combo_obs['producto'] = $combo_info->nombre_producto;
                    if(isset($combos_obs[$combo_info->ref])){
                    }
                    else{
                        $combos_obs[$combo_info->ref] = [];
                    }
                    $combos_obs[$combo_info->ref][] = $combo_obs;
                }
                $prod = new \stdClass;
                $prod->nombre = $p->descripcion;
                $prod->iva = $p->iva;
                $prod->impco = $p->impco;
                $prod->valor = $p->valor;
                $prod->cant = $p->cant;
                if(in_array($combo_info->ref, $added_combos)){
                    foreach ($combos as $combo){
                        if($combo->ref == $combo_info->ref){
                            $combo->productos[] = $prod;
                        }
                    }
                }
                else{
                    $added_combos[] = $combo_info->ref;
                    $combo_info->productos = [$prod];
                    $combos[] = $combo_info;
                }
            }
            else{
                continue;
            }
        }
        foreach ($combos as $combo){
            $combo->tipo_producto = 'COMBO';
            $combo->descripcion= strtoupper($combo->nombre_combo);
            $combo->producto = new \stdClass;
            $combo->producto->descripcion= $combo->descripcion;
            $combo->valor= $combo->precio;
            $combo->total= $combo->precio * $combo->cantidad;
            $combo->cant= $combo->cantidad;
            $aux_iva = 0;
            $aux_impco = 0;
            $aux_total = 0;
            foreach ($combo->productos as $producto){
                $valor = floatval($producto->cant?:0) * floatval($producto->valor?:0);
                $aux_iva += $valor * floatval($producto->iva?:0)/100;
                $aux_impco += $valor * floatval($producto->impco?:0)/100;
                $aux_total += $valor;
            }
            $combo->producto->valor= $aux_total;
            $combo->iva = $aux_iva * 100 / $aux_total;
            $combo->impco = $aux_impco * 100 / $aux_total;
            $combo->obs= [];
            $combo->adicionales= null;
            if(isset($combos_obs[$combo->ref])){
                $combo->obs = $combos_obs[$combo->ref];
            }
        }
        return $combos;
    }

    public static function simplePP($pp){
        return array(
            'id'=> $pp->producto_id,
            'obs'=> $pp->obs,
        );
    }

    public static function reagruparProductosPedidoFactura($pp){
        $new = [];
        $ordenado = [];
        for($i = 0; $i<count($pp); $i++){
            $p = $pp[$i];
            if(in_array($i, $ordenado)){
                continue;
            }
            $tipo = $p->tipo_producto ;
            $new[] = $p;
            $ordenado[] = $i;
            if(($i+1) < count($pp)){
                for($j = $i+1; $j<count($pp); $j++){
                    $p2 = $pp[$j];
                    if(in_array($j, $ordenado)){
                        continue;
                    }
                    $c_p2 = $p2->cant;
                    $p2->cant = $p->cant;
                    $t_p2 = $p2->total;
                    $p2->total = $p->total;
                    if($p == $p2){
                        $p->cant = $p->cant + $c_p2;
                        $p->total = $p->total + $t_p2;
                        $ordenado[] = $j;
                        continue;
                    }
                    $p2->cant = $c_p2;
                    $p2->total = $t_p2;
                    if($tipo == $p2->tipo_producto){
                        $new[] = $p2;
                        $ordenado[] = $j;
                    }
                }
            }
        }
        return $new;
    }

    public static function impresoraI($contenido){
        return self::instruccion('impresora',$contenido);
    }
    public static function textoI($contenido){
        return self::instruccion('texto',$contenido);
    }
    public static function textoD($contenido1,$contenido2,$max_c){
        return self::instruccion('texto',self::impLinea($contenido1,$contenido2,$max_c));
    }
    public static function sencillaI(){
        return self::instruccion('sencilla',1);
    }
    public static function dobleI(){
        return self::instruccion('doble',2);
    }
    public static function instruccion($nombre, $contenido){
        return ["i"=> $nombre, "v"=> $contenido];
    }
    public static function getObservationArray($obsString){
        return ($obsString == null || $obsString == '')?json_decode("{}"):json_decode($obsString);
    }
    protected static function arrayToTextStack($array, $caracteres){
        $stack = [];
        foreach ($array as $i){
            $stack[] = ["i"=>"texto","v"=>self::impLinea($i[0], $i[1], $caracteres)];
        }
        return $stack;
    }
}