<?php

namespace App\Http\Controllers;
use App\Combo;
use App\ComboProducto;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;

class ComboProductoController extends Controller
{
    

    function borrar($id){
        if(ComboProducto::destroy($id)){
            return response(array('mensaje'=>"Producto eliminado del combo.",'status'=>200), 200)
                ->header('Content-Type', 'application/json');
        }
        else{
            return response(array('mensaje'=>"El producto no pude ser eliminado del combo.",'status'=>540), 200)
                ->header('Content-Type', 'application/json');
        }
    }
    function crear(){
        $new_combo = new ComboProducto;
        $new_combo->combo_id = Input::get('combo');
        $new_combo->producto_id = Input::get('producto');
        $new_combo->cantidad = Input::get('cantidad');
        $new_combo->tamano = Input::get('tamano');
        $new_combo->valor = Input::get('valor');
        $new_combo->save();

        return response(array('mensaje'=>"Producto agregado al combo.",'status'=>200,'producto'=>$new_combo), 200)
            ->header('Content-Type', 'application/json');

    }
    //<
    //>

}