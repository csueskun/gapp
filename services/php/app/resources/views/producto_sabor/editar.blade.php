@extends('template.general')
@section('titulo', 'Pedidos Gapp')


@section('lib')
{{ Html::script('js/validator.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        &nbsp;
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        <a href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        <br/>
        <br/>
        @include('template.status', ['status' => session('status')])
        
    <form data-toggle = "validator" role = "form" action = "../editar" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $producto_sabor->id }}">
        <h1 class = "titulo">EDITANDO Producto Sabor</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "producto_id" class = "control-label">Producto</label>
                    <select class = "form-control" id = "producto_id" name = "producto_id"  >
                        @foreach($producto_lista as $producto)
                        @if($producto_sabor->producto_id==$producto->id || old('producto_id')==$producto->id)
                        <option value="{{ $producto->id}}" selected>{{ $producto->descripcion }}</option>
                        @else
                        <option value="{{ $producto->id}}">{{ $producto->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('producto_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "sabor_id" class = "control-label">Sabor</label>
                    <select class = "form-control" id = "sabor_id" name = "sabor_id"  >
                        @foreach($sabor_lista as $sabor)
                        @if($producto_sabor->sabor_id==$sabor->id || old('sabor_id')==$sabor->id)
                        <option value="{{ $sabor->id}}" selected>{{ $sabor->descripcion }}</option>
                        @else
                        <option value="{{ $sabor->id}}">{{ $sabor->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('sabor_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-xs-12">
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary">Guardar</button>
                </div>
            </div>
    
        </form>
    </div>
</section>
<script type='text/javascript'>
    $('.datepicker').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.datetimepicker').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
</script>
@endsection