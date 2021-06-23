@extends('template.general')
@section('titulo', 'Terceros')
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

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">Terceros
            <a href='{{url("tercero/crear")}}' class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        <div class="col-md-7">
            <div class="dataTables_length" id="example_length">
                <label>Mostrar 
                    <select id="por_pagina" name="example_length" aria-controls="example" class="">
                        <option>15</option>
                        <option>10</option>
                        <option>5</option>
                    </select> registros de {{ $tercero_lista->total() }} en total
                </label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar..." id="buscar">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="filtrarTabla()">Buscar</button>
                </span>
            </div><!-- /input-group -->
        </div>
        <br>
        <br>
        <table id="example" class="table normal table-hover table-condensed display datatable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="agregar_ordenar_por" campo="identificacion">Identificación</th>
                    <th class="agregar_ordenar_por" campo="nombrecompleto">Nombre Completo</th>
                    <th class="agregar_ordenar_por" campo="tipoclie">Tipo Cliente</th>
                    <th class="agregar_ordenar_por" campo="telefono">Teléfono</th>
                    <th class="agregar_ordenar_por" campo="email">Dirección</th>
                    <th class="agregar_ordenar_por" campo="puntosacumulados">Puntos Acumulados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tercero_lista as $tercero)
                <tr id='{{ $tercero->id }}'>
                    <td>
                        @if($tercero->tipoidenti=='1')
                        CC
                        @elseif($tercero->tipoidenti=='2')
                        NIT
                        @endif
                        {{ $tercero->identificacion }}
                    </td>
                    <td>{{ $tercero->nombrecompleto }}</td>
                    <td>
                        @if($tercero->tipoclie=='C')
                        Cliente
                        @elseif($tercero->tipoclie=='P')
                        Proveedor
                        @elseif($tercero->tipoclie=='E')
                        Empleado
                        @elseif($tercero->tipoclie=='O')
                        Otro
                        @endif
                    </td>
                    <td>{{ $tercero->telefono }}</td>
                    <td>{{ $tercero->direccion }}</td>
                    <td>{{ $tercero->puntosacumulados }}</td>
                    <td class="min-width text-align-center">
                        <div style="display: flex">
                            <a href='{{url("tercero/$tercero->id/editar")}}' class="btn btn-warning btn-sm"><span class="fa fa-pencil"></span></a>
                            &nbsp;
                            <form action='{{url("/tercero")}}' style="display: inline-block" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="{{ $tercero->id }}">
                                <a href="#" class='pd danger btn btn-danger btn-sm'
                                    data-toggle = "confirmation"
                                    data-btn-ok-label = "Sí" data-btn-ok-icon = "glyphicon glyphicon-share-alt"
                                    data-btn-ok-class = "btn-success"
                                    data-btn-cancel-label = "No" data-btn-cancel-icon = "glyphicon glyphicon-ban-circle"
                                    data-btn-cancel-class = "btn-danger"
                                    data-title = "¿Desea borrar el registro?" data-content = "Esta Acción es definitiva">
                                    <span class = "glyphicon glyphicon-trash"></span>
                                </a>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            
            </tbody>
        </table>
        <table class="" cellspacing="0" width="100%">
            <tr>
                <td class="centrado">
                    {{ $tercero_lista->appends($_GET)->links() }}
                </td>
            </tr>
        </table>
    </div>

<style>
    .table.normal.datatable td{
        background-color: white;
    }
</style>
<script>
    $(function() {
        var por_pagina = getParameterByName('por_pagina');
        var buscar = getParameterByName('buscar');
        console.log(buscar);
        if(por_pagina==''||por_pagina==null){
        }
        else{
            $(":input#por_pagina").val(por_pagina);
        }
        $("input#buscar").val(getParameterByName('buscar'));
        $('[data-toggle=confirmation]').confirmation(
            {
                onConfirm: function () {
                    $(this).closest('form').submit();
                }
            }
        );
    });
</script>


@endsection