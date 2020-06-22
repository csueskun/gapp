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
        <h1 class = "titulo">CREANDO Producto</h1>
                    
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
                <div class = "form-group has-feedback {{ ($errors->first('detalle')) ? 'has-error'  :''}}">
                    <label for = "detalle" class = "control-label">Detalle *</label>
                    <input type = "text"  class = "form-control" id = "detalle" name = "detalle" required value = "{{ old('detalle') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('detalle') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('tipo_producto_id')) ? 'has-error'  :''}}">
                    <label for = "tipo_producto_id" class = "control-label">Tipo Producto *</label>
                    <select class = "form-control" id = "tipo_producto_id" name = "tipo_producto_id" required >
                        @foreach($tipo_producto_lista as $tipo_producto)
                        @if(old('tipo_producto_id')==$tipo_producto->id)
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
                <div class = "form-group">
                    <label for = "observacion" class = "control-label">Observacion</label>
                    <input type = "text"  class = "form-control" id = "observacion" name = "observacion"  value = "{{ old('observacion') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('observacion') }}</div>
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
                <div class = "form-group has-feedback {{ ($errors->first('impcomanda')) ? 'has-error'  :''}}">
                    <label for = "impcomanda" class = "control-label">Impcomanda *</label>
                    <input type = "text"  class = "form-control" id = "impcomanda" name = "impcomanda" required value = "{{ old('impcomanda') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('impcomanda') }}</div>
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