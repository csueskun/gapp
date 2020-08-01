@extends('template.general')
@section('titulo', 'Editando Tercero')
@section('lib')
{{ Html::script('/js/validator.min.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.es.js') }}
{{ Html::style('/css/bootstrap-datetimepicker.min.css') }}
@endsection

<!--@section('breadcrumbs')
<ul class="breadcrumb">
    <li>
        <a href='{{url("/")}}'><i class="fa fa-home" aria-hidden="true"></i></a> <span class="divider">/</span>
    </li>
    <li>
        <a href='{{url("/tercero")}}'>Tercero</a> <span class="divider">/</span>
    </li>
    <li class="active">
        Editando
    </li>
</ul>
@endsection-->

@section('contenido')
<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">Editando Tercero
            <a style="font-size: 20px;" href="../" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
       <br/> 
        @include('template.status', ['status' => session('status')])
        
        <form id="editar-tercero" data-toggle = "validator" role = "form" action = '{{url("/tercero")}}' method="POST"> 
        <br/>
        {{ csrf_field() }}
        
  
        <input type="hidden" name="id" value="{{ $tercero->id }}">
        <input type="hidden" name="_method" value="put">
        <div class = "row">
                    
                    <div class = "col-md-4">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('identificacion')) ? 'has-error' :'' }}">
                            <label for = "identificacion" class = "control-label">Identificación *</label>
                            <input type = "text" class = "form-control" id = "identificacion" name = "identificacion" value = "{{ old('identificacion')?old('identificacion'):$tercero->identificacion }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('identificacion') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class = "campo tipo select form-group has-feedback {{ ($errors->first('tipoidenti')) ? 'has-error' :'' }}">
                            <label for = "tipoidenti" class = "control-label">Tipo Identificación </label>
                            <select class = "form-control" id = "tipoidenti" name = "tipoidenti" >
                               <option value="1">CC</option>
                               <option value="2">NIT</option>
                               <option value="3">CE</option>
                               <option value="4">TI</option>
                            </select>
                            <div class = "help-block with-errors">{{ $errors->first('tipoidenti') }}</div>
                            <script>$("select#tipoidenti").val({{ old('tipoidenti')?old('tipoidenti'):$tercero->tipoidenti }});</script>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <!--<div class = "campo tipo texto form-group has-feedback {{ ($errors->first('tipoclie')) ? 'has-error' :'' }}">
                            <label for = "tipoclie" class = "control-label">Tipo Cliente </label>
                            <input type = "text" class = "form-control" id = "tipoclie" name = "tipoclie" value = "{{ old('tipoclie')?old('tipoclie'):$tercero->tipoclie }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('tipoclie') }}</div>
                        </div>-->
                        <div class = "campo tipo select form-group has-feedback {{ ($errors->first('tipoclie')) ? 'has-error' :'' }}">
                            <label for = "tipoclie" class = "control-label">Tipo Cliente </label>
                            <select class = "form-control" id = "tipoclie" name = "tipoclie" >
                               <option value="C">Cliente</option>
                               <option value="P">Proveedor</option>
                               <option value="E">Empleado</option>
                               <option value="O">Otro</option>
                            </select>
                            <div class = "help-block with-errors">{{ $errors->first('tipoclie') }}</div>
                            <script>$("select#tipoclie").val({{ old('tipoclie')?old('tipoclie'):$tercero->tipoclie }});</script>
                        </div>
                    </div>
                </div>    
                <div class = "row">
                    <div class = "col-md-8">
                        <div class = "campo tipo select form-group has-feedback {{ ($errors->first('nombrecompleto')) ? 'has-error' :'' }}">
                            <label for = "nombrecompleto" class = "control-label">Nombre Completo *</label>
                            <input type = "text" class = "form-control" id = "nombrecompleto" name = "nombrecompleto" value = "{{ old('nombrecompleto')?old('nombrecompleto'):$tercero->nombrecompleto }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('nombrecompleto') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('fecha_nacimiento')) ? 'has-error' :'' }}">
                            <label for = "fecha_nacimiento" class = "control-label">Fecha Nacimiento </label>
                            <input type = "text" class = "form-control" id = "fecha_nacimiento" name = "fecha_nacimiento" value = "{{ old('fecha_nacimiento')?old('fecha_nacimiento'):$tercero->fecha_nacimiento }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('fecha_nacimiento') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('direccion')) ? 'has-error' :'' }}">
                            <label for = "direccion" class = "control-label">Dirección </label>
                            <input type = "text" class = "form-control" id = "direccion" name = "direccion" value = "{{ old('direccion')?old('direccion'):$tercero->direccion }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('direccion') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('telefono')) ? 'has-error' :'' }}">
                            <label for = "telefono" class = "control-label">Teléfono </label>
                            <input type = "text" class = "form-control" id = "telefono" name = "telefono" value = "{{ old('telefono')?old('telefono'):$tercero->telefono }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('telefono') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('celular')) ? 'has-error' :'' }}">
                            <label for = "celular" class = "control-label">Celular </label>
                            <input type = "text" class = "form-control" id = "celular" name = "celular" value = "{{ old('celular')?old('celular'):$tercero->celular }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('celular') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('email')) ? 'has-error' :'' }}">
                            <label for = "email" class = "control-label">Email </label>
                            <input type = "text" class = "form-control" id = "email" name = "email" value = "{{ old('email')?old('email'):$tercero->email }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('email') }}</div>
                        </div>
                    </div>
                    
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('nombre1')) ? 'has-error' :'' }}">
                            <label for = "nombre1" class = "control-label">Primer Nombre </label>
                            <input type = "text" class = "form-control" id = "nombre1" name = "nombre1" value = "{{ old('nombre1')?old('nombre1'):$tercero->nombre1 }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('nombre1') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('nombre2')) ? 'has-error' :'' }}">
                            <label for = "nombre2" class = "control-label">Segundo Nombre </label>
                            <input type = "text" class = "form-control" id = "nombre2" name = "nombre2" value = "{{ old('nombre2')?old('nombre2'):$tercero->nombre2 }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('nombre2') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('apellido1')) ? 'has-error' :'' }}">
                            <label for = "apellido1" class = "control-label">Primer Apellido </label>
                            <input type = "text" class = "form-control" id = "apellido1" name = "apellido1" value = "{{ old('apellido1')?old('apellido1'):$tercero->apellido1 }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('apellido1') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('apellido2')) ? 'has-error' :'' }}">
                            <label for = "apellido2" class = "control-label">Segundo Apellido </label>
                            <input type = "text" class = "form-control" id = "apellido2" name = "apellido2" value = "{{ old('apellido2')?old('apellido2'):$tercero->apellido2 }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('apellido2') }}</div>
                        </div>
                    </div>

                    <div class = "col-md-12">
                        <div class = "key- tipo- form-group has-feedback {{ ($errors->first('observacion')) ? 'has-error'  :''}}">
                           <label for = "observacion" class = "control-label">Observación</label>
                           <textarea  class = "form-control" id = "observacion" name = "observacion">{{ old('observacion') }}</textarea>
                           <!-- <input type = "text" class = "form-control" id = "observacion" name = "observacion" value = "{{ old('observacion') }}" />-->
                           <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                           <div class = "help-block with-errors">{{ $errors->first('observacion') }}</div>
                        </div>
                    </div>
                    
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('nrotarjetapuntos')) ? 'has-error' :'' }}">
                            <label for = "nrotarjetapuntos" class = "control-label">Tarjeta Puntos </label>
                            <input type = "text" class = "form-control" id = "nrotarjetapuntos" name = "nrotarjetapuntos" value = "{{ old('nrotarjetapuntos')?old('nrotarjetapuntos'):$tercero->nrotarjetapuntos }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('nrotarjetapuntos') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('puntosacumulados')) ? 'has-error' :'' }}">
                            <label for = "puntosacumulados" class = "control-label">Puntos Acumulados </label>
                            <input type = "text" class = "form-control" id = "puntosacumulados" name = "puntosacumulados" value = "{{ old('puntosacumulados')?old('puntosacumulados'):$tercero->puntosacumulados }}" />
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('puntosacumulados') }}</div>
                        </div>
                    </div>
                    
                </div>
                <div class="container">
                    <div class = "col-xs-12">
                        <div class = "form-group centrado">
                            <!--<h1 class="titulo">-->
                                <button type = "submit" class = "btn btn-sm btn-success" ><span class="fa fa-save" aria-hidden="true"> Guardar </span></button>
                                <!--<a href='../' class="btn btn-sm btn-primary"><span class="fa fa-th-list" aria-hidden="true"></span> Volver a la Lista</a>-->
                            <!--</h1>-->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection