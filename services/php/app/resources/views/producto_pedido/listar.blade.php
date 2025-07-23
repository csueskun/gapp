
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
        &nbsp;
        <br/>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
        <h1 class="titulo">Producto Pedido</h1>
        <a href="crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        <br/>
        <br/>
        <table id="example" class="display datatable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Valor</th>
                    <th>Total</th>
                    <th>Obs</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($producto_pedido_lista as $producto_pedido)
                <tr>
                    <td>{{ $producto_pedido->pedido->descripcion }}</td>
                    <td>{{ $producto_pedido->producto->descripcion }}</td>
                    <td>{{ $producto_pedido->cant }}</td>
                    <td>{{ $producto_pedido->valor }}</td>
                    <td>{{ $producto_pedido->total }}</td>
                    <td>{{ $producto_pedido->obs }}</td>
                    <td class="min-width text-align-center">
                    <a href="editar/{{ $producto_pedido->id }}"><span class="glyphicon glyphicon-pencil"></span></a>
                    <!--<a href=""><span class="glyphicon glyphicon-search"></span></a>-->
                    <form action="borrar" style="display: inline-block" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="id" value="{{ $producto_pedido->id }}">
                        <a href="#" class='pd'
                            data-toggle = "confirmation"
                            data-btn-ok-label = "Sí" data-btn-ok-icon = "glyphicon glyphicon-share-alt"
                            data-btn-ok-class = "btn-success"
                            data-btn-cancel-label = "No" data-btn-cancel-icon = "glyphicon glyphicon-ban-circle"
                            data-btn-cancel-class = "btn-danger"
                            data-title = "¿Desea borrar el registro?" data-content = "Esta Acción es definitiva">
                            <span class = "glyphicon glyphicon-trash"></span></a>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </section>

<script type = "text/javascript">
$('#example')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
</script>
<script>
    $(function() {
            $( "a.pd" ).click(function( event ) {
                event.preventDefault();
        });
        $('[data-toggle=confirmation]').confirmation(
                {
                    onConfirm: function () {
                        $(this).closest('form').submit();
                    }
                }
        );
    });
</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
                $('#example').DataTable();
        } );
</script>
@endsection