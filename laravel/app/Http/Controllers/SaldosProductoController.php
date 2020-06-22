<?php

namespace App\Http\Controllers;
use App\SaldosProducto;
use App\ProductoIngrediente;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

class SaldosProductoController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    
    public function vistaLista(){
        return view('saldos_producto.listar')->with("saldos_producto_lista",$this->paginar(Input::all()));
    }

    public function vistaCrear(){
        return view('saldos_producto.crear')->with('producto_lista',app('App\Http\Controllers\ProductoController')->todos());
    }

    public function vistaEditar($id){
        return view('saldos_producto.editar')->with("saldos_producto", $this->encontrar($id))->with('producto_lista',app('App\Http\Controllers\ProductoController')->todos());
    }

    public function entradaFromDetalleDocumento($detalleDocumento){
        $this->entradaSalidaFromDetalleDocumento($detalleDocumento,true);
    }
    public function salidaFromDetalleDocumento($detalleDocumento){
        $this->entradaSalidaFromDetalleDocumento($detalleDocumento,false);
    }

    public function entradaSalidaFromDetalleDocumento($detalleDocumento, $tipo){
        $saldos_producto = SaldosProducto::where('producto_id',$detalleDocumento->producto_id)->first();
        
        if($saldos_producto == null){
            $saldos_producto = new SaldosProducto;
            $saldos_producto->producto_id = $detalleDocumento->producto_id;
            $saldos_producto->save();
            $this->entradaSalidaFromDetalleDocumento($detalleDocumento, $tipo);
        }
        else{
            $mes = explode('-',$detalleDocumento->created_at);
            $mes = $mes[1];
            if($tipo){
                $saldos_producto->{"entradas".$mes} += $detalleDocumento->cantidad;
                $saldos_producto->existencia +=  $detalleDocumento->cantidad;
            }
            else{
                $saldos_producto->{"salidas".$mes} += $detalleDocumento->cantidad;
                $saldos_producto->existencia -=  $detalleDocumento->cantidad;
            }
            $saldos_producto->save();
        }
    }

    public function entradaFromDetalleDocumentoIngrediente($detalleDocumento){
        $this->entradaSalidaFromDetalleDocumentoIngrediente($detalleDocumento,true);
    }
    public function salidaFromDetalleDocumentoIngrediente($detalleDocumento){
        $this->entradaSalidaFromDetalleDocumentoIngrediente($detalleDocumento,false);
    }

    public function entradaSalidaFromDetalleDocumentoIngrediente($detalleDocumento, $tipo){
        $saldos_producto = SaldosProducto::where('ingrediente_id',$detalleDocumento->ingrediente_id)->first();
        
        if($saldos_producto == null){
            $saldos_producto = new SaldosProducto;
            $saldos_producto->ingrediente_id = $detalleDocumento->ingrediente_id;
            $saldos_producto->save();
            $this->entradaSalidaFromDetalleDocumentoIngrediente($detalleDocumento, $tipo);
        }
        else{
            $mes = explode('-',$detalleDocumento->created_at);
            $mes = $mes[1];
            if($tipo){
                $saldos_producto->{"entradas".$mes} += $detalleDocumento->cantidad;
                $saldos_producto->existencia +=  $detalleDocumento->cantidad;
            }
            else{
                $saldos_producto->{"salidas".$mes} += $detalleDocumento->cantidad;
                $saldos_producto->existencia -=  $detalleDocumento->cantidad;
            }
            $saldos_producto->save();
        }
    }

    public function salidaFromDetalleDocumentoNoTerminado($detalleDocumento, $obs){
        $obs = json_decode($obs);
        if($obs->tipo == 'MIXTA'){
            $this->salidaFromDetalleDocumentoNoTerminadoMixto($detalleDocumento, $obs);
        }
        else{
            $this->salidaFromDetalleDocumentoNoTerminadoCompleto($detalleDocumento, $obs);
        }
    }

    public function salidaFromDetalleDocumentoNoTerminadoCompleto($detalleDocumento, $obs){
        //ingredientes
        $ingredientes = ProductoIngrediente::where('producto_id', $detalleDocumento->producto_id)->where('tamano', $obs->tamano)->get();
        $sinIngredientes = $obs->sin_ingredientes;

        foreach($ingredientes as $ingrediente){
            $continue = false;
            foreach($sinIngredientes as $sinIngrediente){
                if($ingrediente->ingrediente_id == $sinIngrediente->id){
                    $continue = true;
                    break;
                }
            }
            if($continue){
                continue;
            }
            $saldos_producto = SaldosProducto::where('ingrediente_id',$ingrediente->ingrediente_id)->first();

            if($saldos_producto == null){
                $saldos_producto = new SaldosProducto;
                if(isset($ingrediente->ingrediente_id)){
                    $saldos_producto->ingrediente_id = $ingrediente->ingrediente_id;
                }
                $saldos_producto->save();
                $saldos_producto = SaldosProducto::where('ingrediente_id',$ingrediente->ingrediente_id)->first();
            }
            $mes = explode('-',$detalleDocumento->created_at);
            $mes = $mes[1];

            $cantidad = $ingrediente->cantidad;
            $saldos_producto->{"salidas".$mes} += $cantidad*$detalleDocumento->cantidad;
            $saldos_producto->existencia -=  $cantidad*$detalleDocumento->cantidad;
            $saldos_producto->save();
        }
        //adicionales
        foreach($obs->adicionales as $adicional){
            $saldos_producto = SaldosProducto::where('ingrediente_id',$adicional->ingrediente)->first();

            if($saldos_producto == null){
                $saldos_producto = new SaldosProducto;
                if(isset($adicional->ingrediente)){
                    $saldos_producto->ingrediente_id = $adicional->ingrediente;
                }
                $saldos_producto->save();
                $saldos_producto = SaldosProducto::where('ingrediente_id',$adicional->ingrediente)->first();
            }
            $mes = explode('-',$detalleDocumento->created_at);
            $mes = $mes[1];

            $saldos_producto->{"salidas".$mes} += floatval($adicional->cantidad) *$detalleDocumento->cantidad;
            $saldos_producto->existencia -=  floatval($adicional->cantidad)*$detalleDocumento->cantidad;
            $saldos_producto->save();
        }
    }

    public function salidaFromDetalleDocumentoNoTerminadoMixto($detalleDocumento, $obs){

        //ingredientes
        foreach ($obs->mix as $mix){
            $ingredientes = $mix->ingredientes;
            $sinIngredientes = $mix->sin_ingredientes;

            foreach($ingredientes as $ingrediente){
                $continue = false;
                foreach($sinIngredientes as $sinIngrediente){
                    if($ingrediente->id == $sinIngrediente->id){
                        $continue = true;
                        break;
                    }
                }
                if($continue){
                    continue;
                }
                $saldos_producto = SaldosProducto::where('ingrediente_id',$ingrediente->id)->first();

                if($saldos_producto == null){
                    $saldos_producto = new SaldosProducto;
                    $saldos_producto->ingrediente_id = $ingrediente->id;
                    // if(isset($adicional->ingrediente_id)){
                    //     $saldos_producto->ingrediente_id = $ingrediente->ingrediente_id;
                    // }
                    $saldos_producto->save();
                    $saldos_producto = SaldosProducto::where('ingrediente_id',$ingrediente->id)->first();
                }
                $mes = explode('-',$detalleDocumento->created_at);
                $mes = $mes[1];

                $cantidad = $ingrediente->cantidad;
                if(!(isset($saldos_producto->{"salidas".$mes}))){
                    $saldos_producto->{"salidas".$mes} = 0;
                }
                if(!isset($saldos_producto->existencia)){
                    $saldos_producto->existencia = 0;
                }
                $saldos_producto->{"salidas".$mes} += floatval($cantidad)*($detalleDocumento->cantidad)/count($obs->mix);
                $saldos_producto->existencia -=  floatval($cantidad)*($detalleDocumento->cantidad)/count($obs->mix);
                $saldos_producto->save();
            }
            //adicionales
            foreach($mix->adicionales as $adicional){
                $saldos_producto = SaldosProducto::where('ingrediente_id',$adicional->ingrediente)->first();

                if($saldos_producto == null){
                    $saldos_producto = new SaldosProducto;
                    $saldos_producto->ingrediente_id = $adicional->ingrediente;
                    // if(isset($adicional->ingrediente_id)){
                    //     $saldos_producto->ingrediente_id = $adicional->ingrediente_id;
                    // }
                    $saldos_producto->save();
                    $saldos_producto = SaldosProducto::where('ingrediente_id',$adicional->ingrediente)->first();
                }
                $mes = explode('-',$detalleDocumento->created_at);
                $mes = $mes[1];

                $saldos_producto->{"salidas".$mes} += floatval($adicional->cantidad)*($detalleDocumento->cantidad)/count($obs->mix);
                $saldos_producto->existencia -=  floatval($adicional->cantidad)*($detalleDocumento->cantidad)/count($obs->mix);
                $saldos_producto->save();
            }
        }
    }

    public function todos() {
        return SaldosProducto::all();
    }
    
    public function encontrar($id) {
        return SaldosProducto::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        $rules = array(
            'producto_id' => 'required',
            'bodega' => 'required',
            'fecha_act' => 'required',
            'existencia' => 'required',
            'existencia_max' => 'required',
            'existencia_min' => 'required',
            'entradas00' => 'required',
            'entradas01' => 'required',
            'entradas02' => 'required',
            'entradas03' => 'required',
            'entradas04' => 'required',
            'entradas05' => 'required',
            'entradas06' => 'required',
            'entradas07' => 'required',
            'entradas08' => 'required',
            'entradas09' => 'required',
            'entradas10' => 'required',
            'entradas11' => 'required',
            'entradas12' => 'required',
            'salidas00' => 'required',
            'salidas01' => 'required',
            'salidas02' => 'required',
            'salidas03' => 'required',
            'salidas04' => 'required',
            'salidas05' => 'required',
            'salidas06' => 'required',
            'salidas07' => 'required',
            'salidas08' => 'required',
            'salidas09' => 'required',
            'salidas10' => 'required',
            'salidas11' => 'required',
            'salidas12' => 'required'
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'bodega.required'=>'El campo bodega es obligatorio.',
            'fecha_act.required'=>'El campo Fecha Actualización es obligatorio.',
            'existencia.required'=>'El campo existencia es obligatorio.',
            'existencia_max.required'=>'El campo Existencia Máxima es obligatorio.',
            'existencia_min.required'=>'El campo Existencia Mínima es obligatorio.',
            'entradas00.required'=>'El campo entradas00 es obligatorio.',
            'entradas01.required'=>'El campo entradas01 es obligatorio.',
            'entradas02.required'=>'El campo entradas02 es obligatorio.',
            'entradas03.required'=>'El campo entradas03 es obligatorio.',
            'entradas04.required'=>'El campo entradas04 es obligatorio.',
            'entradas05.required'=>'El campo entradas05 es obligatorio.',
            'entradas06.required'=>'El campo entradas06 es obligatorio.',
            'entradas07.required'=>'El campo entradas07 es obligatorio.',
            'entradas08.required'=>'El campo entradas08 es obligatorio.',
            'entradas09.required'=>'El campo entradas09 es obligatorio.',
            'entradas10.required'=>'El campo entradas10 es obligatorio.',
            'entradas11.required'=>'El campo entradas11 es obligatorio.',
            'entradas12.required'=>'El campo entradas12 es obligatorio.',
            'salidas00.required'=>'El campo salidas00 es obligatorio.',
            'salidas01.required'=>'El campo salidas01 es obligatorio.',
            'salidas02.required'=>'El campo salidas02 es obligatorio.',
            'salidas03.required'=>'El campo salidas03 es obligatorio.',
            'salidas04.required'=>'El campo salidas04 es obligatorio.',
            'salidas05.required'=>'El campo salidas05 es obligatorio.',
            'salidas06.required'=>'El campo salidas06 es obligatorio.',
            'salidas07.required'=>'El campo salidas07 es obligatorio.',
            'salidas08.required'=>'El campo salidas08 es obligatorio.',
            'salidas09.required'=>'El campo salidas09 es obligatorio.',
            'salidas10.required'=>'El campo salidas10 es obligatorio.',
            'salidas11.required'=>'El campo salidas11 es obligatorio.',
            'salidas12.required'=>'El campo salidas12 es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);

        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo crear la Inventario.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $saldos_producto = new SaldosProducto;
            $saldos_producto->producto_id = Input::get('producto_id');
            $saldos_producto->bodega = Input::get('bodega');
            $saldos_producto->fecha_act = Input::get('fecha_act');
            $saldos_producto->existencia = Input::get('existencia');
            $saldos_producto->existencia_max = Input::get('existencia_max');
            $saldos_producto->existencia_min = Input::get('existencia_min');
            $saldos_producto->save();
            return back()->with('status', ["success"=>"Inventario Agregada."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $saldos_producto = SaldosProducto::find(Input::get('id'));
        
        $rules = array(
            'producto_id' => 'required',
            'bodega' => 'required',
            'fecha_act' => 'required',
            'existencia' => 'required',
            'existencia_max' => 'required',
            'existencia_min' => 'required',
            'entradas00' => 'required',
            'entradas01' => 'required',
            'entradas02' => 'required',
            'entradas03' => 'required',
            'entradas04' => 'required',
            'entradas05' => 'required',
            'entradas06' => 'required',
            'entradas07' => 'required',
            'entradas08' => 'required',
            'entradas09' => 'required',
            'entradas10' => 'required',
            'entradas11' => 'required',
            'entradas12' => 'required',
            'salidas00' => 'required',
            'salidas01' => 'required',
            'salidas02' => 'required',
            'salidas03' => 'required',
            'salidas04' => 'required',
            'salidas05' => 'required',
            'salidas06' => 'required',
            'salidas07' => 'required',
            'salidas08' => 'required',
            'salidas09' => 'required',
            'salidas10' => 'required',
            'salidas11' => 'required',
            'salidas12' => 'required',
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'bodega.required'=>'El campo bodega es obligatorio.',
            'fecha_act.required'=>'El campo Fecha Actualización es obligatorio.',
            'existencia.required'=>'El campo existencia es obligatorio.',
            'existencia_max.required'=>'El campo Existencia Máxima es obligatorio.',
            'existencia_min.required'=>'El campo Existencia Mínima es obligatorio.',
            'entradas00.required'=>'El campo entradas00 es obligatorio.',
            'entradas01.required'=>'El campo entradas01 es obligatorio.',
            'entradas02.required'=>'El campo entradas02 es obligatorio.',
            'entradas03.required'=>'El campo entradas03 es obligatorio.',
            'entradas04.required'=>'El campo entradas04 es obligatorio.',
            'entradas05.required'=>'El campo entradas05 es obligatorio.',
            'entradas06.required'=>'El campo entradas06 es obligatorio.',
            'entradas07.required'=>'El campo entradas07 es obligatorio.',
            'entradas08.required'=>'El campo entradas08 es obligatorio.',
            'entradas09.required'=>'El campo entradas09 es obligatorio.',
            'entradas10.required'=>'El campo entradas10 es obligatorio.',
            'entradas11.required'=>'El campo entradas11 es obligatorio.',
            'entradas12.required'=>'El campo entradas12 es obligatorio.',
            'salidas00.required'=>'El campo salidas00 es obligatorio.',
            'salidas01.required'=>'El campo salidas01 es obligatorio.',
            'salidas02.required'=>'El campo salidas02 es obligatorio.',
            'salidas03.required'=>'El campo salidas03 es obligatorio.',
            'salidas04.required'=>'El campo salidas04 es obligatorio.',
            'salidas05.required'=>'El campo salidas05 es obligatorio.',
            'salidas06.required'=>'El campo salidas06 es obligatorio.',
            'salidas07.required'=>'El campo salidas07 es obligatorio.',
            'salidas08.required'=>'El campo salidas08 es obligatorio.',
            'salidas09.required'=>'El campo salidas09 es obligatorio.',
            'salidas10.required'=>'El campo salidas10 es obligatorio.',
            'salidas11.required'=>'El campo salidas11 es obligatorio.',
            'salidas12.required'=>'El campo salidas12 es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo modificar la Inventario.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $saldos_producto->producto_id = Input::get('producto_id');
            $saldos_producto->bodega = Input::get('bodega');
            $saldos_producto->fecha_act = Input::get('fecha_act');
            $saldos_producto->existencia = Input::get('existencia');
            $saldos_producto->existencia_max = Input::get('existencia_max');
            $saldos_producto->existencia_min = Input::get('existencia_min');
            $saldos_producto->save();
            return back()->with('status', ["success"=>"Inventario actualizada."]);
        }
    }
    
    public function borrar() {
        SaldosProducto::destroy(Input::get('id'));
        $post = Input::get('_modal')!=null;
        $status = ["success" => "Inventario borrada."];
        return back()->with('status', $status);
        
    }

    public function paginar_modal() {
        return $this->paginar(Input::all());
    }
    
    public function paginar($input) {
        
        $buscar = isset($input["buscar"])?$input["buscar"]:"";
        $ordenar_por = isset($input["ordenar_por"])?$input["ordenar_por"]:"";
        $sentido = isset($input["sentido"])?$input["sentido"]:"";
        $por_pagina = isset($input["por_pagina"])?$input["por_pagina"]:30;
        
        return $this->paginar_($buscar, $ordenar_por, $sentido, $por_pagina);
    }
    
    public function paginar_($buscar, $ordenar_por, $sentido, $por_pagina) {
        if($ordenar_por==""||$ordenar_por==null){
            return SaldosProducto::Where("id","like", "%$buscar%")
                ->orWhereHas('ingrediente', function ($query) use($buscar){
                    $query->where('descripcion', 'like', "%$buscar%");
                })
                ->orWhereHas('producto', function ($query) use($buscar){
                    $query->where('descripcion', 'like', "%$buscar%");
                })
                ->paginate($por_pagina);
        }
        return SaldosProducto::Where("id","like", "%$buscar%")
            ->orWhereHas('ingrediente', function ($query) use($buscar){
                $query->where('descripcion', 'like', "%$buscar%");
            })
            ->orWhereHas('producto', function ($query) use($buscar){
                $query->where('descripcion', 'like', "%$buscar%");
            })
            ->orderBy($ordenar_por, $sentido)
            ->paginate($por_pagina);
    }

    function api_listar(){
        return response($this->todos(), 200)
        ->header('Content-Type', 'application/json');
    }
    

    function api_encontrar($id){
        return response($this->encontrar($id), 200)
        ->header('Content-Type', 'application/json');
    }
    

    function api_crear(){
        $postData = Input::all();
        $rules = array(
            'producto_id' => 'required',
            'bodega' => 'required',
            'fecha_act' => 'required',
            'existencia' => 'required',
            'existencia_max' => 'required',
            'existencia_min' => 'required',
            'entradas00' => 'required',
            'entradas01' => 'required',
            'entradas02' => 'required',
            'entradas03' => 'required',
            'entradas04' => 'required',
            'entradas05' => 'required',
            'entradas06' => 'required',
            'entradas07' => 'required',
            'entradas08' => 'required',
            'entradas09' => 'required',
            'entradas10' => 'required',
            'entradas11' => 'required',
            'entradas12' => 'required',
            'salidas00' => 'required',
            'salidas01' => 'required',
            'salidas02' => 'required',
            'salidas03' => 'required',
            'salidas04' => 'required',
            'salidas05' => 'required',
            'salidas06' => 'required',
            'salidas07' => 'required',
            'salidas08' => 'required',
            'salidas09' => 'required',
            'salidas10' => 'required',
            'salidas11' => 'required',
            'salidas12' => 'required'
        );
        
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'bodega.required'=>'El campo bodega es obligatorio.',
            'fecha_act.required'=>'El campo Fecha Actualización es obligatorio.',
            'existencia.required'=>'El campo existencia es obligatorio.',
            'existencia_max.required'=>'El campo Existencia Máxima es obligatorio.',
            'existencia_min.required'=>'El campo Existencia Mínima es obligatorio.',
            'entradas00.required'=>'El campo entradas00 es obligatorio.',
            'entradas01.required'=>'El campo entradas01 es obligatorio.',
            'entradas02.required'=>'El campo entradas02 es obligatorio.',
            'entradas03.required'=>'El campo entradas03 es obligatorio.',
            'entradas04.required'=>'El campo entradas04 es obligatorio.',
            'entradas05.required'=>'El campo entradas05 es obligatorio.',
            'entradas06.required'=>'El campo entradas06 es obligatorio.',
            'entradas07.required'=>'El campo entradas07 es obligatorio.',
            'entradas08.required'=>'El campo entradas08 es obligatorio.',
            'entradas09.required'=>'El campo entradas09 es obligatorio.',
            'entradas10.required'=>'El campo entradas10 es obligatorio.',
            'entradas11.required'=>'El campo entradas11 es obligatorio.',
            'entradas12.required'=>'El campo entradas12 es obligatorio.',
            'salidas00.required'=>'El campo salidas00 es obligatorio.',
            'salidas01.required'=>'El campo salidas01 es obligatorio.',
            'salidas02.required'=>'El campo salidas02 es obligatorio.',
            'salidas03.required'=>'El campo salidas03 es obligatorio.',
            'salidas04.required'=>'El campo salidas04 es obligatorio.',
            'salidas05.required'=>'El campo salidas05 es obligatorio.',
            'salidas06.required'=>'El campo salidas06 es obligatorio.',
            'salidas07.required'=>'El campo salidas07 es obligatorio.',
            'salidas08.required'=>'El campo salidas08 es obligatorio.',
            'salidas09.required'=>'El campo salidas09 es obligatorio.',
            'salidas10.required'=>'El campo salidas10 es obligatorio.',
            'salidas11.required'=>'El campo salidas11 es obligatorio.',
            'salidas12.required'=>'El campo salidas12 es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo crear la Inventario.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $saldos_producto = new SaldosProducto;
            $saldos_producto->producto_id = Input::get('producto_id');
            $saldos_producto->bodega = Input::get('bodega');
            $saldos_producto->fecha_act = Input::get('fecha_act');
            $saldos_producto->existencia = Input::get('existencia');
            $saldos_producto->existencia_max = Input::get('existencia_max');
            $saldos_producto->existencia_min = Input::get('existencia_min');
            $saldos_producto->save();
            
            return response(array('mensaje'=>"Inventario Agregada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_editar($id){
        $postData = Input::all();
        $saldos_producto = SaldosProducto::find($id);
        
        $rules = array(
            'producto_id' => 'required',
            'bodega' => 'required',
            'fecha_act' => 'required',
            'existencia' => 'required',
            'existencia_max' => 'required',
            'existencia_min' => 'required',
            'entradas00' => 'required',
            'entradas01' => 'required',
            'entradas02' => 'required',
            'entradas03' => 'required',
            'entradas04' => 'required',
            'entradas05' => 'required',
            'entradas06' => 'required',
            'entradas07' => 'required',
            'entradas08' => 'required',
            'entradas09' => 'required',
            'entradas10' => 'required',
            'entradas11' => 'required',
            'entradas12' => 'required',
            'salidas00' => 'required',
            'salidas01' => 'required',
            'salidas02' => 'required',
            'salidas03' => 'required',
            'salidas04' => 'required',
            'salidas05' => 'required',
            'salidas06' => 'required',
            'salidas07' => 'required',
            'salidas08' => 'required',
            'salidas09' => 'required',
            'salidas10' => 'required',
            'salidas11' => 'required',
            'salidas12' => 'required',
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'bodega.required'=>'El campo bodega es obligatorio.',
            'fecha_act.required'=>'El campo Fecha Actualización es obligatorio.',
            'existencia.required'=>'El campo existencia es obligatorio.',
            'existencia_max.required'=>'El campo Existencia Máxima es obligatorio.',
            'existencia_min.required'=>'El campo Existencia Mínima es obligatorio.',
            'entradas00.required'=>'El campo entradas00 es obligatorio.',
            'entradas01.required'=>'El campo entradas01 es obligatorio.',
            'entradas02.required'=>'El campo entradas02 es obligatorio.',
            'entradas03.required'=>'El campo entradas03 es obligatorio.',
            'entradas04.required'=>'El campo entradas04 es obligatorio.',
            'entradas05.required'=>'El campo entradas05 es obligatorio.',
            'entradas06.required'=>'El campo entradas06 es obligatorio.',
            'entradas07.required'=>'El campo entradas07 es obligatorio.',
            'entradas08.required'=>'El campo entradas08 es obligatorio.',
            'entradas09.required'=>'El campo entradas09 es obligatorio.',
            'entradas10.required'=>'El campo entradas10 es obligatorio.',
            'entradas11.required'=>'El campo entradas11 es obligatorio.',
            'entradas12.required'=>'El campo entradas12 es obligatorio.',
            'salidas00.required'=>'El campo salidas00 es obligatorio.',
            'salidas01.required'=>'El campo salidas01 es obligatorio.',
            'salidas02.required'=>'El campo salidas02 es obligatorio.',
            'salidas03.required'=>'El campo salidas03 es obligatorio.',
            'salidas04.required'=>'El campo salidas04 es obligatorio.',
            'salidas05.required'=>'El campo salidas05 es obligatorio.',
            'salidas06.required'=>'El campo salidas06 es obligatorio.',
            'salidas07.required'=>'El campo salidas07 es obligatorio.',
            'salidas08.required'=>'El campo salidas08 es obligatorio.',
            'salidas09.required'=>'El campo salidas09 es obligatorio.',
            'salidas10.required'=>'El campo salidas10 es obligatorio.',
            'salidas11.required'=>'El campo salidas11 es obligatorio.',
            'salidas12.required'=>'El campo salidas12 es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo actualizar la Inventario.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $saldos_producto->producto_id = Input::get('producto_id');
            $saldos_producto->bodega = Input::get('bodega');
            $saldos_producto->fecha_act = Input::get('fecha_act');
            $saldos_producto->existencia = Input::get('existencia');
            $saldos_producto->existencia_max = Input::get('existencia_max');
            $saldos_producto->existencia_min = Input::get('existencia_min');
            $saldos_producto->save();
            
            return response(array('mensaje'=>"Inventario Actualizada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_borrar($id){
        SaldosProducto::destroy($id);
        return response(array('mensaje'=>"Inventario Eliminada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
    }
    

    function generarDetallado(){
        $union = "ORDER BY 1 DESC";
        $postData = Input::all();
        $tipodoc = '';
        if($postData['tipo_documento']!='TO'){
            $tipodoc = " and pd.tipodoc = '".$postData['tipo_documento']."'";
        }
        $inicio = '';
        if($postData['inicio']!=''){
            $inicio = " and pd.created_at >= '".$postData['inicio']." 00:00:00'";
        }
        $fin = '';
        if($postData['fin']!=''){
            $fin = " and pd.created_at <= '".$postData['fin']." 23:59:59'";
        }
        if($postData['tipo']=='ING'){
            $prod_ing = "pdc.ingrediente_id = ".$postData['id'];
            $union = " 
            union all
            SELECT dd.*, concat(d.tipodoc,d.codprefijo,d.numdoc) as des,  i.descripcion , 'Ingrediente' as tipo, pi.cant as can, 0 as val
            FROM  {$this->conn}_detalle_documento dd 
            inner join {$this->conn}_documento d on(dd.documento_id = d.id)
            inner join {$this->conn}_producto_pedido pp on(d.pedido_id = pp.pedido_id and dd.producto_id = pp.producto_id)
            INNER join {$this->conn}_producto_pedido_ingrediente pi on(pi.producto_pedido_id = pp.id )
            inner join {$this->conn}_ingrediente i on(pi.ingrediente_id = i.id )
            where i.id = ".$postData['id']."
            union all
            SELECT dd.*, concat(tipodoc,codprefijo,numdoc) as des, i.descripcion, 'Adicional' as tipo, pa.cant as can, pa.valor as val
            FROM  {$this->conn}_detalle_documento dd 
            inner join {$this->conn}_documento d on(dd.documento_id = d.id)
            inner join {$this->conn}_producto_pedido pp on(d.pedido_id = pp.pedido_id and dd.producto_id = pp.producto_id)
            INNER join {$this->conn}_producto_pedido_adicional pa on(pa.producto_pedido_id = pp.id )
            inner join {$this->conn}_adicional a on(pa.adicional_id = a.id)
            inner join {$this->conn}_ingrediente i on(a.ingrediente_id = i.id )
            where i.id = ".$postData['id']."
            order by 1 ASC
            ";
        }
        else{
            $prod_ing = "pdc.producto_id = ".$postData['id'];
            $union = '
            order by 1 ASC';
        }

        $sql = ("
        SELECT pdc.*, concat(pd.tipodoc,pd.codprefijo,pd.numdoc) as des , pi.descripcion, pd.tipodoc as tipo, pdc.cantidad as can, pdc.valor
        FROM {$this->conn}_detalle_documento pdc
        JOIN {$this->conn}_documento as pd
        ON pdc.documento_id = pd.id 
        JOIN {$this->conn}_ingrediente as pi
        ON pdc.ingrediente_id = pi.id 
        WHERE $prod_ing $tipodoc $inicio $fin
            ".$union);

        $detallado = DB::select($sql);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML(\App\Util\PDF::InventarioDetallado($detallado, $postData['inicio'], $postData['fin'], $postData['tipo']))->setPaper('letter');
        return $pdf->stream();
    }

//    public function excelExport(){
//        $spreadsheet = new Spreadsheet();
//        $sheet = $spreadsheet->getActiveSheet();
//
//        $sheet->setCellValue('A1', 'Descripción');
//        $sheet->setCellValue('B1', 'Tipo');
//        $sheet->setCellValue('C1', 'Bodega');
//        $sheet->setCellValue('D1', 'Actualización');
//        $sheet->setCellValue('E1', 'Existencia');
//        $sheet->setCellValue('F1', 'Unidad');
//        $sheet->setCellValue('G1', 'Máxima');
//        $sheet->setCellValue('H1', 'Mínima');
//
//        $sheet->getStyle('A1:H1')->applyFromArray(['font' => ['bold' => true]]);
//        $sheet->getColumnDimension('A')->setWidth(50);
//        $sheet->getColumnDimension('B')->setWidth(12);
//        $sheet->getColumnDimension('C')->setWidth(10);
//        $sheet->getColumnDimension('D')->setWidth(15);
//        $sheet->getColumnDimension('E')->setWidth(12);
//        $sheet->getColumnDimension('F')->setWidth(10);
//        $sheet->getColumnDimension('G')->setWidth(10);
//        $sheet->getColumnDimension('H')->setWidth(10);
//
//        $saldos = SaldosProducto::all();
//        $row = 2;
//        foreach ($saldos as $saldo){
//            $tipo = 'Producto';
//            if($saldo->producto){
//                $des = $saldo->producto->descripcion;
//            }
//            elseif ($saldo->ingrediente){
//                $des = $saldo->ingrediente->descripcion;
//                $tipo = 'Ingrediente';
//            }
//            else{
//                continue;
//            }
//            $sheet->setCellValue('A'.$row, $des);
//            $sheet->setCellValue('B'.$row, $tipo);
//            $sheet->setCellValue('C'.$row, $saldo->bodega);
//            $sheet->setCellValue('D'.$row, date('d/m/Y', strtotime($saldo->updated_at)));
//            $sheet->setCellValue('E'.$row, $saldo->existencia);
//            $sheet->setCellValue('F'.$row, $tipo == 'Ingrediente'? ' '.$saldo->ingrediente->unidad : '');
//            $sheet->setCellValue('G'.$row, $saldo->existencia_max);
//            $sheet->setCellValue('H'.$row, $saldo->existencia_min);
//            $row++;
//        }
//
//        $spreadsheet->getActiveSheet()->getStyle('E2:E'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//        $spreadsheet->getActiveSheet()->getStyle('G2:H'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//
//        $writer = new Xlsx($spreadsheet);
//        $filename = "Inventario_".date("Y-m-d").".xlsx";
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment; filename="'.$filename.'"');
//        $writer->save("php://output");
//    }

    public function excelContent(){

        $data = [];

        $saldos = SaldosProducto::all();
        $row = 2;
        foreach ($saldos as $saldo){
            $tipo = 'Producto';
            if($saldo->producto){
                $des = $saldo->producto->descripcion;
            }
            elseif ($saldo->ingrediente){
                $des = $saldo->ingrediente->descripcion;
                $tipo = 'Ingrediente';
            }
            else{
                continue;
            }
            $rowData = [
                $des, $tipo, $saldo->bodega, date('d/m/Y', strtotime($saldo->updated_at)),
                $saldo->existencia, $tipo == 'Ingrediente'? ' '.$saldo->ingrediente->unidad : '',
                $saldo->existencia_max, $saldo->existencia_min
            ];
            $data[] = $rowData;
            $row++;
        }
        return response()->json(array('code'=>200,'msg'=>$data));
    }

    public function pos(){
        $sql = DB::select("SELECT 'P' tipo, p.descripcion,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
          AND date_format(d.created_at, '%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradant,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
           AND date_format(d.created_at, '%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
           AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) as salidant1,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0)  AS  salidant2,
        coalesce(s.existencia,0) total,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
           AND date_format(d.created_at, '%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
           AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) as salidas,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0)  AS  salida2,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
          AND date_format(d.created_at, '%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradas1
        FROM pizza_saldos_producto  s
        inner join pizza_producto p ON(s.producto_id = p.id)
        WHERE s.producto_id is not NULL
        UNION ALL
        SELECT 'I' tipo, i.descripcion,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradant,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) salidant1,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0) SALIDAnt2 ,
        coalesce(s.existencia,0) total,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
            AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) salidas,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0) SALIDAS2 ,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradas1
        
        FROM pizza_saldos_producto  s
        inner join pizza_ingrediente i ON(s.ingrediente_id = i.id)
        WHERE s.ingrediente_id is not NULL ");
        //return response()->json(array('code'=>200,'msg'=>$sql));

        return \App\Util\POS::inventarioPos(
            app('App\Http\Controllers\ConfigController')->first(),
            $sql
        );
    }
    
    //<
    //>

}