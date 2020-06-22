@extends('template.general')
@section('titulo', 'Pedidos Gapp')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/datatables.min.js') }}
{{ Html::script('js/dataTables.bootstrap.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.es.js') }}
{{ Html::style('/css/bootstrap-datetimepicker.min.css') }}

@endsection


@section('contenido')


<div class="container">
    
    <div class="row alertas">
    @include('template.status', ['status' => session('status')])
    </div>
    <br/>
    <br/>
    <div class="row" style="background-color: white; padding: 10px">
        <div class="col-md-12">
            <h2>
                Inventario
                <button class="btn btn-success font bebas" onclick="generateExcel()">
                    <span class="fa fa-file-excel-o" aria-hidden="true"></span>
                    Exportar a Excel
                </button>
                <button class="btn btn-primary busy font bebas" onclick="printPos()">
                    <span class="fa fa-print" aria-hidden="true"></span>
                    Impresión POS
                </button>
            </h2></div><br/><br/><br/>
        <div class="col-md-5">
            <div class="dataTables_length" id="example_length">
                <label>Mostrar 
                    <select id="por_pagina" name="example_length" aria-controls="example" class="">
                        <option>30</option>
                        <option>15</option>
                        <option>10</option>
                        <option>5</option>
                    </select> registros de {{ $saldos_producto_lista->total() }} en total
                </label>
            </div>
        </div>
        <div class="col-md-2">
            <br/>
        </div>
        <div class="col-md-5">
        
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar..." id="buscar">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="filtrarTabla()">Buscar</button>
                </span>
            </div><!-- /input-group -->
        
            <!-- <div class="input-group mb-3" style="float: right;margin: 0">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary" type="button" style="height: 28px;padding: 0px 10px" onclick="filtrarTabla()">Buscar</button>
                </div>
                <input type="text" class="form-control" id="buscar" cargarauto placeholder="" aria-label="" aria-describedby="basic-addon1" style="height: 28px">
            </div> -->
        </div>
        <br/>
        <br/>
        <br/>
        <table class="table normal table-hover table-condensed vertical-n" cellspacing="0" width="100%" style="margin-left: 10px; margin-right: 10px">
            <thead>
                <tr class='font bebas' style='font-size: 20px'>
                    <th class="" campo="">Producto</th>
                    <th class="" campo="">Tipo</th>
                    <th class="agregar_ordenar_por" campo="bodega">Bodega</th>
                    <th class="agregar_ordenar_por" campo="fecha_act">Fecha Act</th>
                    <th class="align-right" campo="existencia">Existencia</th>
                    <th class="align-right" campo="existencia_max">Máxima</th>
                    <th class="align-right" campo="existencia_min">Mínima</th>
                    <th class="min-width fix-datatable" campo=""></th>
                    <!-- <th>Acciones</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($saldos_producto_lista as $saldos_producto)
                @if($saldos_producto->producto)
                @if($saldos_producto->producto->terminado != 1)
                @continue
                @endif
                @endif
                @if(!$saldos_producto->producto && !$saldos_producto->ingrediente)
                    @continue
                @endif
                <tr id='{{ $saldos_producto->id }}' tipo="{{ $saldos_producto->producto ? 'PROD' : 'ING' }}" producto_id="{{ $saldos_producto->producto ? $saldos_producto->producto_id : '' }}{{ $saldos_producto->ingrediente ? $saldos_producto->ingrediente_id : '' }}">
                    <td>
                        {{ $saldos_producto->producto ? $saldos_producto->producto->descripcion : '' }}
                        {{ $saldos_producto->ingrediente ? $saldos_producto->ingrediente->descripcion : '' }}
                    </td>
                    <td>
                    {{ $saldos_producto->producto ? $saldos_producto->producto->tipo_producto->descripcion : '' }}
                    {{ $saldos_producto->ingrediente ? 'INGREDIENTE' : '' }}
                    </td>
                    <td>{{ $saldos_producto->bodega }}</td>
                    <td>{{ date('d/m/Y', strtotime($saldos_producto->updated_at)) }}</td>
                    <td class="align-right">{{ $saldos_producto->existencia }} {{ $saldos_producto->ingrediente ? $saldos_producto->ingrediente->unidad : '' }}</td>
                    <td class="align-right">{{ $saldos_producto->existencia_max }}</td>
                    <td class="align-right">{{ $saldos_producto->existencia_min }}</td>
                    <td class='fix-datatable'>
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" class="btn btn-default" style='padding: 2px 8px'><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="" cellspacing="0" width="100%">
            <tr>
                <td class="centrado">
                    {{ $saldos_producto_lista->appends($_GET)->links() }}
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="detallado" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title font bebas">Generar Detallado</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      <div class="modal-body">
          <form action="" method="post">
            <input type = "hidden" name="id" value=""/>
            <input type = "hidden" name="tipo" value=""/>
            <div class = "col-md-6">
                <div class = "campo tipo texto form-group has-feedback">
                    <label for = "inicio" class = "control-label">Fecha Inicio *</label>
                    <input type = "text" class = "form-control" id = "inicio" name = "inicio" value = "" required/>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                </div>
                <script type='text/javascript'>
                    $('#inicio').datetimepicker({
                        language:  'es',
                        todayBtn:  1,
                        autoclose: 1,
                        todayHighlight: 1,
                        minView: 2,
                        forceParse: 0,
                        format: 'yyyy-mm-dd'
                    });
                </script> 
            </div>
            <div class = "col-md-6">
                <div class = "campo tipo texto form-group has-feedback">
                    <label for = "fin" class = "control-label">Fecha fin *</label>
                    <input type = "text" class = "form-control" id = "fin" name = "fin" value = "" required/>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                </div>
                <script type='text/javascript'>
                    $('#fin').datetimepicker({
                        language:  'es',
                        todayBtn:  1,
                        autoclose: 1,
                        todayHighlight: 1,
                        minView: 2,
                        forceParse: 0,
                        format: 'yyyy-mm-dd'
                    });
                </script> 
            </div>
            <div class = "col-md-6">
                <div class = "campo tipo select form-group has-feedback">
                    <label for = "tipo_documento" class = "control-label">Tipo Documento</label>
                    <select class = "form-control" id = "tipo_documento" name = "tipo_documento" required>
                        <option value="TO">Todos</option>
                        <option value="FV">Factura de Venta</option>
                        <option value="FC">Factura de Compra</option>
                    </select>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="generarDetallado()" class="btn btn-primary font bebas">Generar Detallado</button>
        <button type="button" class="btn btn-secondary font bebas" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<form id="excel" action="/HSPrint/excel.php" method="POST">
    <input type="text" name="data" value="">
