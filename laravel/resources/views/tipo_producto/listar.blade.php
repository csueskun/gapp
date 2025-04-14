
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/datatables.min.js') }}
{{ Html::script('js/dataTables.bootstrap.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Tipo Producto
          <a href="crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        <table id="example" class="display datatable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!--<th>Codigo</th>-->
                    <th>Descripcion</th>
                    <th class="w1">Aplica Tamaños</th>
                    <th class="w1">Aplica Ingredientes</th>
                    <th class="w1">Aplica Sabores</th>
                    <th class="w1">Estado</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipo_producto_lista as $tipo_producto)
                <tr id='{{$tipo_producto->id}}'>
                    <!--<td>{{ $tipo_producto->codigo }}</td>-->
                    <td>{{ $tipo_producto->descripcion }}</td>
                    <td class="centrado"><input type="checkbox" class="manualDisabled checkbox-grande" {{ $tipo_producto->aplica_tamanos == '1'?'checked':'' }}/> </td>
                    <td class="centrado"><input type="checkbox" class="manualDisabled checkbox-grande" {{ $tipo_producto->aplica_ingredientes == '1'?'checked':'' }}/> </td>
                    <td class="centrado"><input type="checkbox" class="manualDisabled checkbox-grande" {{ $tipo_producto->aplica_sabores == '1'?'checked':'' }}/> </td>
                    
                    <td class="fix-datatable text-center">
                        <button class='btn estado estado_1' type="button" onclick="patchEstado({{$tipo_producto->id}},2)" style='display: {{$tipo_producto->estado == 1?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ok"></i>
                        </button>
                        <button class='btn estado estado_2' type="button" onclick="patchEstado({{$tipo_producto->id}},1)" style='display: {{$tipo_producto->estado == 2?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ban-circle"></i>
                        </button>
                    </td>

                    <td class="fix-datatable text-center">
                        {{--<a class="btn btn-warning" href="./editar/{{$tipo_producto->id}}"><span class="glyphicon glyphicon-pencil"></span></a>--}}
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$tipo_producto->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </section>

<form action="borrar" id="borrar" style="display: inline-block" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="id" value="">
</form>

<style>
    h3.popover-title{
        display: none;
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
                  icon: 'glyphicon glyphicon-pencil',
                  onClick: function() {
                      window.location.href = "editar/"+$(this).attr("id");
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
    });
</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
                $('#example').DataTable();
        } );
        
    function patchEstado(id,estado){
        $.post( `/tipo_producto/${id}/estado/${estado}`, 
            { 
                _token: '{{ Session::token() }}',
                _method: 'patch'
            }, 
            function( data ) {
                if(data==1){
                    actualizarEstado(id,estado);
                }
                else{
                    alert("ERROR");
                }
        });
    }
    function actualizarEstado(id,estado){
        $("tr#"+id+" button.estado").not("estado_"+estado).hide();
        $("tr#"+id+" button.estado_"+estado).show();
    }
</script>
@endsection