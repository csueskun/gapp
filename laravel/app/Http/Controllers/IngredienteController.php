<?php

namespace App\Http\Controllers;
use App\Ingrediente;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class IngredienteController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return Ingrediente::all();
    }
    
    public function todosAZ() {
        return Ingrediente::orderBy('descripcion', 'asc')->get();
    }
    
    public function buscar($id) {
        return Ingrediente::find($id);
    }
    
    public function buscarModal($buscar) {
        return Ingrediente::where("descripcion", 'like', "%$buscar%")->get();
    }
    
    public function subirImagen(Request $request){

        if(Input::get('cargar_imagen')==1){
            $extension = $request->file('_imagen')->getClientOriginalExtension();
            if(!in_array($extension, array("PNG", "JPG", "png", "JPEG","GIF","gif","jpg"))){
                return back()->with('status', ["warning"=>"Tipo de imagen no válida."]);
            }
            $imageName = time() . '.' . $extension;
            $request->file('_imagen')->move(
                    base_path() . env('APP_PUBLIC_FOLDER').'images/ingrediente/', $imageName
            );
            return view('ingrediente.crear')->with('_imagen',$imageName);
        }
        else{
            $postData = Input::all();
        
            $rules = array(
                    'descripcion' => 'required|unique:ingrediente'
                    );
            $validator = Validator::make($postData, $rules);
            if ($validator->fails()) {

                return back()->withErrors($validator)
                                ->withInput(Input::except('password'))
                                ->with('status', ["danger" => "No Se Creó el Ingrediente."]);
            } else {
                $ingrediente = new Ingrediente;
                $ingrediente->descripcion = Input::get('descripcion');
                $ingrediente->grupo = Input::get('grupo');
                $ingrediente->imagen = Input::get('imagen');
                $ingrediente->visible = Input::get('visible')?:'';
                $ingrediente->save();

                return back()->with('status', ["success"=>"Ingrediente Agregado."]);
            }
        }
        
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('ingrediente/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $ingrediente = new Ingrediente;
            $ingrediente->descripcion = Input::get('descripcion');
            $ingrediente->grupo = Input::get('grupo');
            $ingrediente->unidad = Input::get('unidad');
            $ingrediente->visible = Input::get('visible')?:'';
            $ingrediente->save();
        
            return Redirect::to('ingrediente/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function crearModal(){
        $postData = Input::all();
        
        $rules = array(
                'descripcion' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        } else {
            $ingrediente = new Ingrediente;
            $ingrediente->descripcion = Input::get('descripcion');
            $ingrediente->grupo = Input::get('grupo');
            $ingrediente->unidad = Input::get('unidad');
            $ingrediente->visible = Input::get('visible')?:'';
            $ingrediente->save();
        
            return response()->json($ingrediente);
        }
    }
    
    public function editar(Request $request){
        //die(base_path() . env('APP_PUBLIC_FOLDER').'images/ingrediente/images.xxx');
        $ingrediente = Ingrediente::find(Input::get('id'));
        
        if(Input::get('cargar_imagen')==1){
            
            
            $extension = $request->file('_imagen')->getClientOriginalExtension();
            if(!in_array($extension, array("PNG", "JPG", "png", "JPEG","GIF","gif","jpg"))){
                return back()->with('status', ["warning"=>"Tipo de imagen no válida."]);
            }
            $imageName = time() . '.' . $extension;
            $request->file('_imagen')->move(
                    base_path() . env('APP_PUBLIC_FOLDER').'images/ingrediente/', $imageName
            );
            if($ingrediente->imagen != 'ingrediente.jpg'){
                \File::delete(base_path() . env('APP_PUBLIC_FOLDER').'images/ingrediente/'.$ingrediente->imagen);
            }
            $ingrediente->imagen = $imageName;
            $ingrediente->save();
            

            return Redirect::to('/ingrediente/editar/'.$ingrediente->id)->with('ingrediente',$ingrediente);
        }
        
        $postData = Input::all();
        
        
        $rules = array(
                'descripcion' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('ingrediente/editar/'.$ingrediente->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $ingrediente->descripcion = Input::get('descripcion');
            $ingrediente->grupo = Input::get('grupo');
            $ingrediente->imagen = Input::get('imagen');
            $ingrediente->unidad = Input::get('unidad');
            $ingrediente->visible = Input::get('visible')?:'';
            $ingrediente->save();
        
            return Redirect::to('ingrediente/editar/'.$ingrediente->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    public function borrar() {
        Ingrediente::destroy(Input::get('id'));
        return Redirect::to('ingrediente/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

    public function borrarPost($id){
        if (Ingrediente::
            join('producto_ingrediente', 'producto_ingrediente.ingrediente_id', '=', 'ingrediente.id')
            ->where('ingrediente.id', $id)
            ->count()>0){
            return response()->json(array('code'=>400,'msg'=>'No se pudo borrar. El ingrediente está asociado a un producto.'));
        }
        else{
            Ingrediente::destroy($id);
            return response()->json(array('code'=>200,'msg'=>'El ingrediente fue eliminado.'));
        }

    }

}