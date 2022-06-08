
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo no-padding">
            Pedidos Activos
            <button class="btn btn-default" data-toggle="modal" data-target="#reporteModal2" style="font-size: 20px">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
                Reporte de pedidos activos
            </button>
        </h1>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <div class="form-group" style="float: left;">
            <label>Registros Por Página: </label>
            <select class = "form-control" id="por_pagina">
                <option>15</option>
                <option>10</option>
                <option>5</option>
            </select>
        </div>
        <div class="form-group"  style="float: right;width: 300px">
            <label>&nbsp;</label>
            <div class="input-group">
                <input type="text" id="buscar" class="form-control cargarauto">
                <div class="input-group-btn">
                    <button class="btn btn-success" onclick="filtrarTabla()">Buscar</button>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <table class="midatatable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="agregar_ordenar_por" campo="id">Pedido #</th>
                    <th class="agregar_ordenar_por" campo="fecha">Fecha y Hora</th>
                    <th class="agregar_ordenar_por" campo="mesa_id">Mesa</th>
                    <th class="agregar_ordenar_por" campo="turno">Turno</th>
                    <th class="agregar_ordenar_por" campo="caja">Caja</th>
                    <th class="" campo="">Observación</th>
                    <th>Cant. Prod.</th>
                    <th class="agregar_ordenar_por" campo="total">Saldo</th>
                    <th class="" campo="">Mesero</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido_lista as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ date_format(date_create($pedido->fecha), 'd/m/Y g:i A') }}</td>
                    <td class="centrado">
                        {{ $pedido->mesa_id == 0?'Domicilio':json_decode($pedido->obs)->mesa_alias }}
                        @if($pedido->mesa_id>1000)
                        <span class="badge btn-warning">L</span>
                        @endif
                    </td>
                    <td>{{ $pedido->turno }}</td>
                    <td>{{ $pedido->caja_id }}</td>
                    <td class="">
                    <?php try{ 
                        if(strtoupper(json_decode($pedido->obs)->cliente)){?> 
                        <strong>Cliente: </strong>{{strtoupper(json_decode($pedido->obs)->cliente)}}<br>
                    <?php }}catch(\Exception $e){}
                    try{ 
                        if(strtoupper(json_decode($pedido->obs)->identificacion)){?> 
                        <strong>Documento: </strong>{{strtoupper(json_decode($pedido->obs)->identificacion)}}<br>
                    <?php }}catch(\Exception $e){}
                    try{
                        if(strtoupper(json_decode($pedido->obs)->entregar_en)!='DOMICILIO'){
                        if(strtoupper(json_decode($pedido->obs)->entregar_en)){?> 
                        <strong>Entregar en: </strong>{{strtoupper(json_decode($pedido->obs)->entregar_en)}}<br>
                    <?php }}}catch(\Exception $e){}
                    try{
                        if(strtoupper(json_decode($pedido->obs)->entregar_en)=='DOMICILIO'){
                        if(strtoupper(json_decode($pedido->obs)->domicilio)){?> 
                        <strong>Dirección: </strong>{{strtoupper(json_decode($pedido->obs)->domicilio)}}<br>
                    <?php }}}catch(\Exception $e){}
                    try{
                        if(strtoupper(json_decode($pedido->obs)->observacion)){?> 
                        <strong>Observación: </strong>{{strtoupper(json_decode($pedido->obs)->observacion)}}<br>
                    <?php }}catch(\Exception $e){} ?>
                    </td>
                    <td class="align-right min-width centrado">{{ count($pedido->productos) }}</td>
                    <td class="align-right">${{ number_format($pedido->total, 0) }}</td>
                    <td class="">{{ $pedido->usuario['nombres'] }} </td>
                    <td class="acciones">
                    <!-- <a href="/pedido/ver/{{ $pedido->id }}"><span class="fa fa-eye"></span></a> -->
                    <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$pedido->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfooter>
                <tr><td colspan='10'><h4 style=""><span class="badge btn-warning">L</span> Mesa liberada</h4></td></tr>
            </tfooter>
        </table>
        {{ $pedido_lista->appends($_GET)->links() }}
        <h4 style="float: right">{{ $pedido_lista->total() }} Encontrados</h4>
    </div>
    <br/>
    <br/>
    <br/>