</form>

<script>
    function generarDetallado(){
        window.open('/inventario/detallado/?'+$("#detallado form").serialize(), '_blank')
    }
    function generateExcel(){
        $('button.busy').attr('disabled', true);
        $('button.busy span').addClass('fa-spin');
        $.get('/saldos_producto/excel', function (data) {
            if(data.code == 200){
                $('form#excel input').val(JSON.stringify(data.msg));
                $('form#excel').submit();
            }
            $('button.busy').attr('disabled', false);
            $('button.busy span').removeClass('fa-spin');
        });
    }
    function printPos(){
        $('button.busy').attr('disabled', true);
        $('button.busy span').addClass('fa-spin');
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.post("/inventario/pos", {}, function (data) {
                //console.log(data);
                enviarAServicioImpresion(servicio_impresion+'?stack='+JSON.stringify(data))
                $('button.busy').attr('disabled', false);
                $('button.busy span').removeClass('fa-spin');
            });
        });
    }
    function printPosx(){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.post("/caja/cuadre-post", {}, function (data) {
                console.log(data);
                //enviarAServicioImpresion(servicio_impresion+'?stack='+JSON.stringify(data))
            });
        });
    }

    function enviarAServicioImpresion(url){
        $.ajax({
            url: url,
            headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
            type: 'GET',
            // This is the important part
            crossDomain: true,
            dataType: "jsonp",
            xhrFields: {
                withCredentials: true,

            },
            // This is the important part
            success: function (response) {
                // handle the response
            },
            error: function (xhr, status) {
                // handle errors
            }
        });
    }
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-primary',
                  label: 'Detallado',
                  icon: 'glyphicon glyphicon-pencil',
                  onClick: function() {
                    $("#detallado form input[name=id]").val($(this).closest("tr").attr("producto_id"));
                    $("#detallado form input[name=tipo]").val($(this).closest("tr").attr("tipo"));
                    $('#detallado').modal('toggle');
                  }
                },
            ]
          }
        );
    });
</script>
<style>
.popover-title{
    display: none;
}
    /*.table.vertical-n td:nth-child(2n+2):not(.fix-datatable) {background: #fbfbfb}*/
    /*.table.vertical-n th:nth-child(2n+2):not(.fix-datatable) {background: #fbfbfb}*/
</style>
@endsection