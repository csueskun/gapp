
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/datatables.min.js') }}
{{ Html::script('js/dataTables.bootstrap.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
{{ Html::style('css/jquery-confirm.min.css') }}
{{ Html::script('js/jquery-confirm.min.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Combos
        <a href="/combo/crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
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
                    <th>Nombre</th>
                    <th width="100">Valor</th>
                    <th class="w1">Estado</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($combo_lista as $combo)
                <tr id='{{$combo->id}}'>
                    <td>{{ $combo->nombre }}</td>
                    <td style="text-align: right">${{number_format($combo->precio, 0)}}</td>
                    <td style="text-align: center" class="fix-datatable">
                        <button class='btn estado estado_1' type="button" onclick="patchEstado({{$combo->id}},2)" style='display: {{$combo->estado == 1?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ok"></i>
                        </button>
                        <button class='btn estado estado_2' type="button" onclick="patchEstado({{$combo->id}},1)" style='display: {{$combo->estado == 2?"initial":"none"}}'>
                            <i class="glyphicon glyphicon-ban-circle"></i>
                        </button>
                    </td>
                    <td class="fix-datatable">
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$combo->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
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
                      window.location.href = "/combo/"+$(this).attr("id")+"/editar/";
                  }
                },
                {
                  class: 'btn btn-danger',
                  label: 'Borrar',
                  icon: 'glyphicon glyphicon-trash',
                  onClick: function(){
                      borrar($(this).attr("id"))
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

    function borrar(id){
        $.confirm({
            title: 'Borrando combo',
            type: 'red',
            typeAnimated: true,
            columnClass: 'col-md-8 col-md-offset-2',

            content: 'Está seguro que quiere borrar el combo? Ésta acción es definitiva.',
            boxWidth: '300px',
            icon: 'fa fa-warning',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    text: 'Borrar',
                    action: function(){
                        mostrarFullLoading();
                        $.post('/combo/borrar-post/'+id,function(data){
                            if(data){
                                if(data.code == 200){
                                    $('#example').DataTable().row($('tr#'+id)).remove().draw();
                                    mostrarSuccess(data.msg);
                                }
                                else if(data.code == 400){
                                    mostrarError(data.msg);
                                }
                                else{
                                    mostrarError('No se pudo borrar el combo.')
                                }
                                ocultarFullLoading();
                            }
                        });
                    }
                },
                cancel: {
                    btnClass: 'btn-red',
                    text: 'Cancelar'
                },
            }
        });

    }
</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
                $('#example').DataTable({
                    "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "Todo"]]
                });
                $.get('/borrar-sesion', function (data) {
                });
        } );

    function patchEstado(id,estado){
        $.post( `/combo/${id}/estado/${estado}`,
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