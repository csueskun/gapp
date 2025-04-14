<?php

namespace App\Http\Controllers;
use App\Adicional;
use App\Producto;
use App\ProductoPedido;
use App\ProductoPedidoIngrediente;
use App\ProductoPedidoAdicional;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoPedidoController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return ProductoPedido::all();
    }
    
    public function buscar($id) {
        return ProductoPedido::find($id);
    }
    
    public function buscarPorPedido($pedido_id) {
        return ProductoPedido::where("pedido_id", $pedido_id)->with("producto.tipo_producto")->get();
    }
    
    public function actualizarComanda($pedido, $comanda){
        ProductoPedido::where('pedido_id',$pedido)->where('comanda',0)->update(['comanda'=>$comanda]);
    }
    
    public function pendientesComanda($pedido){
        return ProductoPedido::where('pedido_id',$pedido)->where('comanda',0)->count();
    }
    public function guardar($datos) {
        if(isset($datos->id)){
            $producto_pedido = ProductoPedido::find($datos->id);
        }
        else{
            $producto_pedido = new ProductoPedido;
            $producto_pedido->obs = json_encode($datos->obs);
        }
        $producto_pedido->pedido_id = $datos->pedido_id;
        $producto_pedido->producto_id = $datos->producto_id;
        $producto_pedido->valor = $datos->valor;
        $producto_pedido->cant = $datos->cant;
        if(isset($datos->combo) && $datos->combo && $datos->combo != null){
            $producto_pedido->combo = json_encode($datos->combo);
        }
        $producto_pedido->total = isset($datos->total)?$datos->total:0;
        $producto_pedido->save();
        return $producto_pedido;
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'pedido_id' => 'required',
                'producto_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required',
                'obs' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto_pedido = new ProductoPedido;
            $producto_pedido->pedido_id = Input::get('pedido_id');
            $producto_pedido->producto_id = Input::get('producto_id');
            $producto_pedido->cant = Input::get('cant');
            $producto_pedido->valor = Input::get('valor');
            $producto_pedido->total = Input::get('total');
            $producto_pedido->obs = Input::get('obs');
            $producto_pedido->save();
        
            return Redirect::to('producto_pedido/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto_pedido = ProductoPedido::find(Input::get('id'));
        
        $rules = array(
                'pedido_id' => 'required',
                'producto_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required',
                'obs' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido/editar/'.$producto_pedido->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto_pedido->pedido_id = Input::get('pedido_id');
            $producto_pedido->producto_id = Input::get('producto_id');
            $producto_pedido->cant = Input::get('cant');
            $producto_pedido->valor = Input::get('valor');
            $producto_pedido->total = Input::get('total');
            $producto_pedido->obs = Input::get('obs');
            $producto_pedido->save();
        
            return Redirect::to('producto_pedido/editar/'.$producto_pedido->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoPedido::destroy(Input::get('id'));
        return Redirect::to('producto_pedido/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    
    public function borrarPorId($id) {
        $ppedido = ProductoPedido::find($id);
        $pedido_id = $ppedido->pedido_id;
        ProductoPedido::destroy($id);
        app('App\Http\Controllers\PedidoController')->actualizarValor($pedido_id);
    }

    public function preparado($id) {
        $ppedido = ProductoPedido::find($id);
        if($ppedido->preparado == '' || $ppedido->entregado == null ){
            $ppedido->preparado = date("Y-m-d H:i:s");;
        }
        else{
            $ppedido->preparado = null;
        }
        $ppedido->save();
    }

    public function borrarPorCombo($combo) {
        return ProductoPedido::where("combo", "like", "%$combo%")->delete();
    }
    public function getFull($id) {
        $pp = ProductoPedido::find($id);
        if($pp){
            try {
                $obs = json_decode($pp->obs);
                $tamano = $obs->tamano;
            } catch (Exception $e) {
                $tamano = 'Error';
            }
            $producto = DB::select(
                "select p.*, tp.descripcion as tipo_producto from pizza_producto p
                join pizza_tipo_producto as tp
                on tp.id = p.tipo_producto_id 
                where p.id = $pp->producto_id"
            );
            $ingredientes = DB::select(
                "select i.*, ppi.cant from pizza_producto_pedido_ingrediente ppi 
                join pizza_ingrediente as i
                on i.id = ppi.ingrediente_id
                where ppi.producto_pedido_id = $id"
            );
            $adicionales = DB::select(
                "select i.*, a.id as adicional, a.cantidad as cantidad from pizza_producto p
                join pizza_tipo_producto as tp
                on tp.id = p.tipo_producto_id
                join pizza_adicional as a
                on a.tipo_producto_id = tp.id
                join pizza_ingrediente as i
                on i.id = a.ingrediente_id
                where p.id = $pp->producto_id and a.tamano = '$tamano'"
            );
            return response()->json(array('code'=>200, 'producto_pedido'=>$pp, 'producto'=>$producto, 'ingredientes'=>$ingredientes, 'adicionales'=>$adicionales));
        }
        else{
            return response()->json(array('code'=>201));
        }
    }
    public function patchObs($id){
        $new_cant = intval(Input::get('ppcantidad'));
        $pp = ProductoPedido::find($id);
        $pp->obs = Input::get('data');
        $pp->obs = json_encode($pp->obs);
        $pp->total = $new_cant * $pp->total/$pp->cant;
        $pp->cant = $new_cant;
        $pp->save();

        if(Input::get('adicional')!=0){
            $adicional = new ProductoPedidoAdicional;
            $adicional->producto_pedido_id = $id;
            $adicional->adicional_id = Input::get('adicional');
            $adicional->cant = is_numeric(Input::get('cantidad'))?intval(Input::get('cantidad')):0;
            $adicional->valor = 0;
            $adicional->total = 0;
            $adicional->cambio = 1;
            $adicional->save();
        }

        return response()->json(array('code'=>200, 'msg'=>'Guardado.'));
    }
}