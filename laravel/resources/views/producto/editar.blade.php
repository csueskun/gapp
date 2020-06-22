@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}

<style>
    table#tamanos th, table#unidades_ingredientes thead th{
        font-family: 'bebas_neuebold';
        font-size: 20px;
        text-align: center;
    }
    span.input-group-addon{
        min-width: 50px;
    }
</style>
@endsection

@section('contenido')
<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">EDITANDO Producto 
            <a style="font-size: 20px;" href="../agregar" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Crear Nuevo</a>
            <a style="font-size: 20px;" href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior fondo-comun z-index-100">
    <div class="container">
    <form data-toggle = "validator" role = "form" action = "crear" id="form-tipo-producto">
        {{ csrf_field() }}
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
                        @if($producto->tipo_producto_id==$tipo_producto->id)
                        <option selected="selected" nombre="{{ $tipo_producto->descripcion}}" value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @else
                        <option nombre="{{ $tipo_producto->descripcion}}" value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @endif
                        @endforeach
                    <select/>
                </div>
            </div>
            <div class = "col-md-12">
                <div class = "form-group has-feedback">
                    <input type = "text"  class = "form-control" name = "tipo_producto_id_" id = "tipo_producto_id_" value="{{$producto->tipo_producto_id}}" required style="position: absolute; top: -2000px"/>
                    <div class = "help-block with-errors"></div>
                </div>
            </div>
        </form>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
    <br/>
    @include('template.status', ['status' => session('status')])
        <br/>
    <form data-toggle = "validator" role = "form" action = "crear" method="POST" id="form-producto">
        {{ csrf_field() }}
        <input type = "hidden"  class = "form-control" id = "id" name = "id" required value = "{{ $producto->id }}">
        <div class = "col-md-12">
            <div class = "form-group">
                <label for = "descripcion" class = "control-label">Tipo de Producto</label>
                <select class = "form-control has-feedback" producto_id="{{$producto->id}}" required id = "tipo_producto_s" name = "tipo_producto_s" onchange="updateTipo({{$producto->id}},this,{{ $producto->tipo_producto->id }})">
                    <option value="">---</option>
                    @foreach($tipo_producto_lista as $tipo_producto)
                        @if($producto->tipo_producto_id==$tipo_producto->id)
                            <option selected="selected" nombre="{{ $tipo_producto->descripcion}}" value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @else
                            <option nombre="{{ $tipo_producto->descripcion}}" value="{{ $tipo_producto->id}}">{{ $tipo_producto->descripcion }}</option>
                        @endif
                    @endforeach
                    <select/>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors"> </div>
            </div>
        </div>
        <div class = "col-md-12">
            <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                <label for = "descripcion" class = "control-label">Descripcion *</label>
                <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ $producto->descripcion }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
            </div>
        </div>
        <div class = "col-md-12">
            <div class = "form-group has-feedback {{ ($errors->first('grupo')) ? 'has-error'  :''}}">
                <label for = "grupo" class = "control-label">Grupo</label>
                <input type = "text"  class = "form-control" id = "grupo" name = "grupo" value = "{{ $producto->grupo }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('grupo') }}</div>
            </div>
        </div>
        <div class = "col-md-12">
            <div class = "form-group has-feedback {{ ($errors->first('detalle')) ? 'has-error'  :''}}">
                <label for = "detalle" class = "control-label">Detalle *</label>
                <textarea type = "text"  class = "form-control" id = "detalle" name = "detalle" required>{{ $producto->detalle }}</textarea>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('detalle') }}</div>
            </div>
        </div>

