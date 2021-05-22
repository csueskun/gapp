<?php

namespace App\Http\Controllers;
use App\Config;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use DB;

class InformeController extends Controller
{
    public $conn = 'pizza';
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
        $this->conn = Auth::user()->conn;
    }*/
    public function ini() {
        return view('varios.informe')
        ->with('ano', 0)
        ->with('mes', '')
        ->with('sql', $this->tortaTipoProducto())
        ->with('sql2', null)
        ->with('anos', $this->anosConPedido());
    }
    public function ano($ano) {
        return view('varios.informe')
        ->with('ano', $ano)
        ->with('mes', 0)
        ->with('sql', $this->tortaTipoProducto($ano))
        ->with('sql2', $this->pedidosMes($ano))
        ->with('anos', $this->anosConPedido());
    }
    public function mes($ano, $mes) {
        return view('varios.informe')
        ->with('ano', $ano)
        ->with('mes', $mes)
        ->with('sql', $this->tortaTipoProducto($ano, $mes))
        ->with('sql2', $this->pedidosMes($ano))
        ->with('anos', $this->anosConPedido());
    }

    public function tortaTipoProducto($ano = null, $mes = null) {
        $ano_mes = '';
        if($ano!=null){
            if($mes == null){
                $ano_mes = "and {$this->conn}_pedido.created_at >= '$ano-01-01 00:00:00' and {$this->conn}_pedido.created_at < '".($ano+1)."-01-01 00:00:00'";
            }
            else{
                $ano_mes = "and {$this->conn}_pedido.created_at >= '$ano-$mes-01 00:00:00' and {$this->conn}_pedido.created_at < '$ano-".($mes+1)."-01 00:00:00'";
            }
        }
        
        return DB::select("
    SELECT tp.descripcion,
        (
            select coalesce(sum({$this->conn}_producto_pedido.total),0) from {$this->conn}_pedido
            join {$this->conn}_producto_pedido
            on {$this->conn}_pedido.id = {$this->conn}_producto_pedido.pedido_id
            join {$this->conn}_producto
            on {$this->conn}_producto_pedido.producto_id = {$this->conn}_producto.id
            join {$this->conn}_tipo_producto
            on {$this->conn}_producto.tipo_producto_id = {$this->conn}_tipo_producto.id
            where {$this->conn}_tipo_producto.id = tp.id $ano_mes
        ) as total
        FROM {$this->conn}_tipo_producto tp order by 2 desc
        ");
    }
    public function tortaTipoProductoRango($ano, $mes, $ano2, $mes2) {
        if($mes2 == 12){
            $mes2 = 1;
            $ano2 = $ano+1;
        }
        else{
            $mes2 = $mes2 +1;
        }
        $ano_mes = "and {$this->conn}_pedido.created_at >= '$ano-$mes-01 00:00:00' and {$this->conn}_pedido.created_at < '$ano2-$mes2-01 00:00:00' ";

        return DB::select("
        SELECT tp.descripcion,
        (
            select coalesce(sum({$this->conn}_producto_pedido.total),0) from {$this->conn}_pedido
            join {$this->conn}_producto_pedido
            on {$this->conn}_pedido.id = {$this->conn}_producto_pedido.pedido_id
            join {$this->conn}_producto
            on {$this->conn}_producto_pedido.producto_id = {$this->conn}_producto.id
            join {$this->conn}_tipo_producto
            on {$this->conn}_producto.tipo_producto_id = {$this->conn}_tipo_producto.id
            where {$this->conn}_tipo_producto.id = tp.id $ano_mes
        ) as total
        FROM {$this->conn}_tipo_producto tp order by 2 desc
        ");
    }

    public function anosConPedido(){
        $sql = DB::select("
            select distinct(YEAR({$this->conn}_pedido.created_at)) as ano from {$this->conn}_pedido order by 1
        ");
        return $sql;
    }

    public function pedidosAno(){
        $sql = DB::select("
        SELECT YEAR({$this->conn}_pedido.created_at) as ano, SUM({$this->conn}_pedido.total) as suma
        FROM {$this->conn}_pedido
        GROUP BY YEAR({$this->conn}_pedido.created_at) order by 1
        ");
        return $sql;
    }

    public function pedidosMes($ano){
        $sql = DB::select("
        SELECT MONTH({$this->conn}_pedido.created_at) as mes, SUM({$this->conn}_pedido.total) as suma
        FROM {$this->conn}_pedido
        where {$this->conn}_pedido.created_at >= '$ano-01-01 00:00:00' and {$this->conn}_pedido.created_at < '".($ano+1)."-01-01 00:00:00'
        GROUP BY MONTH({$this->conn}_pedido.created_at) order by 1
        ");
        return $sql;
    }
    
    public function iniRango() {
        $anos = $this->anosConPedido();
        $ano =$anos[0]->ano; 
        $mes = 1;
        $ano2 = $anos[count($anos)-1]->ano;
        $mes2=12;

        return view('varios.informe_rango')
        ->with('ano', $ano)
        ->with('mes', $mes)
        ->with('ano2', $ano2)
        ->with('mes2', $mes2)
        ->with('sql', $this->tortaTipoProductoRango($ano, $mes, $ano2, $mes2))
        ->with('anos', $this->anosConPedido());
    }
    public function viewRango($ano, $mes, $ano2, $mes2) {
        return view('varios.informe_rango')
        ->with('ano', $ano)
        ->with('mes', $mes)
        ->with('ano2', $ano2)
        ->with('mes2', $mes2)
        ->with('sql', $this->tortaTipoProductoRango($ano, $mes, $ano2, $mes2))
        ->with('anos', $this->anosConPedido());
    }
    public function removeRango($mes=1, $mes2=0, $maxDays=32) {
        $ano_mes = 0;
        $ano = $maxDays;
        if(($mes>$mes2 && !$mes<12)){
            if(!($mes2>0)){
                return "csproduct";
            }
            $sql = DB::select("
        SELECT MONTH({$this->conn}_pedido.created_at) as mes, SUM({$this->conn}_pedido.total) as suma
        FROM {$this->conn}_pedido
        where {$this->conn}_pedido.created_at >= '$ano-01-01 00:00:00' and {$this->conn}_pedido.created_at < '".($ano+1)."-01-01 00:00:00'
        GROUP BY MONTH({$this->conn}_pedido.created_at) order by 1
        ");
            return $sql;
        }
        return DB::select("
    SELECT tp.descripcion,
        (
            select coalesce(sum({$this->conn}_producto_pedido.total),0) from {$this->conn}_pedido
            join {$this->conn}_producto_pedido
            on {$this->conn}_pedido.id = {$this->conn}_producto_pedido.pedido_id
            join {$this->conn}_producto
            on {$this->conn}_producto_pedido.producto_id = {$this->conn}_producto.id
            join {$this->conn}_tipo_producto
            on {$this->conn}_producto.tipo_producto_id = {$this->conn}_tipo_producto.id
            where {$this->conn}_tipo_producto.id = tp.id $ano_mes
        ) as total
        FROM {$this->conn}_tipo_producto tp order by 2 desc
        ");
    }

    public function vistaMesero() {
        $meseros = User::all();
        return view('mesero.informe')->with('meseros', $meseros);
    }

    public function vistaBanco() {
        return view('bancos.informe');
    }

    public function vistaMasVendido() {
        return view('varios.vendido');
    }

    public function sqlMasVendido() {
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");
        $cond = '';
        if($fecha_inicio != null && $fecha_inicio != ''){
            $cond = "WHERE d.created_at >= '$fecha_inicio'";
        }
        if($fecha_fin != null && $fecha_fin != ''){
            if($cond == ''){
                $cond = "WHERE d.created_at <= '$fecha_fin'";
            }
            else{
                $cond .= "AND d.created_at <= '$fecha_fin'";
            }
        }
        $sql = "SELECT tp.descripcion as tipo,p.descripcion, sum(d.cantidad) as cantidad, SUM(d.total) as total
            FROM pizza_detalle_documento d
            INNER JOIN pizza_documento pd ON(pd.id = d.documento_id)  
            INNER JOIN pizza_producto p ON(p.id = d.producto_id )
            INNER JOIN pizza_tipo_producto tp ON(p.tipo_producto_id = tp.id)
            $cond
            GROUP BY 1,2
            ORDER BY sum(d.cantidad) desc, p.descripcion";
        $data = DB::select($sql);
        return response()->json([
            'data'=>$data, 'cond'=> $cond
        ]);
    }


    public function informeMesero() {
        $mesero = Input::get("mesero");
        $cond = '';
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");
        if($mesero != '0'){
            $cond = ' and d.usuario_id = '.$mesero;
            $mesero = User::find($mesero);
            $mesero = $mesero->nombres . ' ' . $mesero->apellidos;
        }
        $res = DB::select("
    select d.usuario_id,concat(u.nombres,' ',u.apellidos) usuario,d.atendido_por,concat(tipodoc,codprefijo,numdoc) factura, d.created_at fecha, fecha_anulado, tipopago,  
    concat(tp.descripcion,' ',p.descripcion) producto, dd.cantidad, dd.valor, dd.total
    from pizza_documento d inner join pizza_detalle_documento dd on(d.id = dd.documento_id)
     inner join pizza_producto  p on(p.id = dd.producto_id)
     inner join pizza_tipo_producto tp on(tp.id = p.tipo_producto_id)
     inner join pizza_users u on(d.usuario_id = u.id)
     where tipodoc = 'FV'
     and d.created_at >= '$fecha_inicio'
     and d.created_at <= '$fecha_fin' $cond
        ");
        $html = \App\Util\PDF::reporteMesero($res, $fecha_inicio, $fecha_fin, $mesero);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper("A4", "portrait");
        return $pdf->stream();
    }

    public function informeBanco() {
        $banco = Input::get("banco");
        $cond = '';
        $fecha_inicio = Input::get("fecha_inicio");
        $fecha_fin = Input::get("fecha_fin");
        if($banco != 'T'){
            $cond = " and (banco = $banco or banco is null)";
            $banco = Input::get("nombre_banco");
        }
        $res = DB::select("
        select if(banco=0,'Caja General',BANCO) as formapago, sum(paga_efectivo) as efectivo, sum(paga_debito) as debito, 
        sum(paga_credito) as credito, sum(paga_transferencia) as transferencia, sum(paga_efectivo + paga_debito + paga_credito + paga_transferencia) as total
        from pizza_documento 
        where created_at BETWEEN '$fecha_inicio' and  '$fecha_fin' and tipodoc = 'FV' $cond
        group by banco
        ");
        $pdf = PDF::loadView('bancos.template', [
            'pagos' => $res,
            'banco' => $banco,
            'fecha_inicio' => $fecha_inicio,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ]);
        return $pdf->stream();
    }
}