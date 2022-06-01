
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
            Pedidos Archivados
            <button class="btn btn-default" data-toggle="modal" data-target="#reporteModal2" style="font-size: 20px">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
                Reporte de ventas
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
                    <th class="agregar_ordenar_por" campo="created_at" style='width:190px'>Fecha</th>
                    <th class="" campo="">Observación</th>
                    <th class="agregar_ordenar_por" campo="mesa_id">Mesa</th>
                    <th class="agregar_ordenar_por" campo="turno">Turno</th>
                    <th class="agregar_ordenar_por" campo="caja">Caja</th>
                    <th>Cant. Productos</th>
                    <th class="agregar_ordenar_por" campo="total">Saldo</th>
                    <th>Mesero</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido_lista as $pedido)
                <tr id="{{ $pedido->id }}">
                    <td>{{ $pedido->id }}</td>
                    <td>{{ date_format(date_create($pedido->fecha), 'd/m/Y g:i A') }}</td>
                    <td>
                    @if($pedido->mesa_id == 0)
                    @if(($pedido->obs != null && $pedido->obs != ''))
                    {{isset(json_decode($pedido->obs)->entregar_en)?("ENTREGAR EN: ".json_decode($pedido->obs)->entregar_en):""}}
                    {{isset(json_decode($pedido->obs)->observacion)?("(".json_decode($pedido->obs)->observacion.")"):""}}
                    @endif
                    @else
                    {{isset(json_decode($pedido->obs)->para_llevar)?("(".json_decode($pedido->obs)->para_llevar.")"):""}}
                    @endif
                    </td>
                    <td class="centrado">{{ $pedido->mesa_id == 0?'Domicilio':$pedido->mesa_id }}</td>
                    <td>{{ $pedido->turno }}</td>
                    <td>{{ $pedido->caja_id }}</td>
                    <td class="align-right min-width centrado">{{ count($pedido->productos) }}</td>
                    <td class="align-right">${{ number_format($pedido->total, 0) }}</td>
                    <td class="">{{ $pedido->usuario['nombres'] }} </td>
                    <td>
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$pedido->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $pedido_lista->appends($_GET)->links() }}
        <h4 style="float: right">{{ $pedido_lista->total() }} Encontrados</h4>
    </div>
    <br/>
    <br/>
    <br/>
</section>

<form action="borrar" id="borrar" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" value="">
</form>


<div class="modal" id="reporteModal2" tabindex="-1" role="dialog" aria-labelledby="reporteModal2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font bebas" id="myModalLabel">Reportes de ventas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
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
        };
        window.open(`/pedido/reporte/?inicio=${data.inicio}&fin=${data.fin}`, '_blank')
    }
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-primary',
                  label: 'Ver',
                  icon: 'glyphicon glyphicon-search',
                  onClick: function() {
                      window.location.href = "ver/"+$(this).attr("id");
                  }
                },
               {
                 class: 'btn btn-danger',
                 label: 'Borrar',
                 icon: 'glyphicon glyphicon-trash',
                 onClick: function() {
                     if(confirm("Esta acción es definitiva, ¿Está Seguro?")){
                         var id = $(this).attr("id");
                         $.post("/pedido/borrar_api", {id: id}, function (data) {
                             if(data){
                                 if(data.code == 200){
                                     $('tr#'+id).remove();
                                     mostrarSuccess(data.msg);
                                 }
                                 else if(data.code == 400){
                                     mostrarError(data.msg);
                                 }
                                 else{
                                     mostrarError('No se pudo borrar el pedido.')
                                 }
                             }
                             else{
                                 mostrarWarning('No se pudo borrar el pedido.');
                             }
                         });
                   }
                 }
               },
                {
                  class: 'btn btn-success',
                  label: 'Cancelar',
                  icon: 'glyphicon glyphicon-remove',
                  cancel: true
                }
            ]
          }
        );
    });
</script>
@endsection