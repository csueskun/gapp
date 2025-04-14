<?php

namespace App\Http\Controllers;
use App\ProductoSabor;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoSaborController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return ProductoSabor::all();
    }
    
    public function buscar($id) {
        return ProductoSabor::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'producto_id' => '',
                'sabor_id' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_sabor/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto_sabor = new ProductoSabor;
            $producto_sabor->producto_id = Input::get('producto_id');
            $producto_sabor->sabor_id = Input::get('sabor_id');
            $producto_sabor->save();
        
            return Redirect::to('producto_sabor/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto_sabor = ProductoSabor::find(Input::get('id'));
        
        $rules = array(
                'producto_id' => '',
                'sabor_id' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto_sabor/editar/'.$producto_sabor->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto_sabor->producto_id = Input::get('producto_id');
            $producto_sabor->sabor_id = Input::get('sabor_id');
            $producto_sabor->save();
        
            return Redirect::to('producto_sabor/editar/'.$producto_sabor->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoSabor::destroy(Input::get('id'));
        return Redirect::to('producto_sabor/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}