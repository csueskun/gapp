@extends('template.general')
@section('titulo', 'Pedidos Gapp')


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
        <h1 class = "titulo">CREANDO Producto Sabor</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "producto_id" class = "control-label">Producto</label>
                    <select class = "form-control" id = "producto_id" name = "producto_id"  >
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
                <div class = "form-group">
                    <label for = "sabor_id" class = "control-label">Sabor</label>
                    <select class = "form-control" id = "sabor_id" name = "sabor_id"  >
                        @foreach($sabor_lista as $sabor)
                        @if(old('sabor_id')==$sabor->id)
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
                    <button type = "submit" class = "btn btn-primary">Crear</button>
                </div>
            </div>
    
        </form>
    </div>
</section>
@endsection