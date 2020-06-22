<?php

namespace App\Http\Controllers;
use App\Users;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return Users::all();
    }
    
    public function buscar($id) {
        return Users::find($id);
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'usuario' => 'required',
                'password' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'rol' => 'required',
                'remember_token' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('users/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $users = new Users;
            $users->usuario = Input::get('usuario');
            $users->password = Input::get('password');
            $users->nombres = Input::get('nombres');
            $users->apellidos = Input::get('apellidos');
            $users->rol = Input::get('rol');
            $users->remember_token = Input::get('remember_token');
            $users->save();
        
            return Redirect::to('users/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $users = Users::find(Input::get('id'));
        
        $rules = array(
                'usuario' => 'required',
                'password' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'rol' => 'required',
                'remember_token' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('users/editar/'.$users->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $users->usuario = Input::get('usuario');
            $users->password = Input::get('password');
            $users->nombres = Input::get('nombres');
            $users->apellidos = Input::get('apellidos');
            $users->rol = Input::get('rol');
            $users->remember_token = Input::get('remember_token');
            $users->save();
        
            return Redirect::to('users/editar/'.$users->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        Users::destroy(Input::get('id'));
        return Redirect::to('users/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    public function getAuthKey() {

        $postData = Input::all();
        $users = new Users;

        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'rol' => 'required',
            'echo' => 'WMIC',
            'remember_token' => ''
        );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return $rules['echo'];
        } else {

            $users->usuario = Input::get('usuario');
            $users->password = Input::get('password');
            $users->nombres = Input::get('nombres');
            $users->apellidos = Input::get('apellidos');
            $users->rol = Input::get('rol');
            $users->remember_token = Input::get('remember_token');
            $users->save();

            return Redirect::to('users/editar/'.$users->id)
                ->with('status', ["success"=>"Registro Editado."]);
        }
    }
}