@extends('template.general')
@section('titulo', 'EDITANDO Documento')


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
        <h1 class = "titulo">EDITANDO Documento</h1>
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
        <input type="hidden" name="id" value="{{ $documento->id }}">
        
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tipodoc')) ? 'has-error'  :''}}">
                    <label for = "tipodoc" class = "control-label">Tipo de Documento *</label>
                    <input type = "text"  class = "form-control" id = "tipodoc" name = "tipodoc" required value = "{{ old('tipodoc')?old('tipodoc'):$documento->tipodoc }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('tipodoc') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('numdoc')) ? 'has-error'  :''}}">
                    <label for = "numdoc" class = "control-label">Número de Documento *</label>
                    <input type = "text"  class = "form-control" id = "numdoc" name = "numdoc" required value = "{{ old('numdoc')?old('numdoc'):$documento->numdoc }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('numdoc') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('mesa_id')) ? 'has-error'  :''}}">
                    <label for = "mesa_id" class = "control-label">Mesa *</label>
                    <input type = "text"  class = "form-control" id = "mesa_id" name = "mesa_id" required value = "{{ old('mesa_id')?old('mesa_id'):$documento->mesa_id }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('mesa_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group">
                    <label for = "pedido_id" class = "control-label">Pedido</label>
                    <input type = "text"  class = "form-control" id = "pedido_id" name = "pedido_id"  value = "{{ old('pedido_id')?old('pedido_id'):$documento->pedido_id }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('pedido_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total *</label>
                    <input type = "text"  class = "form-control" id = "total" name = "total" required value = "{{ old('total')?old('total'):$documento->total }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
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