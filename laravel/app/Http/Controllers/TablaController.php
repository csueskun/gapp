<?php

namespace App\Http\Controllers;
use App\Tabla;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class TablaController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function vistaLista(){
        return view('tabla.listar')->with("tabla_lista",$this->paginar(Input::all()));
    }

    public function vistaCrear(){
        return view('tabla.crear');
    }

    public function vistaEditar($id){
        return view('tabla.editar')->with("tabla", $this->encontrar($id));
    }


    public function todos() {
        return Tabla::all();
    }
    
    public function encontrar($id) {
        return Tabla::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        $rules = array(
            'codigo' => 'required',
            'descripcion' => 'required',
            'tabla' => 'required',
            'valor' => '',
            'valor_alf' => ''
        );
        $messages = array(
            'codigo.required'=>'El campo codigo es obligatorio.',
            'descripcion.required'=>'El campo descripcion es obligatorio.',
            'tabla.required'=>'El campo tabla es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);

        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo crear la tabla.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $tabla = new Tabla;
            $tabla->codigo = Input::get('codigo');
            $tabla->descripcion = Input::get('descripcion');
            $tabla->tabla = Input::get('tabla');
            $tabla->valor = Input::get('valor');
            $tabla->valor_alf = Input::get('valor_alf');
            $tabla->save();
            return back()->with('status', ["success"=>"tabla Agregada."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $tabla = Tabla::find(Input::get('id'));
        
        $rules = array(
            'codigo' => 'required',
            'descripcion' => 'required',
            'tabla' => 'required',
            'valor' => '',
            'valor_alf' => '',
        );
        $messages = array(
            'codigo.required'=>'El campo codigo es obligatorio.',
            'descripcion.required'=>'El campo descripcion es obligatorio.',
            'tabla.required'=>'El campo tabla es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo modificar la tabla.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $tabla->codigo = Input::get('codigo');
            $tabla->descripcion = Input::get('descripcion');
            $tabla->tabla = Input::get('tabla');
            $tabla->valor = Input::get('valor');
            $tabla->valor_alf = Input::get('valor_alf');
            $tabla->save();
            return back()->with('status', ["success"=>"tabla actualizada."]);
        }
    }
    
    public function borrar() {
        Tabla::destroy(Input::get('id'));
        $post = Input::get('_modal')!=null;
        $status = ["success" => "tabla borrada."];
        return back()->with('status', $status);
        
    }

    public function paginar_modal() {
        return $this->paginar(Input::all());
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
            return Tabla::Where("id","like", "%$buscar%")->paginate($por_pagina);
        }
        return Tabla::Where("id","like", "%$buscar%")->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
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
            'codigo' => 'required',
            'descripcion' => 'required',
            'tabla' => 'required',
            'valor' => '',
            'valor_alf' => ''
        );
        
        $messages = array(
            'codigo.required'=>'El campo codigo es obligatorio.',
            'descripcion.required'=>'El campo descripcion es obligatorio.',
            'tabla.required'=>'El campo tabla es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo crear la tabla.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $tabla = new Tabla;
            $tabla->codigo = Input::get('codigo');
            $tabla->descripcion = Input::get('descripcion');
            $tabla->tabla = Input::get('tabla');
            $tabla->valor = Input::get('valor');
            $tabla->valor_alf = Input::get('valor_alf');
            $tabla->save();
            
            return response(array('mensaje'=>"tabla Agregada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_editar($id){
        $postData = Input::all();
        $tabla = Tabla::find($id);
        
        $rules = array(
            'codigo' => 'required',
            'descripcion' => 'required',
            'tabla' => 'required',
            'valor' => '',
            'valor_alf' => '',
        );
        $messages = array(
            'codigo.required'=>'El campo codigo es obligatorio.',
            'descripcion.required'=>'El campo descripcion es obligatorio.',
            'tabla.required'=>'El campo tabla es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo actualizar la tabla.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password')
            ), 422)
            ->header('Content-Type', 'application/json');
        } else {
            $tabla->codigo = Input::get('codigo');
            $tabla->descripcion = Input::get('descripcion');
            $tabla->tabla = Input::get('tabla');
            $tabla->valor = Input::get('valor');
            $tabla->valor_alf = Input::get('valor_alf');
            $tabla->save();
            
            return response(array('mensaje'=>"tabla Actualizada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    

    function api_borrar($id){
        Tabla::destroy($id);
        return response(array('mensaje'=>"tabla Eliminada.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
    }
    
    //<
    //>

}