<?php

namespace App\Http\Controllers;
use App\Config;
use App\Pedido;
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
        $pedidos = Pedido::with("productos")->whereIn("estado", array(1,3))->orderBy('created_at', 'desc')->get();
        return view('cocina.pedidos')->with('pedido_lista', $pedidos);
    }
    public function nuevosPedidos($date) {
        // $date = intval($date);
        // $pedidos = Pedido::with("productos.tipo_producto")->where("id", ">", $date)->where("mesa_id", "!=", 0)->where("estado", 1)->orderBy('created_at', 'asc')->get();

        $date = str_replace('_', ' ', $date);
        $pedidos = Pedido::select('pedido.*')
        ->join('producto_pedido', 'producto_pedido.pedido_id', '=', 'pedido.id')
        ->whereIn("pedido.estado", array(1,3))
        ->where('producto_pedido.created_at', '>', $date)
        ->with("productos.tipo_producto")
        ->orderBy('pedido.created_at', 'asc')->get();

        return response()->json(array('code'=>200,'msg'=>'OK.','pedidos'=>$pedidos));
    }    
}