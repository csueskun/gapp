
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo no-padding">Domicilios Programados</h1>
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
                    <th>Entregar En</th>
                    <th class="agregar_ordenar_por" campo="fecha">Fecha y Hora</th>
                    <th>Cantidad Productos</th>
                    <th class="agregar_ordenar_por" campo="total">Saldo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido_lista as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>
                    @if($pedido->mesa_id == 0)
                    @if(($pedido->obs != null && $pedido->obs != ''))
                    {{isset(json_decode($pedido->obs)->entregar_en)?("".json_decode($pedido->obs)->entregar_en):""}}
                    {{isset(json_decode($pedido->obs)->observacion)?("(".json_decode($pedido->obs)->observacion.")"):""}}
                    @endif
                    @endif
                    </td>
                    <td>{{ date_format(date_create($pedido->fecha), 'd/m/Y g:i A') }}</td>
                    <td>{{ count($pedido->productos) }}</td>
                    <td class="align-right">${{ number_format($pedido->total, 0) }}</td>
                    <td class="acciones">
                    <!-- <a href="pedido/ver/{{ $pedido->id }}"><span class="fa fa-eye"></span></a> -->
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

<form action="/pedido/borrardomicilio" id="borrar" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" value="">
</form>
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
    });
</script>

<script>
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-primary',
                  label: 'Ver',
                  icon: 'glyphicon glyphicon-search',
                  onClick: function() {
                      window.location.href = "/pedido/ver/"+$(this).attr("id");
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