<?php

namespace App\Http\Controllers;
use App\ProductoPedidoAdicional;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoPedidoAdicionalController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return ProductoPedidoAdicional::all();
    }
    
    public function buscar($id) {
        return ProductoPedidoAdicional::find($id);
    }
    
    public function buscarPorProductoPedido($producto_pedido_id) {
        return ProductoPedidoAdicional::where("producto_pedido_id", $producto_pedido_id)->with("adicional.ingrediente")->get();
    }
    
    public function guardar($datos) {
        $producto_pedido_adicional = new ProductoPedidoAdicional;
        $producto_pedido_adicional->producto_pedido_id = $datos->producto_pedido_id;
        $producto_pedido_adicional->adicional_id = $datos->adicional_id;
        $producto_pedido_adicional->valor = $datos->valor;
        $producto_pedido_adicional->total = $datos->total;
        $producto_pedido_adicional->cant = $datos->cant;
        $producto_pedido_adicional->save();
        return $producto_pedido_adicional;
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'producto_pedido_id' => 'required',
                'adicional_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido_adicional/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto_pedido_adicional = new ProductoPedidoAdicional;
            $producto_pedido_adicional->producto_pedido_id = Input::get('producto_pedido_id');
            $producto_pedido_adicional->adicional_id = Input::get('adicional_id');
            $producto_pedido_adicional->cant = Input::get('cant');
            $producto_pedido_adicional->valor = Input::get('valor');
            $producto_pedido_adicional->total = Input::get('total');
            $producto_pedido_adicional->save();
        
            return Redirect::to('producto_pedido_adicional/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto_pedido_adicional = ProductoPedidoAdicional::find(Input::get('id'));
        
        $rules = array(
                'producto_pedido_id' => 'required',
                'adicional_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido_adicional/editar/'.$producto_pedido_adicional->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto_pedido_adicional->producto_pedido_id = Input::get('producto_pedido_id');
            $producto_pedido_adicional->adicional_id = Input::get('adicional_id');
            $producto_pedido_adicional->cant = Input::get('cant');
            $producto_pedido_adicional->valor = Input::get('valor');
            $producto_pedido_adicional->total = Input::get('total');
            $producto_pedido_adicional->save();
        
            return Redirect::to('producto_pedido_adicional/editar/'.$producto_pedido_adicional->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoPedidoAdicional::destroy(Input::get('id'));
        return Redirect::to('producto_pedido_adicional/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}