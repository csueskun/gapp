
@extends('template.general')
@section('titulo', 'Crear Combo')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/funciones.js') }}
<script src="/js/jquery.inputmask.bundle.js"></script>

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h2 class="titulo">Orden de visualización en el menú</h2>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4 class='titulo'>Tipos de productos</h4>
                <ul id="tipo_producto">
                    @foreach($tipos as $tipo)
                    <li tipo_producto-id='{{$tipo->id}}' class="list-group-item"><i class="fa fa-arrows"></i> {{$tipo->descripcion}}</li>
                    @endforeach
                </ul>
                <div style='width: 100%; text-align: center'>
                    <button class='btn btn-success btn-sm btn-save-orden' onclick='guardarOrden("tipo_producto")'><i class="fa fa-save"></i> Guardar oden para tipos de productos</button>
                </div>
            </div>
            <div class="col-md-6">
                <h4 class='titulo'>Combos</h4>
                <ul id="combo">
                    @foreach($combos as $combo)
                    <li combo-id='{{$combo->id}}' class="list-group-item"><i class="fa fa-arrows"></i> {{$combo->nombre}}</li>
                    @endforeach
                </ul>
                <div style='width: 100%; text-align: center'>
                    <button class='btn btn-success btn-sm btn-save-orden' onclick='guardarOrden("combo")'><i class="fa fa-save"></i> Guardar orden para combos</button>
                </div>
            </div>
        </div>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <br>
    <div class="container">
    </div>
    <br>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
    </div>
</section>
<script>
    $( function() {
        $( "#tipo_producto" ).sortable();
        $( "#tipo_producto" ).disableSelection();
        $( "#combo" ).sortable();
        $( "#combo" ).disableSelection();
    } );

    
    function guardarOrden(tabla){
        $('.btn-save-orden').prop('disabled', true);
        $('.btn-save-orden>i').addClass('fa-spin');
        var items = $('#'+tabla + '>li');
        var orden = [];
        items.each(e=>{
            orden.push($(items[e]).attr(tabla+'-id'));
        });
        $.post( `/orden/${tabla}`, {orden: orden}).done(function( data ) {
            $('.btn-save-orden').prop('disabled', false);
            $('.btn-save-orden>i').removeClass('fa-spin');
            if(data.status == 200){
                mostrarSuccess('Nuevo orden establecido');
            }
            else{
                mostrarWarning('No se pudo establecer el nuevo orden');
            }
        });
    }
</script>
@endsection