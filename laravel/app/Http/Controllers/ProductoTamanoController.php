<?php

namespace App\Http\Controllers;
use App\ProductoTamano;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;

class ProductoTamanoController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/

    public function todos() {
        return ProductoTamano::all();
    }
    public function todosAZ() {
        return ProductoTamano::orderBy('descripcion', 'asc')->get();
    }
    
    public function todosConProducto($buscar) {
        
        return ProductoTamano::select(DB::raw("{$this->conn}_producto.id,{$this->conn}_producto_tamano.valor, REPLACE(CONCAT_WS(' ',{$this->conn}_tipo_producto.descripcion,{$this->conn}_producto.descripcion,UPPER({$this->conn}_producto_tamano.tamano)), 'UNICO', '') as detalle"))
                ->join('producto', 'producto.id', '=', 'producto_tamano.producto_id')
                ->join('tipo_producto', 'tipo_producto.id', '=', 'producto.tipo_producto_id')
                ->whereRaw("(pizza_producto.descripcion like '%$buscar%' or pizza_tipo_producto.descripcion like '%$buscar%')")
                ->get();
    }
    
    public function buscarPorProducto($id) {
        return ProductoTamano::where("producto_id",$id)->get();
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('sabor/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $sabor = new ProductoTamano;
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return Redirect::to('sabor/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function crearModal(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => 'required|unique:sabor'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $sabor = new ProductoTamano;
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return response()->json($sabor);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $sabor = ProductoTamano::find(Input::get('id'));
        
        $rules = array(
                'descripcion' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('sabor/editar/'.$sabor->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $sabor->descripcion = Input::get('descripcion');
            $sabor->save();
        
            return Redirect::to('sabor/editar/'.$sabor->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        ProductoTamano::destroy(Input::get('id'));
        return Redirect::to('sabor/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

}