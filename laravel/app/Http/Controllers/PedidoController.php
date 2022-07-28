<?php

namespace App\Http\Controllers;
use App;
use App\Pedido;
use App\Producto;
use App\ProductoPedido;
use App\Documento;
use App\DetalleDocumento;
use App\Tercero;
use App\Util\Fecha;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Auth;
use stdClass;
use Illuminate\Http\Request;

class PedidoController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/

    public function todos() {
        return Pedido::with("productos")->get();
    }

    public function paginar() {
        $ordenar_por = Input::get('ordenar_por');
        $sentido = Input::get('sentido');
        $buscar = Input::get('buscar');
        $por_pagina = Input::get('por_pagina');
        if($ordenar_por==""||$ordenar_por==null){
            return Pedido::where("estado", 2)->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->paginate($por_pagina);
        }
        return Pedido::where("estado", 2)->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
    }

    public function paginardomicilios() {
        $ordenar_por = Input::get('ordenar_por');
        $sentido = Input::get('sentido');
        $por_pagina = Input::get('por_pagina');
        $buscar = Input::get('buscar');
        if($ordenar_por==""||$ordenar_por==null){
            return Pedido::with("productos")->where("mesa_id", 0)->whereRaw("(estado = 3 Or estado = 4 Or estado = 5)")->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->paginate($por_pagina);
        }
        if($ordenar_por == "programado"){
            if($sentido=='asc'){
                return Pedido::with("productos")->where("mesa_id", 0)->whereRaw("(estado = 3 Or estado = 4 Or estado = 5)")->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->orderByRaw("- programado desc")->paginate($por_pagina);
            }
        }
        return Pedido::with("productos")->where("mesa_id", 0)->whereRaw("(estado = 3 Or estado = 4 Or estado = 5)")->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
    }
    

    public function paginaractivos() {
        $ordenar_por = Input::get('ordenar_por');
        $buscar = Input::get('buscar');
        $sentido = Input::get('sentido');
        $por_pagina = Input::get('por_pagina');
        if($ordenar_por==""||$ordenar_por==null){
            return Pedido::with("productos")->where("mesa_id","!=", 0)->whereRaw("(estado = 1 Or estado = 4 Or estado = 5)")->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->paginate($por_pagina);
        }
        return Pedido::with("productos")->where("mesa_id","!=", 0)->whereRaw("(estado = 1 Or estado = 4 Or estado = 5)")->whereRaw("( id like '%".$buscar."%' or obs like '%".$buscar."%')")->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
    }
    
    public function todosActivos() {
        return Pedido::with("productos")->where("mesa_id", "!=", 0)->where("estado", 1)->get();
    }
    
    public function todosInactivos() {
        return Pedido::with("productos")->where("mesa_id", "!=", 0)->where("estado", ">", 2)->limit(200)->get();
    }
    
    public function domiciliosActivos() {
        return Pedido::with("productos")->where("mesa_id", 0)->where("estado", 3)->get();
    }
    
    public function domiciliosInactivos() {
        return Pedido::with("productos")->where("mesa_id", 0)->where("estado", ">", 1)->get();
    }
    
    public function domicilios() {
        return Pedido::with("productos")->where("mesa_id", 0)->get();
    }
    
    public function buscar($id) {
        $pedido = Pedido::find($id);
        $fecha_util = new Fecha;
        $pedido->fechaC = date_format(date_create($pedido->fecha), 'd/m/Y g:i A');
        $pedido->fechaU = date_format(date_create($pedido->updated_at), 'd/m/Y g:i A');
        return $pedido;
    }
    
    public function guardar($datos) {
        $pedido = new Pedido;
        $pedido->fecha = $datos->fecha;
        $pedido->mesa_id = $datos->mesa_id;
        $pedido->estado = $datos->estado;
        $pedido->user_id = $datos->user_id;
        $pedido->obs = $datos->obs;
        $pedido->turno = $datos->turno;
        $pedido->caja_id = $datos->caja_id;
        $pedido->save();
        return $pedido;
    }
    
    public function parallevar($id) {
        $pedido = Pedido::find($id);
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        if(isset($obs->para_llevar)){
            unset($obs->para_llevar);
        }
        else{
            $obs->para_llevar = "PARA LLEVAR";
        }

        $pedido->obs = json_encode($obs);
        $pedido->save();
    }
    
    public function buscarActivo($mesa) {
        $pedido = Pedido::whereRaw("mesa_id = $mesa and ( estado = 1 or estado = 4 Or estado = 5)")->first();
        return $pedido;
    }
    
    public function actualizarValor($id) {
        $pedido = Pedido::find($id);
        $total = $this->calcularValor($id);
        $pedido->total = $total->total;
        $pedido->save();
    }
    
    public function pagar($id, $estado, $formaPago=[]) {


        $pedido = Pedido::where("id", $id)->with("productos")->first();
        if($pedido->estado == 2 || $pedido->estado == 4){
            if($pedido->estado == 4){
                $pedido->estado = 2;
                $pedido->save();
            }
            return Redirect::to('/pedido/ver/'.Input::get('id'))
                        ->with('status', ["success-contenido" => "Pedido Pagado y Archivado."]);
        }
        $total = $this->calcularValor($id);
        $pedido->total = $total->total;
        if($pedido->mesa_id>1000){
            $pedido->mesa_id-=1000;
        }
        $pedido->estado = $estado;
        $pedido->save();
        if($pedido != null){
            $controller = app('App\Http\Controllers\ProductoPedidoController');
            $productos_pedido = $controller->buscarPorPedido($pedido->id);
            $pedido->productos_pedido = $productos_pedido;
            $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');
            foreach($productos_pedido as $producto_pedido){
                $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
            }
            $pedido->productos_pedido = $productos_pedido;
        }

        $pedidoObs = isset($pedido->obs)?$pedido->obs:'{}';
        $pedidoObs = json_decode($pedidoObs);

        
        $documento = new Documento;
        $documento->tipodoc = "FV";
        $documento->tipoie = "E";
        $documento->numdoc = str_pad($pedido->id, 8, "0", STR_PAD_LEFT);
        $documento->mesa_id = $pedido->mesa_id;
        $documento->pedido_id = $pedido->id;
        $documento->total = $pedido->total;
        $documento->usuario_id = $pedido->user_id;
        $documento->caja_id = $pedido->caja_id;
        $documento->tercero_id = isset($pedidoObs->clienteId)?$pedidoObs->clienteId:null;
        if($documento->tercero_id == ""){
            $documento->tercero_id = null;
        }

        if($documento->tercero_id == null){

            if(isset($pedidoObs->cliente)&&isset($pedidoObs->identificacion)){
                $tercero_ = Tercero::where('identificacion', $pedidoObs->identificacion)->first();
                if($tercero_){
                }
                else{
                    $tercero_ = new Tercero;
                    $tercero_->identificacion = $pedidoObs->identificacion;
                    $tercero_->nombrecompleto = strtoupper($pedidoObs->cliente);
                    $tercero_->save();
                }
            }
            else{
                $tercero_ = Tercero::where('nombrecompleto', 'VARIOS')->first();
                if($tercero_){
                }
                else{
                    $tercero_ = new Tercero;
                    $tercero_->identificacion = '00';
                    $tercero_->nombrecompleto = 'VARIOS';
                    $tercero_->save();
                }
            }
            $documento->tercero_id = $tercero_->id;
        }

        $documento->paga_efectivo = isset($formaPago['paga_efectivo'])?$formaPago['paga_efectivo']:null;
        $documento->paga_debito = isset($formaPago['paga_debito'])?$formaPago['paga_debito']:null;
        $documento->paga_credito = isset($formaPago['paga_credito'])?$formaPago['paga_credito']:null;
        $documento->paga_transferencia = isset($formaPago['paga_transferencia'])?$formaPago['paga_transferencia']:null;
        $documento->paga_plataforma = isset($formaPago['paga_plataforma'])?$formaPago['paga_plataforma']:null;
        $documento->num_documento = isset($formaPago['num_documento'])?$formaPago['num_documento']:null;
        $documento->banco = isset($formaPago['banco'])?$formaPago['banco']:null;
        $documento->debe = isset($formaPago['debe'])?$formaPago['debe']:null;
        $documento->descuento = isset($formaPago['descuento'])?$formaPago['descuento']:null;
        $documento->iva = 0;
        $documento->impco = 0;

        
        $tipo_documento_ = app('App\Http\Controllers\TipoDocumentoController')->siguienteTipo($documento->tipodoc);
        // $documento->numdoc = str_pad($tipo_documento_->consecutivo, 8, "0", STR_PAD_LEFT);
        $documento->numdoc = strval($tipo_documento_->consecutivo);


        if($documento->tipodoc=='FV'){
            $config = app('App\Http\Controllers\ConfigController')->first();
            $documento->codprefijo = $config->fvcodprefijo;
        }
        $documento->save();
        $tipo_documento_->aumentarConsecutivo();


        $detalles_adicional = new stdClass;
        
        foreach($pedido->productos_pedido as $producto){
            $valor=0;
            $detalleDocumento = new DetalleDocumento;
            $detalleDocumento->documento_id = $documento->id;
            $detalleDocumento->producto_id = $producto->producto_id;
            $detalleDocumento->cantidad = $producto->cant;
            
            $obs = json_decode($producto->obs);
            if($obs->tipo=="MIXTA"){
                $detalle = "{$producto->producto->tipo_producto->descripcion} $obs->tamano";
                $cantidad_mix = count($obs->mix);
                // $detalles_adicional = new stdClass;
                $jj=0;
                foreach($obs->mix as $mix){
                    try {
                        $fraccion_dist=$obs->dist[$jj];
                    } catch (\Throwable $th) {
                        $fraccion_dist = "1/ ".$cantidad_mix;
                    }
                    $jj++;
                    $detalle.= " $fraccion_dist $mix->nombre ";
                    foreach($mix->adicionales as $mix_adicional){
                        // $detalle.= " EXTRA $mix_adicional->nombre";
                        $mix_adicional->valor = floatval($mix_adicional->valor);
                        $mix_adicional->cantidad = floatval($mix_adicional->cantidad);
                        $key = "i".$mix_adicional->ingrediente;
                        if(isset($detalles_adicional->$key)){
                            $detalles_adicional->$key->total += $mix_adicional->valor * $mix_adicional->cantidad;
                        }
                        else{
                            $mix_adicional->total = $mix_adicional->valor * $mix_adicional->cantidad;
                            $detalles_adicional->$key = $mix_adicional;
                            $detalles_adicional->$key->cantidad = 1;
                        }
                        $valor += ceil($mix_adicional->valor/($cantidad_mix*100))*100;
                    }
                }
                // foreach($detalles_adicional as $key => $value) {
                //     $this->createDetalleFromAdicionalFraccion($detalles_adicional->$key, $documento->id);
                // }
            }
            else{
                $detalle = "{$producto->producto->tipo_producto->descripcion} {$producto->producto->descripcion}";
                foreach ($producto->producto_pedido_adicionales as $adicionales){
                    // $detalle.= " EXTRA ".$adicionales->adicional->ingrediente->descripcion." (\$".  number_format($adicionales->valor,0).") ";
                    // $this->createDetalleFromAdicional($adicionales, $documento->id);
                    $adicionales->valor = floatval($adicionales->valor);
                    $adicionales->cantidad = 1;
                    $key = "i".$adicionales->adicional->ingrediente_id;
                    if(isset($detalles_adicional->$key)){
                        $detalles_adicional->$key->total += $adicionales->valor;
                    }
                    else{
                        $adicional_ = new stdClass;
                        $adicional_->ingrediente = $adicionales->adicional->ingrediente_id;
                        $adicional_->valor = $adicionales->valor;
                        $adicional_->total = $adicionales->valor;
                        $adicional_->cantidad = 1;
                        $adicional_->nombre = $adicionales->adicional->ingrediente->descripcion;
                        $detalles_adicional->$key = $adicional_;
                    }
                    $valor += $adicionales->valor;
                }
            }
//            $valor += $producto->valor;
            $detalleDocumento->valor = $producto->valor;
            $detalleDocumento->total = floatval($producto->valor) * $producto->cant;
            $detalleDocumento->impco = 0;
            $detalleDocumento->iva = 0;

            $iva = (floatval($producto->producto->iva)?:0)/100;
            $ico = (floatval($producto->producto->impco)?:0)/100;
            $base = floatval($detalleDocumento->total)/(1+$iva+$ico);
            $detalleDocumento->impco = $base * $ico;
            $detalleDocumento->iva = $base * $iva;
            $documento->iva = $documento->iva += $detalleDocumento->iva;
            $documento->impco = $documento->impco += $detalleDocumento->impco;
            $detalleDocumento->detalle = $detalle;
            $detalleDocumento->save();

            if($producto->producto->terminado == 1){
                app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumento($detalleDocumento);
            }
            else{
                app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumentoNoTerminado($detalleDocumento,$producto->obs);
            }

        }
        foreach($detalles_adicional as $key => $value) {
            $this->createDetalleFromAdicionalFraccion($detalles_adicional->$key, $documento->id);
        }
        $documento->save();
        
    }
    public function createDetalleFromAdicional($adicionalPedido, $documentoId) {
        $detalleDocumento = new DetalleDocumento;
        $detalleDocumento->documento_id = $documentoId;
        $detalleDocumento->producto_id = null;
        $detalleDocumento->ingrediente_id = $adicionalPedido->adicional->ingrediente_id;
        $detalleDocumento->cantidad = 1;
        $detalleDocumento->valor = $adicionalPedido->valor;
        $detalleDocumento->total = $adicionalPedido->total;
        $detalleDocumento->impco = 0;
        $detalleDocumento->iva = 0;
        $detalleDocumento->detalle = "EXTRA ".$adicionalPedido->adicional->ingrediente->descripcion;
        $detalleDocumento->save();
    }
    public function createDetalleFromAdicionalFraccion($adicionalFraccion, $documentoId) {
        $detalleDocumento = new DetalleDocumento;
        $detalleDocumento->documento_id = $documentoId;
        $detalleDocumento->producto_id = null;
        $detalleDocumento->ingrediente_id = $adicionalFraccion->ingrediente;
        $detalleDocumento->cantidad = 1;
        $detalleDocumento->valor = $adicionalFraccion->total;
        $detalleDocumento->total = $adicionalFraccion->total;
        $detalleDocumento->impco = 0;
        $detalleDocumento->iva = 0;
        $detalleDocumento->detalle = "EXTRA ".$adicionalFraccion->nombre;
        $detalleDocumento->save();
    }
    public function pagarPorId() {
        $formaPago = [];
        $formaPago['paga_efectivo'] = Input::get('paga_efectivo');
        $formaPago['paga_debito'] = Input::get('paga_debito');
        $formaPago['paga_credito'] = Input::get('paga_credito');
        $formaPago['paga_transferencia'] = Input::get('paga_transferencia');
        $formaPago['paga_plataforma'] = Input::get('paga_plataforma');
        $formaPago['num_documento'] = Input::get('num_documento');
        $formaPago['banco'] = Input::get('banco');
        $formaPago['debe'] = Input::get('debe');
        $formaPago['descuento'] = Input::get('descuento');
        $this->pagar(Input::get('id'), 2, $formaPago);
        return Redirect::to('/pedido/ver/'.Input::get('id'))
                        ->with('status', ["success-contenido" => "Pedido Pagado y Archivado."]);
    }
    public function pagarImprimirPorId($id) {
        $this->pagar($id, 4);
        return 1;
    }
    public function preFacturar($id) {
        Pedido::where('id', $id)->update(['prefacturado'=>1]);
        return $this->prefacturaPosStack($id,true, Input::get('propina'), Input::get('val_propina'), Input::get('descuento'));
    }

    public function entrarMesa($mesa) {
        if($mesa == 0){
            return '{"productos_pedido":[]}';
        }
        $pedido = $this->buscarActivo($mesa);
        
        if($pedido == null){
            return '{"productos_pedido":[]}';
        }

        $controller = new App\Http\Controllers\ProductoPedidoController;

        $productos_pedido = $controller->buscarPorPedido($pedido->id);
        
        $pedido->productos_pedido = $productos_pedido;
        
        $controller = new App\Http\Controllers\ProductoPedidoAdicionalController;
        foreach($productos_pedido as $producto_pedido){
            $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
        }
        $pedido->productos_pedido = $productos_pedido;
        if($pedido->usuario){
            $pedido->usuario_ = $pedido->usuario->nombres.' '.$pedido->usuario->apellidos;
        }
        $pedido->usuario = '';
        return $pedido;
    }


    public function liberarMesa($id){
        $pedido = Pedido::find($id);
        Pedido::where('id',$id)->update(['mesa_id'=>$pedido->mesa_id+1000]);
        return response()->json(array('msg'=>'Liberada'));
    }

    public function actualizarComanda($id) {

        $actualizar = app('App\Http\Controllers\ProductoPedidoController')->pendientesComanda($id);

        if($actualizar>0){
            $pedido = Pedido::find($id);
            $pedido->comanda = $pedido->comanda + 1;
            $pedido->save();

            app('App\Http\Controllers\ProductoPedidoController')->actualizarComanda($pedido->id, $pedido->comanda);
        }
        
    }
    public function impFactura($id) {
        
        $pedido = Pedido::find($id);
        $pdf = App::make('dompdf.wrapper');
        $sql = DB::select("
            select 
                tp.descripcion as tipo_producto, 
                pr.descripcion, 
                pp.valor, 
                pp.total, 
                pp.cant, 
                pp.obs, 
                (
                    SELECT CONCAT(
                            '[', GROUP_CONCAT('{\"d\":\"', i.descripcion, '\",', '\"v\":', a.valor, '}'), ']'
                    ) 
                    FROM {$this->conn}_producto_pedido_adicional ppa
                    join {$this->conn}_adicional as a
                    on a.id = ppa.adicional_id
                    join {$this->conn}_ingrediente as i
                    on i.id = a.ingrediente_id

                    where ppa.producto_pedido_id = pp.id
                ) as adicionales
            from {$this->conn}_pedido p

            join {$this->conn}_producto_pedido as pp
            on pp.pedido_id = p.id

            join {$this->conn}_producto as pr
            on pr.id = pp.producto_id

            join {$this->conn}_tipo_producto as tp
            on tp.id = pr.tipo_producto_id

            where p.id=$id order by 2;

            ");
            $documento = DB::table("documento")->where('pedido_id',$id)->first();
        $pdf->loadHTML(App\Util\PDF::ImpFacturaPedido($sql,$pedido->created_at,$pedido->mesa_id, $documento))->setPaper(array(0,0,230,841));
        return $pdf->stream();
    }
    public function impFacturaPos($id) {
        
        $documento = Documento::where('pedido_id',$id)->with('pedido')->first();
        $sql = DB::select("
            select
                tp.descripcion as tipo_producto, 
                pr.descripcion, 
                pp.valor, 
                pp.total, 
                pp.cant, 
                pp.obs,
                (
                    SELECT CONCAT(
                            '[', GROUP_CONCAT('{\"d\":\"', i.descripcion, '\",', '\"v\":', a.valor, '}'), ']'
                    ) 
                    FROM {$this->conn}_producto_pedido_adicional ppa
                    join {$this->conn}_adicional as a
                    on a.id = ppa.adicional_id
                    join {$this->conn}_ingrediente as i
                    on i.id = a.ingrediente_id

                    where ppa.producto_pedido_id = pp.id
                ) as adicionales
            from {$this->conn}_pedido p

            join {$this->conn}_producto_pedido as pp
            on pp.pedido_id = p.id

            join {$this->conn}_producto as pr
            on pr.id = pp.producto_id

            join {$this->conn}_tipo_producto as tp
            on tp.id = pr.tipo_producto_id

            where p.id=$id order by 2;

            ");
            
        return view('imp.factura')->with("documento",$documento)->with("productos",$sql)->with('config', app('App\Http\Controllers\ConfigController')->first());
    }
    
    public function gaveta() {
        return App\Util\POS::gaveta(app('App\Http\Controllers\ConfigController')->first());
    }

    public function prefacturaPosStack($id, $pre=false, $propina = 10, $val_propina = 0, $descuento = 0) {

        $documento = Documento::where('pedido_id',$id)->with('pedido')->first();
        $sql = DB::select("
            select
                tp.descripcion as tipo_producto, 
                pr.descripcion, 
                pp.valor, 
                pp.total, 
                pp.cant, 
                pp.obs,
                pp.combo,
                pr.iva,
                pr.impco,
                (
                    SELECT CONCAT(
                            '[', GROUP_CONCAT('{\"d\":\"', i.descripcion, '\",', '\"v\":', a.valor, '}'), ']'
                    ) 
                    FROM {$this->conn}_producto_pedido_adicional ppa
                    join {$this->conn}_adicional as a
                    on a.id = ppa.adicional_id
                    join {$this->conn}_ingrediente as i
                    on i.id = a.ingrediente_id

                    where ppa.producto_pedido_id = pp.id
                ) as adicionales
            from {$this->conn}_pedido p

            join {$this->conn}_producto_pedido as pp
            on pp.pedido_id = p.id

            join {$this->conn}_producto as pr
            on pr.id = pp.producto_id

            join {$this->conn}_tipo_producto as tp
            on tp.id = pr.tipo_producto_id

            where p.id=$id order by 2;

            ");

        if($documento==null){
            $documento = new Documento;
            $documento->pedido = Pedido::find($id);
            $documento->pedido->caja_id = Auth::user()->caja_id;
        }

        $config = app('App\Http\Controllers\ConfigController')->first();            
        return (App\Util\POS::facturaPosStack($documento,$sql,$config,$pre, floatval($propina), floatval($val_propina), floatval($descuento)));
    }
    
    public function calcularValor($id){
        $this->actualizarTotales($id);     
        return DB::table("pedido")
                ->select(DB::raw(
                        "COALESCE((
                                SELECT SUM(PRPE.total) FROM {$this->conn}_producto_pedido AS PRPE
                            WHERE PRPE.pedido_id = {$this->conn}_pedido.id
                        ),0) as total"
                        ))
                ->where('id', $id)->first();
    }
    
    public function actualizarTotales($id){
        DB::select("UPDATE pizza_producto_pedido as x set x.total = x.cant * 
            (x.valor + COALESCE((select sum(total) from pizza_producto_pedido_adicional where producto_pedido_id = x.id),0)) 
            WHERE pedido_id = {$id}");        
    }

    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'fecha' => 'required',
                'hora' => 'required',
                'mesa_id' => 'required',
                'total' => 'required',
                'obs' => '',
                'tipopedido' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('pedido/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $pedido = new Pedido;
            $pedido->fecha = Input::get('fecha');
            $pedido->hora = Input::get('hora');
            $pedido->mesa_id = Input::get('mesa_id');
            $pedido->total = Input::get('total');
            $pedido->obs = Input::get('obs');
            $pedido->tipopedido = Input::get('tipopedido');
            $pedido->save();
        
            return Redirect::to('pedido/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $pedido = Pedido::find(Input::get('id'));
        
        $rules = array(
                'fecha' => 'required',
                'hora' => 'required',
                'mesa_id' => 'required',
                'total' => 'required',
                'obs' => '',
                'tipopedido' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('pedido/editar/'.$pedido->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $pedido->fecha = Input::get('fecha');
            $pedido->hora = Input::get('hora');
            $pedido->mesa_id = Input::get('mesa_id');
            $pedido->total = Input::get('total');
            $pedido->obs = Input::get('obs');
            $pedido->tipopedido = Input::get('tipopedido');
            $pedido->save();
        
            return Redirect::to('pedido/editar/'.$pedido->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        Pedido::destroy(Input::get('id'));
        return Redirect::to('pedido/listar?ordenar_por=id&sentido=desc')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    public function borrar_api() {
        $id = Input::get('id');
        if (Pedido::
            join('documento', 'documento.pedido_id', '=', 'pedido.id')
                ->where('pedido.id', $id)
                ->count()>0){
            return response()->json(array('code'=>400,'msg'=>'No se pudo borrar. El pedido está asociado a un documento.'));
        }
        else{
            Pedido::destroy($id);
            return response()->json(array('code'=>200,'msg'=>'El pedido fue eliminado.'));
        }
    }
    public function borrarDomicilio() {
        Pedido::destroy(Input::get('id'));
        return Redirect::to('/domicilios?ordenar_por=id&sentido=desc')
                        ->with('status', ["success" => "Domicilio borrado."]);
    }

    public function borrarPorId($id) {
        Pedido::destroy($id);
    }
    
    public function mesasOcupadas() {
        return DB::table('pedido')->selectRaw("concat('[',group_concat(mesa_id),']') as ocupadas")->where("mesa_id", "!=", 0)->where("estado", 1)->get();
    }
    
    public function cambiarMesa($origen, $destino) {
        
        $pedido = Pedido::where('mesa_id', $origen)->where('estado', 1)->first();
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        $obs->mesa_alias = $destino;
        $pedido->obs = json_encode($obs);
        $pedido->mesa_id = $destino;
        $pedido->save();
    }
    
    public function entregarEn($id,$entregar_en,$observacion) {
        $pedido = Pedido::find($id);
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        if($observacion=='-'){
            unset($obs->entregar_en);
            unset($obs->entregar_obs);
        }
        else{
            $obs->entregar_en = $entregar_en;
            $obs->entregar_obs = $observacion;
        }
        $pedido->obs = json_encode($obs);
        $pedido->save();
    }
    
    public function entregar($id,$direccion) {
        $pedido = Pedido::find($id);
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        if($direccion=='-'){
            unset($obs->entregar_en);
        }
        else{
            $obs->entregar_en = $direccion;
        }
        $pedido->obs = json_encode($obs);
        $pedido->save();
    }
    
    public function observacion($id,$observacion) {
        $pedido = Pedido::find($id);
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        if($observacion=='-'){
            unset($obs->entregar_en);
        }
        else{
            $obs->entregar_en = "CAJA";
            $obs->observacion = $observacion;
        }
        $pedido->obs = json_encode($obs);
        $pedido->save();
    }
    
    
    public function programar($id,$fecha) {
        $pedido = Pedido::find($id);
        if($fecha == '' || $fecha == '-'){
            $fecha = null;
        }
        $pedido->programado = $fecha;
        $pedido->save();
    }

    public function entregado($id) {
        $pedido = Pedido::find($id);
        if($pedido->entregado == '' || $pedido->entregado == null ){
            $pedido->entregado = date("Y-m-d H:i:s");
            ProductoPedido::where('pedido_id', $id)->update(['entregado'=>1]);
        }
        else{
            $pedido->entregado = null;
        }
        $pedido->save();
        return response()->json(array('msg'=>'actualizado'));
    }
    
    public function guardarObservacion($id,$observacion) {
        $pedido = Pedido::find($id);
        if($pedido->obs == null || $pedido->obs == ''){
            $obs = json_decode("{}");
        }
        else{
            $obs = json_decode($pedido->obs);
        }
        if($observacion=='-'){
            unset($obs->observacion);
        }
        else{
            $obs->observacion = $observacion;
        }
        $pedido->obs = json_encode($obs);
        $pedido->save();
    }

    public function preFullcomandaPosStack($id){
        return $this->precomandaPosStack($id, true);
    }
    public function precomandaPosStack($id, $re = false){
        $pedido = $this->buscar($id);
        if($pedido != null){
            $this->actualizarTotales($id);
            $controller = app('App\Http\Controllers\ProductoPedidoController');

            $productos_pedido = $controller->buscarPorPedido($pedido->id);

            $pedido->productos_pedido = $productos_pedido;

            $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');

            foreach($productos_pedido as $producto_pedido){
                $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
            }
            $pedido->productos_pedido = $productos_pedido;
        }
        if(!$re){
            $this->actualizarComanda($pedido->id);
        }
        return App\Util\POS::comandaPosStack($pedido,app('App\Http\Controllers\ConfigController')->first(), $re);
    }

    public function validarInventario($producto_pedido_json){

        $validacion = new stdClass();
        $inventario = array();
        $errores = array();

        $producto = Producto::find($producto_pedido_json->producto->id);
        if($producto->terminado == 1){
            $existencia = DB::table('saldos_producto')->select('existencia')->where('producto_id', $producto->id)->first();
            if($existencia == null){
                $existencia = 0;
            }
            else{
                $existencia = $existencia->existencia;
            }
            if(floatval($producto_pedido_json->cantidad) > 0 && floatval($producto_pedido_json->cantidad) > floatval($existencia)){
                $errores[] = [
                    'mensaje'=>'No hay suficiente <strong>'.$producto_pedido_json->producto->nombre_tipo.' '.
                        $producto_pedido_json->producto->nombre.' '.(isset($producto_pedido_json->obs->sabor)?$producto_pedido_json->obs->sabor:'').
                        '</strong> agregar el producto. Necesita: <strong>'.$producto_pedido_json->cantidad.' unidad(es)'.
                        '</strong>, en existencia: <strong style="color: red">'.$existencia.' unidad(es)</strong>'
                ];
            }
            $validacion->errores=$errores;
            return $validacion;
        }

        $validacion->ingredientes = [];

        if($producto_pedido_json->obs->tipo == 'MIXTA'){
            foreach ($producto_pedido_json->obs->mix as $mix){
                $validacion->adicionales = $mix->adicionales;
                foreach ($mix->ingredientes as $ingrediente){
                    $continue = false;
                    foreach ($mix->sin_ingredientes as $sin_ingrediente){
                        if($ingrediente->id == $sin_ingrediente->id){
                            $continue=true;
                            break;
                        }
                    }
                    if($continue){
                        continue;
                    }
                    $validacion->ingredientes[] = $ingrediente;
                }
            }
        }
        else{
            $validacion->adicionales = $producto_pedido_json->adicionales;
            foreach ($producto_pedido_json->ingredientes as $ingrediente){
                $continue = false;
                foreach ($producto_pedido_json->obs->sin_ingredientes as $sin_ingrediente){
                    if($ingrediente->id == $sin_ingrediente->id){
                        $continue=true;
                        break;
                    }
                }
                if($continue){
                    continue;
                }
                $validacion->ingredientes[] = $ingrediente;
            }
        }

        foreach ($validacion->ingredientes as $ingrediente){
            if(!empty($inventario[$ingrediente->id])){
                $inventario[$ingrediente->id]['cantidad'] += floatval($ingrediente->cantidad)*$producto_pedido_json->cantidad;
            }
            else{
                $inventario[$ingrediente->id] = [
                    'id'=>$ingrediente->id,
                    'des'=>$ingrediente->descripcion,
                    'unidad'=>$ingrediente->unidad,
                    'cantidad'=>floatval($ingrediente->cantidad)*$producto_pedido_json->cantidad
                ];
            }
        }
        foreach ($validacion->adicionales as $adicional){
            if(!empty($inventario[$adicional->ingrediente])){
                $inventario[$adicional->ingrediente]['cantidad'] += $adicional->cantidad;
            }
            else{
                $inventario[$adicional->ingrediente] = [
                    'id'=>$adicional->ingrediente,
                    'des'=>$adicional->nombre,
                    'unidad'=>$adicional->unidad,
                    'cantidad'=>floatval($adicional->cantidad)
                ];
            }
        }

        foreach ($inventario as $ingrediente_inventario){
            if($producto_pedido_json->obs->tipo == 'MIXTA'){
                $ingrediente_inventario['cantidad'] = $ingrediente_inventario['cantidad']/count($producto_pedido_json->obs->mix);
            }
            $existencia = DB::table('saldos_producto')->select('existencia')->where('ingrediente_id', $ingrediente_inventario['id'])->first();
            if($existencia==null){
                $inventario[$ingrediente_inventario['id']]['existencia'] = floatval('0.0');
            }
            else{
                $inventario[$ingrediente_inventario['id']]['existencia'] = floatval($existencia->existencia);
            }
            if($ingrediente_inventario['cantidad'] > 0 && $ingrediente_inventario['cantidad'] > $inventario[$ingrediente_inventario['id']]['existencia']){
                $errores[] = [
                    'mensaje'=>'No hay suficiente <strong>'.$ingrediente_inventario['des'].
                    '</strong> agregar el producto. Necesita: <strong>'.$ingrediente_inventario['cantidad'].$ingrediente_inventario['unidad'].
                    '</strong>, en existencia: <strong style="color: red">'.$inventario[$ingrediente_inventario['id']]['existencia'].$ingrediente_inventario['unidad'].'</strong>'
                ];
            }
        }

        $validacion->inventario = $inventario;
        $validacion->errores=$errores;
        return $validacion;
    }

    public function preAgregarProductoPedido(){
        $producto_pedido_json = Input::get('producto_pedido_json');
        $mesa = Input::get('mesa');
        $pedido =  Input::get('pedido');
        if($pedido == null || $pedido == '0' || $pedido == '-1'){
            $pedido = Pedido::where('mesa_id', $mesa)->where('estado', 1)->first();
            if($pedido){
                $pedido = $pedido->id;
            }
        }
        $first =  Input::get('first');
        if($first != 'true' && ($pedido == null || $pedido == '0' || $pedido == '-1') ){
            $producto_pedido_json_ = json_decode($producto_pedido_json);
            $pedido = App\ProductoPedido::where("combo", "like", "%$producto_pedido_json_->comboId%")->first();
            if($pedido){
                $pedido = $pedido->pedido_id;
            }
            else{
                $pedido = null;
            }
        }
        return $this->agregarProductoPedido($producto_pedido_json, $mesa, $pedido, false);
    }

    public function preAgregarComboProductoPedido(){
        $pps = Input::get('productos');
        $mesa = Input::get('mesa');
        $pedido =  Input::get('pedido');
        $force =  Input::get('force') == 1;
        if($pedido == null || $pedido == '0' || $pedido == '-1'){
            $pedido = Pedido::where('mesa_id', $mesa)->where('estado', 1)->first();
            if($pedido){
                $pedido = $pedido->id;
            }
        }
        foreach($pps as $pp){
            $pp['force'] = true;
            $pp['adicionales'] = [];
            if(!array_key_exists('sin_ingredientes',$pp['obs'])){
                $pp['obs']['sin_ingredientes'] = [];
            }
            if(!array_key_exists('ingredientes',$pp)){
                $pp['ingredientes'] = [];
            }
            $producto_pedido_json = json_encode($pp);
            try {
                $res = $this->agregarProductoPedido($producto_pedido_json, $mesa, $pedido, false);
                if(!$pedido){
                    $pedido = json_decode($res);
                    $pedido = $pedido->id;
                }
            } catch (\Throwable $th) {
                try {
                    ProductoPedido::where("id", $pedido)->delete();
                } catch (\Throwable $th) {
                    return $th;
                }
            }
        }
        return $pedido;
    }
    public function agregarProductoPedido($producto_pedido_json = null, $mesa = null, $pedido = null, $es_combo = false){
        $controller = app('App\Http\Controllers\PedidoController');

        if($pedido == 0){
            $pedido = null;
        }
        else{
            $pedido = $this->buscar($pedido);
        }

        $producto_pedido_json = json_decode($producto_pedido_json);

        if(!$producto_pedido_json->force){
            $validarInventario = $controller->validarInventario($producto_pedido_json);
            if($es_combo){
                return $validarInventario;
            }
            if(count($validarInventario->errores) > 0){
                $validarInventario->id = -1;
                return json_encode($validarInventario);
            }
        }

        if($pedido == null){
            $pedido = new stdClass();
            $pedido->fecha = date("Y-m-d H:i:s");
            $pedido->mesa_id = $mesa;
            $pedido->obs = '{"mesa_alias":"'.$producto_pedido_json->alias.'"}';
            $pedido->user_id = Auth::user()->id;
            $pedido->caja_id = Auth::user()->caja_id;
            if($mesa==0){
                $pedido->estado = 3;
            }
            else{
                $pedido->estado = 1;
            }
            $pedido->turno = app('App\Http\Controllers\ConfigController')->asignarTurno();
            $pedido = $this->guardar($pedido);
        }
        else{
            Pedido::where('id',$pedido->id)->update(['entregado'=>null]);
        }
        $producto_pedido = new stdClass();
        $producto_pedido->pedido_id = $pedido->id;
        $producto_pedido->producto_id = $producto_pedido_json->producto->id;
        $producto_pedido_json->obs->adicionales = $producto_pedido_json->adicionales;
        $producto_pedido->obs = $producto_pedido_json->obs;
        $producto_pedido->valor = $producto_pedido_json->producto->valor;
        $producto_pedido->cant = $producto_pedido_json->cantidad;

        if(isset($producto_pedido_json->comboId) && $producto_pedido_json->comboId && $producto_pedido_json->comboId != null && $producto_pedido_json->comboId !=''){
            $producto_pedido->combo = new stdClass();
            $producto_pedido->combo->ref = $producto_pedido_json->comboId;
            $producto_pedido->combo->precio = is_numeric($producto_pedido_json->comboPrecio)?floatval($producto_pedido_json->comboPrecio):0;
            $producto_pedido->combo->nombre_combo = $producto_pedido_json->comboNombre;
            $producto_pedido->combo->cantidad = $producto_pedido_json->comboCantidad;
            $producto_pedido->combo->nombre_producto = $producto_pedido_json->nombre;
        }

        $controller = app('App\Http\Controllers\ProductoPedidoController');

        $producto_pedido = $controller->guardar($producto_pedido);
        $controller = app('App\Http\Controllers\PedidoController');

        $controller->actualizarValor($pedido->id);

        $controller = app('App\Http\Controllers\ProductoPedidoIngredienteController');

        foreach($producto_pedido_json->ingredientes as $ingrediente){
            $producto_pedido_ingrediente = new stdClass();
            $producto_pedido_ingrediente->producto_pedido_id = $producto_pedido->id;
            $producto_pedido_ingrediente->ingrediente_id = $ingrediente->id;
            $producto_pedido_ingrediente->cant = $ingrediente->cantidad;
            $producto_pedido_ingrediente = $controller->guardar($producto_pedido_ingrediente);
        }
        
        $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');

        $producto_pedido_adicionales_valor = 0;
        foreach($producto_pedido_json->adicionales as $adicional){
            $producto_pedido_adicional = new stdClass();
            $producto_pedido_adicional->producto_pedido_id = $producto_pedido->id;
            $producto_pedido_adicional->adicional_id = $adicional->id;
            if(count($producto_pedido_json->obs->mix)>0){
                $valor = ($adicional->valor)/count($producto_pedido_json->obs->mix);
                $valor = ceil($valor/100)*100;
                $producto_pedido_adicional->valor = $valor;
                $producto_pedido_adicional->total = $valor;
                $producto_pedido_adicional->cant = ($adicional->cantidad)/count($producto_pedido_json->obs->mix);;
                $producto_pedido_adicionales_valor+=$valor;
            }
            else{
                $producto_pedido_adicional->valor = floatval($adicional->valor);
                $producto_pedido_adicional->total = floatval($adicional->valor);
                $producto_pedido_adicional->cant = $adicional->cantidad;
                $producto_pedido_adicionales_valor+= floatval($adicional->valor);
            }
            $producto_pedido_adicional = $controller->guardar($producto_pedido_adicional);
        }
        $controller = app('App\Http\Controllers\ProductoPedidoController');

        $producto_pedido->obs = $producto_pedido_json->obs;
        $producto_pedido->total = ($producto_pedido->valor+$producto_pedido_adicionales_valor)*$producto_pedido->cant;
        $producto_pedido = $controller->guardar($producto_pedido);
        $controller = app('App\Http\Controllers\PedidoController');
        $controller->actualizarValor($pedido->id);
        $pedido_id = new stdClass();
        $pedido_id->id = $pedido->id;
        return json_encode($pedido_id);
    }

    public function patchPedido($id){
        $pedido = Pedido::find($id);

        foreach(Input::all() as $key=>$value) {
            $pedido->$key = $value;
        }
        $pedido->save();
        return $pedido;
    }
    public function savePropina($id){
        $pedido = Pedido::find($id);
        $pedido->propina = Input::get('propina');
        $res = $pedido->save();
        if($res){
            return response()->json(array('code'=>200,'msg'=>'OK.'));
        }
        else{
            return response()->json(array('code'=>401,'msg'=>'NO OK.'));
        }
    }
    public function dashboardInfo(){
        $hoy = date('Y-m-d 00:00:00');
        $fecha_inicio = "DATE_ADD('".$hoy."', INTERVAL 3 hour)";
        $hoy = date('Y-m-d 23:59:59');
        $fecha_fin = "DATE_ADD('".$hoy."', INTERVAL 3 hour)";
        $hoy = DB::select("
            SELECT count(id) as total
            FROM pizza_pedido 
            WHERE created_at >= $fecha_inicio 
            AND created_at <= $fecha_fin
        ");
        $activos = DB::select("
            SELECT count(id) as total
            FROM pizza_pedido 
            WHERE created_at >= $fecha_inicio 
            AND created_at <= $fecha_fin
            AND estado in (1, 4, 5)
            AND mesa_id != 0
        ");
        $domicilios = DB::select("
            SELECT count(id) as total
            FROM pizza_pedido 
            WHERE created_at >= $fecha_inicio 
            AND created_at <= $fecha_fin
            AND estado in (3, 4, 5)
            AND mesa_id = 0
        ");
        $mesas = DB::select("
            SELECT mesa_id
            FROM pizza_pedido 
            WHERE estado in (1, 4)
            AND mesa_id != 0
        ");
        $mesas_ocupadas = [];
        foreach ($mesas as $mesa){
            $mesa_id = $mesa->mesa_id;
            if(in_array($mesa_id, $mesas_ocupadas)){
            }
            else{
                $mesas_ocupadas[] = $mesa_id;
            }
        }
        $cantidad_mesas = app('App\Http\Controllers\ConfigController')->first();
        $cantidad_mesas = $cantidad_mesas->cantidad_mesas;
        $mesas = ['ocupadas'=>$mesas_ocupadas, 'total'=>$cantidad_mesas];
        return response()->json(array('hoy'=>$hoy[0],'activos'=>$activos[0],'domicilios'=>$domicilios[0],'mesas'=>$mesas));
    }

    protected function showMesaView($id, Request $request, $mesa_alias=false, $pedido_id=''){
        $propina = 0;
        if($id!=0){
            $propina = app('App\Http\Controllers\ConfigController')->getPropina();
        }
        $dia_operativo = app('App\Http\Controllers\DocumentoController')->esDiaOperativoActivoWithEnv();
        $mesa_alias = $mesa_alias?:app('App\Http\Controllers\ConfigController')->getMesaAlias($id);
        $msg = $this->getMessagesFromRequest($request);
        $view = $this->getMenuVersionFromRequest($request);
        return view($view)->with('mesa', $id)
            ->with('tipos_producto', app('App\Http\Controllers\TipoProductoController')->mostrarMenu())
            ->with('mesa_alias', $mesa_alias)
            ->with('dia_operativo_valido', $dia_operativo)
            ->with('valida_inventario', app('App\Http\Controllers\ConfigController')->getValidaInventario())
            ->with('propina', $propina)
            ->with('status', $msg)
            ->with('pedido_id', $pedido_id)
            ->with('conn', app('App\Http\Controllers\TipoProductoController')->mesaMenu())
            ->with('combos', app('App\Http\Controllers\ComboController')->menu());

    }

    public function mesaView($id, Request $request){
        return $this->showMesaView($id, $request);
    }    

    public function vistaEditar($id, Request $request){
        $pedido = Pedido::find($id);
        try {
            $mesa_alias=json_decode($pedido->obs)->mesa_alias;
        } catch (\Throwable $th) {
            $mesa_alias='';
        }
        return $this->showMesaView($pedido->mesa_id, $request, $mesa_alias, $id);
    }

    protected function getMenuVersionFromRequest($request){
        $view='mesa.menu';
        if($request['v']=='2'){
            $view='mesa.menu-v2';
        }
        return $view;
    }

    protected function getMessagesFromRequest($request){
        $msg = $request['msg'];
        if($msg == 'ml'){
            $msg = ['success' =>'Mesa liberada. Puede retomar el pedido desde la opción "Pedidos Activos"'];
        }
        else{
            $msg = [];
        }
        return $msg;
    }

    public function preReportePedidosActivos(){
        $fecha_inicio = Input::get("inicio");
        $fecha_fin = Input::get("fin");
        $domicilios = Input::get("domicilios");
        $where = "AND pedi.estado in (1,3) ";
        if($domicilios==0){
            $where.="AND pedi.mesa_id != 0";
        }
        return $this->reportePedidos($fecha_inicio, $fecha_fin, $where);
    }

    public function preReportePedidosActivosPos(){
        $fecha_inicio = Input::get("inicio");
        $fecha_fin = Input::get("fin");
        $domicilios = Input::get("domicilios");
        $where = "AND pedi.estado in (1,3) ";
        if($domicilios==0){
            $where.="AND pedi.mesa_id != 0";
        }
        $config = app('App\Http\Controllers\ConfigController')->first();
        $data = $this->reportePedidosData($fecha_inicio, $fecha_fin, $where);
        $printStack = App\Util\POS::ReportePedidosPos(
            $fecha_inicio,$fecha_fin, $data, $config);
        $res = [
            'print' => $printStack,
            'servicio' => $config->servicio_impresion,
        ];
        return response()->json($res);
    }

    public function preDividirCuentaPOS(Request $request){
        $data = $request->all();
        $cuentas = $data['cuentas'];
        $pedido = $data['pedido'];
        for ($i=0; $i < count($cuentas); $i++) { 
            $cuenta = $cuentas[$i];
            $cuenta['pedido'] = [];
            $cuentas[$i]['total'] = '$'.number_format($cuenta['total'], 0);
        }
        for ($j=0; $j < count($pedido); $j++) { 
            $pedidoItem = $pedido[$j];
            $cuenta['pedido'][] = ['nombre'=>$pedidoItem['nombre']];
            for ($i=0; $i < count($pedidoItem['cuentas']); $i++) { 
                $pedidoItemCuenta = $pedidoItem['cuentas'][$i];
                if($pedidoItemCuenta['cantidad']<1){
                    continue;
                }
                $cuentas[$i]['pedido'][] = [
                    'nombre' => $pedidoItem['nombre'],
                    'subtotal' => '$'.number_format($pedidoItemCuenta['subtotal'], 0),
                    'cantidad' => $pedidoItemCuenta['cantidad'],
                ];
            }
        }
        $config = app('App\Http\Controllers\ConfigController')->first();
        $printStack = App\Util\POS::CuentaDividida($cuentas, $config);
        $res = [
            'print' => $printStack,
            'servicio' => $config->servicio_impresion,
        ];
        return response()->json($res);
    }

    public function preReportePedidosArchivados(){
        $fecha_inicio = Input::get("inicio");
        $fecha_fin = Input::get("fin");
        $where = "AND pedi.estado = 2";
        return $this->reportePedidos($fecha_inicio, $fecha_fin, $where);
    }

    public function reportePedidos($fecha_inicio, $fecha_fin, $otherWhere=''){
        $reporte = $this->reportePedidosData($fecha_inicio, $fecha_fin, $otherWhere);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.pedidos', [
            "data" => $reporte,
            "inicio" => $fecha_inicio, 
            "fin" => $fecha_fin, 
            
        ]);
        return $pdf->stream();
    }

    public function reportePedidosData($fecha_inicio, $fecha_fin, $otherWhere){
        $reporte = DB::select("SELECT
            tipr.descripcion as tipo,
            sum(prpe.cant) as cantidad,
            sum(prpe.total) as total
            FROM pizza_producto_pedido prpe
            JOIN pizza_producto prod on prod.id = prpe.producto_id 
            JOIN pizza_pedido pedi on pedi.id = prpe.pedido_id 
            JOIN pizza_tipo_producto tipr on tipr.id = prod.tipo_producto_id
            WHERE pedi.created_at >= '$fecha_inicio'
            AND pedi.created_at <= '$fecha_fin'
            $otherWhere
            GROUP BY 1
            ");
        return $reporte;
    }
}