<!--        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('valor')) ? 'has-error'  :''}}">
                <label for = "valor" class = "control-label">Valor *</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type = "number" min="0" max="999999999"  class = "form-control align-right" id = "valor" name = "valor" required value = "{{ floor($producto->valor) }}">
                </div>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('valor') }}</div>
            </div>
        </div>-->
        <div class = "col-md-12"></div>
        <div class = "col-md-4">
            <div class = "form-group">
                <label class="radio-inline control-label" style="font-weight: bold; padding-left: 0px;">
                    Producto Terminado <input {{ $producto->terminado=='1'?'checked':'' }} id="terminado" type="checkbox" value="1" name="terminado" style="width: 20px; margin-left: 10px;">
                </label>
                <span class = "form-control-feedback"></span>
                <div class = "help-block"></div>
            </div>
        </div>
        <div class = "col-md-4">
            <div class = "form-group">
                <label class="radio-inline control-label" style="font-weight: bold; padding-left: 0px;">
                    Se Imprime En Comanda <input {{ $producto->impcomanda=='1'?'checked':'' }} id="imprime" type="checkbox" value="1" name="comanda" style="width: 20px; margin-left: 10px;">
                </label>
                <span class = "form-control-feedback"></span>
                <div class = "help-block"></div>
            </div>
        </div>
        <div class = "col-md-4">
            <div class = "form-group">
                <label class="radio-inline control-label" style="font-weight: bold; padding-left: 0px;">
                    Compuesto (Ingredientes)
                </label>
                <select id="compuesto" name="compuesto">
                    <option value="0">No</option>
                    <option>1</option><option>2</option><option>3</option><option>4</option>
                    <option>5</option><option>6</option><option>7</option><option>8</option>
                    <option>9</option><option>10</option>
                </select>
                <script>$('#compuesto').val({{$producto->compuesto}})</script>
                <span class = "form-control-feedback"></span>
                <div class = "help-block"></div>
            </div>
        </div>
    </form>
    
    </div>
</section>

<section class="con-imagen">
    <div class="container">
        <form  data-toggle = "validator" method="POST" action="/producto/cambiar_imagen" accept-charset="UTF-8" class="form" novalidate="novalidate" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{$producto->id}}"/>
            <input type="hidden" name="tp_id"/>
            <input type="hidden" name="pr_desc"/>
            <input type="hidden" name="pr_valor"/>
            <br/>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('_imagen')) ? 'has-error'  :''}}">
                    <label for = "_imagen" class = "control-label" >Imagen *</label><br/>
                    <img id="img_imagen" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}" height="150" onclick="$('#_imagen').trigger('click')" style="cursor: pointer"/><br/>
                    
                    <input type = "file"  class = "form-control" id = "_imagen" name = "_imagen" value = "" style="position: absolute; visibility: hidden">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('_imagen') }}</div>
                </div>
            </div>
        </form>
    </div>
    

    <script>
        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $(this).closest('form').validator('destroy');
                $(this).closest('form').find("input[name=tp_id]").val($("#tipo_producto_id").val());
                $(this).closest('form').find("input[name=pr_desc]").val($("#descripcion").val());
                $(this).closest('form').find("input[name=pr_valor]").val($("#tamano-u").val());
                $(this).closest('form').submit();
            });
            /*
            var img_ = '{{isset($producto_->imagen)?"$producto_->imagen":""}}';
            if(img_ == ''){
                img_ = '/images/ingrediente/ingrediente.jpg';
            }
            else{
                $("input#imagen").val(img_);
                $("input#tipo_producto_id_").val('{{isset($producto_->tp_id)?"$producto_->tp_id":""}}');
                $("select#tipo_producto_id").val('{{isset($producto_->tp_id)?"$producto_->tp_id":""}}').trigger('change');
                $("input#tamano-u").val('{{isset($producto_->pr_valor)?"$producto_->pr_valor":""}}');
                $("input#descripcion").val('{{isset($producto_->pr_desc)?"$producto_->pr_desc":""}}');
                img_ = '/images/producto/'+img_;
            }
            $('img#img_imagen').attr('src',img_);
            */
        });
    </script>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
        <form data-toggle = "validator" id="form-tamanos" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
            <h3 class = "titulo">Tamaños</h3>
            <table id='tamanos'>
                <thead>
                    <tr>
                        <th>
                            Único
                        </th>
                        <th class="con-tamanos">
                            Grande
                        </th>
                        <th class="con-tamanos">
                            Mediano
                        </th>
                        <th class="con-tamanos">
                            Pequeño
                        </th>
                        <th class="con-tamanos">
                            Porción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table class="adicional">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                <input nombre="unico_" type="checkbox" value="u" id="u">
                                                </label>
                                            </div>
                
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="unico" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-u" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input nombre='grande_' type="checkbox" value="g" id="g">
                                                </label>
                                            </div>
                
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="grande" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-g" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input nombre='extrag_' type="checkbox" value="xg" id="xg">
                                                </label>
                                            </div>

                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="extrag" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-xg" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input nombre='mediano_' type="checkbox" value="m" id="m">
                                                </label>
                                            </div>
                
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="mediano" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-m" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input nombre='pequeno_' type="checkbox" value="p" id="p">
                                                </label>
                                            </div>
                
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="pequeno" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-p" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="adicional con-tamanos">
                                <tbody>
                                    <tr style="height: 43px">
                                        <td>
                                            <div class="form-group">
                                                <label class="radio-inline control-label">
                                                    <input nombre='porcion_' type="checkbox" value="s" id="s">
                                                </label>
                                            </div>
                
                                        </td>
                                        <td class="form-group" style="padding-left: 8px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input disabled nombre="porcion" type = "number" min="0" max="999999999" class = "align-right form-control" id = "tamano-s" name = "valor" style="max-width: 95px;">
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <br>
    <div class="container">
        <div class = "col-md-3">
            <div class = "form-group has-feedback {{ ($errors->first('iva')) ? 'has-error'  :''}}">
                <label for = "iva" class = "control-label">IVA</label>
                <div class="input-group">
                    <input type = "number" step="1" min="0" max="100"  class = "form-control" id = "iva" name = "iva" value = "{{ old('iva')?:($producto->iva?:0) }}">
                    <span class="input-group-addon">%</span>
                </div>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('iva') }}</div>
            </div>
        </div>
        <div class = "col-md-3">
            <div class = "form-group has-feedback {{ ($errors->first('impco')) ? 'has-error'  :''}}">
                <label for = "impco" class = "control-label">Impuesto al consumo</label>
                <div class="input-group">
                    <input type = "number" step="1" min="0" max="100"  class = "form-control" id = "impco" name = "impco" value = "{{ old('impco')?:($producto->impco?:0) }}">
                    <span class="input-group-addon">%</span>
                </div>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('impco') }}</div>
            </div>
        </div>
    </div>