</section>

<form action="/pedido/borrar" id="borrar" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" value="">
</form>

<div class="modal" id="reporteModal2" tabindex="-1" role="dialog" aria-labelledby="reporteModal2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font bebas" id="myModalLabel">
                    Reportes de pedidos activos
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="incluye_domicilios" class = "font roboto">
                            <input class="scale1_8" type="checkbox" name="incluye_domicilios" identificacion="incluye_domicilios">
                            &nbsp;&nbsp;&nbsp;Incluir domicilios
                        </label>
                    </div>
                    <div class="col-md-6">
                        <div class = "campo tipo fecha form-group has-feedback" id="fecha_inicio2">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class = "campo tipo fecha form-group has-feedback" id="fecha_fin2">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary busy" onclick="report()">Imprimir reporte</button>
            </div>
        </div>
    </div>
</div>

<style>
    h3.popover-title{
        display: none;
    }
    .scale1_8{
        -ms-transform: scale(1.8);
        -moz-transform: scale(1.8);
        -webkit-transform: scale(1.8);
        -o-transform: scale(1.8);
    }
</style>

<script>
    $(function() {
        var por_pagina = getParameterByName('por_pagina');
        if(por_pagina==''||por_pagina==null){
        }
        else{
            $(":input#por_pagina").val(por_pagina);
        }
        $("input#buscar.cargarauto").val(getParameterByName('buscar'));
        $('div#fecha_inicio2').datetimepicker({
            endDate: new Date(),
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0,
            format: "yyyy-mm-dd 00:00:01"
        }).on('changeDate', function(e) {
            var ff = $("div#fecha_fin2");
            ff.data("datetimepicker").startDate = e.date;
            ff.datetimepicker('update');
        });;

        $('div#fecha_fin2').datetimepicker({
            startDate: new Date(),
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0,
            format: "yyyy-mm-dd 23:59:59"
        }).on("changeDate", function (e) {
            var fi = $("div#fecha_inicio2");
            fi.data("datetimepicker").endDate = e.date;
            fi.datetimepicker('update');
        });
        $(".icon-arrow-left").addClass("glyphicon-chevron-left");
        $(".icon-arrow-right").addClass("glyphicon-chevron-right");
    });
</script>

<script>
    function report(){
        var data = {
            inicio: $('div#fecha_inicio2').datetimepicker('getFormattedDate'),
            fin: $('div#fecha_fin2').datetimepicker('getFormattedDate'),
            domicilios: $("input[name=incluye_domicilios]").is(':checked') ? 1 : 0
        };
        window.open(`/pedido/reporte-activos/?inicio=${data.inicio}&fin=${data.fin}&domicilios=${data.domicilios}`, '_blank')
    }
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-warning',
                  label: 'Editar',
                  icon: 'glyphicon glyphicon-pencil',
                  onClick: function() {
                      window.location.href = "/pedido/"+$(this).attr("id")+"/editar?v="+menuVersionFromDevice();
                  }
                },
                {
                  class: 'btn btn-primary',
                  label: 'Ver',
                  icon: 'glyphicon glyphicon-search',
                  onClick: function() {
                      window.location.href = "/pedido/ver/"+$(this).attr("id");
                  }
                },
                @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
               {
                 class: 'btn btn-danger',
                 label: 'Borrar',
                 icon: 'glyphicon glyphicon-trash',
                 onClick: function() {
                     if(confirm("Esta acción es definitiva, ¿Está Seguro?")){
                       $("form#borrar input[name=id]").val($(this).attr("id"));
                       $("form#borrar").submit();
                   }
                 }
               },
                @endif
                {
                  class: 'btn btn-success',
                  label: 'Cerrar',
                  icon: 'glyphicon glyphicon-remove',
                  cancel: true
                }
            ]
          }
        );
    });
</script>

<script type = "text/javascript">
$('#activos')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
$('#archivados')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
        } );
</script>
@endsection