@extends('template.general')
@section('titulo', 'Usuario')


@section('lib')
{{ Html::script('js/validator.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Cambio de Contraseña</h1>
        <br/>
    </div>
</section>

<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
    <form data-toggle = "validator" role = "form" action = "editarpass" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $users->id }}">
                    
        <div class = "form-group has-feedback {{ ($errors->first('password_viejo')) ? 'has-error'  :''}}">
            <label for = "password_viejo" class = "control-label">Contraseña Anterior</label>
            <input type = "password" class = "form-control" id = "password_viejo" name = "password_viejo" required value = "{{ old('password_viejo') }}">
            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
            <div class = "help-block with-errors">{{ $errors->first('password_viejo') }}</div>
        </div>

        <div class = "form-group has-feedback {{ ($errors->first('password')) ? 'has-error'  :''}}">
            <label for = "password" class = "control-label">Contraseña Nueva</label>
            <input type = "password" class = "form-control" id = "password" name = "password" required value = "{{ old('password') }}">
            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
            <div class = "help-block with-errors">{{ $errors->first('password') }}</div>
        </div>

        <div class = "form-group has-feedback {{ ($errors->first('password')) ? 'has-error'  :''}}">
            <label for = "password_confirmation" class = "control-label">Confirmar Contraseña Nueva</label>
            <input type = "password" class = "form-control" id = "password_confirmation" name = "password_confirmation" required value = "{{ old('password') }}">
            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
            <div class = "help-block with-errors">{{ $errors->first('password') }}</div>
        </div>
                    
            <div class = "col-xs-12">
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary">Guardar</button>
                </div>
            </div>
    
        </form>
    </div>
</section>
<script>
    $(function () {
        $('.datepicker').datepicker( {
        });
    });
</script>
@endsection