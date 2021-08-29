<?php

namespace App\Http\Controllers;
use App\Producto;
use App\ProductoIngrediente;
use App\Adicional;
use App\ProductoSabor;
use App\ProductoTamano;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return Producto::where('estado','<>', 3)->get();
    }
    
    public function buscar($id) {
        return Producto::find($id);
    }
    
    
    public function buscarModal($buscar) {
        return DB::table('producto')
                ->where("descripcion", 'like', "%$buscar%")
                ->orWhere("detalle", 'like', "%$buscar%")
                ->limit(20)
                ->get();

    }
    
    public function buscarCompleto($id) {
        $producto = Producto::with("sabores")->with("ingredientes")->find($id);
        $producto->adicionales = Adicional::where("producto_id",$id)->get();
        return $producto;
    }
    public function buscarConIngredientesYAdicionales($id) {
        return Producto::with('ingredientes')->with('sabores')->where("id", $id)->first();

    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'detalle' => 'required',
                'tipo_producto_id' => 'required',
                'observacion' => '',
                'valor' => 'required',
                'impcomanda' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $producto = new Producto;
            $producto->codigo = Input::get('codigo');
            $producto->descripcion = Input::get('descripcion');
            $producto->detalle = Input::get('detalle');
            $producto->tipo_producto_id = Input::get('tipo_producto_id');
            $producto->observacion = Input::get('observacion');
            $producto->valor = Input::get('valor');
            $producto->impcomanda = Input::get('impcomanda');
            $producto->save();
        
            return Redirect::to('producto/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    public function crearCompleto(){
        $data = Input::all();
        $rules = array(
             'descripcion' => 'required|unique:producto,tipo_producto_id'
        );
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $data = json_decode(json_encode($data), FALSE);
            $producto = new Producto;
            $producto->descripcion = $data->descripcion;
            $producto->grupo = $data->grupo;
            $producto->imagen = $data->imagen;
            $producto->tipo_producto_id = $data->tipo_producto_id;
            $producto->detalle = $data->detalle == ''?$data->tipo_producto_nombre." ".$data->descripcion:$data->detalle;
//            $producto->valor = $data->valor;
            $producto->impcomanda = $data->comanda;
            $producto->compuesto = $data->compuesto;
            $producto->terminado = $data->terminado;
            $producto->iva = $data->iva;
            $producto->impco = $data->impco;
            $producto->save();
            
            $data->sabores = isset($data->sabores)?$data->sabores:[];
            $data->tamanos = isset($data->tamanos)?$data->tamanos:[];
            $data->ingredientes = isset($data->ingredientes)?$data->ingredientes:[];

            foreach($data->ingredientes as $ingrediente){
                foreach($ingrediente->inventario as $item_inventario){
                    $productoIngrediente = new ProductoIngrediente;
                    $productoIngrediente->producto_id = $producto->id;
                    $productoIngrediente->ingrediente_id = $ingrediente->ingrediente;
                    $productoIngrediente->tamano = $item_inventario->tamano;
                    $productoIngrediente->cantidad = $item_inventario->cantidad;
                    $productoIngrediente->save();
                }
            }

            foreach($data->sabores as $sabor){
                $productoSabor = new ProductoSabor;
                $productoSabor->producto_id = $producto->id;
                $productoSabor->sabor_id = $sabor;
                $productoSabor->save();
            }
            foreach($data->tamanos as $tamano){
                $productoTamano = new ProductoTamano;
                $productoTamano->producto_id = $producto->id;
                $productoTamano->tamano = $tamano->tamano;
                $productoTamano->valor = $tamano->valor;
                $productoTamano->save();
            }
            
            Session::set('status', ["success"=>"Producto Agregado."]);
            return response()->json($producto);
        }
    }
    
    public function editarCompleto(){
        $data = Input::all();
        $rules = array(
             'descripcion' => 'required|unique:producto,descripcion,'.$data["id"].',id,tipo_producto_id,'.$data["tipo_producto_id"]
        );
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $data = json_decode(json_encode($data), FALSE);
            $producto = Producto::find($data->id);
            $producto->descripcion = $data->descripcion;
            $producto->grupo = $data->grupo;
            $producto->tipo_producto_id = $data->tipo_producto_id;
            $producto->detalle = $data->detalle == ''?$data->tipo_producto_nombre." ".$data->descripcion:$data->detalle;
//            $producto->valor = $data->valor;
            $producto->impcomanda = $data->comanda;
            $producto->compuesto = $data->compuesto;
            $producto->terminado = $data->terminado;
            $producto->iva = $data->iva;
            $producto->impco = $data->impco;
            $producto->save();

            $data->sabores = isset($data->sabores)?$data->sabores:[];
            $data->tamanos = isset($data->tamanos)?$data->tamanos:[];
            $data->ingredientes = isset($data->ingredientes)?$data->ingredientes:[];

            $ingredientesIn = [];
            foreach($data->ingredientes as $ingrediente){
                $ingredientesIn[] = $ingrediente->ingrediente;
                foreach($ingrediente->inventario as $item_inventario){
                    $productoIngrediente = ProductoIngrediente::where('producto_id',$producto->id)
                    ->where('ingrediente_id',$ingrediente->ingrediente)
                    ->where('tamano',$item_inventario->tamano)
                    ->first();    
                    if($productoIngrediente == null){
                        $productoIngrediente = new ProductoIngrediente;
                    }
                    $productoIngrediente->producto_id = $producto->id;
                    $productoIngrediente->ingrediente_id = $ingrediente->ingrediente;
                    $productoIngrediente->tamano = $item_inventario->tamano;
                    $productoIngrediente->cantidad = $item_inventario->cantidad;
                    $productoIngrediente->save();
                }
            }
            ProductoIngrediente::where('producto_id',$producto->id)->whereNotIn("ingrediente_id",$ingredientesIn)->delete();

            ProductoSabor::where("producto_id", $producto->id)->whereNotIn("sabor_id",$data->sabores)->delete();
            foreach($data->sabores as $sabor){
                if (!ProductoSabor::where("producto_id", $producto->id)->where("sabor_id", $sabor)->first()==null){
                    continue;
                }
                $productoSabor = new ProductoSabor;
                $productoSabor->producto_id = $producto->id;
                $productoSabor->sabor_id = $sabor;
                $productoSabor->save();
            }
            
            $tamanos = [];
            foreach ($data->tamanos as $tamano_) {
                $tamano = ProductoTamano::where("producto_id", $producto->id)->where("tamano", $tamano_->tamano)->first();
                if ($tamano == null) {
                    $tamano = new ProductoTamano;
                    $tamano->producto_id = $producto->id;
                    $tamano->tamano = $tamano_->tamano;
                }
                $tamano->valor = $tamano_->valor;
                $tamano->save();
                $tamanos[] = $tamano->tamano;
            }
            ProductoTamano::where("producto_id", $producto->id)->whereNotIn("tamano", $tamanos)->delete();
            
            Session::set('status', ["success"=>"Producto Editado."]);
            return response()->json($producto);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $producto = Producto::find(Input::get('id'));
        
        $rules = array(
                'codigo' => 'required',
                'descripcion' => 'required',
                'detalle' => 'required',
                'tipo_producto_id' => 'required',
                'observacion' => '',
                'valor' => 'required',
                'impcomanda' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('producto/editar/'.$producto->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $producto->codigo = Input::get('codigo');
            $producto->descripcion = Input::get('descripcion');
            $producto->detalle = Input::get('detalle');
            $producto->tipo_producto_id = Input::get('tipo_producto_id');
            $producto->observacion = Input::get('observacion');
            $producto->valor = Input::get('valor');
            $producto->impcomanda = Input::get('impcomanda');
            $producto->save();
        
            return Redirect::to('producto/editar/'.$producto->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }

    public function borrar() {
        if($this->patchEstado(Input::get('id'),3) == 1){
            return Redirect::to('producto/listar')
                            ->with('status', ["success" => "Borrado."]);
        }
        else{
            return Redirect::to('producto/listar')
            ->with('status', ["danger" => "No se pudo borrar."]);
        }
    }

    
    public function subirImagen(Request $request){
        $extension = $request->file('_imagen')->getClientOriginalExtension();
        if(!in_array($extension, array("PNG", "JPG", "png", "JPEG","GIF","gif","jpg", "jpeg"))){
            return back()->with('status', ["warning"=>"Tipo de imagen no válida."]);
        }
        $imageName = time() . '.' . $extension;
        $request->file('_imagen')->move(
                base_path() . env('APP_PUBLIC_FOLDER').'images/producto/', $imageName
        );
        $producto = new \stdClass;
        $producto->imagen = $imageName;
        $producto->tp_id = $request['tp_id'];
        $producto->pr_desc = $request['pr_desc'];
        $producto->pr_valor = $request['pr_valor'];
        // die(var_dump($producto));
        return view('producto.agregar')
                                    ->with('producto_',$producto)
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todosAZ())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todosAZ())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todosAZ());
    }

    public function cambiarImagen(Request $request){
        $extension = $request->file('_imagen')->getClientOriginalExtension();
        if(!in_array($extension, array("PNG", "JPG", "png", "JPEG","GIF","gif","jpg", "jpeg"))){
            return back()->with('status', ["warning"=>"Tipo de imagen no válida."]);
        }
        $imageName = time() . '.' . $extension;
        $request->file('_imagen')->move(
                base_path() . env('APP_PUBLIC_FOLDER').'images/producto/', $imageName
        );
        $producto = Producto::find($request['id']);
        $producto->imagen = $imageName;
        $producto->save();
        return Redirect::to('producto/editar/'.$producto->id);
    }

    public function patchEstado($id, $estado){
        $producto = Producto::find($id);
        $producto->estado = $estado;
//        if($estado == 3){
//            $producto->descripcion = $producto->descripcion . ' B_' . $id;
//        }
        $producto->save();
        return 1;
    }
    public function patchProducto($id){
        $producto = Producto::find($id);

        foreach(Input::all() as $key=>$value) {
            $producto->$key = $value;
        }
        $producto->save();
        return $producto;
    }
    public function getProducto($id){
        return Producto::find($id);
    }
    public function guardarComo($id){
        $model = Producto::where('id', $id)->with('tamanos')->with('sabores')->with('ingredientes')->with('adicionales')->first();

        $new = new Producto;
        $new->descripcion = Input::get('nombre');
        $new->detalle = $model->detalle;
        $new->tipo_producto_id = $model->tipo_producto_id;
        $new->impcomanda = $model->impcomanda;
        $new->imagen = $model->imagen;
        $new->estado = $model->estado;
        $new->terminado = $model->terminado;
        $new->iva = $model->iva;
        $new->grupo = $model->grupo;
        $new->compuesto = $model->compuesto;
        $new->impco = $model->impco;
        $new->save();

        foreach($model->tamanos as $tamano){
            $newTamano = new ProductoTamano;
            $newTamano->tamano = $tamano->tamano;
            $newTamano->valor = $tamano->valor;
            $newTamano->producto_id = $new->id;
            $newTamano->save();
        }
        foreach($model->sabores as $sabor){
            $newSabor = new ProductoSabor;
            $newSabor->sabor_id = $sabor->pivot->sabor_id;
            $newSabor->producto_id = $new->id;
            $newSabor->save();
        }
        foreach($model->ingredientes as $ingrediente){
            $newIngrediente = new ProductoIngrediente;
            $newIngrediente->ingrediente_id = $ingrediente->pivot->ingrediente_id;
            $newIngrediente->tamano = $ingrediente->pivot->tamano;
            $newIngrediente->cantidad = $ingrediente->pivot->cantidad;
            $newIngrediente->producto_id = $new->id;
            $newIngrediente->save();
        }
        
        return response()->json(['code'=>200, 'id'=>$new->id]);
    }
    public function adding($id){
        $tamanos = ProductoTamano::where('producto_id', $id)->get();

        $ingredientes = DB::select(
            "select i.*, pi.* from pizza_producto_ingrediente pi
                join pizza_ingrediente as i
                on i.id = pi.ingrediente_id 
                where pi.producto_id = $id"
        );

        return response()->json([
            'tamanos'=>$tamanos,
            'ingredientes'=>$ingredientes,
        ]);
    }
}