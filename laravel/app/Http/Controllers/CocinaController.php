<?php

namespace App\Http\Controllers;
use App\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class CocinaController extends Controller
{
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
    }*/
    public function vistaCocina() {
        return view('cocina.pedidos')
            ->with('pedido_lista', app('App\Http\Controllers\PedidoController')->todosActivos());
    }
    
}