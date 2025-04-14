<?php

namespace App\Http\Controllers;
use App\ProductoPedidoIngrediente;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoPedidoIngredienteController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function guardar($datos) {
        $producto_pedido_ingrediente = new ProductoPedidoIngrediente;
        $producto_pedido_ingrediente->producto_pedido_id = $datos->producto_pedido_id;
        $producto_pedido_ingrediente->ingrediente_id = $datos->ingrediente_id;
        $producto_pedido_ingrediente->cant = $datos->cant;
        $producto_pedido_ingrediente->save();
        return $producto_pedido_ingrediente;
    }

}