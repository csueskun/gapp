<?php

namespace App\Util;
class SQL{
    
    public static $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    public static $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    public static function StringAFormatoLargo($fecha){
        $fecha = date_create($fecha);
        $imp = self::$dias[date_format($fecha,'w')];
        $imp.= " ".date_format($fecha,'d');
        $imp.= " de ".self::$meses[date_format($fecha,'n')-1];
        $imp.= " de ".date_format($fecha,'Y');
        $imp.= " ".date_format($fecha,'h:i A');
        return $imp;
    }

    public static function sqlInventarioPOS(){
        return "SELECT 'P' tipo, p.descripcion,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
          AND date_format(d.created_at, '%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradant,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
           AND date_format(d.created_at, '%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
           AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) as salidant1,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0)  AS  salidant2,
        coalesce(s.existencia,0) total,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
           AND date_format(d.created_at, '%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
           AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) as salidas,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0)  AS  salida2,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.producto_id = p.id
          AND date_format(d.created_at, '%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradas1
        FROM pizza_saldos_producto  s
        inner join pizza_producto p ON(s.producto_id = p.id)
        WHERE s.producto_id is not NULL
        UNION ALL
        SELECT 'I' tipo, i.descripcion,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradant,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) salidant1,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') <= date_format(date_add(NOW(), INTERVAL -1 DAY), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0) SALIDAnt2 ,
        coalesce(s.existencia,0) total,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
            AND d.tipodoc IN ('FV','CO')
            AND dd.cantidad > 0),0) salidas,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(), '%d/%m/%Y')
            AND d.tipodoc IN ('NI')
            AND dd.cantidad < 0
        ),0) SALIDAS2 ,
        coalesce((SELECT SUM(cantidad) FROM pizza_documento d
          INNER JOIN pizza_detalle_documento dd
             ON(d.id = dd.documento_id)
          WHERE dd.ingrediente_id = i.id
            AND date_format(d.created_at,'%d/%m/%Y') = date_format(NOW(),  '%d/%m/%Y')
            AND d.tipodoc IN ('FC','NI')
            AND dd.cantidad > 0),0) entradas1
        
        FROM pizza_saldos_producto  s
        inner join pizza_ingrediente i ON(s.ingrediente_id = i.id)
        WHERE s.ingrediente_id is not NULL ";
    }
}