</section>
    
<section class="borde-inferior form fondo-comun con-ingredientes">
    <div class="container">
        <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
            <h3 class = "titulo">Ingredientes <button type="button"  class="btn btn-warning" data-toggle="modal" data-target="#modal-ingrediente"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</button></h3>
            <div class="col-md-12">
                <div class="form-group campo-radios" id="ingredientes">
                    @foreach($ingrediente_lista as $ingrediente)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="radio-inline font bebas" style="margin-bottom: 10px">
                        <input type="checkbox" value="{{$ingrediente->id}}" name="ingrediente" id="ingrediente-{{$ingrediente->id}}">{{$ingrediente->descripcion}}
                    </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</section>


<section class="borde-inferior form fondo-comun con-ingredientes">
    <div class="container">
        
        <form data-toggle = "validator" role = "form" action = "crear" method="POST">
            <h3 class = "titulo">Inventario</h3>
            <table id='unidades_ingredientes'>
                <thead>
                    <tr>
                        <th>
                        </th>
                        <th class='tamano unico'>
                            Único
                        </th>
                        <th class='tamano grande'>
                            Grande
                        </th>
                        <th class='tamano extrag'>
                            Extra Grande
                        </th>
                        <th class='tamano mediano'>
                            Mediano
                        </th>
                        <th class='tamano pequeno'>
                            Pequeño
                        </th>
                        <th class='tamano porcion'>
                            Porción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingrediente_lista as $ingrediente)
                    <tr id='in_{{$ingrediente->id}}' style='display: none'>
                        <th class="font bebas" style="vertical-align: initial; font-size: 1.2em; padding-right: 4px; text-align: right">
                            {{$ingrediente->descripcion}}
                        </th>
                        <td class="form-group tamano unico" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="unico" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-unico" name = "{{$ingrediente->id}}-unico" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                        <td class="form-group tamano grande" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="grande" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-grande" name = "{{$ingrediente->id}}-grande" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                        <td class="form-group tamano extrag" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="extrag" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-extrag" name = "{{$ingrediente->id}}-extrag" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                        <td class="form-group tamano mediano" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="mediano" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-mediano" name = "{{$ingrediente->id}}-mediano" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                        <td class="form-group tamano pequeno" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="pequeno" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-pequeno" name = "{{$ingrediente->id}}-pequeno" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                        <td class="form-group tamano porcion" style="padding-left: 8px;">
                            <div class="input-group">
                                <input nombre="porcion" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-porcion" name = "{{$ingrediente->id}}-porcion" style="max-width: 95px;">
                                <span class="input-group-addon">{{$ingrediente->unidad}}</span>
                            </div>
                            <div class="help-block with-errors"></div>
                        </td>
                    </tr>
                    @endforeach
                    @foreach($producto_ingredientes as $producto_ingrediente)
                    @if($producto_ingrediente->tamano != null && $producto_ingrediente->tamano != '')
                    <script>
                        $("table#unidades_ingredientes tr#in_{{$producto_ingrediente->ingrediente_id}}").find("input[nombre={{$producto_ingrediente->tamano}}]").val('{{$producto_ingrediente->cantidad}}');
                    </script>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</section>
    
