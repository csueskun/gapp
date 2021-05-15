@extends('template.general')
@section('titulo', 'EDITANDO Documento')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">Documento
            <a style="font-size: 20px;" href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
    </div>
</section>

<section class="borde-inferior form fondo-comun">
    <div class="container">
    
        <br/>
        <div class="alert alert-success" role="alert" style="display: none;">
            <strong>!</strong> Datos Actualizados
        </div>
        <div class="alert alert-danger" role="alert" style="display: none;">
            <strong>Error</strong> No se actualizaron los datos
        </div>
        <br/>

        <form data-toggle = "validator" id="form-documento">
            {{ csrf_field() }}
            <input type="hidden" name="imagen" id="imagen"/>
            <div class = "col-md-3">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tipodoc')) ? 'has-error'  :''}}">
                    <label for = "tipodoc" class = "control-label">Tipo de Documento *</label>
                    <input type="text" class="form-control" value='{{ array("FV"=>"Factura de Venta", "NI"=>"Nota Inventario", "FC"=>"Factura de Compra", "PN"=>"Pago de Nómina", "BI"=>"Base Inicial", "CO"=>"Consumo","RC"=>"Recibo de Cartera","RT"=>"Recibo de Tesorería","CI"=>"Comprobante de Ingreso","CE"=>"Comprobante de Egreso")[$documento->tipodoc] }}' readonly/>
                </div>
            </div> 

            <div class = "col-md-3">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('numodoc')) ? 'has-error'  :''}}">
                    <label for = "numdoc" class = "control-label">Número Documento</label>
                    <input type="text" class="form-control" value="{{ $documento->numdoc==0?'':$documento->numdoc }}" readonly/>
                </div>
            </div>

            <div class = "col-md-3">
                <div class = "campo tipo fecha form-group has-feedback {{ ($errors->first('created_at')) ? 'has-error' :'' }}">
                    <label for = "created_at" class = "control-label">Fecha </label>
                    <div class="input-group">
                        <input type = "text" redquired readonly class = "form-control datepicker readonly blanca" name = "_created_at" id = "_created_at"  value = "{{ old('created_at') }}"  data-date-format="MM dd, yyyy hh:ii" data-link-field="created_at" data-link-format="yyyy-mm-dd hh:ii"/>
                        <div class="input-group-btn">
                            <button onclick="$('#_created_at').datetimepicker('show');" class="cal btn btn-secondary" type="button"><span class="glyphicon glyphicon-calendar"/></button>
                        </div>
                    </div>
                    <input class = "form-control z-index-100" id = "created_at" name = "created_at" value="{{ old('created_at')?old('created_at'):$documento->created_at }}"/>
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
                        else if("{{ $documento->created_at }}"!==""){
                            var fecha = new Date("{{ $documento->created_at }}");
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

            <div class = "col-md-3">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tipodoc')) ? 'has-error'  :''}}">
                    <label for = "tipodoc" class = "control-label">Número de Pedido</label>
                    <input type="text" class="form-control" value="{{ $documento->pedido_id==0?'':$documento->pedido_id }}" readonly/>
                </div>
            </div>
            
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('tercero_id')) ? 'has-error'  :''}}">
                    <label for = "tercero_id" class = "control-label">Tercero</label>
                    <select class = "form-control" id = "tercero_id" name = "tercero_id"  >
                        <option value=""></option>
                        @foreach($tercero_lista as $tercero)
                            @if(old('tercero_id')==$tercero->id || $documento->tercero_id==$tercero->id)
                                <option value="{{ $tercero->id}}" selected>{{ $tercero->nombrecompleto }}</option>
                            @else
                                <option value="{{ $tercero->id}}">{{ $tercero->nombrecompleto }}</option>
                            @endif
                        @endforeach
                    <select/>
                    <div class = "help-block with-errors">{{ $errors->first('tercero_id') }}</div>
                </div>
            </div>
            
            
            <div class = "col-md-3">
                    <div class = "key- tipo- form-group has-feedback {{ ($errors->first('caja_id')) ? 'has-error'  :''}}">
                    <label for = "caja_id" class = "control-label">Caja *</label>
                    <select name="caja_id" id="caja_id" class="form-control font bebas">
                        <option value="1">CAJA PRINCIPAL</option>
                        <option value="2">CAJA 2</option>
                        <option value="3">CAJA 3</option>
                    </select>
                    <script>$("#caja_id").val("{{$documento->caja_id}}")</script>
                    @if(old('caja_id'))
                      <script>$("select#caja_id").val('{{old('caja_id')}}');</script>
                    @endif
                      <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                      <div class = "help-block with-errors">{{ $errors->first('caja_id') }}</div>
                </div>
            </div>
                

            <div class = "col-md-3">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('mesa_id')) ? 'has-error'  :''}}">
                    <label for = "mesa_id" class = "control-label">Mesa *</label>
                    <select class = "form-control" id = "mesa_id" name = "mesa_id">
                        <option value="999">No Aplica</option>
                    </select>
                    <script>
                        for(var i=1;i<21;i++){
                            if(i=={{old('mesa_id')?old('mesa_id'):$documento->mesa_id}}){
                                $("select#mesa_id").append("<option selected value='%i'>Mesa %i</option>".replace(/%i/g, i));
                            }
                            else{
                                $("select#mesa_id").append("<option value='%i'>Mesa %i</option>".replace(/%i/g, i));
                            }
                        }
                    </script>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('mesa_id') }}</div>
                </div>
            </div>
            
            
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('paga_efectivo')) ? 'has-error'  :''}}">
                    <label for = "paga_efectivo" class = "control-label">Efectivo</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input name="paga_efectivo" type="text" class="form-control metodo-pago" value="{{ number_format(old('paga_efectivo')?old('paga_efectivo'):$documento->paga_efectivo, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('paga_efectivo') }}</div>
                </div>
            </div>
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('paga_debito')) ? 'has-error'  :''}}">
                    <label for = "paga_debito" class = "control-label">Débito</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input name="paga_debito" type="text" class="form-control metodo-pago" value="{{ number_format(old('paga_debito')?old('paga_debito'):$documento->paga_debito, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('paga_debito') }}</div>
                </div>
            </div>
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('paga_credito')) ? 'has-error'  :''}}">
                    <label for = "paga_credito" class = "control-label">Crédito</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input name="paga_credito" type="text" class="form-control metodo-pago" value="{{ number_format(old('paga_credito')?old('paga_credito'):$documento->paga_credito, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('paga_credito') }}</div>
                </div>
            </div>
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('paga_transferencia')) ? 'has-error'  :''}}">
                    <label for = "paga_transferencia" class = "control-label">Transferencia</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input name="paga_transferencia" type="text" class="form-control metodo-pago" value="{{ number_format(old('paga_transferencia')?old('paga_transferencia'):$documento->paga_transferencia, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('paga_transferencia') }}</div>
                </div>
            </div>
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('descuento')) ? 'has-error'  :''}}">
                    <label for = "descuento" class = "control-label">Descuento</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input name="descuento" type="text" class="form-control metodo-pago" value="{{ number_format(old('descuento')?old('descuento'):$documento->descuento, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('descuento') }}</div>
                </div>
            </div>
            <div class = "col-md-2">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('total')) ? 'has-error'  :''}}">
                    <label for = "total" class = "control-label">Total Documento</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id='total' readonly type="text" class="form-control" value="{{ number_format(old('total')?old('total'):$documento->total, 0) }}"/>
                    </div>
                    <div class = "help-block with-errors">{{ $errors->first('total') }}</div>
                </div>
            </div>
            <div class="col-md-12 total-error">
                <div class="alert alert-danger"  style="width: 100%;" role="alert">
                    La suma de los métodos de pago debe ser igual al total del documento
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('banco')) ? 'has-error'  :''}}">
                    <label for = "banco" class = "control-label">Banco</label>
                    <select name="banco" id="banco" class="form-control font bebas">
                        <option value="0">Caja General</option>
                        <option value="1">BanColombia</option>
                                        <option value="2">Banco Bogotá</option>
                                        <option value="3">Davivienda</option>
                                        <option value="4">BBVA</option>
                                        <option value="5">Uplace Colombia</option>
                                        <option value="6">Domicilios.com</option>
                                        <option value="7">Rappi</option>
                                        <option value="8">Ifood</option>
                                        <option value="9">Nequi</option>
                                        <option value="10">Otro</option>
                    </select>
                    <script>$("#banco").val("{{$documento->banco}}")</script>
                    <div class = "help-block with-errors">{{ $errors->first('banco') }}</div>
                </div>
            </div>
            <div class = "col-md-3">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('num_documento')) ? 'has-error'  :''}}">
                    <label for = "num_documento" class = "control-label">Número doc. relacionado</label>
                    <input readonly type="text" class="form-control" value="{{ old('num_documento')?old('num_documento'):$documento->num_documento }}"/>
                    <div class = "help-block with-errors">{{ $errors->first('num_documento') }}</div>
                </div>
            </div>
            <div class = "col-md-12">
                <div class = "key- tipo- form-group has-feedback {{ ($errors->first('observacion')) ? 'has-error'  :''}}">
                    <label for = "numdoc" class = "control-label">Observación</label>
                    <textarea style='font-size: 16px'  class = "form-control" id = "observacion" name = "observacion">{{ old('observacion')?old('observacion'):$documento->observacion }}</textarea>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('observacion') }}</div>
                </div>
            </div>
        </form>

        <div class = "col-md-12">
            
            <table id="table_productos" class="table">
                <thead>
                    <tr class="azul">
                        <th>Descripción Producto / Servicio / Ingrediente</th>
                        <th class="align-right">Cantidad</th>
                        <th class="align-right">Valor Base</th>
                        <th class="align-right">Total</th>
                    </tr>
                </thead>
                <tbody style="border: thin solid #d8d8d8; background-color: white;">
                    @foreach($detalles as $detalle)
                    <tr>
                        <td>
                            {{ $detalle->detalle }}
                        </td>
                        <td class="align-right">
                            {{ number_format($detalle->cantidad,0) }}
                        </td>
                        <td class="align-right">
                            ${{ number_format($detalle->valor,0) }}
                        </td>
                        <td class="align-right">
                            ${{ number_format($detalle->total,0) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan='3'>
                            <h4>Total</h4>
                        </td>
                        <td>
                            <h4 class="align-right">${{ number_format($documento->total,0) }}</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class = "col-xs-12">
            <div class = "form-group">
                <button id='save-button' type = "button" onclick="enviarForm()" class = "btn btn-primary pd"><img height='18' src='/images/loading.gif'/> Guardar</button>
            </div>
        </div>
    </div>
    
</section>

<style>
    table#table_productos td.no-mostrar{
        display: none;
    }
    div.alert, button>img{
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
    $(function(){
        $('.total-error').hide();
        $('.metodo-pago').each(function(i,v){
            v.value = v.value.replace(',','');
        });    
    });
    var validTotal = true;
    function validateTotal() {
        var total = $('#total').val();
        total = parseInt(total.replace(',',''));
        var totalMp = 0;
        $('.metodo-pago').each(function(i,v){
            totalMp+=parseInt(v.value);
        });    
        validTotal = totalMp == total;
        if(validTotal){
            $('#save-button').prop('disabled', false);
            $('.metodo-pago').closest('.form-group').removeClass('has-error');
            $('.total-error').hide();
            
        }
        else{
            $('.metodo-pago').closest('.form-group').addClass('has-error');
            $('#save-button').prop('disabled', true)
            $('.total-error').show();
        }
    }
    $('.metodo-pago').on('keyup', validateTotal);
    function enviarForm(){
        $('.alert:not(.total)').hide();
        $('button>img').show();

        $.post( 
            "/documento/{{$documento->id}}/editar-post", 
            $("form#form-documento").serialize(),
            function( data ) {
                if(data.id!=null){
                    // $('.alert:not(.total).alert-success').show();
                    // $('.alert:not(.total).alert-danger').hide();
                    mostrarSuccess('Datos actualizados');
                    $('button>img').hide();
                }
                else{
                    mostrarError('No se actualizaron los datos');
                    $('.alert:not(.total).alert-success').hide();
                    $('.alert:not(.total).alert-danger').show();
                    $('button>img').hide();
                }
            }
        );   
    }
</script>
@endsection