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
        <input type="hidden" name="id" value="{{ $pedido->id }}">
        <h1 class = "titulo">EDITANDO Pedido</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('fecha')) ? 'has-error'  :''}}">
                    <label for = "fecha" class = "control-label">Fecha *</label>
                    <input type = "text" class = "form-control datepicker" id = "fecha" name = "fecha" required value = "{{ old('fecha')?old('fecha'):$pedido->fecha }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('fecha') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('hora')) ? 'has-error'  :''}}">
                    <label for = "hora" class = "control-label">Hora *</label>
                    <input type = "text" class = "form-control" id = "hora" name = "hora" required value = "{{ old('hora')?old('hora'):$pedido->hora }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('hora') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('mesa_id')) ? 'has-error'  :''}}">
                    <label for = "mesa_id" class = "control-label">Mesa *</label>
                    <input type = "text" class = "form-control" id = "mesa_id" name = "mesa_id" required value = "{{ old('mesa_id')?old('mesa_id'):$pedido->mesa_id }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('mesa_id') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total *</label>
                    <input type = "text" class = "form-control" id = "total" name = "total" required value = "{{ old('total')?old('total'):$pedido->total }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "obs" class = "control-label">Obs</label>
                    <input type = "text" class = "form-control" id = "obs" name = "obs"  value = "{{ old('obs')?old('obs'):$pedido->obs }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('obs') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('tipopedido')) ? 'has-error'  :''}}">
                    <label for = "tipopedido" class = "control-label">Tipopedido *</label>
                    <input type = "text" class = "form-control" id = "tipopedido" name = "tipopedido" required value = "{{ old('tipopedido')?old('tipopedido'):$pedido->tipopedido }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('tipopedido') }}</div>
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