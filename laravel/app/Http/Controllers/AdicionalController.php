<?php

namespace App\Http\Controllers;
use App\Adicional;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class AdicionalController extends Controller
{
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
    }*/

    public function todos() {
        return Adicional::all();
    }
    
    public function buscar($id) {
        return Adicional::find($id);
    }
    public function buscarPorProducto($id) {
        return Adicional::with('ingrediente')->where("producto_id",$id)->get();
    }
    public function buscarPorTipoProducto($id) {
        return Adicional::with('ingrediente')->where("tipo_producto_id",$id)->get();
    }
    
    public function guardar(){
//        die($data);
        $data = Input::get('data');
//        $data = json_decode($data);
        $data = json_decode(json_encode($data), FALSE);
        $ingredientes_adicionales = [];
        $tipo_producto_id = 0;
        foreach($data as $adicional_){
            $adicional = Adicional::where("tipo_producto_id", $adicional_->tipo_producto_id)->where("ingrediente_id", $adicional_->ingrediente_id)->where("tamano", $adicional_->tamano)->first();
            if($adicional==null){
                $adicional = new Adicional;
                $adicional->tipo_producto_id = $adicional_->tipo_producto_id;
                $adicional->descripcion = $adicional_->descripcion;
                $adicional->ingrediente_id = $adicional_->ingrediente_id;
                $adicional->tamano = $adicional_->tamano;
            }
            $adicional->valor = $adicional_->valor;
            $adicional->cantidad = $adicional_->cantidad;
            $adicional->save();
            $ingredientes_adicionales[] = $adicional->id;
            $tipo_producto_id = $adicional->tipo_producto_id;
        }
        Adicional::where("tipo_producto_id", $adicional->tipo_producto_id)->whereNotIn("id", $ingredientes_adicionales)->delete();
        Session::set('status', ["success"=>"Adicionales Guardados."]);
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'valor' => 'required',
                'producto_id' => '',
                'tipo_producto_id' => '',
                'ingrediente_id' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('adicional/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $adicional = new Adicional;
            $adicional->codigo = Input::get('codigo');
            $adicional->descripcion = Input::get('descripcion');
            $adicional->valor = Input::get('valor');
            $adicional->producto_id = Input::get('producto_id');
            $adicional->tipo_producto_id = Input::get('tipo_producto_id');
            $adicional->ingrediente_id = Input::get('ingrediente_id');
            $adicional->save();
        
            return Redirect::to('adicional/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $adicional = Adicional::find(Input::get('id'));
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'valor' => 'required',
                'producto_id' => '',
                'tipo_producto_id' => '',
                'ingrediente_id' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('adicional/editar/'.$adicional->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $adicional->codigo = Input::get('codigo');
            $adicional->descripcion = Input::get('descripcion');
            $adicional->valor = Input::get('valor');
            $adicional->producto_id = Input::get('producto_id');
            $adicional->tipo_producto_id = Input::get('tipo_producto_id');
            $adicional->ingrediente_id = Input::get('ingrediente_id');
            $adicional->save();
        
            return Redirect::to('adicional/editar/'.$adicional->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        Adicional::destroy(Input::get('id'));
        return Redirect::to('adicional/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    
    public function getAdicionales(){
        $params = Input::get('params', '{}');
        $params = json_decode($params, true);
        $adicionales = Adicional::where($params)
        ->with('ingrediente')
        ->get();
        return $adicionales;
    }

}