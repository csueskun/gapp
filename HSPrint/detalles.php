<?php
    $servername = "localhost";
    $username = "root";
    $password = "123456";
    $dbname = "gapp2";

    
    $limite = $_GET['limite']?:-1;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $script = "select d.id, dd.documento_id, d.total, dd.total totaldet, d.tipodoc, d.codprefijo, d.numdoc, dd.id as detalle_id from pizza_documento d inner join pizza_detalle_documento dd on(d.id = dd.documento_id) where d.created_at >= '2021-06-01 00:00:01' and d.created_at <= '2021-06-30 23:59:59' and (impreso = '0' or impreso is null) and tipodoc = 'FV'";
    
    if($limite==-1){
    }
    else{
        $result = $conn->query($script);
        $delete = [];
        $deltedoc = [];
        $documentos = [];
        while($row = $result->fetch_assoc()) {
            if($row["totaldet"]>=floatval($limite)){
                $delete[] = $row["detalle_id"];
                $documentos[] = ['id'=>$row['id'], 'restar'=>$row["totaldet"]];
            }
        }
        echo 'Borrando detalles mayores o iguales a $'.$limite.', '.count($delete).' en total';
        $script = "delete from pizza_detalle_documento where id in (".implode(",", $delete).");";
        $result = $conn->query($script);
        foreach ($documentos as $doc) {
            $script = "update pizza_documento set total = pizza_documento.total - ".$doc['restar']." where id = ".$doc['id'].";";
            echo $script;
            echo '<br>';
            $result = $conn->query($script);
        }
        echo '<br>';
        echo '<a href="detalles.php">Volver</a>';

    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <?php if($limite==-1){ ?>
            <form action="detalles.php">
                <label for="limite">Escriba el límite del valor del detalle.</label>
                <input type="number" name="limite" id="limite">
                <br>
                <br>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Total documento</th>
                        <th>Detalle</th>
                        <th>Total detalle</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $result = $conn->query($script);
                    $lastdoc = '';
                    while($row = $result->fetch_assoc()) {
                        $td1 = $row['tipodoc'];
                        $td2 = $row['codprefijo'].$row['numdoc'];
                        $td3 = $row['total'];
                        if($lastdoc == $row['id']){
                            $td1 = '';
                            $td2 = '';
                            $td3 = '';
                        }
                        ?>
                    <tr>
                        <td><?=$td1?></td>
                        <td><?=$td2?></td>
                        <td><?=$td3?></td>
                        <td><?=$row['detalle_id']?></td>
                        <td><?=$row["totaldet"]?></td>
                    </tr>
                <?php 
                    $lastdoc = $row['id'];
                    } ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
    </html>

    <style>
        table,
        table td{
            border-collapse: collapse;
            border: thin solid gainsboro;
        }
    </style>

    <?php
        $conn->close();
    ?>