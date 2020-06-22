
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
        <h1 class="titulo">Producto
        <a href="agregar" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        </h1>
        <br/>
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
                    <th>Tipo</th>
                    <th>Producto</th>
                    <th>Grupo</th>
                    <th width="100">Valor</th>
                    <th width="1">Imprime Comanda</th>
                    <th width="1">Producto terminado</th>
                    <th>Iva</th>
                    <th width="1">Imp. Consumo</th>
                    <th class="w1">Estado</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($producto_lista as $producto)
                @if($producto->codigo=='00')
                @continue
                @endif
                <tr id='{{$producto->id}}'>
                    <td>{{ $producto->tipo_producto->descripcion }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->grupo }}</td>
                    <td>
                        @foreach($producto->tamanos as $tamano)
                            @if($tamano->tamano == 'unico')
                                ${{number_format($tamano->valor, 0)}}
                            @else
                                @if(in_array($tamano->tamano, array("grande",'mediano','pequeno','porcion')))
                                    {{ array("grande" => 'GRA.', 'mediano'=>'MED.', 'pequeno'=>'PEQ.', 'porcion'=>'POR.')[$tamano->tamano]  }} ${{number_format($tamano->valor, 0)}} <br/>
                                @else
                                    {{$tamano->tamano}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $producto->impcomanda=='1'?'Sí':'No' }}</td>
                    <td>{{ $producto->terminado=='1'?'Sí':'No' }}</td>
                    <td style="text-align: center;">
                        @if($producto->iva == null)
                        @else
                            {{$producto->iva == 0?0:$producto->iva}}%
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($producto->impco == null)
                        @else
                            {{$producto->impco == 0?0:$producto->impco}}%
                        @endif
                    </td>
                    <td style="text-align: center" class="fix-datatable">
                        <button class='btn estado estado_1' type="button" onclick="patchEstado({{$producto->id}},2)" style='display: {{$producto->estado == 1?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ok"></i>
                        </button>
                        <button class='btn estado estado_2' type="button" onclick="patchEstado({{$producto->id}},1)" style='display: {{$producto->estado == 2?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ban-circle"></i>
                        </button>
                    </td>
                    <td class="fix-datatable">
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$producto->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <form action="borrar" id="borrar" style="display: inline-block" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="">
    </form>
    </section>

<div id="modalGuardar" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Guardar producto cómo...</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-10">
                <input type="text" name="guardar-como" id="guardar-como" class="form-control">
                <input type="hidden" name="producto-id">
                <span id="error-gc">Campo obligatorio</span>
                
            </div>
            <div class="col-md-2">
                <button type='button' onclick="guardarComo()" class="btn btn-success font bebas">Guardar</button>
            </div>
            <div class="col-md-12"></div>
            <div class="col-md-12">
                <div id='succ-gc' class="alert alert-success" role="alert">
                    
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default font bebas" data-dismiss="modal">Salir</button>
      </div>
    </div>

  </div>
</div>


<style>
    h3.popover-title{
        display: none;
    }
    span#error-gc{
        color: red;
    }
    div#succ-gc{
        display: none;
    }
    button.font.bebas{
        font-size: 1.5em;
    }
</style>
<script type = "text/javascript">
    $('#example')
    .removeClass( 'display' )
    .addClass('table table-striped table-bordered');
    $(function() {
        $('[data-toggle=confirmation]').confirmation(
        {
            buttons: [
                {
                  class: 'btn btn-primary',
                  label: 'Guardar cómo',
                  icon: 'glyphicon glyphicon-copy',
                  onClick: function() {
                      abrirGuardarComo($(this).attr("id"));
                  }
                },
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
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "Todo"]]
        });
        $.get('/borrar-sesion', function (data) {
        });
        $('input[name=guardar-como]').on('keyup blur', function(){
            var error = $('span#error-gc');

            if($('input[name=guardar-como]').val() == ''){
                error.show();
                error.closest('div').addClass('has-error');
            }
            else{
                error.hide();
                error.closest('div').removeClass('has-error');
            }
        })
    } );

    function patchEstado(id,estado){
        $.post( `/producto/${id}/estado/${estado}`, 
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
    function abrirGuardarComo(productoId){
        $('#modalGuardar').modal('show');
        $('input[name=producto-id]').val(productoId);
    }
    function guardarComo(){
        var productoId = 0;
        productoId = $('input[name=producto-id]').val();
        var guardarComoInput = $('input[name=guardar-como]').val();
        if(guardarComoInput == ''){
            return false;
        }
        mostrarFullLoading();
        $('div#succ-gc').hide();
        $('div#succ-gc').html('');
        $.post( "/producto/guardar-como/"+productoId, {nombre: guardarComoInput}).done(function( data ) {
            if(data.code==200){
                console.log(data)
                $('div#succ-gc').html('Producto creado. <a href="/producto/editar/'+data.id+'">Ver producto</a>');
                $('div#succ-gc').show();
            }
            ocultarFullLoading();
        });
    }
</script>
@endsection