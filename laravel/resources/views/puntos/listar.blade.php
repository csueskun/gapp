
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
        <h1 class = "titulo">Puntos</h1>
        <br/>
    </div>
</section>
<section class="borde-inferior lista fondo-comun"  style="">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        <br/>
        <table class="midatatable" cellspacing="0" id='tabla_puntos'>
            <thead>
                <tr>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Puntos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($puntos_lista as $puntos)
                <tr id="{{ $puntos->id }}">
                    <td>{{ $puntos->desde }}</td>
                    <td>{{ $puntos->hasta }}</td>
                    <td>{{ $puntos->puntos }}</td>
                    <td class="min-width text-align-center">
                        <button data-toggle="modal" data-target="#editModal" onclick="prepareEdit('{{($puntos)}}')" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></button>
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

<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal">
    <div class="modal-dialog" role="document" style="width: 300px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font bebas" id="myModalLabel">
                    Actualizando rango de puntos
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="edit-form" action="borrar" id="borrar" method="PUT">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class = "form-group">
                                        <label for="desde" class="control-label">Desde</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input min="0" required type="number" class="align-right form-control" id="desde" name="desde" value="">
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class = "form-group ">
                                        <label for="hasta" class="control-label">Hasta</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input min="0" required type="number" class="align-right form-control" id="hasta" name="hasta" value="">
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class = "form-group ">
                                        <label for="puntos" class="control-label">Puntos</label>
                                        <input min="0" required type="number" class="align-right form-control" id="puntos" name="puntos" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary busy" onclick="submitEdit()">Guardar</button>
            </div>
        </div>
    </div>
</div>



<script type = "text/javascript">
$('#tabla_puntos')
.removeClass( 'display' )
.addClass('table table-striped table-bordered');
</script>
<script>

    $(function() {
        // $('html').on('click', 'ul',function (event) {
        //     console.log(event);
        // });
        var table = $('#tabla_puntos').DataTable({
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
                        $.post('/puntos/borrar-post/'+id,function(data){
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
    function prepareEdit(puntos){
        var puntos = JSON.parse(puntos);
        populate('#edit-form', puntos);
    }
    function submitEdit(){
        const formData = getFormData($("#edit-form"));
        const doneCallback = function(data){
            location.href = '/puntos/?updated=1';
        }
        ajaxPut('/puntos/', formData, doneCallback, function(){
            mostrarError('No se pudo actualizar el rango.')
        });
    }

    function borrar(id){

        $.confirm({
            title: 'Borrando puntos',
            type: 'red',
            typeAnimated: true,
            columnClass: 'col-md-8 col-md-offset-2',

            content: 'Está seguro que quiere borrar los puntos? Ésta acción es definitiva.',
            boxWidth: '300px',
            icon: 'fa fa-warning',
            buttons: {
                confirm: {
                    btnClass: 'btn-blue',
                    text: 'Borrar',
                    action: function(){
                        mostrarFullLoading();
                        $.post('/puntos/borrar-post/'+id,function(data){
                            if(data){
                                if(data.code == 200){
                                    $('#tabla_puntos').DataTable().row($('tr#'+id)).remove().draw();
                                    mostrarSuccess(data.msg);
                                }
                                else if(data.code == 400){
                                    mostrarError(data.msg);
                                }
                                else{
                                    mostrarError('No se pudo borrar el puntos.')
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