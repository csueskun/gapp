@extends('template.general')
@section('titulo', 'Pedidos H-Software')


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
        <input type="hidden" name="id" value="{{ $adicional->id }}">
        <h1 class = "titulo">EDITANDO Adicional</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('codigo')) ? 'has-error'  :''}}">
                    <label for = "codigo" class = "control-label">Codigo *</label>
                    <input type = "text" class = "form-control" id = "codigo" name = "codigo" required value = "{{ old('codigo')?old('codigo'):$adicional->codigo }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('codigo') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                    <label for = "descripcion" class = "control-label">Descripcion *</label>
                    <input type = "text" class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion')?old('descripcion'):$adicional->descripcion }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('valor')) ? 'has-error'  :''}}">
                    <label for = "valor" class = "control-label">Valor *</label>
                    <input type = "text" class = "form-control" id = "valor" name = "valor" required value = "{{ old('valor')?old('valor'):$adicional->valor }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "producto_id" class = "control-label">Producto</label>
                    <select class = "form-control" id = "producto_id" name = "producto_id"  >
                        @foreach($producto_lista as $producto)
                        @if($adicional->producto_id==$producto->id || old('producto_id')==$producto->id)
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
                    <label for = "tipo_producto_id" class = "control-label">Tipo Producto</label>
                    <select class = "form-control" id = "tipo_producto_id" name = "tipo_producto_id"  >
                        @foreach($tipo_producto_lista as $tipo_producto)
                        @if($adicional->tipo_producto_id==$tipo_producto->id || old('tipo_producto_id')==$tipo_producto->id)
                        <option value="{{ $tipo_producto->id}}" selected>{{ $tipo_producto->descripcion }}</option>
                        @else
                        <option value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('tipo_producto_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('ingrediente_id')) ? 'has-error'  :''}}">
                    <label for = "ingrediente_id" class = "control-label">Ingrediente *</label>
                    <select class = "form-control" id = "ingrediente_id" name = "ingrediente_id" required >
                        @foreach($ingrediente_lista as $ingrediente)
                        @if($adicional->ingrediente_id==$ingrediente->id || old('ingrediente_id')==$ingrediente->id)
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