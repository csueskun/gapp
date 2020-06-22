@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
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
        <a href="listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        <br/>
        <br/>
        @include('template.status', ['status' => session('status')])
        
    <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
        <h1 class = "titulo">CREANDO Producto Ingrediente</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('producto_id')) ? 'has-error'  :''}}">
                    <label for = "producto_id" class = "control-label">Producto *</label>
                    <select class = "form-control" id = "producto_id" name = "producto_id" required >
                        @foreach($producto_lista as $producto)
                        @if(old('producto_id')==$producto->id)
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
                <div class = "form-group has-feedback {{ ($errors->first('ingrediente_id')) ? 'has-error'  :''}}">
                    <label for = "ingrediente_id" class = "control-label">Ingrediente *</label>
                    <select class = "form-control" id = "ingrediente_id" name = "ingrediente_id" required >
                        @foreach($ingrediente_lista as $ingrediente)
                        @if(old('ingrediente_id')==$ingrediente->id)
                        <option value="{{ $ingrediente->id}}" selected>{{ $ingrediente->descripcion }}</option>
                        @else
                        <option value="{{ $ingrediente->id}}">{{ $ingrediente->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('ingrediente_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-xs-12">
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary">Crear</button>
                </div>
            </div>
    
        </form>
    </div>
</section>
@endsection