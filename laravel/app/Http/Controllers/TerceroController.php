<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Tercero;
use DB;

class TerceroController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function vistaLista(){
        return view('tercero.listar')->with("tercero_lista",$this->paginar(Input::all()));
    }

    public function vistaCrear(){
        return view('tercero.crear');
    }

    public function vistaEditar($id){
        return view('tercero.editar')->with("tercero", $this->encontrar($id));
    }


    public function todos() {
        return Tercero::all();
    }
    
    public function encontrar($id) {
        return Tercero::find($id);
    }
    
    public function encontrarPorCampo($campo, $buscar) {
        return Tercero::where($campo, $buscar)->get();
    }
    
    public function crear(){
        $postData = Input::all();
        $rules = array(
            'identificacion' => 'required|unique:tercero',
            'tipoidenti' => '',
            'nombrecompleto' => '',
            'fecha_nacimiento' => '',
            'direccion' => '',
            'telefono' => '',
            'tipoclie' => '',
            'observacion' => '',
            'email' => '',
            'celular' => '',
            'nrotarjetapuntos' => '',
            'puntosacumulados' => '',
            'nombre1' => '',
            'nombre2' => '',
            'apellido1' => '',
            'apellido2' => ''
        );
        $messages = array(
            'identificacion.required'=>'El campo Identificación es obligatorio.',
            'identificacion.unique'=>'Identificación "'. Input::get('identificacion') . '" ya está en uso.',
            'nombrecompleto.required'=>'El campo Nombre Completo es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);

        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo crear el Tercero.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $tercero = new Tercero;
            $tercero->identificacion = Input::get('identificacion');
            $tercero->tipoidenti = Input::get('tipoidenti');
            $tercero->nombrecompleto = Input::get('nombrecompleto');
            $tercero->fecha_nacimiento = Input::get('fecha_nacimiento');
            
            $tercero->direccion = Input::get('direccion');
            $tercero->telefono = Input::get('telefono');
            $tercero->tipoclie = Input::get('tipoclie');
            $tercero->observacion = Input::get('observacion');
            $tercero->email = Input::get('email');
            $tercero->celular = Input::get('celular');
            $tercero->nrotarjetapuntos = Input::get('nrotarjetapuntos');
            $tercero->puntosacumulados = Input::get('puntosacumulados');
            $tercero->nombre1 = Input::get('nombre1');
            $tercero->nombre2 = Input::get('nombre2');
            $tercero->apellido1 = Input::get('apellido1');
            $tercero->apellido2 = Input::get('apellido2');
            $tercero->save();
            return back()->with('status', ["success"=>"Tercero Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $tercero = Tercero::find(Input::get('id'));
        $rules = array(
            'identificacion' => 'required|unique:tercero,identificacion,'.Input::get('id'),
            'tipoidenti' => '',
            'nombrecompleto' => '',
            'fecha_nacimiento' => '',
            'direccion' => '',
            'telefono' => '',
            'tipoclie' => '',
            'observacion' => '',
            'email' => '',
            'celular' => '',
            'nrotarjetapuntos' => '',
            'puntosacumulados' => '',
            'nombre1' => '',
            'nombre2' => '',
            'apellido1' => '',
            'apellido2' => '',
        );
        $messages = array(
            'identificacion.required'=>'El campo Identificación es obligatorio.',
            'identificacion.unique'=>'Identificación "'. Input::get('identificacion') . '" ya está en uso.',
            'nombrecompleto.required'=>'El campo Nombre Completo es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            $status = ['danger' => 'No se pudo modificar el Tercero.'];
            return back()->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', $status);
        } else {
            $tercero->identificacion = Input::get('identificacion');
            $tercero->tipoidenti = Input::get('tipoidenti');
            $tercero->nombrecompleto = Input::get('nombrecompleto');
            $tercero->fecha_nacimiento = Input::get('fecha_nacimiento');
            $tercero->direccion = Input::get('direccion');
            $tercero->telefono = Input::get('telefono');
            $tercero->tipoclie = Input::get('tipoclie');
            $tercero->observacion = Input::get('observacion');
            $tercero->email = Input::get('email');
            $tercero->celular = Input::get('celular');
            $tercero->nrotarjetapuntos = Input::get('nrotarjetapuntos');
            $tercero->puntosacumulados = Input::get('puntosacumulados');
            $tercero->nombre1 = Input::get('nombre1');
            $tercero->nombre2 = Input::get('nombre2');
            $tercero->apellido1 = Input::get('apellido1');
            $tercero->apellido2 = Input::get('apellido2');
            $tercero->save();
            return back()->with('status', ["success"=>"Tercero actualizado."]);
        }
    }
    
    public function borrar() {
        Tercero::destroy(Input::get('id'));
        $post = Input::get('_modal')!=null;
        $status = ["success" => "Tercero borrado."];
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

        $res = Tercero::orWhere('identificacion', 'like', "%$buscar%")
        ->orWhere('nombrecompleto', 'like', "%$buscar%")
        ->orWhere('telefono', 'like', "%$buscar%");

        if($ordenar_por==""||$ordenar_por==null){
            return $res->paginate($por_pagina);
        }
        return $res->orderBy($ordenar_por, $sentido)->paginate($por_pagina);
    }

    function api_listar(){
        return response($this->todos(), 200)
        ->header('Content-Type', 'application/json');
    }

    function api_encontrar($id){
        return response($this->encontrar($id), 200)
        ->header('Content-Type', 'application/json');
    }
    
    public function api_listar_por_campo($campo, $buscar) {
        return response($this->encontrarPorCampo($campo, $buscar), 200)
        ->header('Content-Type', 'application/json');
    }

    public function crearIf(Request $request){
        $data = $request->all();
        try {
            if($data['cliente_id']){
                return response(array('data'=>'Tercero ya registrado'), 201)->header('Content-Type', 'application/json');
            }
        } catch (\Throwable $th) {}
        try {
            if(!$data['identificacion'] || !$data['cliente']){
                return response(array('data'=>'Datos incompletos'), 201)->header('Content-Type', 'application/json');
            }
        } catch (\Throwable $th) {
            return response(array('data'=>'Datos incompletos'), 201)->header('Content-Type', 'application/json');
        }
        
        $tercero = Tercero::where('identificacion', $data['identificacion'])->count();
        if($tercero){
            return response(array('data'=>'Identificación ya registrada'), 540)->header('Content-Type', 'application/json');
        }
        $tercero = new Tercero;
        $tercero->identificacion = $data['identificacion'];
        $tercero->nombrecompleto = $data['cliente'];
        $tercero->telefono = isset($data['telefono'])?$data['telefono']:'';
        $tercero->direccion = isset($data['domicilio'])?$data['domicilio']:'';
        $tercero->tipoclie = 'C';
        $tercero->save();
        return response(array('data'=>$tercero), 200)->header('Content-Type', 'application/json');
    }

    function api_crear(){
        $postData = Input::all();
        $rules = array(
            'identificacion' => 'required|unique:tercero',
            'tipoidenti' => '',
            'nombrecompleto' => '',
            'fecha_nacimiento' => '',
            'direccion' => '',
            'telefono' => '',
            'tipoclie' => '',
            'observacion' => '',
            'email' => '',
            'celular' => '',
            'nrotarjetapuntos' => '',
            'puntosacumulados' => '',
            'nombre1' => '',
            'nombre2' => '',
            'apellido1' => '',
            'apellido2' => ''
        );
        
        $messages = array(
            'identificacion.required'=>'El campo Identificación es obligatorio.',
            'identificacion.unique'=>'Identificación "'. Input::get('identificacion') . '" ya está en uso.',
            'nombrecompleto.required'=>'El campo Nombre Completo es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo crear el Tercero.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password'),
                'status'=>422
            ), 200)
            ->header('Content-Type', 'application/json');
        } else {
            $tercero = new Tercero;
            $tercero->identificacion = Input::get('identificacion');
            $tercero->tipoidenti = Input::get('tipoidenti');
            $tercero->nombrecompleto = Input::get('nombrecompleto');
            $tercero->fecha_nacimiento = Input::get('fecha_nacimiento');
            $tercero->direccion = Input::get('direccion');
            $tercero->telefono = Input::get('telefono');
            $tercero->tipoclie = Input::get('tipoclie');
            $tercero->observacion = Input::get('observacion');
            $tercero->email = Input::get('email');
            $tercero->celular = Input::get('celular');
            $tercero->nrotarjetapuntos = Input::get('nrotarjetapuntos');
            $tercero->puntosacumulados = Input::get('puntosacumulados');
            $tercero->nombre1 = Input::get('nombre1');
            $tercero->nombre2 = Input::get('nombre2');
            $tercero->apellido1 = Input::get('apellido1');
            $tercero->apellido2 = Input::get('apellido2');
            $tercero->save();
            $tercero = Tercero::with('nombrecompleto')->find($tercero->id);
            return response(array('mensaje'=>"Tercero Agregado.",
            'data'=>$tercero,
            'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }

    function api_editar($id){
        $postData = Input::all();
        $tercero = Tercero::with('nombrecompleto')->find($id);
        
        $rules = array(
            'identificacion' => 'required|unique:tercero,identificacion,'.$tercero->id,
            'tipoidenti' => '',
            'nombrecompleto' => '',
            'fecha_nacimiento' => '',
            'direccion' => '',
            'telefono' => '',
            'tipoclie' => '',
            'observacion' => '',
            'email' => '',
            'celular' => '',
            'nrotarjetapuntos' => '',
            'puntosacumulados' => '',
            'nombre1' => '',
            'nombre2' => '',
            'apellido1' => '',
            'apellido2' => '',
        );
        $messages = array(
            'identificacion.required'=>'El campo Identificación es obligatorio.',
            'identificacion.unique'=>'Identificación "'. Input::get('identificacion') . '" ya está en uso.',
            'nombrecompleto.required'=>'El campo Nombre Completo es obligatorio.',
        );
        $validator = Validator::make($postData, $rules, $messages);
        $post = Input::get('_modal')!=null;
        if ($validator->fails()) {
            return response(array(
                'mensaje' => 'No se pudo actualizar el Tercero.',
                'errors'=>$validator->errors(),
                'input'=>Input::except('password'),
                'status'=>422
            ), 200)
            ->header('Content-Type', 'application/json');
        } else {
            $tercero->identificacion = Input::get('identificacion');
            $tercero->tipoidenti = Input::get('tipoidenti');
            $tercero->nombrecompleto = Input::get('nombrecompleto');
            $tercero->fecha_nacimiento = Input::get('fecha_nacimiento');
            $tercero->direccion = Input::get('direccion');
            $tercero->telefono = Input::get('telefono');
            $tercero->tipoclie = Input::get('tipoclie');
            $tercero->observacion = Input::get('observacion');
            $tercero->email = Input::get('email');
            $tercero->celular = Input::get('celular');
            $tercero->nrotarjetapuntos = Input::get('nrotarjetapuntos');
            $tercero->puntosacumulados = Input::get('puntosacumulados');
            $tercero->nombre1 = Input::get('nombre1');
            $tercero->nombre2 = Input::get('nombre2');
            $tercero->apellido1 = Input::get('apellido1');
            $tercero->apellido2 = Input::get('apellido2');
            $tercero->save();
            
            return response(array('mensaje'=>"Tercero Actualizado.",
                'data'=>$tercero,
                'status'=>200), 200)
            ->header('Content-Type', 'application/json');
        }
    }
    
    function api_borrar($id){
        Tercero::destroy($id);
        return response(array('mensaje'=>"Tercero Eliminado.",'status'=>200), 200)
            ->header('Content-Type', 'application/json');
    }
    
    function paginate(){
        $descending = Input::get('descending')?:'true';
        $pagination = array(
            'page' => Input::get('page')?:1,
            'rowsPerPage' => Input::get('rowsPerPage')?:5,
            'sortBy' => Input::get('sortBy')?:'created_at',
            'descending' => $descending=='true'?'desc':'asc',
            'totalItems' => 0
        );
        $skip = $pagination['rowsPerPage'] * ($pagination['page'] - 1);
        $result = Tercero::skip($skip)
            ->take($pagination['rowsPerPage'])
            ->orderBy($pagination['sortBy'], $pagination['descending'])
            ->get();
        $pagination['totalItems'] = Tercero::count();
        return response(array('data'=>$result, 'pagination'=>$pagination), 200)
            ->header('Content-Type', 'application/json');
    }
    
    function filter(){
        $id = Input::get('id')?:'';
        $result = Tercero::where('id', 'like', "%$id%")
            ->take(10)
            ->get();
        return response($result, 200)->header('Content-Type', 'application/json');
    }


    public function getTerceros(){
        $limit = Input::get('limit')?:10;
        $buscar = Input::get('params', '');
        $result = Tercero::orWhere('identificacion', 'like', "%$buscar%")
            ->orWhere('nombrecompleto', 'like', "%$buscar%")
            ->orWhere('telefono', 'like', "%$buscar%")
            ->take($limit)
            ->get();
        return $result;
    }

    public function buscar($buscar){
        return $this->paginar($buscar);
    }
    


    
    //<
    //>

}