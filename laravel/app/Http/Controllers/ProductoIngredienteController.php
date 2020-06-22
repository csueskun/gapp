<?php

namespace App\Http\Controllers;
use App\ProductoIngrediente;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoIngredienteController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return ProductoIngrediente::all();
    }
    
    public function buscar($id) {
        return ProductoIngrediente::find($id);
    }
    
    public function buscarPorProducto($id) {
        return ProductoIngrediente::where('producto_id', $id)->get();
    }
    
    public function buscarPorProductoIngrediente($producto_id,$ingrediente_id) {
        return ProductoIngrediente::where('producto_id', $producto_id)->where('ingrediente_id', $ingrediente_id)->get();
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'producto_id' => 'required',
                'ingrediente_id' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_ingrediente/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto_ingrediente = new ProductoIngrediente;
            $producto_ingrediente->producto_id = Input::get('producto_id');
            $producto_ingrediente->ingrediente_id = Input::get('ingrediente_id');
            $producto_ingrediente->save();
        
            return Redirect::to('producto_ingrediente/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto_ingrediente = ProductoIngrediente::find(Input::get('id'));
        
        $rules = array(
                'producto_id' => 'required',
                'ingrediente_id' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_ingrediente/editar/'.$producto_ingrediente->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto_ingrediente->producto_id = Input::get('producto_id');
            $producto_ingrediente->ingrediente_id = Input::get('ingrediente_id');
            $producto_ingrediente->save();
        
            return Redirect::to('producto_ingrediente/editar/'.$producto_ingrediente->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoIngrediente::destroy(Input::get('id'));
        return Redirect::to('producto_ingrediente/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    public function getIngredientes(){
        $params = Input::get('params', '{}');
        $params = json_decode($params, true);
        $ingredientes = ProductoIngrediente::where($params)
        ->with('ingrediente')
        ->get();
        return $ingredientes;
    }

}