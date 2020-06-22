<?php

namespace App\Http\Controllers;
use App\Combo;
use App\ComboProducto;
use App\ProductoPedido;
use App\Producto;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ComboController extends Controller
{

    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
    }*/
    
    public function vistaLista(){
        return view('combo.listar')->with("combo_lista", $this->todos());
    }
    public function vistaLista2(){
        return view('combo.listar')->with("combo_lista",$this->paginar(Input::all()));
    }

    public function vistaCrear(){
        $productos = Producto::orderBy('tipo_producto_id', 'asc')->with('tipo_producto')->get();
        $productos_ = [];
        foreach($productos as $producto){
            if($producto->tipo_producto->estado == 1 && $producto->estado == 1){
                $productos_[] = $producto;
            }
        }
        return view('combo.crear')->with('producto_lista', $productos_);
    }

    public function vistaEditar($id){
        return view('combo.editar')->
        with("combo", $this->encontrar($id))->
        with('producto_lista',app('App\Http\Controllers\ProductoController')->todos());
    }

    public function menu(){
        $combos = Combo::with('comboProductos.producto')->orderBy('updated_at', 'asc')->get();
        return $combos;
    }
    
    public function orden(){
        $postData = Input::all();
        $orden = Input::get('orden');
        $now = date("Y-m-d H:i:s");
        $sql = '';
        $i = 0;
        $sql_arr = [];
        foreach($orden as $o){
            $date = date('Y-m-d H:i:s',strtotime('+'.$i.' seconds',strtotime($now)));
            $sql_arr[] = ['id'=>$o, 'value'=> $date];
            $i++;
        }
        foreach ($sql_arr as $key) {
            Combo::where('id', $key['id'])->update(['updated_at'=>$key['value']]);
        }
        return response()->json(['status'=> 200, 'sql'=> $sql_arr]);
    }

    public function todos() {
        return Combo::all();
    }
    
    public function encontrar($id) {
        return Combo::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        $rules = array(
            'producto_id' => 'required',
            'producto_hijo_id' => 'required',
            'cantidad' => 'required',
            'unidad' => 'required',
            'precio' => 'required'
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'producto_hijo_id.required'=>'El campo producto_hijo_id es obligatorio.',
            'cantidad.required'=>'El campo cantidad es obligatorio.',
            'unidad.required'=>'El campo unidad es obligatorio.',
            'precio.required'=>'El campo precio es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);

        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo crear la combo.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $combo = new Combo;
            $combo->producto_id = Input::get('producto_id');
            $combo->producto_hijo_id = Input::get('producto_hijo_id');
            $combo->cantidad = Input::get('cantidad');
            $combo->unidad = Input::get('unidad');
            $combo->precio = Input::get('precio');
            $combo->imagen = Input::get('imagen');
            $combo->save();
            return back()->with('status', ["success"=>"combo Agregada."]);
        }
    }

    public function crearCompleto(){

        $combo = Combo::where('nombre', Input::get('nombre'))->get();
        if(count($combo)>0){
            return response(array('status'=>201,'message'=>'Ya exite un combo con ese nombre'), 200)
                ->header('Content-Type', 'application/json');
        }

        $combo = new Combo;
        $combo->nombre = Input::get('nombre');
        $combo->precio = Input::get('precio');
        $combo->imagen = Input::get('imagen');
        $combo->save();

        foreach(Input::get('productos') as $producto){
            $comboProducto = new ComboProducto;
            $comboProducto->combo_id = $combo->id;
            $comboProducto->producto_id = $producto['producto'];
            $comboProducto->cantidad = $producto['cantidad'];
            $comboProducto->tamano = $producto['tamano'];
            $comboProducto->valor = $producto['valor'];
            $comboProducto->save();
        }

        return response(array('status'=>200), 200)
            ->header('Content-Type', 'application/json');

    }

    public function editarCombo($id){

        $combo = Combo::find($id);
        $combo->nombre = Input::get('nombre');
        $combo->precio = Input::get('precio');
        $combo->save();

        return response(array('status'=>200), 200)
            ->header('Content-Type', 'application/json');

    }
    
    public function editar(){
        $postData = Input::all();
        $combo = Combo::find(Input::get('id'));
        
        $rules = array(
            'producto_id' => 'required',
            'producto_hijo_id' => 'required',
            'cantidad' => 'required',
            'unidad' => 'required',
            'precio' => 'required',
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'producto_hijo_id.required'=>'El campo producto_hijo_id es obligatorio.',
            'cantidad.required'=>'El campo cantidad es obligatorio.',
            'unidad.required'=>'El campo unidad es obligatorio.',
            'precio.required'=>'El campo precio es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo modificar la combo.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $combo->producto_id = Input::get('producto_id');
            $combo->producto_hijo_id = Input::get('producto_hijo_id');
            $combo->cantidad = Input::get('cantidad');
            $combo->unidad = Input::get('unidad');
            $combo->precio = Input::get('precio');
            $combo->imagen = Input::get('imagen');
            $combo->save();
            return back()->with('status', ["success"=>"combo actualizada."]);
        }
    }
    
    public function borrar() {
        Combo::destroy(Input::get('id'));
        $post = Input::get('_modal')!=null;
        $status = ["success" => "combo borrada."];
        return back()->with('status', $status);
        
    }

    public function paginar_modal() {
        return $this->paginar(Input::all());
    }

    public function recalcular($id) {
        $combo = Combo::find($id);
        $valor = 0;
        foreach ($combo->comboProductos as $producto){
            $valor += $producto->cantidad * $producto->valor;
        }
        $combo->precio = $valor;
        $combo->save();
        return $combo;
    }
    
    public function paginar($input) {
        
        $buscar = isset($input["buscar"])?$input["buscar"]:"";
        $ordenar_por = isset($input["ordenar_por"])?$input["ordenar_por"]:"";
        $sentido = isset($input["sentido"])?$input["sentido"]:"";
        $por_pagina = isset($input["por_pagina"])?$input["por_pagina"]:"";
        
        return $this->paginar_($buscar, $ordenar_por, $sentido, $por_pagina);
    }
    
    public function paginar_($buscar, $ordenar_por, $sentido, $por_pagina) {
        if($ordenar_por==""||$ordenar_por==null){
            return Combo::Where("id","like", "%$buscar%")->paginate($por_pagina);
        }
        return Combo::Where("id","like", "%$buscar%")->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
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
            'producto_hijo_id' => 'required',
            'cantidad' => 'required',
            'unidad' => 'required',
            'precio' => 'required'
        );
        
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'producto_hijo_id.required'=>'El campo producto_hijo_id es obligatorio.',
            'cantidad.required'=>'El campo cantidad es obligatorio.',
            'unidad.required'=>'El campo unidad es obligatorio.',
            'precio.required'=>'El campo precio es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo crear la combo.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $combo = new Combo;
            $combo->producto_id = Input::get('producto_id');
            $combo->producto_hijo_id = Input::get('producto_hijo_id');
            $combo->cantidad = Input::get('cantidad');
            $combo->unidad = Input::get('unidad');
            $combo->precio = Input::get('precio');
            $combo->imagen = Input::get('imagen');
            $combo->save();
            
            return response(array('mensaje'=>"combo Agregada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_editar($id){
        $postData = Input::all();
        $combo = Combo::find($id);
        
        $rules = array(
            'producto_id' => 'required',
            'producto_hijo_id' => 'required',
            'cantidad' => 'required',
            'unidad' => 'required',
            'precio' => 'required',
        );
        $messages = array(
            'producto_id.required'=>'El campo producto_id es obligatorio.',
            'producto_hijo_id.required'=>'El campo producto_hijo_id es obligatorio.',
            'cantidad.required'=>'El campo cantidad es obligatorio.',
            'unidad.required'=>'El campo unidad es obligatorio.',
            'precio.required'=>'El campo precio es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo actualizar la combo.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $combo->producto_id = Input::get('producto_id');
            $combo->producto_hijo_id = Input::get('producto_hijo_id');
            $combo->cantidad = Input::get('cantidad');
            $combo->unidad = Input::get('unidad');
            $combo->precio = Input::get('precio');
            $combo->imagen = Input::get('imagen');
            $combo->save();
            
            return response(array('mensaje'=>"combo Actualizada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_borrar($id){
        Combo::destroy($id);
        return response(array('mensaje'=>"combo Eliminada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
    }


    public function patchEstado($id, $estado){
        $combo = Combo::find($id);
        $combo->estado = $estado;
        $combo->save();
        return 1;
    }
    public function subirImagen(Request $request){
        $file = $request->file('archivo1');
        $extension = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extension;
        $file->move(base_path() . env('APP_PUBLIC_FOLDER').'images/combo/', $imageName);
        return $imageName;
    }
    public function editarImagen($id, Request $request){
        $file = $request->file('archivo1');
        $extension = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extension;
        $file->move(base_path() . env('APP_PUBLIC_FOLDER').'images/combo/', $imageName);
        $combo = Combo::find($id);
        $combo->imagen = $imageName;
        $combo->save();
        return $imageName;
    }

    public function borrarPost($id){
        $combo = Combo::find($id);
        if (ProductoPedido::
            join('pedido', 'pedido.id', '=', 'producto_pedido.pedido_id')
                ->where('pedido.estado', 1)
                ->where("combo", "like", "%$combo->nombre%")
                ->count()>0){
            return response()->json(array('code'=>400,'msg'=>'No se pudo borrar. El combo está asociado a un pedido activo.'));
        }
        else{
            Combo::destroy($id);
            return response()->json(array('code'=>200,'msg'=>'El combo fue eliminado.'));
        }

    }
    
    //<
    //>

}