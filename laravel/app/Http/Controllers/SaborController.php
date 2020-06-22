<?php

namespace App\Http\Controllers;
use App\Sabor;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use Config;

class SaborController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return Sabor::all();
    }
    public function todosAZ() {
        return Sabor::orderBy('descripcion', 'asc')->get();
    }
    
    public function buscar($id) {
        return Sabor::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('sabor/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $sabor = new Sabor;
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return Redirect::to('sabor/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function crearModal(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => 'required|unique:sabor'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $sabor = new Sabor;
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return response()->json($sabor);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $sabor = Sabor::find(Input::get('id'));
        
        $rules = array(
                'descripcion' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('sabor/editar/'.$sabor->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return Redirect::to('sabor/editar/'.$sabor->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        Sabor::destroy(Input::get('id'));
        return Redirect::to('sabor/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}