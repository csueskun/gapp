<?php

namespace App\Http\Controllers;
use App\ProductoPedidoDocumento;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoPedidoDocumentoController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return ProductoPedidoDocumento::all();
    }
    
    public function encontrar($id) {
        return ProductoPedidoDocumento::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'producto_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required',
                'producto_pedido_id' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido_documento/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto_pedido_documento = new ProductoPedidoDocumento;
            $producto_pedido_documento->producto_id = Input::get('producto_id');
            $producto_pedido_documento->cant = Input::get('cant');
            $producto_pedido_documento->valor = Input::get('valor');
            $producto_pedido_documento->total = Input::get('total');
            $producto_pedido_documento->producto_pedido_id = Input::get('producto_pedido_id');
            $producto_pedido_documento->save();
        
            return Redirect::to('producto_pedido_documento/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto_pedido_documento = ProductoPedidoDocumento::find(Input::get('id'));
        
        $rules = array(
                'producto_id' => 'required',
                'cant' => 'required',
                'valor' => 'required',
                'total' => 'required',
                'producto_pedido_id' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_pedido_documento/editar/'.$producto_pedido_documento->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto_pedido_documento->producto_id = Input::get('producto_id');
            $producto_pedido_documento->cant = Input::get('cant');
            $producto_pedido_documento->valor = Input::get('valor');
            $producto_pedido_documento->total = Input::get('total');
            $producto_pedido_documento->producto_pedido_id = Input::get('producto_pedido_id');
            $producto_pedido_documento->save();
        
            return Redirect::to('producto_pedido_documento/editar/'.$producto_pedido_documento->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoPedidoDocumento::destroy(Input::get('id'));
        return Redirect::to('producto_pedido_documento/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}