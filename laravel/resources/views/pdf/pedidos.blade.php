<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table class="">
        <tr>
            <th colspan="3" class= "center">
                <img src="images/logo_empresa.png" height='64' alt="">
                <!-- <div class="logo"></div> -->
                <h2 class="center">Reporte de pedidos</h2>
            </th>
        </tr>
        <tr>
            <th width="33%" class="center">Fecha de generación</th>
            <th width="34%" class="center">Periodo desde</th>
            <th width="33%" class="center">Periodo hasta</th>
        </tr>
        <tr>
            <td class="center"><?= date("d/m/Y g:i a") ?></td>
            <td class="center"><?= date_format(date_create($inicio), 'd/m/Y g:i:a') ?></td>
            <td class="center"><?= date_format(date_create($fin), 'd/m/Y g:i:a') ?></td>
        </tr>
    </table>
    <br>
    <br>
    <table class="content">
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="right">Cantidad</th>
                <th class="right">Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cantidad = 0;
            $total = 0;
            foreach ($data as $row) {
                $cantidad+= $row->cantidad;
                $total+= $row->total;
            ?>
            <tr>
                <td><?= $row->tipo ?></td>
                <td class="right"><?=number_format($row->cantidad, 0)?></td>
                <td class="right">$ <?= number_format($row->total,0) ?></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <th>Total</th>
                <th class="right"><?=number_format($cantidad, 0)?></th>
                <th class="right">$ <?=number_format($total,0)?></th>
            </tr>
        </tbody>
    </table>
</body>

<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    table.content{
        border: none;
        width: 500px;
        margin: auto;
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
        font-size: 11px;
    }
    table.header td{
        border: none;
    }
    .header .logo{
        width: 92px;
    }
    .header .logo>img{
        height: 90px;
        width: 90px;
    }
    .header .title{
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        color: #222;
    }
    .header .title span{
        font-size: 15px;
        color: #222;
    }
    .header .subtitle{
        text-align: center;
        font-size: 13px;
        color: #333;
    }
    .center{
        text-align: center;
    }
    .right{
        text-align: right;
    }
    
    div.logo{
        background-image: url(./images/logo_empresa.png);
        height: 64px;
        width: 182px;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
</html>