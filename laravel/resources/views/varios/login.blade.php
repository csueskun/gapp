@extends('template.general')
@section('menu-login')
<li class='active'><a href="/login">Iniciar Sesión</a></li>
@endsection
@section('lib')
{{ Html::script('js/validator.min.js') }}
<style>
    html{
        background-color: #f8f8f8;
    }
    body{
        /* padding-top: 90px !important; */
        padding-bottom: 40px;
        background-color: #f8f8f8;
    }
    form{
        padding-top: 90px !important;
    }
    input[type=text] {
           
           border-radius:20px;
         -webkit-appearance: none;
    }
    input[type=password] {
           
           border-radius:20px;
         -webkit-appearance: none;
    }
</style>

@endsection
@section('titulo', 'Inicio de Sesión')
@section('contenido')


<div class="container">
    
    
    
    <form data-toggle="validator" role="form" action="hacerlogin" class="form-login"  >
        @include('template.status', ['status' => session('status')])
        <div align="center">
          <img class="mb-4" src="/images/logoinicio.png" alt="" width="150" height="150">
        </div>
        <h1 class="fuente bebas" style="color: #777" align="center">Inicie Sesión</h1>
        <div class="form-group has-feedback no-margin">
            <input type="text" class="form-control" id="nombre" name="usuario" required value="{{ old('usuario') }}" placeholder=" Nombre de usuario" style="color: #666">
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback {{ ($errors->first('password')) ? 'has-error'  :''}}">
            <input type="password" class="form-control" id="password" name="password" required placeholder=" Contraseña">
            <span class="glyphicon form-control-feedback {{ ($errors->first('password')) ? 'glyphicon-remove'  :''}}" aria-hidden="true"></span>
            <div class="help-block with-errors">{{ $errors->first('password') }}</div>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember_token" value="remember-me"> Recordarme
            </label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-primary btn-block">Enviar</button>
        </div>
       
        
    </form>
</div>

<script>
    $(document).ready(function () {
        agregarClase("nav.mi-footer","fijo-bottom");
    });
</script>

@endsection