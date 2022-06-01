<!DOCTYPE html>
<html>
    <head>
        @section('title')
        <title>@yield('titulo')</title>
        @show
        {{ Html::style('css/dataTables.bootstrap.min.css') }}
        {{ Html::style('bootstrap-3.3.6-dist/css/bootstrap.min.css') }} 
        {{ Html::style('css/ui.css') }} 
        {{ Html::style('css/css.css') }} 
        {{ Html::style('css/pizza.css') }} 
        {{ Html::style('fonts/roboto_light/stylesheet.css') }} 
        {{ Html::style('fonts/roboto_regular/stylesheet.css') }} 
        {{ Html::style('fonts/bebas-neue/stylesheet.css') }} 
        {{ Html::style('fonts/font-awesome-4.7.0/css/font-awesome.css') }}
        {{ Html::script('js/jquery-2.2.4.min.js') }}
        {{ Html::script('js/jquery-ui-1.9.2.custom.min.js') }}
        {{ Html::script('bootstrap-3.3.6-dist/js/bootstrap.min.js') }}
        {{ Html::script('js/funciones.js') }}
        {{ Html::style('css/toastr.min.css') }}
        {{ Html::script('js/toastr.min.js') }}
        {{ Html::script('js/angular.min.js') }}
        {{ Html::script('js/angular-animate.js') }}
        {{ Html::script('js/socket.io.min.js') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        @section('lib')
        @show
        
        <style>
        /* The side navigation menu */
        .sidenav {
            height: 100%; /* 100% Full-height */
            width: 380px; /* 0 width - change this with JavaScript */
            position: fixed; /* Stay in place */
            z-index: 1; /* Stay on top */
            top: 0; /* Stay at the top */
            left: -380px;
            overflow-x: hidden; /* Disable horizontal scroll */
            padding-top: 20px; /* Place content 60px from the top */
            transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
            background-color: #dd4b39;
            -webkit-box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3);
            -moz-box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3);
            box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3);
            z-index: 1031;
            color: white;
            padding-left: 15px;
        }
        .sidenav>a{
            margin: -10px 12px 0px 0px;
            font-size: 3em !important;
        }
        .sidenav a{
            font-size: 38px;
            color: white;
            font-family: 'bebas_neuebold';
        }
        .sidenav ul li a{
            margin: 0px !important;
            padding: 0px 10px !important;
            line-height: 40px;
            padding-left: 0px !important;
        }
        .sidenav h2{
            color: #ffd800;
            font-family: 'bebas_neuebold';
            margin-left: 4px;
        }
        .sidenav a, .sidenav a:hover, .sidenav a:active{
            text-decoration: none;
            background-color: #dd4b39 !important;
        }
        
        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
        #main {
            transition: margin-left .5s;
            padding: 20px;
        }
        #logo-dapp{
            margin-left: 5px;
            transition: margin-left .5s;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            
        }
        </style>
        
    </head>
    <body style="background-color: #f2f7f8">
        <div id="fullscreen-loading" class="z-index-100"></div>
        @if((Auth::check()))
        <div id="mySidenav" class="sidenav">
            <a href="#" onclick="closeNav()" style="float: right;"><i class="glyphicon glyphicon-remove" style="font-size: 50px"></i></a>
            
            <!-- <a href="/" style="float: left"><i class="glyphicon glyphicon-home"></i></a> -->
            <br/>
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Pedidos</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/pedido/listar?ordenar_por=id&sentido=desc" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i> Pedidos Activos</a>
                </li>
                <li class="nav-item">
                    <a href="/domicilios?ordenar_por=id&sentido=desc" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-motorcycle" aria-hidden="true"></i> Domicilios Activos</a>
                </li>
                <li class="nav-item">
                    <li><a href="/pedido/archivados?ordenar_por=id&sentido=desc" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-archive" aria-hidden="true"></i> Archivados</a></li>
                </li>
            </ul>
            @if(Auth::user()->rol=='Administrador')
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Datos básicos</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/producto/listar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-coffee" aria-hidden="true"></i> Productos</a></li>
                <li class="nav-item"><a href="/ingrediente/listar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-lemon-o" aria-hidden="true"></i> Ingredientes</a></li>
                <li class="nav-item"><a href="/tipo_producto/listar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tags" aria-hidden="true"></i> Tipos de Productos</a></li>
                <li class="nav-item"><a href="/adicional/agregar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-glass" aria-hidden="true"></i> Adicionales</a></li>
                <li class="nav-item"><a href="/combo" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-qrcode" aria-hidden="true"></i> Combos</a></li>
                <li class="nav-item"><a href="/tercero" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-address-book-o" aria-hidden="true"></i> Terceros</a></li>
            </ul>
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Gestión</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/dashboard" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tachometer" aria-hidden="true"></i> Tablero</a></li>
                <li class="nav-item"><a href="/documento/listar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Documentos</a></li>
                <li class="nav-item"><a href="/cocina" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-television" aria-hidden="true"></i> Cocina</a></li>
                <li class="nav-item"><a href="/caja/cuadre" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-money" aria-hidden="true"></i> Cuadre de Caja</a></li>
                <li class="nav-item"><a href="/config/editar" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cogs" aria-hidden="true"></i> Configuración</a></li>
                <li class="nav-item"><a href="/config/orden" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-exchange" aria-hidden="true"></i> Orden del menú</a></li>
            </ul>
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Reportes</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/mesero/informe" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-address-card" aria-hidden="true"></i> Ventas por usuario</a></li>
                <li class="nav-item"><a href="/bancos/informe" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-university" aria-hidden="true"></i> Ventas por banco</a></li>
                <li class="nav-item"><a href="/saldos_producto" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-text-o" aria-hidden="true"></i> Inventario</a></li>
                <li class="nav-item"><a href="/informe" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-database" aria-hidden="true"></i> Histórico</a></li>
            </ul>
            @endif
            @if(Auth::user()->rol=='Cocinero')
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Gestión</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/cocina" role="button" aria-haspopup="true" aria-expanded="false">&#8226; Cocina</a></li>
            </ul>
            @endif
            @if(Auth::user()->rol=='Cajero')
            <h2 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Gestión</span>
            </h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/caja/cuadre" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-money" aria-hidden="true"></i> Cuadre de Caja</a></li>
                <li class="nav-item"><a href="/informe" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bar-chart" aria-hidden="true"></i> Informes</a></li>
                <li class="nav-item"><a href="/tercero" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-address-book-o" aria-hidden="true"></i> Terceros</a></li>
                <li class="nav-item"><a href="/config/orden" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-exchange" aria-hidden="true"></i> Orden del menú</a></li>
            </ul>
            @endif
        </div>
        @endif
        
        <nav class="navbar navbar-light bg-light" style="font-size: 2em; background-color: #dd4b39; height: 70px; margin: 0; border-radius: 0px">
          <a class="navbar-brand" href="#" style="font-size: 1em; height: 70px">
            @if((Auth::check()))
              <a href="#" onclick="openNav()"><i class="glyphicon glyphicon-menu-hamburger" style="color: white; font-size: 50px; vertical-align: middle"></i></a>
            @endif
              <a id="logo-dapp" href="/"><img src="/images/logo_empresa.png" height="64" alt=""></a>
          </a>

            <ul class="navbar-nav pull-right" style='padding-top:5px; padding-right:10px; padding-left: 4px;'>
                @if((Auth::check()))
                <a href="/login" class='usuario' class="nav-link dropdown-toggle" href='#' id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <img src='/images/avatar_blank.png' height='25' style='margin-right:4px'/>
                    <span class="hidden-xs"> {{Auth::user()->nombres}} {{Auth::user()->apellidos}} : {{Auth::user()->rol}} : {{ Auth::user()->caja_id? 'Caja '.Auth::user()->caja_id:'' }}</span>
                    <span class="fa fa-caret-down"></span>
                    <span class="visible-xs-inline">&nbsp;&nbsp;</span>
                </a>
                <div id='usuario-dropdown' class="moverconnavcuenta_margin dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <h4 class="visible-xs-inline font roboto">{{Auth::user()->nombres}} {{Auth::user()->apellidos}} : {{Auth::user()->rol}}</h4>
                    <a class="dropdown-item" href='/usuario/editar'><i class="glyphicon glyphicon-pencil"></i>  Actualizar Datos de Usuario</a>
                    <a class="dropdown-item" href='/usuario/editar-password'><i class="glyphicon glyphicon-lock"></i>  Cambiar Contraseña</a>
                    <a class="dropdown-item" href='/logout'><i class="glyphicon glyphicon-log-out"></i>  Cerrar Sesión</a>
                </div>
                @else
                <a href="/login" class='usuario'>
                    <!-- <img src='/images/avatar_blank.png' height='40'/> -->
                    <i class="glyphicon glyphicon-log-out"></i>
                    <span>Iniciar Sesión</span>
                </a>
                @endif
            </ul>
        </nav>
        
            <div class="row" style="border-bottom: thin solid #d6d6d6;">
                <div class="col-md-12" style="padding: 0;border-top: thin solid #dd4b39">
                    
                    <div class="row encabezado">

                        

                        <div class="col-md-12 contenido" style="/*background-color: #efefef; */min-height: 70vh">
                            <div class="col-sm-12" id="content-fix">
                                

                                @section('contenido')
                                @show
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row moverconnavcuenta" style="border: thin solid #d6d6d6; border-bottom: none;">
                        <div class="col-md-12 alinear centrado" style="padding: 20px;">
                            <img height="18" src="/images/logo3.png" alt="" style="vertical-align: top">
                            <br/>
                            2019 ©
                        </div>
                    </div>
                    
                </div>
                
            </div>
        <div id='contenedor-alertas-fijas' class="cerrado">
        </div>
    </body>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.left = "0px";
            document.getElementById("logo-dapp").style.marginLeft = "350px";
        }
        function closeNav() {
            document.getElementById("mySidenav").style.width = "380px";
            document.getElementById("mySidenav").style.left = "-380px";
            document.getElementById("logo-dapp").style.marginLeft  = "5px";
        }
        // const socket = io('http://localhost:3000', {
        //     transports: ['websocket'],
        // });
    </script>
</html>