
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
{{ Html::script('js/moment.js') }}
{{ Html::script('js/moment.es.js') }}

@endsection
@section('contenido')

<style>
    h3.popover-title{
        display: none;
    }
    td.calcular>span{
        font-family: 'bebas_neuebold';
        font-size: 22px;
        line-height: 22px;
        vertical-align: bottom;
    }
    td.calcular>span.a-tiempo{
        color: green;
    }
    td.calcular>span.tarde{
        color: red;
    }
</style>
<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo no-padding">Domicilios Activos</h1>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <div class="form-group" style="float: left;">
            <label>Registros Por Página: </label>
            <select class = "form-control" id="por_pagina">
                <option>100</option>
                <option>50</option>
                <option>20</option>
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
                    <th class="agregar_ordenar_por" campo="turno">Turno</th>
                    <th class="agregar_ordenar_por" campo="programado">Programado</th>
                    <th>Cantidad<br/>Productos</th>
                    <th class="agregar_ordenar_por" campo="total">Saldo</th>
                    <th>Mesero</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido_lista as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td style="max-width: 200px">

                    @if(($pedido->obs != null && $pedido->obs != ''))

                    @if(isset(json_decode($pedido->obs)->entregar_en))
                        @if(json_decode($pedido->obs)->entregar_en=='CAJA')
                        CAJA ({{ isset(json_decode($pedido->obs)->entregar_obs)?json_decode($pedido->obs)->entregar_obs : '' }})
                        @else
                        {{ isset(json_decode($pedido->obs)->entregar_obs)?json_decode($pedido->obs)->entregar_obs : '' }}
                        @endif
                    @endif

                    @endif

                    </td>
                    <td>{{ date_format(date_create($pedido->fecha), 'd/m/Y g:i A') }}</td>
                    <td>{{ $pedido->turno }}</td>
                    <td class="{{$pedido->programado?'calcular':''}}" valor="{{$pedido->programado}}"><span></span></td>
                    <td class="min-width centrado">{{ count($pedido->productos) }}</td>
                    <td class="align-right">${{ number_format($pedido->total, 0) }}</td>
                    <td class="">{{ $pedido->usuario['nombres']}} {{$pedido->usuario['apellidos'] }}</td>
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

    function calcularProgramados(){
        $("td.calcular").each(function(e){
            var valor = $(this).attr("valor");
            var mom = moment(valor, "YYYY-MM-DD HH:mm");
            var dif = mom.diff(moment(), 'minutes', true);
            var horas = 0;
            var minutos = 0;

            if(dif<0){
                horas = Math.floor(dif/-60);
                minutos = Math.floor(dif%-60);
            }
            else{
                horas = Math.floor(dif/60);
                minutos = Math.floor(dif%60);
            }
            if(minutos<0){
                minutos*=-1;
            }

            var mostrar = "";
            if(horas == 0 && minutos == 0){
                mostrar = "JUSTO AHORA";
                $(this).find("span").addClass('a-tiempo').html("Justo Ahora");
            }
            else{
                if(horas>0){
                    if(horas>1){
                        mostrar += horas+" horas ";
                    }
                    else{
                        mostrar += horas+" hora ";
                    }
                }
                if(minutos>0){
                    if(minutos>1){
                        mostrar+= minutos+" minutos";
                    }
                    else{
                        mostrar+= minutos+" minuto";
                    }
                }
                if(dif < 0){
                    $(this).find("span").addClass('tarde').html("HACE "+mostrar);
                }
                else{
                    $(this).find("span").addClass('a-tiempo').html("DENTRO DE "+mostrar);
                }
            }               
            
        });
    }

    $(function() {

        calcularProgramados();
        setInterval(function(){ calcularProgramados(); }, 10000);

        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-warning',
                  label: 'Editar',
                  icon: 'glyphicon glyphicon-pencil',
                  onClick: function() {
                      window.location.href = "/pedido/"+$(this).attr("id")+"/editar";
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
                  class: 'btn btn-default',
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
@endsection