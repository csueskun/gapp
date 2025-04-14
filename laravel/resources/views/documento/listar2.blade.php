
@extends('template.general')
@section('titulo', 'LISTA')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/datatables.min.js') }}
{{ Html::script('js/dataTables.bootstrap.min.js') }}
{{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Documento <a href="crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></h1>
        
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
                    <th>Fecha y Hora</th>
                    <th>Tipo de Documento</th>
                    <th>Número de Documento</th>
                    <th>Mesa</th>
                    <!--<th>Pedido</th>-->
                    <th>Total</th>
                    <th class="w1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($documento_lista as $documento)
                <tr>
                    <td>{{ date_format(date_create($documento->created_at), 'd/m/Y g:i a') }}</td>
                    <td>{{ array("FV"=>"Factura de Venta", "FC"=>"Factura de Compra", "PN"=>"Pago de Nómina", "BI"=>"Base Inicial", "NI"=>"Nota de inventario", "CO"=>"Consumo")[$documento->tipodoc] }}</td>
                    <td>{{ $documento->numdoc }}</td>
                    <td>{{ $documento->mesa_id }}</td>
                    <!--<td>{{ $documento->pedido_id }}</td>-->
                    <td class="text-align-right">${{ number_format($documento->total,0) }}</td>
                    <td class="fix-datatable">
                        <button data-toggle = "confirmation" data-placement="left" data-singleton="true" id="{{$documento->id}}" class="btn btn-default"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </section>
<a id="imprimir" target="_blank" href=""></a>
<form action="borrar" id="borrar" method="POST">
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
    });
</script>
<script type='text/javascript' charset='utf-8'>
        $(document).ready(function() {
                $('#example').DataTable();
        } );
</script>
@endsection