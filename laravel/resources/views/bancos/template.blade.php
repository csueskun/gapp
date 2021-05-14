
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VENTAS POR BANCO</title>
</head>
<body>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    table.content{
        border: none;
    }
    table.content{
        border: none;
    }
    table.content th{
        line-height: 20px;
        vertical-align: middle;
        background-color: #eee;
    }
    table.content td{
        line-height: 18px;
        vertical-align: middle;
    }
    table{
        width: 100%;
        margin: auto;
        border-collapse: collapse;
        font-size: 12px;
    }
    table.header td{
        border: none;
    }
    .header .logo{
        width: 92px;
    }
    .header .logo>img{
        height: 90px;
    }
    .header .title{
        font-weight: bold;
        font-size: 18px;
        color: #222;
        padding-left: 8px;
    }
    .header .title span{
        font-size: 15px;
        color: #222;
    }
    .header .subtitle{
        font-size: 13px;
        color: #333;
        padding-left: 8px;
    }
    .center{
        text-align: center;
    }
    .right{
        text-align: right;
    }
</style>

<table class="header">
    <tr>
        <td rowspan='3' class="logo"><img src="./images/logo_empresa.png"></td>
        <td class="title" colspan='5'>
            Reporte de ventas por banco <br>
        </td>
    </tr>
    <tr>
        <td class="subtitle" colspan='5'>
            <strong>Periodo:</strong> <?= $fecha_inicio?substr($fecha_inicio, 0, 10):'*' ?> - <?= $fecha_fin?substr($fecha_fin, 0, 10):'*' ?><br>
            <strong>Fecha de impresión</strong>: <?= date("Y-m-d g:i a") ?>
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<br>
<table class="content">
    <thead>
        <tr>
            <th>Forma de pago</th>
            <th class="right">Pago en efectivo</th>
            <th class="right">Pago en débito</th>
            <th class="right">Pago en crédito</th>
            <th class="right">Pago en transferencia</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $bancos = [
            "b0"=>"CAJA GENERAL",
            "b1"=>"BANCOLOMBIA",
            "b2"=>"BANCO BOGOTÁ",
            "b3"=>"DAVIVIENDA",
            "b4"=>"BANCO CAJA SOCIAL",
            "b5"=>"BANCO AVVILLAS",
            "b6"=>"BANCO BBVA",
            "b7"=>"BANCO FALLABELA",
            "b8"=>"BANCO POPULAR",
            "b9"=>"BANCO DE OCCIDENTE",
            "b10"=>"BANCO COLPATRIA",
            "b11"=>"CITIBANK",
            "b12"=>"BANCO SANTANDER"
        ];
        $total = [0, 0, 0, 0, 0];
        foreach($pagos as $pago){
            $total[0]+=$pago->efectivo;
            $total[1]+=$pago->debito;
            $total[2]+=$pago->credito;
            $total[3]+=$pago->transferencia;
            $total[4]+=$pago->total;
            ?>
            <tr>
                <td><?= array_key_exists('b'.$pago->formapago, $bancos)?$bancos['b'.$pago->formapago]:$pago->formapago ?></td>
                <td class="right"><?= number_format($pago->efectivo,0) ?></td>
                <td class="right"><?= number_format($pago->debito,0) ?></td>
                <td class="right"><?= number_format($pago->credito,0) ?></td>
                <td class="right"><?= number_format($pago->transferencia,0) ?></td>
                <td class="right"><?= number_format($pago->total,0) ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th>Total</th>
            <th class="right"><?= number_format($total[0],0) ?></th>
            <th class="right"><?= number_format($total[1],0) ?></th>
            <th class="right"><?= number_format($total[2],0) ?></th>
            <th class="right"><?= number_format($total[3],0) ?></th>
            <th class="right"><?= number_format($total[4],0) ?></th>
        </tr>
    </tbody>
</table>
</body></html>
