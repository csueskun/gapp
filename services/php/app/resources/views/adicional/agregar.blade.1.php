@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-comun">
    <div class="container">
    <h1 class = "titulo">CREANDO ADICIONALES</h1>
    <br/>
    @include('template.status', ['status' => session('status')])
        <form data-toggle = "validator" role = "form" action = "crear" id="form-tipo-producto">
            <h3 class = "titulo">Tipo De Producto 
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-tipo-producto">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                </button>
            </h3>
            <div class = "col-md-12">
                <div class = "form-group has-feedback {{ ($errors->first('tipo_producto_id')) ? 'has-error'  :''}}">
                    <select class = "form-control has-feedback" required id = "tipo_producto_id" name = "tipo_producto_id" >
                        <option value="">---</option>
                        @foreach($tipo_producto_lista as $tipo_producto)
                        @if($tipo_producto->aplica_ingredientes=='1')
                        <option nombre="{{ $tipo_producto->descripcion}}" aplica_tamanos="{{$tipo_producto->aplica_tamanos}}" value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                </div>
            </div>
            <div class = "col-md-12">
                <div class = "form-group has-feedback">
                    <input type = "text"  class = "form-control" name = "tipo_producto_id_" id = "tipo_producto_id_" value="" required style="position: absolute; top: -2000px"/>
                    <div class = "help-block with-errors"></div>
                </div>
            </div>
        </form>
    </div>
</section>
<section class="borde-inferior form fondo-comun sin-tamanos" style="display: none">
    <div class="container">
        <form data-toggle = "validator" role = "form" action = "crear" id="form-adicionales" method="POST">
            <h3 class = "titulo">Adicionales<button type="button"  class="btn btn-warning" data-toggle="modal" data-target="#modal-ingrediente"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</button></h3>
            @foreach($ingrediente_lista as $ingrediente)
            <table class="adicional">
                <tbody>
                    <tr style="height: 43px">
                        <td>
                            <div class="form-group">
                                <label class="radio-inline control-label">
                                    <input type="checkbox" value="{{$ingrediente->id}}" id="ingrediente-{{$ingrediente->id}}" name="ingrediente">{{$ingrediente->descripcion}}
                                </label>
                            </div>

                        </td>
                        <td class="form-group" style="padding-left: 8px;">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input disabled type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}" nombre = "{{$ingrediente->descripcion}}" name = "valor" style="max-width: 95px;">
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            
        </form>
    </div>
</section>
<section class="borde-inferior form fondo-comun con-tamanos" style="display: none">
    <div class="container">
        <form data-toggle = "validator" role = "form" action = "crear" id="form-adicionales" method="POST">
            <h3 class = "titulo">Adicionales</h3>
            @foreach($ingrediente_lista as $ingrediente)
                    <div class="container">
                            <h3 class = "titulo">{{$ingrediente->descripcion}}</h3>
<!--                            <table class="adicional">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input type="checkbox" value="{{$ingrediente->id}}-u" id="{{$ingrediente->id}}-u">Tamaño Único
                                                </label>
                                            </div>
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled required nombre="ÚNICO" type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}-u" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>-->
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label font bebas">
                                                    <input type="checkbox" value="{{$ingrediente->id}}-g" id="{{$ingrediente->id}}-g">Grande
                                                </label>
                                            </div>

                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="GRANDE" type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}-g" name = "valor" style="max-width: 95px;" ingrediente-nombre='{{$ingrediente->descripcion}}'>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label font bebas">
                                                    <input type="checkbox" value="{{$ingrediente->id}}-m" id="{{$ingrediente->id}}-m">Mediano
                                                </label>
                                            </div>

                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="MEDIANO" type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}-m" name = "valor" style="max-width: 95px;" ingrediente-nombre='{{$ingrediente->descripcion}}'>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label font bebas">
                                                    <input type="checkbox" value="{{$ingrediente->id}}-p" id="{{$ingrediente->id}}-p">Pequeño
                                                </label>
                                            </div>

                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="PEQUEÑO" type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}-p" name = "valor" style="max-width: 95px;" ingrediente-nombre='{{$ingrediente->descripcion}}'>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label font bebas">
                                                    <input type="checkbox" value="{{$ingrediente->id}}-s" id="{{$ingrediente->id}}-s">Porción
                                                </label>
                                            </div>

                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="PORCIÓN" type = "number" min="0" max="999999999" class = "align-right form-control" id = "valor-{{$ingrediente->id}}-s" name = "valor" style="max-width: 95px;" ingrediente-nombre='{{$ingrediente->descripcion}}'>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                <!--</div>-->
            @endforeach
            <!--</div>-->
        </form>
    </div>
</section>
    

<section class="borde-inferior form fondo-blanco">
    <br/>
    <div class="container_ centrado">
        <div class = "col-xs-12">
            <div class = "form-group">
                <button type="button"onclick="guardarAdicionales()" class = "boton-agregar-producto btn btn-success"><span class="fa fa-save"></span> GUARDAR ADICIONALES</button>
            </div>
        </div>
    </div>
    <br/>
</section>

<div id="modal-tipo-producto" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h1 class = "titulo">CREANDO Tipo Producto</h1></h4>
            </div>
            <div class="modal-body">
                <form data-toggle = "validator" role = "form">
                    {{ csrf_field() }}

                    <div class = "col-md-12">
                        <div class = "form-group has-feedback">
                            <label for = "descripcion" class = "control-label">Descripcion *</label>
                            <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion') }}">
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                        </div>
                    </div>
                    
                    <div class = "col-md-12">
                        <div class = "form-group campo-radios">
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1" name="aplica_tamanos">Aplica Tamaños
                            </label>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1" name="aplica_ingredientes">Aplica Ingredientes
                            </label>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1" name="aplica_sabores">Aplica Sabores
                            </label>
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group campo-radios" id="fracciones">
                            <label for = "descripcion" class = "control-label">Fracciones *</label><br/>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input disabled checked type="checkbox" value="1/1" name="fraccion">1/1(Completa)
                            </label>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1/2" name="fraccion">1/2(Mitades)
                            </label>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1/3" name="fraccion">1/3(Tercios)
                            </label>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1/4" name="fraccion">1/4(Cuartos)
                            </label>
                        </div>
                    </div>
                    
                    <div class = "col-md-12">
                        <div class="mensaje"></div>
                    </div>

                    <div class = "col-xs-12">
                        <div class = "form-group">
                            <button type = "button" onclick="crearTipoProducto($(this).closest('form'))" class = "btn btn-primary">Crear</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>

<div id="modal-ingrediente" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h1 class = "titulo">CREANDO INGREDIENTE</h1></h4>
            </div>
            <div class="modal-body">
                <form data-toggle = "validator" role = "form" action = "crear" method="POST">
                    {{ csrf_field() }}
                    <div class = "col-md-12">
                        <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                            <label for = "descripcion" class = "control-label">Descripcion *</label>
                            <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion') }}">
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-12">
                        <div class="mensaje"></div>
                    </div>
                    <div class = "col-xs-12">
                        <div class = "form-group">
                            <button type = "button" onclick="crearIngrediente()" class = "btn btn-primary">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>

{{ Html::script('js/adicional.js') }}
    
@endsection