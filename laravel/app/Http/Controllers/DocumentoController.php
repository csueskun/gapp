<?php

namespace App\Http\Controllers;
use App\Documento;
use App\DetalleDocumento;
use App\Pedido;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\ProductoPedido;
use App\Http\Controllers\SaldosProductoController;
use DB;
use Auth;

class DocumentoController extends Controller
{

    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function todos() {
        return Documento::orderBy("id","desc")->get();
    }
    
    public function encontrar($id) {
        return Documento::with("detalles.producto")->where("id", $id)->first();
    }
    public function encontrarPorPedido($id) {
        return Documento::where('pedido_id', $id)->first();
    }
    public function cuadre() {
        $mail = Input::get("mail") == 1;
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");

        $with_hours = Input::get('hora');
        if($with_hours){
            $fecha_inicio = "'$fecha_inicio'";
            $fecha_fin = "'$fecha_fin'";
        }
        else{
            $fecha_inicio = "DATE_ADD('".$fecha_inicio."', INTERVAL 3 hour)";
            $fecha_fin = "DATE_ADD('".$fecha_fin."', INTERVAL 3 hour)";
        }

        $caja_id = Input::get("caja_id");
        $caja_condicion = '';
        $caja_condicion_d = '';
        $caja_condicion_f = '';
        $caja_condicion_p = '';
        $caja_condicion_fp = '';
        
        if($caja_id == '0'){
        }
        else{
            $caja_condicion = "and {$this->conn}_documento.caja_id = $caja_id";
            $caja_condicion_d = "and d.caja_id = $caja_id";
            $caja_condicion_p = "and p.caja_id = $caja_id";
            $caja_condicion_f = "and caja_id = $caja_id";
            $caja_condicion_fp = "and {$this->conn}_pedido.caja_id = $caja_id";
        }

        $cuadre = DB::select("
            Select 'I' as ie, 'BI' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'BI' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'PN' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'PN' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'DES' as tipo, sum(COALESCE(descuento, 0)) as total 
            from {$this->conn}_documento
            where {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'PRO' as tipo, sum(COALESCE(propina, 0)) as total 
            from {$this->conn}_pedido
            where {$this->conn}_pedido.created_at >= $fecha_inicio 
            and {$this->conn}_pedido.created_at <= $fecha_fin and {$this->conn}_pedido.estado = 2 
            $caja_condicion_fp
            
            UNION ALL
            Select 'I' as ie, 'FV' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'FV' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'FC' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'FC' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'CI' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'CI' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'CE' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'CE' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'RC' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'RC' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'RT' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'RT' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            
            UNION ALL
            Select '0' AS ie, '00' AS tipo, sum(
            case when tipodoc in ('FV', 'BI','CI','RC') then (total - COALESCE(descuento,0)) else (total*-1) end) as total 
            from {$this->conn}_documento 
            where {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion 
            and ( pizza_documento.tipodoc = 'BI' or pizza_documento.tipodoc = 'PN'
            or pizza_documento.tipodoc = 'FV'    or pizza_documento.tipodoc = 'CI' 
            or pizza_documento.tipodoc = 'RC'    or pizza_documento.tipodoc = 'FC'
            or pizza_documento.tipodoc = 'CE'    or pizza_documento.tipodoc = 'RT')
            ");
        
        $fv = DB::select("
            select concat(tp.descripcion,' ',JSON_EXTRACT(pp.obs, '$.tamano')) as descripcion, 
            sum(pp.cant) as cantidad, sum(pp.total) as total, numdoc, d.id from {$this->conn}_documento d

            join {$this->conn}_pedido as p
            on p.id = d.pedido_id

            join {$this->conn}_producto_pedido as pp
            on p.id = pp.pedido_id

            join {$this->conn}_producto as pr
            on pr.id = pp.producto_id

            join {$this->conn}_tipo_producto as tp
            on pr.tipo_producto_id = tp.id

            where d.fecha_anulado is null and d.created_at >= $fecha_inicio and d.created_at <= $fecha_fin and d.tipodoc = 'FV' 
            $caja_condicion_d 
            group by 1
            
            UNION ALL
            Select 'Otros' as descripcion, 1 as cantidad, sum(total) as total, numdoc, id
            from pizza_documento where fecha_anulado is null and tipodoc = 'FV' and (pedido_id = 0 or pedido_id is NULL)
            and created_at >= $fecha_inicio and created_at <= $fecha_fin 
            $caja_condicion_f
            ");
        
        $fv_count = DB::select("
            select min(numdoc) as min, max(numdoc) as max, count(numdoc) as count
            from {$this->conn}_documento d
            where d.created_at >= $fecha_inicio and d.created_at <= $fecha_fin and d.tipodoc = 'FV' 
            $caja_condicion_d 
            ");
        
        $anulados = DB::select("
            select tipodoc, codprefijo, numdoc from pizza_documento d
            where d.created_at >= $fecha_inicio and d.created_at <= $fecha_fin and d.fecha_anulado is not null 
            $caja_condicion_d 
            ");

        $descuentos = DB::select("
            select sum(COALESCE(d.descuento, 0)) as v
            from pizza_documento d
            where d.tipodoc = 'FV'
            and d.created_at >= $fecha_inicio
            and d.created_at <= $fecha_fin 
            $caja_condicion_d
            ");
    
        $propinas = DB::select("
            select sum(COALESCE(p.propina, 0)) as v
            from pizza_pedido p
            where p.estado = 2
            and p.created_at >= $fecha_inicio
            and p.created_at <= $fecha_fin 
            $caja_condicion_p
            ");
    
        $total = DB::select("
            SELECT sum(iva) impiva, sum(impco) impcon, sum(descuento) dcto, sum(
                case when tipodoc in ('FV','BI','RC','CI') then paga_efectivo else (0) end) efectivo, 
            SUM(paga_debito) debito, 
            sum(paga_credito) tcredito,
            sum(paga_transferencia) transferencia,
            sum(paga_plataforma) plataforma 
            FROM pizza_documento
            WHERE created_at >= $fecha_inicio
            AND created_at <= $fecha_fin 
            $caja_condicion_f
            ");
        
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");
        $fecha_inicio = date_create($fecha_inicio);
        $fecha_fin = date_create($fecha_fin);
        if(!$with_hours){
            date_add($fecha_inicio, date_interval_create_from_date_string('3 hours'));
            date_add($fecha_fin, date_interval_create_from_date_string('3 hours'));
        }
        $fecha_inicio = date_format($fecha_inicio, "d/m/Y g:ia");
        $fecha_fin = date_format($fecha_fin, "d/m/Y g:ia");
        
        $html = \App\Util\PDF::ImpCuadre($cuadre, $fv, $fv_count, $fecha_inicio, $fecha_fin, $descuentos, $propinas, $total, $caja_id, $anulados);
        if($mail){
            return response()->json(array('code'=>200,'msg'=>$html));
        }
        else{
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($html)->setPaper(array(0,0,300,1000));
            return $pdf->stream();
        }

//        return $cuadre;
    }

    public function mail($html){
        $to = "csueskun@gmail.com";
        $subject = "Mensaje desde www.h-software.co";
        $message = $html;

        $from = "www.hsoftware.co";
        $headers = "De:" . $from . "\r\n";
        $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            return 777;
        } else {
            return 666;
        }
    }
    public function reporteTipodoc(){
        $fecha_inicio = Input::get("inicio");
        $fecha_fin = Input::get("fin");
        $tipo = Input::get("tipo");
        $caja_id = Input::get("caja_id");
        $fecha_inicio = "DATE_ADD('".$fecha_inicio."', INTERVAL 3 hour)";
        $fecha_fin = "DATE_ADD('".$fecha_fin."', INTERVAL 3 hour)";
        $nombre = Input::get("nombre");
        if($tipo == 'FV'){
            return $this->reporteFv($nombre, $fecha_inicio, $fecha_fin, $caja_id);
        }
        return $this->reporteTipodocPos($nombre, $tipo, $fecha_inicio, $fecha_fin, $caja_id);
    }

    public function preReporteVentas(){
        $fecha_inicio = Input::get("inicio");
        $fecha_fin = Input::get("fin");
        $fecha_inicio = "DATE_ADD('".$fecha_inicio."', INTERVAL 3 hour)";
        $fecha_fin = "DATE_ADD('".$fecha_fin."', INTERVAL 3 hour)";
        return $this->reporteVentas($fecha_inicio, $fecha_fin);
    }

    public function reporteTipodocPos($nombre, $tipo, $fecha_inicio, $fecha_fin, $caja_id){
        $caja_sql = '';
        if($caja_id == '0'){
        }
        else{
            $caja_sql = "and d.caja_id = $caja_id";
        }
        $reporte = DB::select("
        select 0, dd.detalle as des, sum(dd.cantidad) as x, sum(dd.total) as v
        from pizza_detalle_documento dd
        join pizza_documento d
        on dd.documento_id = d.id
        where d.tipodoc = '$tipo' 
        $caja_sql 
        and dd.created_at >= $fecha_inicio
        and dd.created_at <= $fecha_fin
        group by 2
        order by 4 desc
        ");
        $fecha_inicio = substr($fecha_inicio,-38,-28);
        $fecha_fin = substr($fecha_fin,-38,-28);
        $config = app('App\Http\Controllers\ConfigController')->first();
        return (\App\Util\POS::reporteTipodoc($nombre, $config, $reporte, $fecha_inicio, $fecha_fin, [], $caja_id));
    }

    public function reporteFv($nombre, $fecha_inicio, $fecha_fin, $caja_id){
        $caja_sql = '';
        if($caja_id == '0'){
        }
        else{
            $caja_sql = "and d.caja_id = $caja_id";
        }
        $reporte = DB::select("
        select dd.producto_id, coalesce(dd.detalle,p.descripcion,'OTRO') as des, sum(dd.cantidad) as x, sum(dd.total) as v 
        from pizza_detalle_documento dd
        left join pizza_producto as p
        on p.id = dd.producto_id
        join pizza_documento d
        on dd.documento_id = d.id
        where dd.producto_id is not null and d.tipodoc = 'FV'
        and dd.created_at >= $fecha_inicio
        and dd.created_at <= $fecha_fin 
        $caja_sql 
        group by 1
        union all
        select 0, dd.detalle as des, sum(dd.cantidad), sum(dd.total)
        from pizza_detalle_documento dd
        join pizza_documento d
        on dd.documento_id = d.id
        where dd.producto_id is null and d.tipodoc = 'FV' 
        $caja_sql
        and dd.created_at >= $fecha_inicio
        and dd.created_at <= $fecha_fin
        group by 2
        order by 4 desc
        ");
        $descuentos = DB::select("
        select sum(COALESCE(d.descuento, 0)) as v
        from pizza_documento d
        where d.tipodoc = 'FV' 
        $caja_sql
        and d.created_at >= $fecha_inicio
        and d.created_at <= $fecha_fin
        ");
        $fecha_inicio = substr($fecha_inicio,-38,-28);
        $fecha_fin = substr($fecha_fin,-38,-28);
        $config = app('App\Http\Controllers\ConfigController')->first();
        return (\App\Util\POS::reporteTipodoc($nombre, $config, $reporte, $fecha_inicio, $fecha_fin, $descuentos, $caja_id));
    }
    public function reporteVentas($fecha_inicio, $fecha_fin){
//        $reporte = DB::select("select * from `pizza_documento` where `created_at` >= $fecha_inicio and `created_at` <= $fecha_fin");
        $reporte = Documento::whereRaw("`created_at` >= $fecha_inicio and `created_at` <= $fecha_fin")->with('tercero')->get();
        $fecha_inicio = substr($fecha_inicio,-38,-28);
        $fecha_fin = substr($fecha_fin,-38,-28);
        $config = app('App\Http\Controllers\ConfigController')->first();
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML(\App\Util\PDF::reporteVentas($config, $reporte, $fecha_inicio, $fecha_fin))->setPaper('letter');
        return $pdf->stream();
    }

    public function preCuadrePos() {
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");

        $with_hours = Input::get('hora');
        if($with_hours){
            $fecha_inicio = "'$fecha_inicio'";
            $fecha_fin = "'$fecha_fin'";
        }
        else{
            $fecha_inicio = "DATE_ADD('".$fecha_inicio."', INTERVAL 3 hour)";
            $fecha_fin = "DATE_ADD('".$fecha_fin."', INTERVAL 3 hour)";
        }

        $caja_id = Input::get("caja_id");
        $caja_condicion = '';
        $caja_condicion_d = '';
        $caja_condicion_f = '';
        $caja_condicion_p = '';
        $caja_condicion_fp = '';
        
        if($caja_id == '0'){
        }
        else{
            $caja_condicion = "and {$this->conn}_documento.caja_id = $caja_id";
            $caja_condicion_d = "and d.caja_id = $caja_id";
            $caja_condicion_p = "and p.caja_id = $caja_id";
            $caja_condicion_f = "and caja_id = $caja_id";
            $caja_condicion_fp = "and {$this->conn}_pedido.caja_id = $caja_id";
        }
        // var_dump($caja_condicion);
        // var_dump($caja_condicion_d);
        // var_dump($caja_condicion_p);
        // var_dump($caja_condicion_f);
        // var_dump($caja_condicion_fp);
        // asdasd;

        $cuadre = DB::select("
            Select 'I' as ie, 'BI' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'BI' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion

            UNION ALL
            Select 'E' as ie, 'PN' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'PN' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'FV' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'FV' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'FC' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'FC' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'RC' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'RC' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'RT' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'RT' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'I' as ie, 'CI' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'CI' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select 'E' as ie, 'CE' as tipo, COALESCE(sum(total),0) as total 
            from {$this->conn}_documento where {$this->conn}_documento.tipodoc = 'CE' 
            and {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            
            UNION ALL
            Select '0' AS ie, '00' AS tipo, sum(
            case when tipodoc in ('FV','BI','RC','CI') then total else (total*-1) end) as total 
            from {$this->conn}_documento 
            where {$this->conn}_documento.created_at >= $fecha_inicio 
            and {$this->conn}_documento.created_at <= $fecha_fin 
            $caja_condicion
            and ( pizza_documento.tipodoc = 'BI' or pizza_documento.tipodoc = 'PN'
            or pizza_documento.tipodoc = 'FV'    or pizza_documento.tipodoc = 'FC'
            or pizza_documento.tipodoc = 'RC'    or pizza_documento.tipodoc = 'RT'
            or pizza_documento.tipodoc = 'CI'    or pizza_documento.tipodoc = 'CE' )
            ");

        $fv = DB::select("
            select concat(tp.descripcion,' ',JSON_EXTRACT(pp.obs, '$.tamano')) as descripcion, 
            sum(pp.cant) as cantidad, sum(pp.total) as total, d.numdoc, d.id from {$this->conn}_documento d

            join {$this->conn}_pedido as p
            on p.id = d.pedido_id

            join {$this->conn}_producto_pedido as pp
            on p.id = pp.pedido_id

            join {$this->conn}_producto as pr
            on pr.id = pp.producto_id

            join {$this->conn}_tipo_producto as tp
            on pr.tipo_producto_id = tp.id

            where d.fecha_anulado is null 
            and d.created_at >= $fecha_inicio 
            and d.created_at <= $fecha_fin and d.tipodoc = 'FV' 
            $caja_condicion_d
            group by 1
            
            UNION ALL
            Select 'Otros' as descripcion, 1 as cantidad, sum(total) as total, numdoc, id
            from pizza_documento where fecha_anulado is null and tipodoc = 'FV' and (pedido_id = 0 or pedido_id is NULL)
            and created_at >= $fecha_inicio and created_at <= $fecha_fin  
            $caja_condicion_f
        ");

        $fv_count = DB::select("
            select min(numdoc) as min, max(numdoc) as max, count(numdoc) as count
            from {$this->conn}_documento d
            where d.created_at >= $fecha_inicio and d.created_at <= $fecha_fin and d.tipodoc = 'FV' 
            $caja_condicion_d 
        ");

        $anulados = DB::select("
            select tipodoc, codprefijo, numdoc from {$this->conn}_documento d
            where d.created_at >= $fecha_inicio and d.created_at <= $fecha_fin and d.fecha_anulado is not null 
            $caja_condicion_d 
        ");


        $descuentos = DB::select("
            select sum(COALESCE(d.descuento, 0)) as v
            from pizza_documento d
            where d.tipodoc = 'FV'
            and d.created_at >= $fecha_inicio
            and d.created_at <= $fecha_fin 
            $caja_condicion_d
        ");

        $propinas = DB::select("
            select sum(COALESCE(p.propina, 0)) as v
            from pizza_pedido p
            where p.estado = 2
            and p.created_at >= $fecha_inicio
            and p.created_at <= $fecha_fin 
            $caja_condicion_p
        ");

        $total = DB::select("
            SELECT sum(iva) impiva, sum(impco) impcon, sum(descuento) dcto, sum(
                case when tipodoc in ('FV','BI','RC','CI') then paga_efectivo else (0) end) efectivo, 
            SUM(paga_debito) debito, SUM(paga_credito) tcredito, 
            sum(paga_transferencia) transferencia, sum(paga_plataforma) plataforma
            FROM pizza_documento
            WHERE created_at >= $fecha_inicio
            AND created_at <= $fecha_fin 
            $caja_condicion_f
        ");

        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");
        $fecha_inicio = date_create($fecha_inicio);
        $fecha_fin = date_create($fecha_fin);
        if(!$with_hours){
            date_add($fecha_inicio, date_interval_create_from_date_string('3 hours'));
            date_add($fecha_fin, date_interval_create_from_date_string('3 hours'));
        }
        $fecha_inicio = date_format($fecha_inicio, "d/m/Y g:ia");
        $fecha_fin = date_format($fecha_fin, "d/m/Y g:ia");

        return (\App\Util\POS::cuadrePos(app('App\Http\Controllers\ConfigController')->first(),$cuadre, $fv, $fv_count, $fecha_inicio, $fecha_fin, $descuentos, $propinas, $total, $caja_id, Auth::user()->caja_id, $anulados));
    }

    public function crear(){
        $postData = Input::all();
        $rules = array(
                'tipodoc' => 'required',
                'mesa_id' => 'required',
                'pedido_id' => '',
                'num_documento' => '',
                'banco' => '',
                'paga_efectivo' => '',
                'paga_transferencia' => '',
                'paga_debito' => '',
                'paga_credito' => '',
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('documento/crear')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Completó el Registro."]);
        } else {
            $productos = json_decode(Input::get('productos'));
            
            if($productos->detalles){
                $total = 0;
                foreach($productos->detalles as $detalle){
                    $total+=$detalle->total;
                }
            }
            else{
                $total = Input::get('total');
            }
            $documento = new Documento;
            $documento->tipodoc = Input::get('tipodoc');
            $documento->tipoie = Input::get('tipoie');
            $documento->mesa_id = Input::get('mesa_id');
            $documento->tercero_id = Input::get('tercero_id');
            $documento->banco = Input::get('banco');
            $documento->num_documento = Input::get('num_documento');
            $documento->paga_efectivo = Input::get('paga_efectivo');
            $documento->paga_transferencia = Input::get('paga_transferencia');
            $documento->paga_plataforma = Input::get('paga_plataforma');
            $documento->paga_debito = Input::get('paga_debito');
            $documento->paga_credito = Input::get('paga_credito');
            $documento->observacion = Input::get('observacion');
            $documento->caja_id = Input::get('caja_id');
            $documento->created_at = Input::get('created_at').':00';
            $documento->usuario_id = Auth::user()->id;
            $documento->total = $total;
            $documento->pedido_id = 0;

            $tipo_documento_ = app('App\Http\Controllers\TipoDocumentoController')->siguienteTipo($documento->tipodoc);
            // $documento->numdoc = str_pad($tipo_documento_->consecutivo, 8, "0", STR_PAD_LEFT);
            $documento->numdoc = strval($tipo_documento_->consecutivo);
            if($documento->tipodoc=='FV'){
                $config = app('App\Http\Controllers\ConfigController')->first();
                $documento->codprefijo = $config->fvcodprefijo;
            }
            $documento->save();

            $tipo_documento_->aumentarConsecutivo();
            
            foreach($productos->detalles as $detalle){
                $detalleDocumento = new DetalleDocumento;
                $detalleDocumento->documento_id = $documento->id;
                if($detalle->tipo=='ingrediente'){
                    $detalleDocumento->ingrediente_id = $detalle->producto_id;
                }
                else{
                    $detalleDocumento->producto_id = $detalle->producto_id;
                }
                $detalleDocumento->cantidad = $detalle->cantidad;
                $detalleDocumento->valor = $detalle->valor;
                $detalleDocumento->total = $detalle->total;
                $detalleDocumento->detalle = $detalle->detalle;
                $detalleDocumento->save();
                
                if($detalle->tipo=='ingrediente'){
                    if($documento->tipodoc == 'FC' || $documento->tipodoc == 'NI'){
                        app('App\Http\Controllers\SaldosProductoController')->entradaFromDetalleDocumentoIngrediente($detalleDocumento);
                    }
                    else if($documento->tipodoc == 'FV'){
                        app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumentoIngrediente($detalleDocumento);
                    }
                    else if($documento->tipodoc == 'CO'){
                        app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumentoIngrediente($detalleDocumento);
                    }
                }
                else{
                    if($documento->tipodoc == 'FC' || $documento->tipodoc == 'NI'){
                        app('App\Http\Controllers\SaldosProductoController')->entradaFromDetalleDocumento($detalleDocumento);
                    }
                    else if($documento->tipodoc == 'FV'){
                        app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumento($detalleDocumento);
                    }
                    else if($documento->tipodoc == 'CO'){
                        app('App\Http\Controllers\SaldosProductoController')->salidaFromDetalleDocumento($detalleDocumento);
                    }
                }

            }
        
            return Redirect::to('documento/listar')
            ->with('status', ["success"=>"Registro Agregado."]);
        }
    }
    
    public function editar(){
        $postData = Input::all();
        $documento = Documento::find(Input::get('id'));
        
        $rules = array(
                'tipodoc' => 'required',
                'numdoc' => 'required',
                'mesa_id' => 'required',
                'pedido_id' => '',
                'num_documento' => '',
                'banco' => '',
                'paga_efectivo' => '',
                'paga_transferencia' => '',
                'paga_debito' => '',
                'paga_credito' => '',
                'total' => 'required'
                );
        $validator = Validator::make($postData, $rules);
        if ($validator->fails()) {

            return Redirect::to('documento/editar/'.$documento->id)
                            ->withErrors($validator)
                            ->withInput(Input::except('password'))
                            ->with('status', ["danger" => "No Se Editó el Registro."]);
        } else {
            
            $documento->tipodoc = Input::get('tipodoc');
            $documento->numdoc = Input::get('numdoc');
            $documento->mesa_id = Input::get('mesa_id');
            $documento->pedido_id = Input::get('pedido_id');
            $documento->num_documento = Input::get('num_documento');
            $documento->banco = Input::get('banco');
            $documento->paga_efectivo = Input::get('paga_efectivo');
            $documento->paga_transferencia = Input::get('paga_transferencia');
            $documento->paga_plataforma = Input::get('paga_plataforma');
            $documento->paga_debito = Input::get('paga_debito');
            $documento->paga_credito = Input::get('paga_credito');
            $documento->total = Input::get('total');
            $documento->tercero_id = Input::get('tercero_id');
            $documento->caja_id = Input::get('caja_id');
            $documento->save();
        
            return Redirect::to('documento/editar/'.$documento->id)
            ->with('status', ["success"=>"Registro Editado."]);
        }
    }
    
    public function editarPost($id){
        $postData = Input::all();
        $documento = Documento::find($id);
        $documento->mesa_id = Input::get('mesa_id');
        $documento->observacion = Input::get('observacion');
        $documento->created_at = Input::get('created_at').':00';
        $documento->tercero_id = Input::get('tercero_id');
        $documento->num_documento = Input::get('num_documento');
        $documento->banco = Input::get('banco');
        $documento->paga_efectivo = Input::get('paga_efectivo');
        $documento->paga_transferencia = Input::get('paga_transferencia');
        $documento->paga_plataforma = Input::get('paga_plataforma');
        $documento->paga_debito = Input::get('paga_debito');
        $documento->paga_credito = Input::get('paga_credito');
            
        $documento->save();
        return response($documento, 200)->header('Content-Type', 'application/json');
    }
    public function borrar() {
        Documento::destroy(Input::get('id'));
        return Redirect::to('documento/listar')
                        ->with('status', ["success" => "Registro borrado."]);
    }
    
    public function vistaLista(){
        return view('documento.listar')->with("documento_lista",$this->paginar(Input::all()));
    }
    
    public function paginar($input) {
        $tipodoc = isset($input["tipodoc"])?$input["tipodoc"]:"";;
        $buscar = isset($input["buscar"])?$input["buscar"]:"";
        $ordenar_por = isset($input["ordenar_por"])?$input["ordenar_por"]:"";
        $sentido = isset($input["sentido"])?$input["sentido"]:"";
        $por_pagina = isset($input["por_pagina"])?$input["por_pagina"]:100;

        $where = [];
        if($tipodoc!=""){
            $where['tipodoc'] = $tipodoc;
        }
        return $this->paginar_($buscar, $ordenar_por, $sentido, $por_pagina, $where);
    }
    
    public function paginar_($buscar, $ordenar_por, $sentido, $por_pagina, $where) {
        if($where==null){
            if($ordenar_por==""||$ordenar_por==null){
                return Documento::Where("numdoc","like", "%$buscar%")
                    ->orderBy('created_at', 'desc')
                    ->paginate($por_pagina);
            }
            return Documento::Where("numdoc","like", "%$buscar%")
                ->orderBy($ordenar_por, $sentido)
                ->paginate($por_pagina);
        }
        if($ordenar_por==""||$ordenar_por==null){
            return Documento::Where("numdoc","like", "%$buscar%")
                ->where($where)
                ->orderBy('created_at', 'desc')
                ->paginate($por_pagina);
        }
        return Documento::Where("numdoc","like", "%$buscar%")
            ->where($where)
            ->orderBy($ordenar_por, $sentido)
            ->paginate($por_pagina);
    }

    public function vistaEditar($id){
        return view('documento.editar')
        ->with("documento", Documento::find($id))
        ->with("tercero_lista", app('App\Http\Controllers\TerceroController')->todos())
        ->with("detalles", app('App\Http\Controllers\DetalleDocumentoController')->buscarPorDocumento($id));
    }

    public function impDocumento($id){
        $documento = $this->encontrar($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML(\App\Util\PDF::ImpDocumento($documento))->setPaper('letter');
        return $pdf->stream();
    }

    public function saveDomicilioDocumento(Request $request){

        $ie = [
            'RC'=> 'I',
            'RT'=> 'E',
            'CE'=> 'E',
            'CI'=> 'I'
        ];

        $data = $request->all();
        $documento = new Documento;
        $documento->total = $request->valor;
        $documento->observacion = $request->observacion;
        $documento->tipodoc = $request->tipodoc;
        $documento->pedido_id = $request->pedido_id;
        $documento->mesa_id = $request->mesa_id;
        $documento->usuario_id = Auth::user()->id;
        $documento->caja_id = Auth::user()->caja_id;
        $documento->tipoie = $ie[$request->tipodoc];
        $documento->paga_efectivo = $request->valor;
        $documento->tercero_id = 1;
        $tipo_documento_ = app('App\Http\Controllers\TipoDocumentoController')->siguienteTipo($documento->tipodoc);
        // $documento->numdoc = str_pad($tipo_documento_->consecutivo, 8, "0", STR_PAD_LEFT);
        $documento->numdoc = strval($tipo_documento_->consecutivo);
        if($documento->tipodoc=='FV'){
            $config = app('App\Http\Controllers\ConfigController')->first();
            $documento->codprefijo = $config->fvcodprefijo;
        }
        $documento->save();
        $tipo_documento_->aumentarConsecutivo();

        $detalleDocumento = new DetalleDocumento;
        $detalleDocumento->documento_id = $documento->id;
        $detalleDocumento->producto_id = 1;
        $detalleDocumento->cantidad = 1;
        $detalleDocumento->valor = $documento->total;
        $detalleDocumento->total = $documento->total;
        $detalleDocumento->detalle = $documento->observacion;
        $detalleDocumento->save();

        return response(array('data'=>''), 200)->header('Content-Type', 'application/json');
    }

    public function savePagoCompra(Request $request){

        $ie = [
            'RC'=> 'I',
            'RT'=> 'E',
            'CE'=> 'E',
            'BI'=> 'I',
            'PN'=> 'E',
            'CI'=> 'I'
        ];

        $data = $request->all();
        $documento = new Documento;
        $documento->total = $request->valor;
        $documento->observacion = $request->tipo . ' ' .$request->observacion;
        $documento->tipodoc = $request->tipodoc;
        $documento->pedido_id = 0;
        $documento->mesa_id = 999;
        $documento->usuario_id = Auth::user()->id;
        $documento->caja_id = Auth::user()->caja_id;
        $documento->tipoie = $ie[$request->tipodoc];
        $documento->paga_efectivo = $request->valor;
        $documento->tercero_id = 1;
        $tipo_documento_ = app('App\Http\Controllers\TipoDocumentoController')->siguienteTipo($documento->tipodoc);
        // $documento->numdoc = str_pad($tipo_documento_->consecutivo, 8, "0", STR_PAD_LEFT);
        $documento->numdoc = strval($tipo_documento_->consecutivo);
        if($documento->tipodoc=='FV'){
            $config = app('App\Http\Controllers\ConfigController')->first();
            $documento->codprefijo = $config->fvcodprefijo;
        }
        $documento->save();
        $tipo_documento_->aumentarConsecutivo();

        $detalleDocumento = new DetalleDocumento;
        $detalleDocumento->documento_id = $documento->id;
        $detalleDocumento->producto_id = 1;
        $detalleDocumento->cantidad = 1;
        $detalleDocumento->valor = $documento->total;
        $detalleDocumento->total = $documento->total;
        $detalleDocumento->detalle = $documento->observacion;
        $detalleDocumento->save();

        return response(array('data'=>''), 200)->header('Content-Type', 'application/json');
    }

    public function anular($id, Request $request){
        $justificacion = Input::get('justificacion');
        $data = ['id'=> $id, 'r'=> $request->justificacion, 'justificacion'=>$justificacion];
        $documento = Documento::find($id);
        $inventario = $this->inventarioFromDocumento($id);
        if($documento->tipoie='I'){
            $ingredientes_ = [];
            $productos_ = [];
            foreach($inventario['ingredientes'] as $ingrediente){
                $ingrediente['cantidad'] = $ingrediente['cantidad']*-1;
                $ingredientes_[] = $ingrediente;
            }
            foreach($inventario['productos'] as $producto){
                $producto['cantidad'] = $producto['cantidad']*-1;
                $productos_[] = $producto;
            }
            $inventario['ingredientes'] = $ingredientes_;
            $inventario['productos'] = $productos_;
        }
        $saldosController = new SaldosProductoController;
        $res = $saldosController->sumarExistencias($inventario);
        $documento->fecha_anulado = date("Y-m-d H:i:s");
        $documento->total = 0;
        $documento->total_iva = 0;
        $documento->paga_efectivo = 0;
        $documento->paga_transferencia = 0;
        $documento->paga_plataforma = 0;
        $documento->paga_debito = 0;
        $documento->paga_credito = 0;
        $documento->debe = 0;
        $documento->descuento = 0;
        $documento->iva = 0;
        $documento->impco = 0;
        $documento->justificacion_anula = $justificacion;
        $documento->save();

        $detalles_documento = DetalleDocumento::where('documento_id',$id)->get();
        foreach($detalles_documento as $detalle){
            $detalle->cantidad = 0;
            $detalle->valor = 0;
            $detalle->total = 0;
            $detalle->impco = 0;
            $detalle->iva = 0;
            $detalle->save();
        }

        return response($documento, 200)->header('Content-Type', 'application/json');
    }

    public function inventarioFromDocumento($documento){
        $inventario = ['ingredientes'=>[], 'productos'=>[]];
        $documento = Documento::with('pedido.productos', 'detalles.producto.ingredientes')->find($documento);
        $mes = explode('-',$documento->created_at);
        $mes = $mes[1];
        $ingredientes = [];
        $productos = [];
        if($documento->pedido_id){
            $pp = ProductoPedido::where('pedido_id', $documento->pedido_id)->with('producto.ingredientes')->get();
            foreach($pp as $p){
                if($p->combo){
                    $p->observation = json_decode($p->combo);
                    $p->observation = json_decode($p->observation);
                }
            }
            foreach($pp as $p){
                if($p->producto->terminado){
                    $productos[] = ['id'=>$p->producto->id, 'cantidad'=>$p->cant, 'mes'=>$mes];
                }
                else{
                    $p->observation = json_decode($p->obs);
                    foreach($p->producto->ingredientes as $i){
                        if(floatval($i->pivot->cantidad)==0){
                            continue;
                        }
                        $ingredientes[] = ['id'=>$i->id, 'cantidad'=>floatval($i->pivot->cantidad)*$p->cant, 'mes'=>$mes];
                    }
                    foreach($p->observation->adicionales as $a){
                        if(floatval($a->cantidad)==0){
                            continue;
                        }
                        $ingredientes[] = ['id'=>intval($a->ingrediente), 'cantidad'=>floatval($i->cantidad)*$p->cant, 'mes'=>$mes];
                    }
                    foreach($p->observation->sin_ingredientes as $i){
                        if(floatval($i->cantidad)==0){
                            continue;
                        }
                        $ingredientes[] = ['id'=>intval($i->id), 'cantidad'=>floatval($i->cantidad)*-1*$p->cant, 'mes'=>$mes];
                    }
                }
            }
        }
        else{
            foreach($documento->detalles as $d){
                if($d->ingrediente_id){
                    $ingredientes[] = ['id'=>intval($d->ingrediente_id), 'cantidad'=>floatval($d->cantidad), 'mes'=>$mes];
                }
                if($d->producto_id){
                    if($d->producto->terminado){
                        $productos[] = ['id'=>intval($d->producto_id), 'cantidad'=>floatval($d->cantidad), 'mes'=>$mes];
                    }
                    else{
                        foreach($d->producto->ingredientes as $i){
                            if(floatval($i->pivot->cantidad)==0){
                                continue;
                            }
                            $ingredientes[] = ['id'=>$i->id, 'cantidad'=>floatval($i->pivot->cantidad), 'mes'=>$mes];
                        }
                    }
                }
            }
        }
        $inventario['productos'] = $productos;
        $inventario['ingredientes'] = $ingredientes;
        return $inventario;
    }
    public function posPrint(){
        $id = Input::get('id');
        $documento = Documento::find($id);
        return (\App\Util\POS::printDocumento(app('App\Http\Controllers\ConfigController')->first(),$documento, Auth::user()->caja_id));
    }

    public function patchDocumento($pedido){
        try {
            $documento = Documento::where('pedido_id', $pedido)->first();
            foreach(Input::all() as $key=>$value) {
                $documento->$key = $value;
            }
            $documento->save();
            return $documento;
        } catch (\Throwable $th) {
            var_dump($th);
            return [];
        }
    }

    public function getMonthFV(Request $request){
        $month = "month(CURRENT_DATE())";
        $month_ = Input::get("mes");
        if($month_){
            $month = $month_;
        }

        $data = DB::select("
            SELECT sum(total) as total, DAY(created_at) as day 
            FROM pizza_documento 
            WHERE tipodoc = 'FV'
            and month(created_at) = $month
            and year(created_at) = year(CURRENT_DATE())
            group by 2
        ");
        return $data;
    }

    public function getVenderores(Request $request){
        $month = "month(CURRENT_DATE())";
        $month_ = Input::get("mes");
        if($month_){
            $month = $month_;
        }
        $data = DB::select("
            SELECT sum(d.total) as total, u.usuario as usuario
            FROM pizza_documento as d
            inner JOIN pizza_pedido as p
            on p.id = d.pedido_id
            inner JOIN pizza_users as u
            on u.id = p.user_id
            WHERE tipodoc = 'FV'
            and month(d.created_at) = $month
            and year(d.created_at) = year(CURRENT_DATE())
            group by 2
            order by 1 desc
        ");
        return response()->json($data);
    }

    public function getMonthExpenses(Request $request){
        $month = "month(CURRENT_DATE())";
        $month_ = Input::get("mes");
        if($month_){
            $month = $month_;
        }
        $data = DB::select("
            SELECT sum(total) as total, DAY(created_at) as day 
            FROM pizza_documento 
            WHERE tipodoc in ('RT', 'CE', 'PN', 'FC','NI')
            and month(created_at) = $month
            and year(created_at) = year(CURRENT_DATE())
            group by 2
        ");
        return response()->json($data);
    }

    public function getMonthData(){
        $fv = $this->getMonthFV();
        $egresos = $this->getMonthExpenses();
        return response()->json(['ventas'=>$fv, 'gastos'=>$egresos]);
    }
}