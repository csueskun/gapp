@extends('template.general')
@section('titulo', 'Nuevo Documento')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
{{ Html::script('js/accounting.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">CREANDO Documento <a href="listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a></h1>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
    <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
        <input type="hidden" id="productos" name="productos"/>          
        <input type="hidden" id="tipoie" name="tipoie" value="{{old('tipoie')}}"/>          
            <div class = "row">
                <div class = "col-md-6">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tipodoc')) ? 'has-error'  :''}}">
                      <label for = "tipodoc" class = "control-label">Tipo de Documento *</label>
                      <select class = "form-control actualiza-tipoie" id = "tipodoc" name = "tipodoc">
                        <option value="FV">Factura de Venta</option>
                        <option value="FC">Factura de Compra</option>
                        <option value="BI">Base Inicial</option>
                        <option value="PN">Pago de Nómina</option>
                        <option value="NI">Nota Inventario</option>
                        <option value="CO">Consumo</option>
                        <option value="RC">Recibo Cartera</option>
                        <option value="RT">Recibo Tesorería</option>
                        <option value="CI">Comprobante de Ingreso</option>
                        <option value="CE">Comprobante de Egreso</option>
                      </select>
                      @if(old('tipodoc'))
                      <script>$("select#tipodoc").val('{{old('tipodoc')}}');</script>
                      @endif
                      <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                      <div class = "help-block with-errors">{{ $errors->first('tipodoc') }}</div>
                    </div>
                </div>   
                <div class = "col-md-3">
                    <div class = "key- tipo- form-group">
                       <label for = "numdoc" class = "control-label">Número Documento</label>
                       <input readonly type = "text"  class = "form-control" id = "numdoc" name = "numdoc"  value = "{{ old('numdoc') }}">
                       <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                       <div class = "help-block with-errors">{{ $errors->first('numdoc') }}</div>
                    </div>
                </div>
            
                <div class = "col-md-3">
                    <div class = "campo tipo fecha form-group has-feedback {{ ($errors->first('created_at')) ? 'has-error' :'' }}">
                       <label for = "created_at" class = "control-label">Fecha Documento</label>
                       <div class="input-group">
                          <input type = "text" redquired readonly class = "form-control datepicker" name = "_created_at" id = "_created_at"  value = "{{ old('created_at') }}"  data-date-format="MM dd, yyyy hh:ii" data-link-field="created_at" data-link-format="yyyy-mm-dd hh:ii"/>
                          <div class="input-group-btn">
                            <button onclick="$('#_created_at').datetimepicker('show');" class="cal btn btn-secondary" type="button"><span class="glyphicon glyphicon-calendar"/></button>
                          </div>
                       </div>
                       <input class = "form-control z-index-100" id = "created_at" name = "created_at" value="{{ old('created_at') }}"/>
                       <script type='text/javascript'>
                         $('#_created_at').datetimepicker({
                            endDate: new Date(),
                            language:  'es',
                            todayBtn:  1,
                            autoclose: 1,
                            todayHighlight: 1,
                            startView: 2,
                            showMeridian: 1,
                            forceParse: 0
                        });
                        if("{{ old('created_at') }}"!==""){
                            var fecha = new Date("{{ old('created_at') }}");
                            dateToInputValue(fecha, "yyyy-mm-dd hh:ii", $("input#created_at"));
                            dateToInputValue(fecha, "MM dd, yyyy hh:ii", $("input#_created_at"));
                        }
                        else{
                            var fecha = new Date();
                            dateToInputValue(fecha, "yyyy-mm-dd hh:ii", $("input#created_at"));
                            dateToInputValue(fecha, "MM dd, yyyy hh:ii", $("input#_created_at"));
                        }
                        </script>                        
                        <div class = "help-block with-errors">{{ $errors->first('created_at') }}</div>
                    </div>
                </div>
            
                <div class = "col-md-8">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tercero_id')) ? 'has-error'  :''}}">
                       <label for = "tercero_id" class = "control-label">Tercero</label>
                       <select class = "form-control" id = "tercero_id" name = "tercero_id"  >
                           <option value=""></option>
                            @foreach($tercero_lista as $tercero)
                            @if(old('tercero_id')==$tercero->id)
                                <option value="{{ $tercero->id}}" selected>{{ $tercero->nombrecompleto }}</option>
                            @else
                                <option value="{{ $tercero->id}}">{{ $tercero->nombrecompleto }}</option>
                            @endif
                        @endforeach
                       <select/>
                       <div class = "help-block with-errors">{{ $errors->first('tercero_id') }}</div>
                    </div>
                </div>

                <div class = "col-md-4">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('mesa_id')) ? 'has-error'  :''}}">
                       <label for = "mesa_id" class = "control-label">Mesa *</label>
                       <select class = "form-control" id = "mesa_id" name = "mesa_id">
                          <option value="999">No Aplica</option>
                       </select>
                       @if(old('mesa_id'))
                       <script>$("select#mesa_id").val({{old('mesa_id')}});</script>
                       @endif
                       <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                       <div class = "help-block with-errors">{{ $errors->first('mesa_id') }}</div>
                    </div>
                </div>

                <div class = "col-md-8">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('banco')) ? 'has-error'  :''}}">
                    <label for = "banco" class = "control-label">Banco</label>
                    <select name="banco" id="banco" class="form-control font bebas">
                        <option value="0">CAJA GENERAL</option>
                        <option value="1">BANCOLOMBIA</option>
                        <option value="2">BANCO BOGOTÁ</option>
                        <option value="3">DAVIVIENDA</option>
                        <option value="4">BANCO CAJA SOCIAL</option>
                        <option value="5">BANCO AVVILLAS</option>
                        <option value="6">BANCO BBVA</option>
                        <option value="7">BANCO FALLABELA</option>
                        <option value="8">BANCO POPULAR</option>
                        <option value="9">BANCO DE OCCIDENTE</option>
                        <option value="10">BANCO COLPATRIA</option>
                        <option value="11">CITIBANK</option>
                        <option value="12">BANCO SANTANDER</option>
                    </select>
                    @if(old('banco'))
                      <script>$("select#banco").val('{{old('banco')}}');</script>
                    @endif
                      <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                      <div class = "help-block with-errors">{{ $errors->first('banco') }}</div>
                   </div>
                </div>

                <div class = "col-md-4">
                    <div class = "key- tipo- form-group">
                       <label for = "numero_documento" class = "control-label">Número Doc. Relacionado</label>
                       <input type = "text"  class = "form-control" id = "numero_documento" name = "numero_documento"  value = "{{ old('numero_documento') }}">
                       <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                       <div class = "help-block with-errors">{{ $errors->first('numero_documento') }}</div>
                    </div>
                </div>
                
                <div class = "col-md-3">
                        <div class = "key- tipo- form-group ">
                            <label for = "paga_efectivo" class = "control-label">Efectivo</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" value="{{ old('paga_efectivo')}}"/>
                            </div>
                            <div class = "help-block with-errors">{{ $errors->first('paga_efectivo') }}</div>
                        </div>
                </div>
                <div class = "col-md-3">
                        <div class = "key- tipo- form-group ">
                            <label for = "paga_debito" class = "control-label">Débito</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" value="{{ old('paga_debito') }}"/>
                            </div>
                            <div class = "help-block with-errors">{{ $errors->first('paga_debito') }}</div>
                        </div>
                </div>
                <div class = "col-md-3">
                        <div class = "key- tipo- form-group ">
                            <label for = "paga_credito" class = "control-label">Crédito</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" value="{{ old('paga_credito') }}"/>
                            </div>
                            <div class = "help-block with-errors">{{ $errors->first('paga_credito') }}</div>
                        </div>
                </div>

                <div class = "col-md-3">
                    <div class = "key- tipo- form-group ">
                       <label for = "total" class = "control-label">Total *</label>
                       <input  type = "text"  class = "form-control" id = "total" name = "total" required value = "{{ old('total') }}">
                       <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                       <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
                    </div>
                </div>  
     
        
                <div class = "col-md-12">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('observacion')) ? 'has-error'  :''}}">
                       <label for = "observacion" class = "control-label">Observación</label>
                       <textarea  class = "form-control" id = "observacion" name = "observacion">{{ old('observacion') }}</textarea>
                       <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                       <div class = "help-block with-errors">{{ $errors->first('observacion') }}</div>
                    </div>
                </div>
                 
                    
