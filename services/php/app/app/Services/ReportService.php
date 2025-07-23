<?php

namespace App\Services;

use DB;

class ReportService
{
    public function resumenDeVentas($input, $maxPrinterChars, $printService)
    {
        $fecha_inicio = $input['fecha_inicio'];
        $fecha_fin = $input['fecha_fin'];
        $fecha_inicio = date_format(date_create($fecha_inicio), "d/m/Y g:ia");
        $fecha_fin = date_format(date_create($fecha_fin), "d/m/Y g:ia");

        return array_merge(
            [
                $printService->addBoldText('RESUMEN DE VENTAS'),
                $printService->createSeparator($maxPrinterChars)
            ],
            $printService->createTable(
                [['Desde:', $fecha_inicio],['Hasta:', $fecha_fin]],
                [8, $maxPrinterChars-8],
                ['left', 'right']   
            ),
            [$printService->createSeparator($maxPrinterChars)],
            $printService->createTableWithHeader(
                [
                    [['Producto', 'Cant', 'Total']],
                    ['max:'.$maxPrinterChars, 'min', 'min'],
                    ['left', 'right', 'right'],
                ],
                $this->resumenDeVentasQuery($input),
                ['max:'.$maxPrinterChars, 'min', 'min'],
                $maxPrinterChars,
                ['left', 'right', 'right']
            ),
            [$printService->createSeparator($maxPrinterChars)],
            $printService->createGeneratedAtRow($maxPrinterChars),
            $printService->createSignature($maxPrinterChars)
        );
    }

    public function resumenDeVentasQuery($input)
    {
        $fecha_inicio = $input['fecha_inicio'];
        $fecha_fin = $input['fecha_fin'];
        $query = DB::select("
            SELECT pr.descripcion, SUM(pp.cant) as cantidad, SUM(pp.total) as total
            FROM gapp_documento d
            INNER JOIN gapp_pedido p on p.id = d.pedido_id
            INNER JOIN gapp_producto_pedido pp on pp.pedido_id = p.id
            INNER JOIN gapp_producto pr on pr.id = pp.producto_id
            WHERE d.tipodoc = 'FV'
            AND d.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'
            GROUP BY pr.id
            ORDER BY SUM(pp.cant) DESC
        ");
        $result = array_map(function($item) {
            return [
            $item->descripcion, 
            $item->cantidad, 
            "$".number_format($item->total, 0, ',', '.')
            ];
        }, $query);

        $totalCantidad = array_sum(array_column($query, 'cantidad'));
        $totalVentas = array_sum(array_column($query, 'total'));

        $result[] = [
            'TOTAL', 
            $totalCantidad, 
            "$".number_format($totalVentas, 0, ',', '.')
        ];

        return $result;
    }
}
