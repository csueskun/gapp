<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Form;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;

class LoginController extends Controller
{
    public function __construct(){
        DB::setDefaultConnection("mysql");
    }

    public function hacerLogin(){
        
        $postData = Input::all();
        
        $rules = array(
            'usuario' => 'required',
            'password' => 'required|min:1'
        );
        $validator = Validator::make($postData, $rules);
        if (!$validator->fails() && Auth::attempt($postData)) {
            // $sql = DB::select("
            // SELECT valor from hsoft where variable = 'dd'
            // ");
            $sql = [0];
            if(count($sql)>0){
//                if(strcmp(str_replace(" ", "",$sql[0]->valor), str_replace(" ","",preg_replace( "/\r|\n/", "", trim($this->doAuthLogin())))) === 0){
                if(true){
                    return Redirect::to('/')->with('status', ["success-contenido" => "Bienvenido ".Auth::user()->nombres." ".Auth::user()->apellidos]);
                }
            }
            $validator->getMessageBag()->add('password', "Usuario no asociado a H-Software");
            return Redirect::to('/login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $validator->getMessageBag()->add('password', 'Nombre De Usuario o Password Incorrectos');
            return Redirect::to('/login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }
    }
    
    public function hacerLogout(){
        Auth::logout();
        return Redirect::to('/login')
            ->with('status', ["warning-contenido" => "Se ha cerrado su sesión."]);
    }

    public function doAuthLogin(){
        $companyName = 'H-Software';
        $output = shell_exec(app('App\Http\Controllers\ConfigController')->configInitPrinter()." | ".app('App\Http\Controllers\UsersController')->getAuthKey()." ".app('App\Http\Controllers\InformeController')->removeRango()." get uuid");
        if ($output){
            return $output;
        }
        else{
            return $companyName;
        }
    }
}
