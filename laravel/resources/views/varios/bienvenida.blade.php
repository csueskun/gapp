@extends('template.general')
@section('titulo', 'Pedidos H-Software')
@section('contenido')

@section('lib')
{{ Html::script('js/moment.js') }}
{{ Html::script('js/moment.es.js') }}
{{ Html::script('/controller/menu.js') }}
@endsection

<section class="borde-inferior lista fondo-rojo">
    <div class="container_ centrado">
        <h1 class="titulo">Mesas</h1>
    </div>
</section>

<section class="borde-inferior lista fondo-comun">
    <br>
    <div class="col-xs-12" style="text-align: center">
        <div class="input-group">
            <span class="input-group-btn"  style="font-family: 'bebas_neuebold';">
                @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
                <a class="btn btn-success" data-toggle="modal" data-target="#modal_pagar" type="button" style="font-size: 20px">
                    <i class="fa fa-usd"></i> Pagos/Compras
                </a>
                @endif
                <button class="btn btn-primary" type="button" onclick="$('.fecha_toma_pedido').toggle();" style="font-size: 20px">
                    <span class="fa fa-eye"></span> Tiempo
                </button>
                <button class="btn btn-warning" type="button"  data-toggle="modal" data-target="#modal_cambiar_mesa"  style="font-size: 20px" onclick="cargarOcupadas()">
                    <i class="glyphicon glyphicon-random"></i> Traslados
                </button>
            </span>
        </div>
    </div>
    <br>
    <br>
    <div class="container_ centrado" style="margin: auto">
        @include('template.status', ['status' => session('status')])
        <br/>
        <a href="/mesa/0" class="btn btn-warning cuadrado boton-grande mesa">
            <span>&nbsp;</span>
            <i class="fa fa-motorcycle"></i>
            <span>&nbsp;</span>
            <h2 class="no-margin">Domicilio</h2>
            <span>&nbsp;</span>
            <span>&nbsp;</span>
        </a>
        @for($i = 1; $i<=$config->cantidad_mesas;$i++)
        <a href="/mesa/{{$i}}" id="mesa{{$i}}" class="{{ isset($estado_mesas[$i]) ? $estado_mesas[$i]['clase'] : 'btn btn-success'}} cuadrado boton-grande mesa">
            <span>&nbsp;</span>
            <i class="glyphicon glyphicon-cutlery"></i>
            <span>&nbsp;</span>
            <h2 class="no-margin">{{$i}}</h2>
            <span style="height: 16px">
                <span style="" class="no-margin fecha_ {{ isset($estado_mesas[$i]) ? 'fecha_toma_pedido' : ''}}" fecha="{{ isset($estado_mesas[$i]) ? $estado_mesas[$i]['fecha'] : ''}}">&nbsp;</span>
            </span>
            <span>&nbsp;</span>
        </a>
        @endfor
        
    </div>
    
    <br/>
    <br/>
    <br/>
</section>


<section class="borde-inferior lista fondo-blanco">
    <br/>
    <div class="container_ centrado">
        <div class="row" style="background-color: white;">
            <div class="col-xs-12 col-sm-3">
                <h4 class="titulo no-margin-padding"><button class="btn btn-success"></button> Disponible</h4>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4 class="titulo no-margin-padding"><button class="btn btn-danger"></button> Con Pedido</h4>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4 class="titulo no-margin-padding"><button class="btn btn-warning"></button> Con Pedido Entregado</h4>
            </div>
            <div class="col-xs-12 col-sm-3">
                <h4 class="titulo no-margin-padding"><button class="btn btn-default"></button> No Disponible</h4>
            </div>
            <br>
        </div>
    </div>
    <br/>
</section>



<div class="modal fade" tabindex="-1" role="dialog" id='modal_cambiar_mesa' aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- <div class="modal-header" style="background-color: #ffd800;">
                <h1 class="modal-title" id="exampleModalLabel">Trasladar Pedido</h1>
            </div> -->
            <div class="modal-body">
                <div class="row">
                    <ul id="mesas_ocupadas_cambiar">
                        <img src="/images/loading.gif" height="50px"/>
                    </ul>
                </div>
                <div class="row">
                    <ul style="display: none" id="mesas_libres_cambiar">
                        <h2>A la mesa:</h2>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-lg btn-block" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> CERRAR</button>
            </div>
        </div>
    </div>