<!--            <div class = "col-md-6">
                <div class = "key- tipo- form-group">
                    <label for = "pedido_id" class = "control-label">Pedido</label>
                    <input type = "text"  class = "form-control" id = "pedido_id" name = "pedido_id"  value = "{{ old('pedido_id') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('pedido_id') }}</div>
                </div>
            </div>-->
                    
<!--           -->
        <!--<div class = "col-md-6"></div>-->
            </div>
            <div class = "row">
                <div class = "col-md-12">
            
                <table id="table_productos" class="table">
                <thead>
                    <tr>
                        <th colspan="5" class="centrado" style="font-size: 24px;">
                            Productos/Servicios 
                            <button  data-toggle="modal" data-target="#modal-producto_id"  class="btn btn-success" type="button">
                                <span class="fa fa-plus"/>
                            </button>
                            &nbsp;
                            Ingredientes  
                            <button  data-toggle="modal" data-target="#modal-ingrediente_id"  class="btn btn-success" type="button">
                                <span class="fa fa-plus"/>
                            </button>
                        </th>
                    </tr>
                    <tr class="azul">
                        <th>Descripción Producto / Servicio / Ingrediente</th>
                        <th class="align-right">Cantidad</th>
                        <th class="align-right">Valor</th>
                        <th class="align-right">Total</th>
                        <th></th>
                    </tr>
                    
                </thead>
                <tbody style="border: thin solid #d8d8d8; background-color: white;">
                   
                </tbody>
                    
                </table>
            </div>
                    
            <div class = "col-xs-12">
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary pd">Guardar Documento</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</section>

