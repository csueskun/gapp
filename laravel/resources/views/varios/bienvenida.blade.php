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
        @include('template.status', ['status' => session('status')])
        <div class="input-group">
            <span class="input-group-btn"  style="font-family: 'bebas_neuebold';">
                @if(Auth::user()->rol=='Mesero')
                <button id='mis-mesas' state='off' class="btn btn-default" type="button" style="font-size: 20px;" onclick="misMesas()">
                    <i class="fa fa-cutlery"></i> <span>Ver mis mesas</span>
                </button>
                @endif
                @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
                <a class="btn btn-success" data-toggle="modal" data-target="#modal_pagar" type="button" style="font-size: 20px">
                    <i class="fa fa-usd"></i> Pagos/Compras
                </a>
                @endif
                <button class="btn btn-primary" type="button" onclick="$('.fecha_toma_pedido').toggle();" style="font-size: 20px">
                    <span class="fa fa-eye"></span> Tiempo
                </button>
                <button class="btn btn-warning" type="button"  data-toggle="modal" data-target="#modal_cambiar_mesa"  style="font-size: 20px" onclick="cargarOcupadas()">
                    <i class="fa fa-random"></i> Traslados
                </button>
            </span>
        </div>
    </div>
    <br>
    <br>
    <div class="container centrado" style="margin: auto">
        @if(Auth::user()->rol=='Administrador')
        <br>
        <div class="row">
            <label for="mesero">Filtrar por mesero:</label> 
            <select class = "form-control" name = "mesero" onchange="filtrarMesero(event)" style="margin: auto; max-width: 250px; display: inline">
                <option value="">Todos</option>
                @foreach($meseros as $mesero)
                <option value="{{ $mesero->id }}">{{ $mesero->nombres }} {{ $mesero->apellidos }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="sin-mesas" style='display: none'>
            <br>
            <div class="alert alert-danger">El mesero no tiene mesas abiertas</div>
        </div>
    </div>
    <div class="container_ centrado mesas-container" style="margin: auto">
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
            <h2 class="no-margin"><i class="fa fa-cutlery"></i> {{$i}}</h2>
            <span>&nbsp;</span>
            <span style="height: 16px">
                <span style="" class="no-margin fecha_ {{ isset($estado_mesas[$i]) ? 'fecha_toma_pedido' : ''}}" fecha="{{ isset($estado_mesas[$i]) ? $estado_mesas[$i]['fecha'] : ''}}">&nbsp;</span>
            </span>
            <span>&nbsp;</span>
            <span style="height: 16px">
            @if(isset($estado_mesas[$i]))
            <i class="fa fa-wpforms @if(!$estado_mesas[$i]['prefacturado']) hidden @endif es_prefacturado"></i>
            <span class='comandas'><i class="fa fa-print"></i> {{$estado_mesas[$i]['comandas']}}</span>
            @else
            &nbsp;
            @endif
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
            <h4 class="titulo no-margin-padding">
                <i class="fa fa-square ml-2" style="color: #5cb85c;"></i> Disponible
                <i class="fa fa-square ml-2" style="color: #d9534f;"></i> Con Pedido Entregado
                <i class="fa fa-square-o ml-2"></i> No Disponible
                <i class="fa fa-wpforms ml-2"></i> Prefacturado
            </h4>
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
                                    <label class="checkbox-inline xl" ng-click="pagoCompra.tipo='OTRO'">
                                        OTRO CONCEPTO   
                                        <i class="fa" ng-class="{'fa-check-circle-o color-success': pagoCompra.tipo=='OTRO', 'fa-circle-o': pagoCompra.tipo!='OTRO'}"></i>
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
        for(var i=1;i<=cant_mesas;i++){
            if(mesas.includes(i+"")){
                var mesa = $("a#mesa"+i);
                limpiarColoresMesas($("a#mesa"+i),data[i].clase);
                mesa.find(".fecha_").addClass('fecha_toma_pedido').attr('fecha', data[i].fecha);
                mesa.find("span.comandas").html('<i class="fa fa-print"></i> '+data[i].comandas);
                if(data[i].prefacturado){
                    mesa.find("i.es_prefacturado").removeClass('hidden');
                }
                else{
                    mesa.find("i.es_prefacturado").addClass('hidden');
                }
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
        mesa.removeClass (function (index, className) {
            return (className.match (/\bmesero-\S+/g) || []).join(' ');
        });
        mesa.removeClass('btn').removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').addClass(clase);
    }

    $(function(){
        cargarEstadoMesas();
        calcularTiempo();
        // if("{{Auth::user()->rol}}" == 'Mesero'){
        //     vistaMesero("{{Auth::user()->id}}");
        // }
        var x = setInterval(function() {
            // if("{{Auth::user()->rol}}" == 'Administrador'){
                filtrarMesero({target:{value:$('select[name=mesero]').val()}});
            // }
            // if("{{Auth::user()->rol}}" == 'Mesero'){
            //     if($("#mis-mesas").attr('state') == 'off'){
            //         vistaMesero("{{Auth::user()->id}}");
            //     }
            //     else{
            //         filtrarMesero({target:{value:"{{Auth::user()->id}}"}});
            //     }
            // }
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

        $("div.mesas-container").on('click', 'a.mesa', function(e){
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            if(width>799){
                e.preventDefault();
                var target = $(e.currentTarget).attr('href').replace('/mesa/', '/mesa-v2/');
                window.location.href = target;
            }
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

    function filtrarMesero(event){
        $('a.mesa').show();
        $('.sin-mesas').hide();
        if(!event.target.value){
            return false;
        }
        $('a.mesa:not(.mesero-'+event.target.value+')').hide();
        if($('a.mesero-'+event.target.value).length){
        }
        else{
            $('.sin-mesas').show();
        }
    }

    function vistaMesero(id){
        $('a.mesa.btn-success').show();
        $('a.mesa').removeClass('disabled');
        $('a.mesa.btn-danger:not(.mesero-'+id+')').addClass('disabled');
    }

    function misMesas(){
        var isOn = $("#mis-mesas").attr('state') == 'off';
        $("#mis-mesas").attr('state', isOn?'on':'off');
        if(isOn){
            $("#mis-mesas span").html('Ver todas las mesas');
            filtrarMesero({target:{value:"{{Auth::user()->id}}"}});
        }
        else{
            filtrarMesero({target:{value:""}});
            $("#mis-mesas span").html('Ver mis mesas');
            vistaMesero("{{Auth::user()->id}}");
        }
    }

</script>

@endsection