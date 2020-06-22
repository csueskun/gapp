
@extends('template.general')
@section('titulo', 'LISTA')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/datatables.min.js') }}
{{ Html::script('js/dataTables.bootstrap.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Documento
            <a href="crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
            <button class="btn btn-default" data-toggle="modal" data-target="#reporteModal" style="font-size: 20px">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
                Reportes
            </button>
            <button class="btn btn-default" data-toggle="modal" data-target="#reporteModal2" style="font-size: 20px">
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
                Reporte de ventas
            </button>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
        
        <br/>
        <div class="col-md-4">
            <div class="dataTables_length" id="example_length">
                <label>Mostrar 
                    <select id="por_pagina" name="example_length" aria-controls="example" class="">
                        <option>100</option>
                        <option>50</option>
                        <option>20</option>
                    </select> registros de {{ $documento_lista->total() }} en total
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <select onchange="addParam('tipodoc', $(this).val(), true)" class = "form-control actualiza-tipoie" id = "tipodoc" name = "tipodoc" style="height: 28px; padding: 4px">
                <option value="">Tipo de documento</option>
                <option value="FV">Factura de Venta</option>
                <option value="FC">Factura de Compra</option>
                <option value="BI">Base Inicial</option>
                <option value="PN">Pago de Nómina</option>
                <option value="NI">Nota Inventario</option>
                <option value="CO">Consumo</option>
            </select>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" style="height: 28px;padding: 0px 10px" onclick="filtrarTabla()">Buscar</button>
                    </span>
                    <input type="text" class="form-control" id="buscar" cargarauto placeholder="" aria-label="" aria-describedby="basic-addon1" style="height: 28px">
            </div><!-- /input-group -->
<!--             
            <div class="input-group mb-3" style="float: right;margin: 0">
                
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary" type="button" style="height: 28px;padding: 0px 10px" onclick="filtrarTabla()">Buscar</button>
                </div>
                <input type="text" class="form-control" id="buscar" cargarauto placeholder="" aria-label="" aria-describedby="basic-addon1" style="height: 28px">
            </div>
             -->
        </div>
        <br/>
        <br/>
        <table class="table normal table-hover table-condensed display datatable" cellspacing="0" width="100%" style="margin-left: 10px; margin-right: 10px; background-color: white">
            <thead>
                <tr>
                    <th class="agregar_ordenar_por" campo="tipodoc">Tipo de Documento</th>
                    <th class="agregar_ordenar_por" campo="numdoc">Número</th>
                    <th class="">Tercero</th>
                    <th class="agregar_ordenar_por" campo="mesa_id">Mesa</th>
                    <th class="agregar_ordenar_por" campo="pedido_id">Pedido</th>
                    <th class="agregar_ordenar_por" campo="total">Total</th>
                    <th class="agregar_ordenar_por" campo="created_at">Fecha</th>
                    {{--<th class="agregar_ordenar_por" campo="observacion">Observacion</th>--}}
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documento_lista as $documento)
                <tr id='{{ $documento->id }}'>
                    <td>{{ array("FV"=>"Factura de Venta", "NI"=>"Nota Inventario", "FC"=>"Factura de Compra", "PN"=>"Pago de Nómina", "BI"=>"Base Inicial", "NI"=>"Nota de inventario", "CO"=>"Consumo")[$documento->tipodoc] }}</td>
                    <td>{{ $documento->numdoc }}</td>
                    <td>{{ $documento->tercero?$documento->tercero->nombrecompleto:'VARIOS' }}</td>
                    <td>{{ $documento->mesa_id==999?'':$documento->mesa_id }}</td>
                    <td class="align-right">{{ $documento->pedido_id==0?'':$documento->pedido_id }}</td>
                    <td class="align-right">${{ number_format($documento->total,0) }}</td>
                    <td>{{ date_format(date_create($documento->created_at), 'd/m/Y g:i a') }}</td>
{{--                    <td>{{ $documento->observacion }}</td>--}}
                    <td class="fix-datatable">
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$documento->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="" cellspacing="0" width="100%">
            <tr>
                <td class="centrado">
                    {{ $documento_lista->appends($_GET)->links() }}
                </td>
            </tr>
        </table>
    </div>
    </section>
<a id="imprimir" target="_blank" href=""></a>
<form action="borrar" id="borrar" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" value="">
</form>

<div class="modal" id="reporteModal" tabindex="-1" role="dialog" aria-labelledby="reporteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font bebas" id="myModalLabel">Reportes por tipo de documento</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class = "col-md-12">
                        <div class = "key- tipo- form-group has-feedback">
                            <label for = "tipodoc" class = "control-label">Tipo de Documento *</label>
                            <select class = "form-control actualiza-tipoie" id = "tipo_doc" name = "tipodoc">
                                <option selected value="FV">Factura de Venta</option>
                                <option value="FC">Factura de Compra</option>
                                <option value="BI">Base Inicial</option>
                                <option value="PN">Pago de Nómina</option>
                                <option value="NI">Nota Inventario</option>
                                <option value="CO">Consumo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class = "campo tipo fecha form-group has-feedback" id="fecha_inicio">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class = "campo tipo fecha form-group has-feedback" id="fecha_fin">
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
                <button type="button" class="btn btn-primary busy" onclick="report2()">Imprimir reporte</button>
            </div>
        </div>
    </div>
</div>

<style>
    h3.popover-title{
        display: none;
    }
    .contenedor.ordenar_por span{
        color: white;
        font-size: 0.8em;
    }
    td.day.disabled{
        cursor: not-allowed !important;
        color: #e23c3c47 !important;
    }
    td.day:not(.disabled){

    }
</style>
<script type = "text/javascript">
$('#example')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
</script>
<script>
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-warning',
                  label: 'Editar',
                  icon: 'fa fa-pencil',
                  onClick: function() {
                    window.location.href = $(this).attr("id")+'/editar';
                  }
                },
                {
                  class: 'btn btn-primary',
                  label: 'Imprimir',
                  icon: 'fa fa-print',
                  onClick: function() {
                    $("a#imprimir").attr("href","imprimir/"+$(this).attr("id"));
                    document.getElementById("imprimir").click();
                  }
                },
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
                {
                  class: 'btn btn-success',
                  label: 'Cancelar',
                  icon: 'glyphicon glyphicon-remove',
                  cancel: true
                }
            ]
          }
        );
        $('div#fecha_inicio').datetimepicker({
            endDate: new Date(),
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0,
            format: "yyyy-mm-dd 00:00:01"
        }).on('changeDate', function(e) {
            var ff = $("div#fecha_fin");
            ff.data("datetimepicker").startDate = e.date;
            ff.datetimepicker('update');
        });;

        $('div#fecha_fin').datetimepicker({
            startDate: new Date(),
            language:  'es',
            autoclose: 1,
            todayHighlight: 1,
            minView: 2,
            forceParse: 0,
            format: "yyyy-mm-dd 23:59:59"
        }).on("changeDate", function (e) {
            var fi = $("div#fecha_inicio");
            fi.data("datetimepicker").endDate = e.date;
            fi.datetimepicker('update');
        });
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


    function report(){
        $(".busy").attr('disabled',true);
        var data = {
            nombre: $("#tipo_doc option:selected").text(),
            tipo: $("#tipo_doc").val(),
            inicio: $('div#fecha_inicio').datetimepicker('getFormattedDate'),
            fin: $('div#fecha_fin').datetimepicker('getFormattedDate'),
        };
        impPos(data);
    }

    function report2(){
        var data = {
            inicio: $('div#fecha_inicio2').datetimepicker('getFormattedDate'),
            fin: $('div#fecha_fin2').datetimepicker('getFormattedDate'),
        };
        window.open(`/caja/reporte-ventas/?inicio=${data.inicio}&fin=${data.fin}`, '_blank')
    }
    function impPos(params, reporte = "/caja/reporte-tipodoc"){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.post(reporte, params, function (data) {
                enviarAServicioImpresion(servicio_impresion+'?stack='+JSON.stringify(data))
            });
        });
    }
    function enviarAServicioImpresion(url){
        $.ajax({
            url: url,
            headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
            type: 'GET',
            crossDomain: true,
            dataType: "jsonp",
            xhrFields: {
                withCredentials: true,
            },
            success: function (response) {
                $(".busy").attr('disabled',false);
            },
            error: function (xhr, status) {
                $(".busy").attr('disabled',false);
            }
        });
    }


</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
                $('#example').DataTable();
            $("#tipodoc").val(getParameterByName('tipodoc', false));
        } );
</script>
@endsection