<script>
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
</script>

<div id="modal-producto_id" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title font bebas">Buscando Producto</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class = "col-md-12">
                            <div class = "input-group">
                                <span class="input-group-addon">Buscar</span>
                                <input type = "text" id = "buscar_producto_id" class="form-control" name = "buscar_producto_id" value="">
                                <span class="input-group-addon icono-buscando" style="display: none"><img src="http://4.bp.blogspot.com/-hO_3iF0kUXc/Ui_Qa3obNpI/AAAAAAAAFNs/ZP-S8qyUTiY/s1600/pageloader.gif" height="20px"/></span>    
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <table id="table_producto_id" class="table">
                            <thead>
                                <tr><th>Producto</th><th>Cantidad</th><th>Valor</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr class="" nombre="VARIOS" id="11">
                                    <td class="detalle no-mostrar" detalle="VARIOS"><div class = "input-group min"><input type = "text" name="detalle" value="VARIOS" class="form-control" style="" onkeyup="$(this).closest('td.detalle').attr('detalle',$(this).val());"/></div></td>
                                    <td class="no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="cantidad" value="0" class="form-control"/></div></td>
                                    <td class="valor no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="valor" value="0" class="form-control" style="width: 90px;"/></div></td>
                                    <td class="no-mostrar" style="width: 1px;"><button class="btn btn-success minimo"><span class="fa fa-play"></span></button></td>
                                </tr>
                                @foreach($producto_lista as $producto)
                                @if($producto->codigo != '00')
                                <tr class="" nombre="{{$producto->detalle}}" id="{{$producto->id}}">
                                    <td class="detalle no-mostrar" detalle="{{$producto->detalle}}">{{$producto->detalle}}</td>
                                    <td class="no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="cantidad" value="1" class="form-control"/></div></td>
                                    <td class="valor no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="valor" value="{{floor($producto->valor)}}" class="form-control" style="width: 90px;"/></div></td>
                                    <td class="no-mostrar" style="width: 1px;"><button class="btn btn-success minimo"><span class="fa fa-play"></span></button></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <script>
                        $("#buscar_producto_id").keyup(function(){
                            var val = $(this).val();
//                            console.log(val.replace(" ", "")==="");
                            if(val.length<1||val.replace(/ /g, '')===""){
                                return false;
                            }
                            delay(function(){
                                $("span.icono-buscando").toggle();
                                $.get("/producto/buscarConTamano/" + val, function (data) {
                                    if(data&&data.length>0){
                                        $("table#table_producto_id thead").html("<tr><th>Producto</th><th>Cantidad</th><th>Valor</th><th></th></tr>");
                                        var html = "<tr class=\"\" nombre=\"VARIOS\" id=\"11\"><td class=\"detalle no-mostrar\" detalle=\"VARIOS\"><div class = \"input-group min\"><input type = \"text\" name=\"detalle\" value=\"VARIOS\" class=\"form-control\" style=\"\" onkeyup=\"$(this).closest('td.detalle').attr('detalle',$(this).val());\"/></div></td><td class=\"valor no-mostrar\"><div class = \"input-group min\"><input type = \"number\" min=\"1\" max=\"99\" name=\"valor\" value=\"0\" class=\"form-control\"/></div></td><td class=\"no-mostrar\"><div class = \"input-group min\"><input type = \"number\" min=\"1\" max=\"99\" name=\"cantidad\" style=\"width: 90px;\" value=\"0\" class=\"form-control\"/></div></td><td class=\"no-mostrar\" style=\"width: 1px;\"><button class=\"btn btn-success minimo\"><span class=\"fa fa-play\"></span></button></td></tr>";
                                        for(var i=0;i<data.length;i++){
                                            if(data[i]==='00'){
                                                continue;
                                            }
                                            html+="<tr nombre='"+data[i].detalle+"' id='"+data[i].id+"'>";
                                            html+="<td class='detalle no-mostrar' detalle='"+data[i].detalle+"'>"+data[i].detalle+"</td>";
                                            html+="<td class='no-mostrar'><div class = \"input-group min\"><input type = \"number\" name=\"cantidad\" min=\"1\" max=\"99\" value=\"1\" class=\"form-control\"/></div></td>";
                                            html+="<td class='valor no-mostrar '><div class='input-group min'><input type='number' min='1' max='99' name='valor' value='"+Math.floor(data[i].valor)+"' class='form-control' style='width: 90px'/></div></td>";
                                            html+="<td class=\"no-mostrar\"><button class=\"btn btn-success minimo\"><span class=\"fa fa-play\"></span></button></td>";
                                            html+="</tr>";
                                        }
                                        $("table#table_producto_id tbody").html(html);
                                    }
                                    else{
                                        $("table#table_producto_id thead").html("");
                                        $("table#table_producto_id tbody").html("<tr class='sin-resultado'><td>Sin Resultados.</td></tr>");
                                    }
                                    $("span.icono-buscando").toggle();
                                })
                            }, 700 );
                        });