<section class="borde-inferior form fondo-comun con-sabores">
    <div class="container">
        <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
            <h3 class = "titulo">Sabores <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-sabor"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</button></h3>
            <div class="col-md-12">
                <div class="form-group campo-radios" id="sabores">
                    @foreach($sabor_lista as $sabor)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="radio-inline font bebas">
                        <input type="checkbox" value="{{$sabor->id}}" name="sabor-{{$sabor->id}}" id="sabor-{{$sabor->id}}">{{$sabor->descripcion}}
                    </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</section>

<section class="borde-inferior form fondo-blanco" style="height: 90px;">
    <br/>
    <div class="container_ centrado">
        <div class = "col-xs-12">
            <div class = "form-group">
                <button type="button"onclick="editarProduto()" class = "boton-agregar-producto btn btn-success"><span class="fa fa-save"></span> GUARDAR PRODUCTO</button>
                <br/>
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
                        <div class="mensaje"></div>
                    </div>

                    <div class = "col-xs-12">
                        <div class = "form-group">
                            <button type = "button" onclick="crearTipoProducto()" class = "btn btn-primary">Crear</button>
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
                        <div class = "form-group has-feedback {{ ($errors->first('unidad')) ? 'has-error'  :''}}">
                            <label for = "descripcion" class = "control-label">Unidad *</label>
                            <select class = "form-control" id = "unidad" name = "unidad" required>
                                <option value="gr">Gramos</option>
                                <option value="kg">Kilogramos</option>
                                <option value="lt">Litros</option>
                                <option value="ml">Miliitros</option>
                                <option value="und">Unidad</option>
                            </select>
                            <div class = "help-block with-errors">{{ $errors->first('unidad') }}</div>
                            <script>$("select#unidad").val({{ old('unidad')}});</script>
                        </div>
                    </div>
                    <div class = "col-md-12">
                        <div class = "form-group">
                            <label class="radio-inline control-label" style="font-weight: bold; padding-left: 0px;">
                                Visible <input id="visible" checked type="checkbox" value="1" name="visible" style="width: 20px; margin-left: 10px;">
                            </label>
                            <span class = "form-control-feedback"></span>
                            <div class = "help-block"></div>
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

