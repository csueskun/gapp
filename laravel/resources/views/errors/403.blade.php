<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{ Html::style('fonts/bebas-neue/stylesheet.css') }} 
    <style>
        .container { 
            height: 80vh;
            position: relative;
        }
        
        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            width: 100%;
        }
        h1,h2{
            text-align: center;
            font-family: 'bebas_neuebold';
            margin: auto;
        }
        h1{
            font-size: 50px;
            color: #9e9e9e;
        }
        h2{

        }
    </style>
</head>
<body>
    <div class="container">
        <div class="vertical-center">
        
            <h1>Acceso Denegado</h1>
            <h2>{{ $exception->getMessage() }}</h2>
        </div>
    </div>
    <center>
        <div class="col-md-12 alinear centrado" style="padding: 20px;">
            <img height="18" src="/images/logo3.png" alt="" style="vertical-align: top">
            <br/>
            2018 © 
        </div>
    </center>
</body>
</html>