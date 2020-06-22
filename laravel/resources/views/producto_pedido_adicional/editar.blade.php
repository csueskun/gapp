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
        <input type="hidden" name="id" value="{{ $producto_pedido_adicional->id }}">
        <h1 class = "titulo">EDITANDO Producto Pedido Adicional</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('producto_pedido_id')) ? 'has-error'  :''}}">
                    <label for = "producto_pedido_id" class = "control-label">Producto Pedido *</label>
                    <select class = "form-control" id = "producto_pedido_id" name = "producto_pedido_id" required >
                        @foreach($producto_pedido_lista as $producto_pedido)
                        @if($producto_pedido_adicional->producto_pedido_id==$producto_pedido->id || old('producto_pedido_id')==$producto_pedido->id)
                        <option value="{{ $producto_pedido->id}}" selected>{{ $producto_pedido->descripcion }}</option>
                        @else
                        <option value="{{ $producto_pedido->id}}">{{ $producto_pedido->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('producto_pedido_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('adicional_id')) ? 'has-error'  :''}}">
                    <label for = "adicional_id" class = "control-label">Adicional *</label>
                    <select class = "form-control" id = "adicional_id" name = "adicional_id" required >
                        @foreach($adicional_lista as $adicional)
                        @if($producto_pedido_adicional->adicional_id==$adicional->id || old('adicional_id')==$adicional->id)
                        <option value="{{ $adicional->id}}" selected>{{ $adicional->descripcion }}</option>
                        @else
                        <option value="{{ $adicional->id}}">{{ $adicional->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('adicional_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('cant')) ? 'has-error'  :''}}">
                    <label for = "cant" class = "control-label">Cant *</label>
                    <input type = "text" class = "form-control" id = "cant" name = "cant" required value = "{{ old('cant')?old('cant'):$producto_pedido_adicional->cant }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('cant') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('valor')) ? 'has-error'  :''}}">
                    <label for = "valor" class = "control-label">Valor *</label>
                    <input type = "text" class = "form-control" id = "valor" name = "valor" required value = "{{ old('valor')?old('valor'):$producto_pedido_adicional->valor }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total *</label>
                    <input type = "text" class = "form-control" id = "total" name = "total" required value = "{{ old('total')?old('total'):$producto_pedido_adicional->total }}">
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