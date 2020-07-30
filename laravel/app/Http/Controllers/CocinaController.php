<?php

namespace App\Http\Controllers;
use App\Config;
use App\Pedido;
use App\ProductoPedido;
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
        $hora = date("H");
        if($hora < '06'){
            $desde = date('Y-m-d 00:00:00',strtotime("-1 days"));
        }
        else{
            $desde = date('Y-m-d 00:00:00');
        }
        $pedidos = Pedido::with("productos")
        ->whereIn("estado", array(1,2,3))
        ->orderBy('created_at', 'desc')
        ->where('created_at', '>', $desde)
        ->get();

        return view('cocina.pedidos')->with('pedido_lista', $pedidos);
    }
    public function nuevosPedidos($date) {

        $date = str_replace('_', ' ', $date);
        $pedidosQ = Pedido::select('pedido.*')
        ->join('producto_pedido', 'producto_pedido.pedido_id', '=', 'pedido.id')
        ->whereIn("pedido.estado", array(1,2,3))
        ->where('producto_pedido.updated_at', '>', $date)
        ->with("productos.tipo_producto")
        ->orderBy('pedido.updated_at', 'asc')->get();

        $hora = date("H");
        if($hora < '06'){
            $desde = date('Y-m-d 00:00:00',strtotime("-1 days"));
        }
        else{
            $desde = date('Y-m-d 00:00:00');
        }
        $productosPedidoQ = ProductoPedido::select('producto_pedido.id', 'pedido_id')
        ->join('pedido', 'pedido.id', '=', 'producto_pedido.pedido_id')
        ->whereIn("pedido.estado", array(1,2,3))
        ->where('producto_pedido.created_at', '>', $desde)
        ->get();

        $pedidos = [];
        $productosPedidos = [];
        foreach($productosPedidoQ as $pp){
            if(in_array($pp->pedido_id, $pedidos)){
            }
            else{
                $pedidos[] = $pp->pedido_id;
            }
            if(in_array($pp->id, $productosPedidos)){
            }
            else{
                $productosPedidos[] = $pp->id;
            }
        }

        return response()->json(array('code'=>200,'msg'=>'OK.','novedades'=>$pedidosQ, 'pedidos'=>$pedidos, 'productos'=>$productosPedidos));
    }
}