//                        $("input[name=detalle]").on("keyup", function(event){
//                            
//                        });
                        $("#table_producto_id tbody").on("click", "button", function(event){
//                            $('#modal-producto_id').modal('toggle');
                            var tr = $(this).closest("tr");
                            var id = tr.attr("id");
                            var detalle = tr.find("td.detalle").attr("detalle");
                            var cantidad = tr.find("input[name=cantidad]").val();
                            var valor = tr.find("td.valor input").val();
                            var total = +cantidad*(+valor);
                            $("table#table_productos>tbody").append("<tr tipo='producto' id='"+id+"'>"+tr.html()+"<td class='det'>"+detalle+"</td><td class='cantidad align-right'>"+cantidad+"</td><td class='val align-right' valor='"+valor+"'>"+accounting.formatMoney(valor)+"</td><td class='align-right'>"+accounting.formatMoney(total)+"</td><td><button  onclick='$(this).closest(\"tr\").remove()' class='btn btn-danger minimo'><span class='fa fa-trash'></span></button></td></tr>");
                        });
                    </script>
                </div>
            </div>

        </div>
    </div>
<div id="modal-ingrediente_id" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title font bebas">Buscando Ingrediente</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class = "col-md-12">
                            <div class = "input-group">
                                <span class="input-group-addon">Buscar</span>
                                <input type = "text" id = "buscar_ingrediente_id" class="form-control" name = "buscar_ingrediente_id" value="">
                                <span class="input-group-addon icono-buscando" style="display: none"><img src="http://4.bp.blogspot.com/-hO_3iF0kUXc/Ui_Qa3obNpI/AAAAAAAAFNs/ZP-S8qyUTiY/s1600/pageloader.gif" height="20px"/></span>    
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <table id="table_ingrediente_id" class="table">
                            <thead>
                                <tr><th>Ingrediente</th><th>Cantidad</th><th>Valor Unitario</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr class="" nombre="VARIOS" id="11">
                                    <td class="detalle no-mostrar" detalle="VARIOS"><div class = "input-group min"><input type = "text" name="detalle" value="VARIOS" class="form-control" style="" onkeyup="$(this).closest('td.detalle').attr('detalle',$(this).val());"/></div></td>
                                    <td class="valor no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="valor" value="1" class="form-control"/></div></td>
                                    <td class="no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="cantidad" value="0" class="form-control" style="width: 90px;"/></div></td>
                                    <td class="no-mostrar" style="width: 1px;"><button class="btn btn-success minimo"><span class="fa fa-play"></span></button></td>
                                </tr>
                                @foreach($ingrediente_lista as $ingrediente)
                                @if($ingrediente->codigo != '00')
                                <tr class="" nombre="{{$ingrediente->detalle}}" id="{{$ingrediente->id}}">
                                    <td class="detalle no-mostrar" detalle="{{$ingrediente->descripcion}}">{{$ingrediente->descripcion}}</td>
                                    <td class="no-mostrar"><div class = "input-group min" style='display: flex'><input type = "number" min="1" max="99" name="cantidad" value="1" class="form-control"/>&nbsp;{{$ingrediente->unidad}}</div></td>
                                    <td class="valor no-mostrar"><div class = "input-group min"><input type = "number" min="1" max="99" name="valor" value="0" class="form-control" style="width: 90px;"/></div></td>
                                    <td class="no-mostrar" style="width: 1px;"><button class="btn btn-success minimo"><span class="fa fa-play"></span></button></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <script>
                        $("#buscar_ingrediente_id").keyup(function(){
                            var val = $(this).val();
//                            console.log(val.replace(" ", "")==="");
                            if(val.length<1||val.replace(/ /g, '')===""){
                                return false;
                            }
                            delay(function(){
                                $("span.icono-buscando").toggle();
                                $.get("/ingrediente/buscar/" + val, function (data) {
                                    if(data&&data.length>0){
                                        $("table#table_ingrediente_id thead").html("<tr><th>Ingrediente</th><th>Cantidad</th><th>Valor</th><th></th></tr>");
                                        var html = "<tr class=\"\" nombre=\"VARIOS\" id=\"11\"><td class=\"detalle no-mostrar\" detalle=\"VARIOS\"><div class = \"input-group min\"><input type = \"text\" name=\"detalle\" value=\"VARIOS\" class=\"form-control\" style=\"\" onkeyup=\"$(this).closest('td.detalle').attr('detalle',$(this).val());\"/></div></td><td class=\"valor no-mostrar\"><div class = \"input-group min\"><input type = \"number\" min=\"1\" max=\"99\" name=\"valor\" value=\"0\" class=\"form-control\"/></div></td><td class=\"no-mostrar\"><div class = \"input-group min\"><input type = \"number\" min=\"1\" max=\"99\" name=\"cantidad\" style=\"width: 90px;\" value=\"0\" class=\"form-control\"/></div></td><td class=\"no-mostrar\" style=\"width: 1px;\"><button class=\"btn btn-success minimo\"><span class=\"fa fa-play\"></span></button></td></tr>";
                                        for(var i=0;i<data.length;i++){
                                            if(data[i]==='00'){
                                                continue;
                                            }
                                            console.log(data[i]);
                                            html+="<tr nombre='"+data[i].descripcion+"' id='"+data[i].id+"'>";
                                            html+="<td class='detalle no-mostrar' detalle='"+data[i].descripcion+"'>"+data[i].descripcion+"</td>";
                                            html+="<td class='no-mostrar'><div class = \"input-group min\"  style='display: flex'><input type = \"number\" name=\"cantidad\" min=\"1\" max=\"99\" value=\"1\" class=\"form-control\"/>&nbsp;"+data[i].unidad+"</div></td>";
                                            html+="<td class='valor no-mostrar'><div class='input-group min'><input type='number' min='1' max='99' name='valor' value='0' class='form-control' style='width: 90px'/></div></td>";
                                            html+="<td class=\"no-mostrar\"><button class=\"btn btn-success minimo\"><span class=\"fa fa-play\"></span></button></td>";
                                            html+="</tr>";
                                        }
                                        $("table#table_ingrediente_id tbody").html(html);
                                    }
                                    else{
                                        $("table#table_ingrediente_id thead").html("");
                                        $("table#table_ingrediente_id tbody").html("<tr class='sin-resultado'><td>Sin Resultados.</td></tr>");
                                    }
                                    $("span.icono-buscando").toggle();
                                })
                            }, 700 );
                        });
