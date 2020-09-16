
@extends('template.general')
@section('titulo', 'Cuadre de Caja')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Cuadre de Caja</h1>
        <br/>
    </div>
</section>

<section class="borde-inferior form fondo-comun">
    <div class="container">
        <h2 class="titulo">Seleccione la caja
            <br/>
            <div class = "col-md-6" style='padding: 0'>
                <div class = "key- tipo- form-group has-feedback">
                    <select name="_caja_id" id="_caja_id" class="form-control font bebas">
                        <option value="0">Todas</option>
                        <option value="1">Caja 1</option>
                        <option value="2">Caja 2</option>
                        <option value="3">Caja 3</option>
                    </select>
                </div>
            </div>
        </h2>
    </div>
</section>

<section class="borde-inferior form fondo-comun">
    <div class="container">
        <h2 class="titulo">Cuadre de Caja Diario
            <br/>
            <button class="btn btn-success" id="diario" href="diario" onclick="cuadreDiario()">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span> PDF
            </button>
            <button class="btn btn-primary" id="diario" href="diario" onclick="posDiario()">
                <span class="fa fa-print" aria-hidden="true"></span> POS
            </button>
            <button class="btn btn-warning" id="diario" href="diario" onclick="preEnviarCorreo()">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Correo Electrónico
            </button>
        </h2>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
        <h2 class="titulo">Cuadre de Caja Mensual
            <br/>
            <button class="btn btn-success" id="mensual" href="diario" onclick="cuadreMensual()">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span> PDF
            </button>
            <button class="btn btn-primary" id="mensual" href="mensual" onclick="posMensual()">
                <span class="fa fa-print" aria-hidden="true"></span> POS
            </button>
            <button class="btn btn-warning" id="mensual" href="mensual" onclick="preEnviarCorreo(2)">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Correo Electrónico
            </button>
        </h2>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
        <h2 class="titulo">Cuadre de Caja por Fechas
            <br/>
            <button class="btn btn-success" href="diario" onclick="$('form#cuadre').submit()">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span> PDF
            </button>
            <button class="btn btn-primary" onclick="posManual()">
                <span class="fa fa-print" aria-hidden="true"></span> POS
            </button>
            <button class="btn btn-warning" onclick="preEnviarCorreo(3)">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Correo Electrónico
            </button>
        </h2>
        <form id="cuadre" action="cuadre" method="POST" target="_blank">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="POST"/>
            <div class = "col-md-4">
                <label>Fecha de Inicio</label>
                <div class = "campo tipo fecha form-group has-feedback">
                    <div id="fecha_inicio"></div>
                </div>
            </div>
            <div class = "col-md-4">
                <label>Fecha Final</label>
                <div class = "campo tipo fecha form-group has-feedback">
                    <div id="fecha_fin"></div>
                </div>
            </div>
            <div class = "col-md-4">
            </div>
            <input type="hidden" name="fecha_inicio"/>
            <input type="hidden" name="fecha_fin"/>
            <input type="hidden" name="mail"/>
            
        </form>
    </div>
</section>
<form id="cuadre_" action="cuadre" method="POST" target="_blank">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST"/>
    <input type="hidden" name="fecha_inicio"/>
    <input type="hidden" name="fecha_fin"/>
    <input type="hidden" name="mail"/>
    <input type="hidden" name="caja_id" value='0'/>
</form>
<form id="mail" action="http://h-software.co/mail-service/mail.php" method="POST" target="_blank">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST"/>
    <input type="hidden" name="cliente"/>
    <input type="hidden" name="data"/>
    <input type="hidden" name="subject" value="Cuadre de caja"/>
</form>

<!-- Modal -->
<div class="modal fade" id="email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <label for="" class="">Dirección de correo electrónico: </label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary busy" onclick="enviarCorreo()"><i class="fa fa-envelope"></i> Enviar Correo</button>
                <button type="button" class="btn btn-primary busy is-busy" disabled="true"><i class="fa fa-spinner fa-spin"></i> Enviar Correo</button>
            </div>
        </div>
    </div>
</div>


<script>
    var tipoCorreo = 1;
    function enviarCorreo(){
        $('form#mail input[name=cliente]').val($('input[name=email]').val());
        if(tipoCorreo == 1){
            cuadreDiario(true);
        }
        else if(tipoCorreo == 2){
            cuadreMensual(true);
        }
        else if(tipoCorreo == 3){
            prepareMail();
        }
    }
    function preEnviarCorreo(tipo=1){
        tipoCorreo = tipo;
        $('div#email').modal('show');
    }
    $(function(){
        var fecha = new Date();

        $("button#diario").append(formatearFecha(fecha, "MM dd, yyyy"));
        $("button#mensual").append(formatearFecha(fecha, "MM, yyyy"));
        $("form#cuadre input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00"));
        $("form#cuadre input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59"));
        
        $('div#fecha_inicio').datetimepicker({
            endDate: new Date(),
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0
        });
        $('div#fecha_fin').datetimepicker({
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0
        });
        
        $('#_caja_id').on('change', function() {
            $("form#cuadre_ input[name=caja_id]").val($(this).val());
        });
        
        $('div#fecha_inicio').on('changeDate', function() {
            var fecha = new Date($('div#fecha_inicio').datetimepicker('getFormattedDate'));
            $("form#cuadre input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00"));
        });
        $('div#fecha_fin').on('changeDate', function() {
            var fecha = new Date($('div#fecha_fin').datetimepicker('getFormattedDate'));
            $("form#cuadre input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59"));
        });
        
        $(".icon-arrow-left").addClass("glyphicon-chevron-left");
        $(".icon-arrow-right").addClass("glyphicon-chevron-right");
    });
    function cuadreDiario(mail = 0){
        var fecha = new Date();
        $("form#cuadre_ input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00"));
        $("form#cuadre_ input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59"));
        if(mail){
            $('button.busy').toggleClass('is-busy');
            $("form#cuadre_ input[name=mail]").val(1);
            $.post( "/caja/cuadre", $( "#cuadre_" ).serialize()).done(function( data ) {
                if(data.code==200){
                    $('form#mail input[name=data]').val(data.msg);
                    $("form#mail").submit();
                }
                $('div#email').modal('hide');
                $('button.busy').toggleClass('is-busy');
                $("form#cuadre_ input[name=mail]").val(0);
            });
            return false;
        }
        $("form#cuadre_").submit();
    }
    function cuadreMensual(mail = 0){
        var fecha = new Date();
        $("form#cuadre_ input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-01 00:00:00"));
        var fechaF = new Date(fecha.getFullYear(),fecha.getMonth()+1,0);
        $("form#cuadre_ input[name=fecha_fin]").val(formatearFecha(fechaF, "yyyy-mm-dd 23:59:59"));
        if(mail){
            $('button.busy').toggleClass('is-busy');
            $("form#cuadre_ input[name=mail]").val(1);
            $.post( "/caja/cuadre", $( "#cuadre_" ).serialize()).done(function( data ) {
                if(data.code==200){
                    $('form#mail input[name=data]').val(data.msg);
                    $("form#mail").submit();
                }
                $('div#email').modal('hide');
                $('button.busy').toggleClass('is-busy');
                $("form#cuadre_ input[name=mail]").val(0);
            });
            return false;
        }
        $("form#cuadre_").submit();
    }
    function prepareMail(){
        $('button.busy').toggleClass('is-busy');
        $("form#cuadre_ input[name=mail]").val(1);
        $.post( "/caja/cuadre", $( "#cuadre" ).serialize()).done(function( data ) {
            if(data.code==200){
                $('form#mail input[name=data]').val(data.msg);
                $("form#mail").submit();
            }
            $('div#email').modal('hide');
            $('button.busy').toggleClass('is-busy');
            $("form#cuadre_ input[name=mail]").val(0);
        });
    }
</script>
<style>
    .table-condensed{
        border: thin solid gray;
        background-color: white;
    }
    .table-condensed tbody{
        border-bottom: thin solid gray;
    }
    h2>button{
        font-size: 18px !important;
    }
    .is-busy{
        display: none;
    }
</style>
    <script>

        var fecha = new Date();
        function posDiario(){
            var fechaI = formatearFecha(fecha, "yyyy-mm-dd 00:00:00");
            var fechaF = formatearFecha(fecha, "yyyy-mm-dd 23:59:59");

            // $.post("/caja/cuadre-post", {fecha_inicio: fechaI, fecha_fin: fechaF}, function (data) {
            //     enviarAServicioImpresion('http://localhost:8000/HtmlPrint?stack='+JSON.stringify(data))
            // });

            impPos({fecha_inicio: fechaI, fecha_fin: fechaF, caja_id:$("form#cuadre_ input[name=caja_id]").val()});
        }
        function posMensual(){
            var fechaI = formatearFecha(fecha, "yyyy-mm-01 00:00:00");
            var fechaF = new Date(fecha.getFullYear(),fecha.getMonth()+1,0);
            fechaF = formatearFecha(fechaF, "yyyy-mm-dd 23:59:59");

            impPos({fecha_inicio: fechaI, fecha_fin: fechaF, caja_id:$("form#cuadre_ input[name=caja_id]").val()});
            // $.post("/caja/cuadre-post", {fecha_inicio: fechaI, fecha_fin: fechaF}, function (data) {
            //     enviarAServicioImpresion('http://localhost:8000/HtmlPrint?stack='+JSON.stringify(data))
            // });
        }
        function posManual(){
            var fechaI = new Date($('div#fecha_inicio').datetimepicker('getFormattedDate'));
            fechaI = formatearFecha(fechaI, "yyyy-mm-dd 00:00:00");
            var fechaF = new Date($('div#fecha_fin').datetimepicker('getFormattedDate'));
            fechaF = formatearFecha(fechaF, "yyyy-mm-dd 23:59:59");

            // $.post("/caja/cuadre-post", {fecha_inicio: fechaI, fecha_fin: fechaF}, function (data) {
            //     enviarAServicioImpresion('http://localhost:8000/HtmlPrint?stack='+JSON.stringify(data))
            // });
            impPos({fecha_inicio: fechaI, fecha_fin: fechaF, caja_id:$("form#cuadre_ input[name=caja_id]").val()});
        }

        function impPos(params){
            $.get('/config/servicio-impresion', function (data) {
                servicio_impresion = data;
                $.post("/caja/cuadre-post", params, function (data) {
                    enviarAServicioImpresionPost(servicio_impresion, data);;
                    // enviarAServicioImpresion(servicio_impresion+'?stack='+JSON.stringify(data))
                });
            });

        }

        // function enviarAServicioImpresion(url){
        //     $.ajax({
        //         url: url,
        //         headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
        //         type: 'GET',
        //         // This is the important part
        //         crossDomain: true,
        //         dataType: "jsonp",
        //         xhrFields: {
        //             withCredentials: true,

        //         },
        //         // This is the important part
        //         success: function (response) {
        //             // handle the response
        //         },
        //         error: function (xhr, status) {
        //             // handle errors
        //         }
        //     });
        // }
    </script>
@endsection