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
        <h1 class = "titulo">CREANDO Config</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('codigo')) ? 'has-error'  :''}}">
                    <label for = "codigo" class = "control-label">Codigo *</label>
                    <input type = "text"  class = "form-control" id = "codigo" name = "codigo" required value = "{{ old('codigo') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('codigo') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                    <label for = "descripcion" class = "control-label">Descripcion *</label>
                    <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('tabla')) ? 'has-error'  :''}}">
                    <label for = "tabla" class = "control-label">Tabla *</label>
                    <input type = "text"  class = "form-control" id = "tabla" name = "tabla" required value = "{{ old('tabla') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('tabla') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "valor" class = "control-label">Valor</label>
                    <input type = "text"  class = "form-control" id = "valor" name = "valor"  value = "{{ old('valor') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group">
                    <label for = "valor_alf" class = "control-label">Valor Alf</label>
                    <input type = "text"  class = "form-control" id = "valor_alf" name = "valor_alf"  value = "{{ old('valor_alf') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('valor_alf') }}</div>
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