@extends('template.general')
@section('titulo', 'EDITANDO Producto Pedido Documento')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/formularios.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">EDITANDO Producto Pedido Documento</h1>
        <a href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        <br/>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
    <form data-toggle = "validator" role = "form" action = "../editar" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $producto_pedido_documento->id }}">
        
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('producto_id')) ? 'has-error'  :''}}">
                    <label for = "producto_id" class = "control-label">Producto *</label>
                    <input type = "text"  class = "form-control" id = "producto_id" name = "producto_id" required value = "{{ old('producto_id')?old('producto_id'):$producto_pedido_documento->producto_id }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('producto_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('cant')) ? 'has-error'  :''}}">
                    <label for = "cant" class = "control-label">Cant *</label>
                    <input type = "text"  class = "form-control" id = "cant" name = "cant" required value = "{{ old('cant')?old('cant'):$producto_pedido_documento->cant }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('cant') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('valor')) ? 'has-error'  :''}}">
                    <label for = "valor" class = "control-label">Valor *</label>
                    <input type = "text"  class = "form-control" id = "valor" name = "valor" required value = "{{ old('valor')?old('valor'):$producto_pedido_documento->valor }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total *</label>
                    <input type = "text"  class = "form-control" id = "total" name = "total" required value = "{{ old('total')?old('total'):$producto_pedido_documento->total }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group">
                    <label for = "producto_pedido_id" class = "control-label">Producto Pedido</label>
                    <input type = "text"  class = "form-control" id = "producto_pedido_id" name = "producto_pedido_id"  value = "{{ old('producto_pedido_id')?old('producto_pedido_id'):$producto_pedido_documento->producto_pedido_id }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('producto_pedido_id') }}</div>
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

@endsection