</div>
<div ng-app="myApp" ng-controller="menuController">
    <div class="modal fade" tabindex="-1" role="dialog" id='modal_pagar' aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 630px;">
            <div class="modal-content">
                <div class="modal-body">
                    <table style='width: 100%'>
                        <tr>
                            <td>
                                <h2 class="fuente bebas ml-2" style="margin-top: 0px; margin-bottom: 0px;">Pago/Compra</h2>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-md-12">
                                    <label class="xl">Tipo de documento: </label>   
                                </div>
                                <div class="col-md-12">
                                    <select required class="xl form-control" name="" id="" ng-model="pagoCompra.tipodoc">
                                        <option ng-repeat='tipo in tipoDocumentos' value="@{{tipo.codigo}}">@{{tipo.descripcion}}</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="xl">Valor: </label>   
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" ng-click='pagoCompra.valor = pagoCompra.valor + 500' style="font-size: 25px">
                                                <i class="fa fa-plus-square"></i>
                                            </button>
                                        </div>
                                        <input required type="number" min="0" ng-model="pagoCompra.valor" class="xl form-control centrado">
                                        <div class="input-group-btn">
                                            <button class="btn btn-danger" ng-click='pagoCompra.valor = pagoCompra.valor - 500' style="font-size: 25px">
                                                <i class="fa fa-minus-square"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="xl">Tipo: </label>   
                                </div>
                                <div class="col-md-12">
                                    <label class="checkbox-inline xl" ng-click="pagoCompra.tipo='PAGO'">
                                        PAGO
                                        <i class="fa" ng-class="{'fa-check-circle-o color-success': pagoCompra.tipo=='PAGO', 'fa-circle-o': pagoCompra.tipo!='PAGO'}"></i>
                                    </label>
                                    <label class="checkbox-inline xl" ng-click="pagoCompra.tipo='COMPRA'">
                                        COMPRA
                                        <i class="fa" ng-class="{'fa-check-circle-o color-success': pagoCompra.tipo=='COMPRA', 'fa-circle-o': pagoCompra.tipo!='COMPRA'}"></i>
                                    </label>
                                    <label class="checkbox-inline xl" ng-click="pagoCompra.tipo='VENTA'">
                                        VENTA
                                        <i class="fa" ng-class="{'fa-check-circle-o color-success': pagoCompra.tipo=='VENTA', 'fa-circle-o': pagoCompra.tipo!='VENTA'}"></i>
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <label class="xl">Observaciones: </label>   
                                </div>
                                <div class="col-md-12">
                                    <textarea class="form-control xl" ng-model="pagoCompra.observacion" name="obs" style="height: 120px"></textarea>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" ng-disabled="saving || !(pagoCompra.tipodoc&&pagoCompra.valor&&pagoCompra.observacion)" class="btn btn-success min" ng-click="savePagoCompra()"> <i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-default min" data-dismiss="modal"> <i class="fa fa-door-open"></i> Salir</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    var j = 1; 
    var estado = "";
    function myLoop () {
        
        setTimeout(function () {
            $.get( "/estadomesas", function( data ) {
                if(estado==""){
                    estado= data;
                }
                else{
                    if(JSON.stringify(estado)!=JSON.stringify(data)){
                        actualizarMesas(data);
                        estado=data;
                    }
                    else{
                    }
                }
            });
            
            if (j<10){
                myLoop();
            }
        },5000);
    }
    myLoop();   

    function actualizarMesas(data){
        var dat = {data};
        var cant_mesas = parseInt('{{$config->cantidad_mesas}}');
        var mesas = Object.keys(data);
        // console.log({data});
        for(var i=1;i<=cant_mesas;i++){
            if(mesas.includes(i+"")){
                limpiarColoresMesas($("a#mesa"+i),data[i].clase);
                $("a#mesa"+i).find(".fecha_").addClass('fecha_toma_pedido').attr('fecha', data[i].fecha);
            }
            else{
                if($("a#mesa"+i).hasClass("btn-default") || $("a#mesa"+i).hasClass("btn-success")){

                }
                else{
                    $("a#mesa"+i).find(".fecha_").removeClass('fecha_toma_pedido').html('');
                    limpiarColoresMesas($("a#mesa"+i),'btn btn-success');
                }
            }
        }
    }

    function limpiarColoresMesas(mesa, clase){
        mesa.removeClass('btn').removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').addClass(clase);
    }

    $(function(){
        cargarEstadoMesas();
        calcularTiempo();
        var x = setInterval(function() {
            calcularTiempo();
        }, 5000);

        $("ul#mesas_ocupadas_cambiar").on('click', 'button', function(){
            $(this).closest('ul').attr('numero', $(this).attr('numero'));
            $(this).closest('ul').find('h2').html('Trasladar pedido de la mesa: '+$(this).attr('numero'));
            $(this).closest('ul').find('button').removeClass('seleccionado');
            $(this).addClass('seleccionado');
            $("ul#mesas_libres_cambiar").show();
        });

        $("ul#mesas_libres_cambiar").on('click', 'button', function(){
            var origen = $("ul#mesas_ocupadas_cambiar").attr('numero');
            var destino = $(this).attr('numero');
            $(this).closest('div.modal-content').html('<br/><img src="/images/loading.gif" height="50px"/><br/><br/>');
            $(this).closest('ul').html('');
            $.get("/pedido/cambiarmesa/"+origen+'/'+destino, function (data){
                window.location.href = '/';
            });
        });
        $.get('/borrar-sesion', function (data) {});
    });
    function cargarEstadoMesas(){
        var json = "{{json_encode($config->mesas)}}".replace(/&quot;/g,'"');
        json = JSON.parse(json);
        for(var i=0;i<json.length;i++){
            var mesa = json[i];
            if(mesa.alias != null || typeof mesa.alias != 'undefined'){
                $("a#mesa"+mesa.mesa+'>h2').html(mesa.alias);
            }
            if(!mesa.disponible){
                $("a#mesa"+mesa.mesa).attr("href","#");
                $("a#mesa"+mesa.mesa).css("cursor","not-allowed");
                $("a#mesa"+mesa.mesa).addClass("btn-default");
                $("a#mesa"+mesa.mesa).removeClass("btn-success");
                $("a#mesa"+mesa.mesa).removeClass("btn-warning");
            }
        }
    }

    function calcularTiempo(){
        $('.fecha_toma_pedido').each(function () {
            var dur = moment.duration(moment().diff(moment($(this).attr('fecha'), "YYYY-MM-DD HH:mm:ss").format()));
            var h = Math.floor(dur.asHours());
            var m = Math.floor(dur.asMinutes() - h*60);
            $(this).html( (h>0?h+' h ':'')+(m>0?m+' min':'')+(m<1&&h<1?'Menos de un minuto':'') );
        });
    }

    function cargarOcupadas(){
        $("ul#mesas_libres_cambiar").html('');
        $.get("/mesa/listar/ocupadas/", function (data) {
            var json = "{{json_encode($config->mesas)}}".replace(/&quot;/g,'"');
            json = JSON.parse(json);
            var mesas = JSON.parse(data[0].ocupadas);
            if(mesas == null){
                mesas = [];
            }
            mesas.sort(function(a, b){return a - b});

            var html = '<h2>Trasladar pedido de la mesa:</h2>';
            for(var i=0;i<mesas.length;i++){
                try {
                    var alias = json[mesas[i]-1].mesa;
                } catch (error) {
                    continue;
                }
                if(json[mesas[i]-1].alias != null && typeof json[mesas[i]-1].alias != 'undefined'){
                    alias = json[mesas[i]-1].alias;
                }
                html += '<li><button class="btn bt-danger mesa_cambio mesa_origen" numero="'+mesas[i]+'">'+alias+'</button></li>';
            }
            $("ul#mesas_ocupadas_cambiar").html(html);
            html = '<h2>A la mesa:</h2>';

            for(var i=1;i<={{$config->cantidad_mesas}};i++){
                if(mesas.includes(i)){
                    continue;
                }
                var alias = json[i-1].mesa;
                if(json[i-1] != null && typeof json[i-1] != 'undefined' && json[i-1].alias != null && typeof json[i-1].alias != 'undefined'){
                    var alias = json[i-1].alias;
                }
                html += '<li><button class="btn bt-success mesa_cambio mesa_destino" numero="'+i+'">'+alias+'</button></li>';
            }
            $("ul#mesas_libres_cambiar").html(html);
        });
    }





</script>

@endsection