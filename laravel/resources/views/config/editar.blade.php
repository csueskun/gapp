@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">Configuración</h1>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
    <br/>
    
        <form data-toggle = "validator" role = "form" action = "editar" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" value="{{ $config->id }}">
            <input type="hidden" name="mesas" id="mesas" value="{{ json_encode($config->mesas) }}">
            <div class = "col-md-2">
                <div class = "form-group has-feedback {{ ($errors->first('cantidad_mesas')) ? 'has-error'  :''}}">
                    <label for = "cantidad_mesas" class = "control-label">Cantidad de mesas *</label>
                    <!--<input type = "number" min="1" max="99" class = "form-control" id = "cantidad_mesas" name = "cantidad_mesas" required value = "{{ old('cantidad_mesas')?old('cantidad_mesas'):$config->cantidad_mesas }}">-->
                    <select  class = "form-control" id = "cantidad_mesas" name = "cantidad_mesas">
                        @for($i=1;$i<100;$i++)
                        <option>{{$i}}</option>
                        @endfor
                    </select>
                    <div class = "help-block with-errors">{{ $errors->first('cantidad_mesas') }}</div>
                </div>
            </div>
            <div class = "col-md-10">
                <div class = "form-group">
                    <label for = "cantidad_mesas" class = "control-label">Mesas *</label>
                    <ul id="config-mesas">
                    </ul>
                </div>
            </div>
            <div class = "col-md-12" style="padding: 0">
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('impresora_comanda')) ? 'has-error'  :''}}">
                    <label for = "impresora_comanda" class = "control-label">Impresora POS Comandas</label>
                    <input type = "text" class = "form-control" id = "impresora_comanda" name = "impresora_comanda" value = "{{ old('impresora_comanda')?old('impresora_comanda'):str_replace('\\\\', '\\', $config->impresora_comanda) }}">
                    <div class = "help-block with-errors">{{ $errors->first('impresora_comanda') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('num_impresora_comanda')) ? 'has-error'  :''}}">
                    <label for = "impresora_comanda" class = "control-label">Número de Caractéres Impresora POS Comandas </label>
                    <input type = "number" class = "form-control" id = "num_impresora_comanda" name = "num_impresora_comanda" value = "{{ old('num_impresora_comanda')?old('num_impresora_comanda'):$config->num_impresora_comanda }}">
                    <div class = "help-block with-errors">{{ $errors->first('num_impresora_comanda') }}</div>
                </div>
            </div>
            <div class = "col-md-12" style="padding: 0">
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('impresora')) ? 'has-error'  :''}}">
                    <label for = "impresora" class = "control-label">Impresora POS Facturas</label>
                    <input type = "text" class = "form-control" id = "impresora" name = "impresora" value = "{{ old('impresora')?old('impresora'):str_replace('\\\\', '\\', $config->impresora) }}">
                    <div class = "help-block with-errors">{{ $errors->first('impresora') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('num_impresora')) ? 'has-error'  :''}}">
                    <label for = "impresora_comanda" class = "control-label">Número de Caractéres Impresora POS Facturas </label>
                    <input type = "number" class = "form-control" id = "num_impresora" name = "num_impresora" value = "{{ old('num_impresora')?old('num_impresora'):$config->num_impresora }}">
                    <div class = "help-block with-errors">{{ $errors->first('num_impresora') }}</div>
                </div>
            </div>
            </div>
            <div class = "col-md-12" style="padding: 0">
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('impresora2')) ? 'has-error'  :''}}">
                    <label for = "impresora2" class = "control-label">Impresora POS Facturas Caja 2</label>
                    <input type = "text" class = "form-control" id = "impresora2" name = "impresora2" value = "{{ old('impresora2')?old('impresora2'):str_replace('\\\\', '\\', $config->impresora2) }}">
                    <div class = "help-block with-errors">{{ $errors->first('impresora2') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('num_impresora2')) ? 'has-error'  :''}}">
                    <label for = "num_impresora2" class = "control-label">Número de Caractéres Impresora POS Caja 2 </label>
                    <input type = "number" class = "form-control" id = "num_impresora2" name = "num_impresora2" value = "{{ old('num_impresora2')?old('num_impresora2'):$config->num_impresora2 }}">
                    <div class = "help-block with-errors">{{ $errors->first('num_impresora2') }}</div>
                </div>
            </div>
            </div>
            <div class = "col-md-12" style="padding: 0">
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('impresora3')) ? 'has-error'  :''}}">
                    <label for = "impresora3" class = "control-label">Impresora POS Facturas Caja 3</label>
                    <input type = "text" class = "form-control" id = "impresora3" name = "impresora3" value = "{{ old('impresora3')?old('impresora3'):str_replace('\\\\', '\\', $config->impresora3) }}">
                    <div class = "help-block with-errors">{{ $errors->first('impresora3') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('num_impresora3')) ? 'has-error'  :''}}">
                    <label for = "num_impresora3" class = "control-label">Número de Caractéres Impresora POS Caja 3 </label>
                    <input type = "number" class = "form-control" id = "num_impresora3" name = "num_impresora3" value = "{{ old('num_impresora3')?old('num_impresora3'):$config->num_impresora3 }}">
                    <div class = "help-block with-errors">{{ $errors->first('num_impresora3') }}</div>
                </div>
            </div>
            </div>
            <div class = "col-md-12" style="padding: 0">
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('ecabezado_pos')) ? 'has-error'  :''}}">
                    <label for = "ecabezado_pos" class = "control-label">Encabezado Factura POS</label><br/>
                    <textarea name='encabezado_pos' style='font-family: monospace; height: 100px' cols='40'>{{ old('encabezado_pos')?old('encabezado_pos'):$encabezado }}</textarea>
                    <div class = "help-block with-errors">{{ $errors->first('encabezado_pos') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('ecabezado_comanda')) ? 'has-error'  :''}}">
                    <label for = "ecabezado_pos" class = "control-label">Encabezado Comanda Pos</label><br/>
                    <textarea name='encabezado_comanda' style='font-family: monospace; height: 100px' cols='40'>{{ old('encabezado_comanda')?old('encabezado_comanda'):$encabezado_comanda }}</textarea>
                    <div class = "help-block with-errors">{{ $errors->first('encabezado_comanda') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback {{ ($errors->first('pie_pos')) ? 'has-error'  :''}}">
                    <label for = "pie_pos" class = "control-label">Pie Factura POS</label><br/>
                    <textarea name='pie_pos' style='font-family: monospace; height: 100px' cols='40'>{{ old('pie_pos')?old('pie_pos'):$pie }}</textarea>
                    <div class = "help-block with-errors">{{ $errors->first('pie_pos') }}</div>
                </div>
            </div>
                <hr>
                <div class = "col-md-12" style="padding: 0">
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('iva')) ? 'has-error'  :''}}">
                            <label for = "iva" class = "control-label">IVA</label><br/>
                            <input type = "number" step="0.01" class = "form-control" id = "iva" name = "iva" value = "{{ old('iva')?old('iva'):$config->iva }}">
                            <div class = "help-block with-errors">{{ $errors->first('iva') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('impcon')) ? 'has-error'  :''}}">
                            <label for = "impcon" class = "control-label">Impuesto al consumo</label><br/>
                            <input type = "number" step="0.01" class = "form-control" id = "impcon" name = "impcon" value = "{{ old('impcon')?old('impcon'):$config->impcon }}">
                            <div class = "help-block with-errors">{{ $errors->first('impcon') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('turno')) ? 'has-error'  :''}}">
                            <label for = "turno" class = "control-label">Siguiente turno</label><br/>
                            <input type = "number" step="1" class = "form-control" id = "turno" name = "turno" value = "{{ old('turno')?old('turno'):$config->turno }}">
                            <div class = "help-block with-errors">{{ $errors->first('turno') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('turno_limite')) ? 'has-error'  :''}}">
                            <label for = "turno_limite" class = "control-label">Límite de Turno</label><br/>
                            <input type = "number" step="1" class = "form-control" id = "turno_limite" name = "turno_limite" value = "{{ old('turno_limite')?old('turno_limite'):$config->turno_limite }}">
                            <div class = "help-block with-errors">{{ $errors->first('turno_limite') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('fvcodprefijo')) ? 'has-error'  :''}}">
                            <label for = "fvcodprefijo" class = "control-label">Prefijo factura</label><br/>
                            <input type = "text" maxlength='4' class = "form-control" id = "fvcodprefijo" name = "fvcodprefijo" value = "{{ old('fvcodprefijo')?old('fvcodprefijo'):$config->fvcodprefijo }}">
                            <div class = "help-block with-errors">{{ $errors->first('fvcodprefijo') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback {{ ($errors->first('propina')) ? 'has-error'  :''}}">
                            <label for = "propina" class = "control-label">Propina por defecto (%)</label><br/>
                            <input type = "number" step="0.1" class = "form-control" id = "propina" name = "propina" value = "{{ old('propina')?old('propina'):$config->propina }}">
                            <div class = "help-block with-errors">{{ $errors->first('propina') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback">
                            <label for = "valida_inventario" class = "control-label">&nbsp;</label><br/>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1" name="valida_inventario" {{ $config->valida_inventario == '1'?'checked':'' }}><strong>Validar inventario</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class = "form-group has-feedback">
                            <label for = "subtotales_factura" class = "control-label">&nbsp;</label><br/>
                            <label class="radio-inline" style="margin-bottom: 10px">
                                <input type="checkbox" value="1" name="subtotales_factura" {{ $config->subtotales_factura == '1'?'checked':'' }}><strong>Subtotales en factura</strong>
                            </label>
                        </div>
                    </div>

            <div class = "col-xs-12">
                <br/>
                <br/>
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</section>
<style>
    ul#config-mesas{
        list-style: none;
    }
    ul#config-mesas>li{
        display: inline-block;
        margin-right: 20px;
        margin-top: 10px;
    }
    ul#config-mesas>li>span{
        width: 50px;
        margin-right: 4px;
    }
    ul#config-mesas>li>input{
        -ms-transform: scale(1.8);
        -moz-transform: scale(1.8);
        -webkit-transform: scale(1.8);
        -o-transform: scale(1.8);
        /* padding: 10px; */
        /* margin-top: 15px; */
        /* margin-right: 10px; */
        margin-left: 4px;
    }
