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
        
    <form data-toggle = "validator" role = "form" action = "editar" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $users->id }}">
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('usuario')) ? 'has-error'  :''}}">
                <label for = "usuario" class = "control-label">Nombre de Usuario *</label>
                <input type = "text" class = "form-control" id = "usuario" name = "usuario" required value = "{{ old('usuario')?old('usuario'):$users->usuario }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('usuario') }}</div>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('rol')) ? 'has-error'  :''}}">
                <label for = "rol" class = "control-label">Nombre de Rol *</label>
                <input readonly type = "text" class = "form-control" id = "rol" name = "rol" required value = "{{ old('rol')?old('rol'):$users->rol }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('rol') }}</div>
            </div>
        </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('nombres')) ? 'has-error'  :''}}">
                    <label for = "nombres" class = "control-label">Nombres *</label>
                    <input type = "text" class = "form-control" id = "nombres" name = "nombres" required value = "{{ old('nombres')?old('nombres'):$users->nombres }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('nombres') }}</div>
                </div>
            </div>
                    
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('apellidos')) ? 'has-error'  :''}}">
                    <label for = "apellidos" class = "control-label">Apellidos *</label>
                    <input type = "text" class = "form-control" id = "apellidos" name = "apellidos" required value = "{{ old('apellidos')?old('apellidos'):$users->apellidos }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('apellidos') }}</div>
                </div>
            </div>

            <div class = "col-md-6">
               <div class = "form-group has-feedback {{ ($errors->first('caja_id')) ? 'has-error'  :''}}">
                <label for = "rol" class = "control-label">Caja *</label>
                <input readonly type = "text" class = "form-control" id = "caja_id" name = "caja_id" value = "{{ old('caja_id')?old('caja_id'):$users->caja_id }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('caja_id') }}</div>
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
<script>
    $(function () {
        $('.datepicker').datepicker( {
        });
    });
</script>
@endsection