<div id="modal-sabor" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h1 class = "titulo">CREANDO SABOR</h1></h4>
            </div>
            <div class="modal-body">
                <form data-toggle = "validator" role = "form" action = "crear" method="POST">
                    {{ csrf_field() }}
                    <div class = "col-md-12">
                        <div class = "form-group">
                            <label for = "descripcion" class = "control-label">Descripcion</label>
                            <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion"  value = "{{ old('descripcion') }}">
                            <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                            <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                        </div>
                    </div>
                    <div class = "col-md-12">
                        <div class="mensaje"></div>
                    </div>

                    <div class = "col-xs-12">
                        <div class = "form-group">
                            <button type = "button" onclick="crearSabor()" class = "btn btn-primary">Crear</button>
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
<script>
    $(function () {
        cargarIngredientes(JSON.parse('{{$producto->ingredientes}}'.replace(/&quot;/g,'"')));
        cargarSabores(JSON.parse('{{$producto->sabores}}'.replace(/&quot;/g,'"')));
        cargarTamanos(JSON.parse('{{$tamanos}}'.replace(/&quot;/g,'"')));
        if('{{$producto->tipo_producto->aplica_ingredientes}}'==='1'){
            $(".con-ingredientes").show();
        }
        else{
            $(".con-ingredientes").hide();
        }
        if('{{$producto->tipo_producto->aplica_tamanos}}'==='1'){
            $(".con-tamanos").show();
        }
        else{
            $(".con-tamanos").hide();
        }
        if('{{$producto->tipo_producto->aplica_sabores}}'==='1'){
            $(".con-sabores").show();
        }
        else{
            $(".con-sabores").hide();
        }
    });
    $(document).ready(function() {
                $.get('/borrar-sesion', function (data) {
                });
                
            mostrarInventario();
            mostrarColumnasInventario();


        $("div#ingredientes input[type=checkbox]").each(function(){
            mostrarInventario();
            if($(this).is(':checked')){
                $('table#unidades_ingredientes tr#in_'+$(this).val()).show();
            }
            else{
                $('table#unidades_ingredientes tr#in_'+$(this).val()).hide();
            }
        });

        $("div#ingredientes").on('change', 'input[type=checkbox]',function(){
            console.log($(this));
            mostrarInventario();
            if($(this).is(':checked')){
                $('table#unidades_ingredientes tr#in_'+$(this).val()).show();
            }
            else{
                $('table#unidades_ingredientes tr#in_'+$(this).val()).hide();
            }
        });

        $("table#tamanos input[type=checkbox]").on('change', function(){
            setTimeout(function(){ mostrarColumnasInventario(); }, 1000);
        });
    } );

    function mostrarInventario(){
        if($("div#ingredientes input[type=checkbox]:checked").length>0){
            $("table#unidades_ingredientes").closest('section').show();
        }
        else{
            $("table#unidades_ingredientes").closest('section').hide();
        }
    }

    function mostrarColumnasInventario(){
        $("table#tamanos input[type=checkbox]").each(function(){
            var cb = $(this);
            var obj;
            if(cb.val() == 'u'){
                obj = $('table#unidades_ingredientes .tamano.unico');
                sel = ('table#unidades_ingredientes .tamano.unico');
            }
            else if(cb.val() == 'g'){
                obj = $('table#unidades_ingredientes .tamano.grande');
                sel = ('table#unidades_ingredientes .tamano.grande');
            }
            else if(cb.val() == 'xg'){
                obj = $('table#unidades_ingredientes .tamano.extrag');
                sel = ('table#unidades_ingredientes .tamano.extrag');
            }
            else if(cb.val() == 'm'){
                obj = $('table#unidades_ingredientes .tamano.mediano');
                sel = ('table#unidades_ingredientes .tamano.mediano');
            }
            else if(cb.val() == 'p'){
                obj = $('table#unidades_ingredientes .tamano.pequeno');
                sel = ('table#unidades_ingredientes .tamano.pequeno');
            }
            else{
                obj = $('table#unidades_ingredientes .tamano.porcion');
                sel = ('table#unidades_ingredientes .tamano.porcion');
            }
            if(cb.is(':checked')){
                obj.show();
            }
            else{
                obj.find('input').val('');
                obj.hide();
            }
        });
    }
</script>
{{ Html::script('js/producto.js') }}
    
@endsection