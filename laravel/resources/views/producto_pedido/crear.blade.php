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
        <h1 class = "titulo">CREANDO Producto Pedido</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('pedido_id')) ? 'has-error'  :''}}">
                    <label for = "pedido_id" class = "control-label">Pedido *</label>
                    <select class = "form-control" id = "pedido_id" name = "pedido_id" required >
                        @foreach($pedido_lista as $pedido)
                        @if(old('pedido_id')==$pedido->id)
                        <option value="{{ $pedido->id}}" selected>{{ $pedido->descripcion }}</option>
                        @else
                        <option value="{{ $pedido->id}}">{{ $pedido->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('pedido_id') }}</div>
                </div>
            </div>
                    
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
                <div class = "form-group has-feedback {{ ($errors->first('cant')) ? 'has-error'  :''}}">
                    <label for = "cant" class = "control-label">Cant *</label>
                    <input type = "text"  class = "form-control" id = "cant" name = "cant" required value = "{{ old('cant') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('cant') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('valor')) ? 'has-error'  :''}}">
                    <label for = "valor" class = "control-label">Valor *</label>
                    <input type = "text"  class = "form-control" id = "valor" name = "valor" required value = "{{ old('valor') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total *</label>
                    <input type = "text"  class = "form-control" id = "total" name = "total" required value = "{{ old('total') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "obs" class = "control-label">Obs</label>
                    <input type = "text"  class = "form-control" id = "obs" name = "obs"  value = "{{ old('obs') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('obs') }}</div>
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