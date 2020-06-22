@extends('template.general')
@section('titulo', 'Usuario')


@section('lib')
{{ Html::script('js/validator.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Creando Usuario <a href="listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a></h1>
        <br/>
    </div>
</section>

<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        
    <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
        <h1 class = "titulo">Usuario</h1>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('usuario')) ? 'has-error'  :''}}">
                    <label for = "usuario" class = "control-label">Nombre de Usuario *</label>
                    <input type = "text" class = "form-control" id = "usuario" name = "usuario" required value = "{{ old('usuario') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('usuario') }}</div>
                </div>
            </div>
        
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('rol')) ? 'has-error'  :''}}">
                    <label for = "rol" class = "control-label">Rol *</label>
                    <select type = "text" class = "form-control" id = "rol" name = "rol"  value = "{{ old('rol') }}">
                        <option>Administrador</option>
                        <option>Mesero</option>
                        <option>Cocinero</option>
                    </select>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('rol') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('password')) ? 'has-error'  :''}}">
                    <label for = "password" class = "control-label">Contraseña *</label>
                    <input type = "password" class = "form-control" id = "password" name = "password" required value = "{{ old('password') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('password') }}</div>
                </div>
            </div>
        
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('password')) ? 'has-error'  :''}}">
                    <label for = "password_confirmation" class = "control-label">Confirmar Contraseña</label>
                    <input type = "password" class = "form-control" id = "password_confirmation" name = "password_confirmation" required value = "{{ old('password') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('password') }}</div>
                </div>
            </div>
        
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('nombres')) ? 'has-error'  :''}}">
                    <label for = "nombres" class = "control-label">Nombres *</label>
                    <input type = "text" class = "form-control" id = "nombres" name = "nombres" required value = "{{ old('nombres') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('nombres') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('apellidos')) ? 'has-error'  :''}}">
                    <label for = "apellidos" class = "control-label">Apellidos *</label>
                    <input type = "text" class = "form-control" id = "apellidos" name = "apellidos" required value = "{{ old('apellidos') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('apellidos') }}</div>
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
<script>
    $(function () {
        $('.datepicker').datepicker( {
        });
    });
</script>
@endsection