//                        $("input[name=detalle]").on("keyup", function(event){
//                            
//                        });
                        $("#table_ingrediente_id tbody").on("click", "button", function(event){
//                            $('#modal-ingrediente_id').modal('toggle');
                            var tr = $(this).closest("tr");
                            var id = tr.attr("id");
                            var detalle = tr.find("td.detalle").attr("detalle");
                            var cantidad = tr.find("input[name=cantidad]").val();
                            var valor = tr.find("td.valor input").val();
                            var total = +cantidad*(+valor);
                            $("table#table_productos>tbody").append("<tr tipo='ingrediente' id='"+id+"'>"+tr.html()+"<td class='det'>"+detalle+"</td><td class='cantidad align-right'>"+cantidad+"</td><td class='val align-right' valor='"+valor+"'>"+accounting.formatMoney(valor)+"</td><td class=' align-right'>"+accounting.formatMoney(total)+"</td><td class='align-right'><button  onclick='$(this).closest(\"tr\").remove()' class='btn btn-danger minimo'><span class='fa fa-trash'></span></button></td></tr>");
                        });
                    </script>
                </div>
            </div>

        </div>
    </div>
<style>
    table#table_productos td.no-mostrar{
        display: none;
    }
    div.min>input{
        font-size: 1em;
        padding: 0px;
        height: 24px;
        text-align: right;
    }
    input[name=detalle]{
        text-align: left !important;
    }
    .btn.minimo{
        padding: 0px 10px;
    }
    th{
        font-size: 20px;
        font-family: 'bebas_neuebold';
    }
    tr.azul{
        color: white;
        background-color: #337ab7;
    }
