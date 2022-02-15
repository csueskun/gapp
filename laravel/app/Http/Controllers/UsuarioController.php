<?php

namespace App\Http\Controllers;
use App\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class UsuarioController extends Controller
{
    public function __construct(){
        DB::setDefaultConnection("mysql");
    }

    public function todos(){
        return Usuario::all();
    }
    
    public function todosPorRol($rol){
        return Usuario::where('rol', '=', $rol)->get();
    }
    
    public function viewEditarPass(){
        return view("usuario.editarpassword")->with('users', Auth::user());
    }
    
    public function viewEditar(){
        return view("usuario.editar")->with('users', Auth::user());
    }
    
    public function editardatos(){
        return view("usuario.editar")->with('users', Auth::user());
    }
    
    public function inicio(){
        
        if(Auth::check()){
            $controller = app('App\Http\Controllers\ConfigController');
            $estadomesas = $controller->estado_mesas2();
            $meseros = Usuario::where('rol', 'Mesero')->get();
            return view("varios.bienvenida")
                ->with("estado_mesas", $estadomesas)
                ->with("meseros", $meseros)
                ->with("config", $controller->first());
        }
        else {
            return Redirect('/login');
        }
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
            'usuario' => 'unique:users', // make sure the email is an actual email
            'password' => 'required|min:3|confirmed',
            'nombres' => 'required',
            'apellidos' => 'required'
        );
        
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('/usuario/crear')
                            ->withErrors($validator) // send back all errors to the login form
                            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            $usuario = new Usuario;

            $usuario->usuario = Input::get('usuario');
            $usuario->rol = Input::get('rol');
            $usuario->nombres = Input::get('nombres');
            $usuario->apellidos = Input::get('apellidos');
            $usuario->password = Hash::make(Input::get('password'));

            $usuario->save();
            
            return Redirect::to('/usuario/listar') // redirect the user to the login screen
            ->with('status', ["success"=>"usuario '$usuario->usuario' creado."]);
        }

    }
    
    
    public function editar(){
        
        $postData = Input::all();
        
        $rules = array(
            'usuario' => 'unique:users,usuario,'.Auth::user()->id,
            'nombres' => 'required',
            'apellidos' => 'required'
        );

        $messages = array(
            'usuario.unique'=>'El usuario "'.Input::get('usuario').'" ya está en uso.'
        );
        
        $validator = Validator::make($postData, $rules, $messages);
        if ($validator->fails()) {

            return Redirect::to('/usuario/editar')
                            ->withErrors($validator) // send back all errors to the login form
                            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            $usuario=Auth::user();
            $usuario->usuario = Input::get('usuario');
            $usuario->nombres = Input::get('nombres');
            $usuario->apellidos = Input::get('apellidos');
            $usuario->save();
            
            return Redirect::to('/usuario/editar') // redirect the user to the login screen
            ->with('status', ["success"=>"Datos Actualizados."]);
        }

    }
    
    public function editarpass(){
        
        $postData = Input::all();
        
        $rules = array(
            'password_viejo' => 'required', // make sure the email is an actual email
            'password' => 'required|min:3|confirmed' // password can only be alphanumeric and has to be greater than 3 characters
        );
        
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('usuario/editar')
                            ->withErrors($validator) // send back all errors to the login form
                            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
                             // send back the input (not the password) so that we can repopulate the form
            
        } else if (!Hash::check(Input::get('password_viejo'), Auth::user()->password)) {
            
            $validator->errors()->add('password_viejo', 'La contraseña es incorrecta');
            return Redirect::to('usuario/editar') // redirect the user to the login screen
                            ->withErrors($validator)->withInput(Input::except('password'));
        } else {
            
            $usuario=Auth::user();
            $usuario->password = Hash::make(Input::get('password'));
            $usuario->save();
            
            return Redirect::to('usuario/editar-password') // redirect the user to the login screen
            ->with('status', ["success"=>"Contraseña modificada."]);
        }

    }
    public function borrar(){
        Usuario::destroy(Input::get('id'));
        return Redirect::to('usuario/listar')
            ->with('status', ["success"=>"usuario borrado."]);
    }
    public function hash(){
        return Hash::make((Input::get('str')));
    }
    public function machineId(){
        return shell_exec("echo | WMIC csproduct get uuid");
    }
}