</style>
<script>
    $(function(){
        var json = "{{json_encode($config)}}".replace(/(\r\n\t|\n|\r\t)/gm,"");
        json = json.replace(/&quot;/g,'"');
        json = JSON.parse(json);
        
        
        if("{{$config->cantidad_mesas}}"!==""){
            $("select#cantidad_mesas").val("{{$config->cantidad_mesas}}");
        }
        cargarMesas($("select#cantidad_mesas").val());
        cargarEstadoMesas();
        $("select#cantidad_mesas").change(function(){
            cargarMesas2($(this).val());
        });
        $("form").submit(function (e){
            mostrarFullLoading();
    //        e.preventDefault();
            var json = JSON.parse("[]");
            $("ul#config-mesas>li").each(function(){
                var mesa = $(this).attr("mesa");
                var checked = $(this).find("input").is(':checked');
                json.push({mesa: mesa, disponible: checked});
            });
            $("input#mesas").val(JSON.stringify(json));
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
        $("ul#config-mesas>li").on("change","input",function(){
            if($(this).is(":checked")){
                $(this).closest("li").find("span").removeClass("btn-default");
                $(this).closest("li").find("span").addClass("btn-success");
            }
            else{
                $(this).closest("li").find("span").removeClass("btn-success");
                $(this).closest("li").find("span").addClass("btn-default");
            }
        });
        
    });
    function cargarMesas(cantidad){
        $("ul#config-mesas").html("");
        for(var i=1;i<=(+cantidad);i++){
            $("ul#config-mesas").append('<li id="mesa'+i+'" mesa="'+i+'"><span class="btn btn-default">'+i+'</span><input type="checkbox" checked id="'+i+'" name="'+i+'"/></li>');
        }
    }
    function cargarMesas2(cantidad){
        $("ul#config-mesas").html("");
        for(var i=1;i<=(+cantidad);i++){
            $("ul#config-mesas").append('<li id="mesa'+i+'" mesa="'+i+'"><span class="btn btn-success">'+i+'</span><input type="checkbox" checked id="'+i+'" name="'+i+'"/></li>');
        }
    }
    function cargarEstadoMesas(){
        var json = "{{json_encode($config->mesas)}}".replace(/&quot;/g,'"');
        json = JSON.parse(json);
        for(var i=0;i<json.length;i++){
            var mesa = json[i];
            $("input[name="+mesa.mesa+"]").prop("checked",mesa.disponible);
            if(mesa.disponible){
                $("li#mesa"+mesa.mesa+" span").addClass("btn-success");
            }
            else{
                $("li#mesa"+mesa.mesa+" span").addClass("btn-default");
            }
        }
    }
    
</script>
@endsection