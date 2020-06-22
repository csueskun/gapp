@extends('template.tabla')
@section('titulo', 'Editando Inventario')
@section('lib')
{{ Html::script('/js/validator.min.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('/js/bootstrap-datetimepicker.es.js') }}
{{ Html::style('/css/bootstrap-datetimepicker.min.css') }}
@endsection

@section('breadcrumbs')
<ul class="breadcrumb">
    <li>
        <a href='{{url("/")}}'><i class="fa fa-home" aria-hidden="true"></i></a> <span class="divider">/</span>
    </li>
    <li>
        <a href='{{url("/saldos_producto")}}'>Inventario</a> <span class="divider">/</span>
    </li>
    <li class="active">
        Editando
    </li>
</ul>
@endsection

@section('tabla')

<div class='container'>
    <div class="row alertas">
    @include('template.status', ['status' => session('status')])
    </div>
    
    <div class="row" style="background-color: white; padding: 10px">
        <div class="col-md-12"><h2>Editando Inventario </h2></div><br/><br/>
        <div class="col-md-12">
            <form id="editar-saldos_producto" data-toggle = "validator" role = "form" action = '{{url("/saldos_producto")}}' method="POST">
                <br/>
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $saldos_producto->id }}">
                <input type="hidden" name="_method" value="put">
                <div class = "row">
                    
                    <div class = "col-md-6">
                        <div class = "campo tipo select form-group has-feedback {{ ($errors->first('producto_id')) ? 'has-error' :'' }}">
                            <label for = "producto_id" class = "control-label">producto_id *</label>
                            <select class = "form-control" id = "producto_id" name = "producto_id" required>
                                
                                @foreach($producto_lista as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
                                @endforeach
                            </select>
                            <div class = "help-block with-errors">{{ $errors->first('producto_id') }}</div>
                            <script>$("select#producto_id").val({{ old('producto_id')?old('producto_id'):$saldos_producto->producto_id }});</script>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('bodega')) ? 'has-error' :'' }}">
                            <label for = "bodega" class = "control-label">bodega *</label>
                            <input type = "text" class = "form-control" id = "bodega" name = "bodega" value = "{{ old('bodega')?old('bodega'):$saldos_producto->bodega }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('bodega') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo fecha form-group has-feedback {{ ($errors->first('fecha_act')) ? 'has-error' :'' }}">
                            <label for = "fecha_act" class = "control-label">Fecha Actualización *</label>
                            <div class="input-group">
                                <input type = "text" readonly class = "form-control datepicker" name = "_fecha_act" id = "_fecha_act" required value = ""  data-date-format="MM dd, yyyy" data-link-field="fecha_act" data-link-format="yyyy-mm-dd 00:00"/>
                                <div class="input-group-append">
                                    <button onclick="$('#_fecha_act').datetimepicker('show');" class="cal btn btn-secondary" type="button"><span class="fa fa-calendar"/></button>
                                </div>
                            </div>
                            <input class = "form-control z-index-100" required id = "fecha_act" name = "fecha_act" value=""/>
                            <script type='text/javascript'>
                                $('#_fecha_act').datetimepicker({
                                    
                                    language:  'es',
                                    todayBtn:  1,
                                    autoclose: 1,
                                    todayHighlight: 1,
                                    minView: 2,
                                    forceParse: 0
                                });
                                if("{{ old('fecha_act') }}"!==""){
                                    var fecha = new Date("{{ old('fecha_act') }}");
                                    dateToInputValue(fecha, "yyyy-mm-dd 00:00", $("input#fecha_act"));
                                    dateToInputValue(fecha, "MM dd, yyyy", $("input#_fecha_act"));
                                }
                                else if("{{ $saldos_producto->fecha_act }}"!==""){
                                    var fecha = new Date("{{ $saldos_producto->fecha_act }}");
                                    dateToInputValue(fecha, "yyyy-mm-dd 00:00", $("input#fecha_act"));
                                    dateToInputValue(fecha, "MM dd, yyyy", $("input#_fecha_act"));
                                }
                        else{
                            var fecha = new Date();
                            dateToInputValue(fecha, "yyyy-mm-dd 00:00", $("input#fecha_act"));
                            dateToInputValue(fecha, "MM dd, yyyy", $("input#_fecha_act"));
                        }
                            </script>                        
                            <div class = "help-block with-errors">{{ $errors->first('fecha_act') }}</div>
                        </div>
                    </div>
            
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('existencia')) ? 'has-error' :'' }}">
                            <label for = "existencia" class = "control-label">existencia *</label>
                            <input type = "text" class = "form-control" id = "existencia" name = "existencia" value = "{{ old('existencia')?old('existencia'):$saldos_producto->existencia }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('existencia') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('existencia_max')) ? 'has-error' :'' }}">
                            <label for = "existencia_max" class = "control-label">Existencia Máxima *</label>
                            <input type = "text" class = "form-control" id = "existencia_max" name = "existencia_max" value = "{{ old('existencia_max')?old('existencia_max'):$saldos_producto->existencia_max }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('existencia_max') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "campo tipo texto form-group has-feedback {{ ($errors->first('existencia_min')) ? 'has-error' :'' }}">
                            <label for = "existencia_min" class = "control-label">Existencia Mínima *</label>
                            <input type = "text" class = "form-control" id = "existencia_min" name = "existencia_min" value = "{{ old('existencia_min')?old('existencia_min'):$saldos_producto->existencia_min }}" required/>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('existencia_min') }}</div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class = "col-xs-12">
                        <div class = "form-group centrado">
                            <h1 class="titulo">
                                <button type = "submit" class = "btn btn-sm btn-success"><span class="fa fa-save" aria-hidden="true"></span> Guardar</button>
                                <a href='../' class="btn btn-sm btn-primary"><span class="fa fa-th-list" aria-hidden="true"></span> Volver a la Lista</a>
                            </h1>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection