<?php

namespace App\Http\Controllers;
use App\TipoDocumento;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class TipoDocumentoController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return TipoDocumento::all();
    }
    
    public function buscar($id) {
        return TipoDocumento::find($id);
    }
    
    public function siguienteTipo($codigo){
        $tipo_documento = TipoDocumento::where('codigo', $codigo)->first();
        if($tipo_documento == null){
            $tipo_documento = new TipoDocumento;
            $tipo_documento->codigo = $codigo;
            $tipo_documento->descripcion = $codigo;
            $tipo_documento->consecutivo = 1;
            $tipo_documento->save();
        }
        return $tipo_documento;
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'imparqueo' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('tipo_documento/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $tipo_documento = new TipoDocumento;
            $tipo_documento->codigo = Input::get('codigo');
            $tipo_documento->descripcion = Input::get('descripcion');
            $tipo_documento->imparqueo = Input::get('imparqueo');
            $tipo_documento->save();
        
            return Redirect::to('tipo_documento/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $tipo_documento = TipoDocumento::find(Input::get('id'));
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'imparqueo' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('tipo_documento/editar/'.$tipo_documento->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $tipo_documento->codigo = Input::get('codigo');
            $tipo_documento->descripcion = Input::get('descripcion');
            $tipo_documento->imparqueo = Input::get('imparqueo');
            $tipo_documento->save();
        
            return Redirect::to('tipo_documento/editar/'.$tipo_documento->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        TipoDocumento::destroy(Input::get('id'));
        return Redirect::to('tipo_documento/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}