</style>

<script>
    $(function () {

        $("form").submit(function(e){
            var json = JSON.parse('{"detalles":[]}');
            
            $(this).find("table#table_productos>tbody>tr").each(function(i){
                var id = $(this).attr("id");
                var tipo = $(this).attr("tipo");
                var detalle = $(this).find("td.det").html();
                var cantidad = $(this).find("td.cantidad").html();
                var valor = $(this).find("td.val").attr("valor");
                var total = +cantidad*(+valor);
                json.detalles.push({detalle:detalle,tipo:tipo,producto_id:id,cantidad:cantidad,valor:valor,total:total});
            });
            $(this).find("input#productos").val(JSON.stringify(json));
        });
        
        $("select.actualiza-tipoie").change(function(){
            actualizarTipoIE($(this).val());
        });
        actualizarTipoIE("{{old('tipodoc')}}");
        
        


    });
    function actualizarTipoIE(tipo){
        if(tipo===""){
            tipo="FV";
        }
        var ie = "I";
        if(tipo==="FV"||tipo==="BI"||tipo==="CO"){
            ie="E";
        }
        $("input#tipoie").val(ie);

        if(tipo=='FV'){
            $("select#mesa_id").html('');
            for(var i=1;i<21;i++){
                $("select#mesa_id").append("<option val='%i'>Mesa %i</option>".replace(/%i/g, i));
            }
        }
        else{
            $("select#mesa_id").html('<option value="999">No Aplica</option>');
        }
    }
</script>
@endsection