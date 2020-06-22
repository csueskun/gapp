<?php

namespace App\Http\Controllers;
use App\Adicional;
use App\TipoProducto;
use App\Producto;
use App\Combo;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class TipoProductoController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return TipoProducto::orderBy('updated_at', 'asc')->with("adicionales")->get();
    }
    public function todosActivos() {
        return TipoProducto::where('estado',1)->orderBy('updated_at', 'asc')->with("adicionales")->get();
    }
    public function mostrarMenu() {
        $grupos = [];
        $tipos = TipoProducto::where('estado',1)->orderBy('updated_at', 'asc')->with("productos")->get();
        foreach ($tipos as $tipo){
            $grupos = [];
            foreach ($tipo->productos as $producto){
                if($producto->estado != 1){
                    continue;
                }
                if($producto->grupo==''||$producto->grupo==null){
                    $producto->grupo = 'SIN GRUPO';
                }
                $yet = true;
                if(count($grupos)>0){
                    foreach ($grupos as $grupo){
                        if($grupo->nombre == $producto->grupo){
                            array_push($grupo->productos,$producto);
                            $yet = false;
                        }
                    }
                }
                if($yet){
                    $nuevo_grupo = new \stdClass;
                    $nuevo_grupo->nombre = $producto->grupo;
                    $nuevo_grupo->productos = array($producto);
                    array_push($grupos,$nuevo_grupo);
                }
            }
            $tipo->grupos = $grupos;
            $tipo->adicionalesg = $this->adicionalesToGroup($tipo->adicionales);
            $tipo->tamanos = json_decode($tipo->tamanos);
//            unset($tipo->adicionales);
        }
        return $tipos;
    }

    public function adicionalesToGroup($adicionales){

        $agrupados = [];
        $adicionales2 = [];
        foreach ($adicionales as $adicional){
            $adicional2 = new \stdClass;
            $adicional2->pivot = new \stdClass;
            $adicional2->id = $adicional->id;
            $adicional2->grupo = $adicional->grupo;
            $adicional2->imagen = $adicional->imagen;
            $adicional2->unidad = $adicional->unidad;
            $adicional2->descripcion = $adicional->descripcion;
            $adicional2->pivot->tamano = $adicional->pivot->tamano;
            $adicional2->pivot->id = $adicional->pivot->id;
            $adicional2->pivot->valor = $adicional->pivot->valor;
            $adicional2->pivot->cantidad = $adicional->pivot->cantidad;
            if($adicional2->grupo == null || $adicional2->grupo == ''){
                $adicional2->grupo = 'SIN GRUPO';
            }
            $adicionales2[] = $adicional2;
        }
        usort($adicionales2, function($a, $b){
            $cmp = strcmp($a->grupo, $b->grupo);
            if($cmp == 0){
                strcmp($a->descripcion, $b->descripcion);
            }
            return $cmp;
        }
        );
        $adicionales = $adicionales2;
        foreach ($adicionales as $adicional){

            $grupo = $adicional->grupo;
            if($grupo == null || $grupo == ''){
                $grupo = 'SIN GRUPO';
            }
            if(!isset($agrupados[$grupo])){
                $agrupados[$grupo] = [];
            }
            $agrupados[$grupo][] = $adicional;
        }


        return $agrupados;
    }



    public function todosAZ() {
        return TipoProducto::orderBy('descripcion', 'asc')->get();
    }
    
    public function buscar($id) {
        return TipoProducto::find($id);
    }
    

    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'codigo' => '',
                'descripcion' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('tipo_producto/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $tipo_producto = new TipoProducto;
            $tipo_producto->codigo = Input::get('codigo');
            $tipo_producto->descripcion = Input::get('descripcion');
            $tipo_producto->fracciones = Input::get("fracciones");
            $tipo_producto->tamanos = Input::get("tamanos");
            $tipo_producto->impresora = Input::get("impresora");
            $tipo_producto->aplica_tamanos = Input::get("aplica_tamanos");
            $tipo_producto->aplica_sabores = Input::get("aplica_sabores");
            $tipo_producto->aplica_ingredientes = Input::get("aplica_ingredientes");
            $tipo_producto->valor_editable = Input::get("valor_editable");
            $tipo_producto->save();
        
            return Redirect::to('tipo_producto/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
//            ->with('tipo_producto', $tipo_producto);
        }
    }
    public function crearModal(){
        $postData = Input::all();
        $rules = array(
                'descripcion' => 'required|unique:tipo_producto'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $tipo_producto = new TipoProducto;
            $tipo_producto->codigo = Input::get('codigo');
            $tipo_producto->descripcion = Input::get('descripcion');
            $tipo_producto->fracciones = Input::get("fracciones");
            $tipo_producto->tamanos = Input::get("tamanos");
            $tipo_producto->impresora = Input::get("impresora");
            $tipo_producto->aplica_tamanos = Input::get("aplica_tamanos");
            $tipo_producto->aplica_sabores = Input::get("aplica_sabores");
            $tipo_producto->aplica_ingredientes = Input::get("aplica_ingredientes");
            $tipo_producto->valor_editable = Input::get("valor_editable");
            $tipo_producto->save();
            return response()->json($tipo_producto);
        }
    }

    public function editar(){
        $postData = Input::all();
        $tipo_producto = TipoProducto::find(Input::get('id'));
        
        $rules = array(
                'codigo' => '',
                'descripcion' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('tipo_producto/editar/'.$tipo_producto->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $tipo_producto->codigo = Input::get('codigo');
            $tipo_producto->descripcion = Input::get('descripcion');
            $tipo_producto->fracciones = Input::get("fracciones");
            $tipo_producto->tamanos = Input::get("tamanos");
            $tipo_producto->impresora = Input::get("impresora");
            $tipo_producto->aplica_tamanos = Input::get("aplica_tamanos");
            $tipo_producto->aplica_sabores = Input::get("aplica_sabores");
            $tipo_producto->aplica_ingredientes = Input::get("aplica_ingredientes");
            $tipo_producto->valor_editable = Input::get("valor_editable");
            $tipo_producto->save();
        
            return Redirect::to('tipo_producto/editar/'.$tipo_producto->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }

    public function ordenView(){
        $tipos = TipoProducto::where('estado', 1)->orderBy('updated_at', 'asc')->get();
        $combos = Combo::with('comboProductos.producto')->orderBy('updated_at', 'asc')->get();
        return view("varios.orden")->with('tipos', $tipos)->with('combos', $combos);
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
            TipoProducto::where('id', $key['id'])->update(['updated_at'=>$key['value']]);
        }
        return response()->json(['status'=> 200, 'sql'=> $sql_arr]);
    }

    public function borrar() {
        $tipo = $this->buscar(Input::get('id'));
        if(count($tipo->productos)>0){
            return Redirect::to('tipo_producto/listar')
                ->with('status', ["danger" => "No se puede borrar el tipo de producto porque tiene productos asociados."]);
        }
        TipoProducto::destroy(Input::get('id'));
        return Redirect::to('tipo_producto/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

    public function patchEstado($id, $estado){
        $tipo_producto = TipoProducto::find($id);
        $tipo_producto->estado = $estado;
        $tipo_producto->save();
        return 1;
    }

    public function mesaMenu(){
        return DB::getDefaultConnection();
    }


}