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
        <input type="hidden" name="id" value="{{ $tipo_documento->id }}">
        <h1 class = "titulo">EDITANDO Tipo Documento</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('codigo')) ? 'has-error'  :''}}">
                    <label for = "codigo" class = "control-label">Codigo *</label>
                    <input type = "text" class = "form-control" id = "codigo" name = "codigo" required value = "{{ old('codigo')?old('codigo'):$tipo_documento->codigo }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('codigo') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                    <label for = "descripcion" class = "control-label">Descripcion *</label>
                    <input type = "text" class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion')?old('descripcion'):$tipo_documento->descripcion }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('imparqueo')) ? 'has-error'  :''}}">
                    <label for = "imparqueo" class = "control-label">Imparqueo *</label>
                    <input type = "text" class = "form-control" id = "imparqueo" name = "imparqueo" required value = "{{ old('imparqueo')?old('imparqueo'):$tipo_documento->imparqueo }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('imparqueo') }}</div>
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