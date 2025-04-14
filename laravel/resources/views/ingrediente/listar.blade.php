
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
        <h1 class = "titulo">Ingredientes
            <a href="crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        </h1>
        <br/>
    </div>
</section>
<section class="borde-inferior lista fondo-comun"  style="">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        <br/>
        <table class="midatatable" cellspacing="0" id='tabla_ingredientes'>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Grupo</th>
                    <th>Unidad</th>
                    <th>Imagen</th>
                    <th>Visible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingrediente_lista as $ingrediente)
                <tr id="{{ $ingrediente->id }}">
                    <td style='max-width: 200px;'>
                        {{ $ingrediente->descripcion }}
                    </td>
                    <td> {{ $ingrediente->grupo }}</td>
                    <td class=''>
                        {{array(
                            'gr'=>'Gramos (gr)',
                            'grs'=>'Gramos (gr)',
                            'kg'=>'Kilogramos (kg)',
                            'kgr'=>'Kilogramos (kg)',
                            'ml'=>'Mililitros (ml)',
                            'mls'=>'Mililitros (ml)',
                            'lt'=>'Litros (lt)',
                            'lts'=>'Litros (lt)',
                            'und'=>'Unidades (und)',
                            'unds'=>'Unidades (und)',
                        )[$ingrediente->unidad]}}
                    </td>
                    <td class='centrado min-width'><img src="/images/ingrediente/{{ $ingrediente->imagen }}" style="height: 60px"/></td>
                    <td class="centrado"><input type="checkbox" class="manualDisabled checkbox-grande" {{ $ingrediente->visible == '1'?'checked':'' }}/> </td>
                    <td class="min-width text-align-center">
                        <a href="editar/{{ $ingrediente->id }}" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></a>
                        <button onclick="(borrar({{ $ingrediente->id }}))" class="btn btn-danger"><span class = "glyphicon glyphicon-trash"></span></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br/>
    <br/>
    <br/>
</section>



<script type = "text/javascript">
$('#tabla_ingredientes')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
</script>
<script>
    $(function() {
        // $('html').on('click', 'ul',function (event) {
        //     console.log(event);
        // });
        var table = $('#tabla_ingredientes').DataTable({
            "pageLength": 8,
            "lengthMenu": [[8, 16, 32, -1], [8, 16, 32, "Todos"]]
        });
        $( "a.pd" ).click(function( event ) {
            event.preventDefault();
        });
        $('[data-toggle=confirmation]').confirmation(
                {
                    onConfirm: function () {
                        var el = $(this);
                        var tr = el.closest('tr');
                        var id = el.find('input#id').val();
                        mostrarFullLoading();
                        $.post('/ingrediente/borrar-post/'+id,function(data){
                            if(data){
                                // console.log(data)
                                // table.row(tr).remove().draw();
                                // ocultarFullLoading();
                            }
                        });
                    }
                }
        );
    });
    function borrar(id){

        $.confirm({
            title: 'Borrando ingrediente',
            type: 'red',
            typeAnimated: true,
            columnClass: 'col-md-8 col-md-offset-2',

            content: 'Está seguro que quiere borrar el ingrediente? Ésta acción es definitiva.',
            boxWidth: '300px',
            icon: 'fa fa-warning',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    text: 'Borrar',
                    action: function(){
                        mostrarFullLoading();
                        $.post('/ingrediente/borrar-post/'+id,function(data){
                            if(data){
                                if(data.code == 200){
                                    $('#tabla_ingredientes').DataTable().row($('tr#'+id)).remove().draw();
                                    mostrarSuccess(data.msg);
                                }
                                else if(data.code == 400){
                                    mostrarError(data.msg);
                                }
                                else{
                                    mostrarError('No se pudo borrar el ingrediente.')
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
@endsection