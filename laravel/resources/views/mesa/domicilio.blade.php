<div class="modal fade" tabindex="-1" role="dialog" id='modal_pagar' aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id='content-pagar'>
            <!-- <div class="modal-header" style="">
                <h1 class="modal-title" id="exampleModalLabel">Observaciones</h1>
            </div> -->
            <div class="modal-body">
                <div id="cambio">
                    <form>
                        <table style="margin: auto;">
                            <tr>
                                <td class="label">Tercero</td><td id='tercero_des'><input readonly name="tercero_des" class="form-control"/></td>
                            </tr>
                            <tr>
                                <td class="label">Total</td><td width="400px" id='cambio_total'><input name="" class="form-control curr" readonly/></td>
                            </tr>
                            <tr>
                                <td class="label">Efectivo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td id='paga_efectivo'><input name="paga_efectivo" onkeyup="calcularCambio()" class="form-control curr"/></td>
                            </tr>

                            <tr>
                                <td width="130" colspan="2" class="label" style='display: inline-block; text-align: left'>
                                    Ver otros medios de pago
                                    <input style="height: 25px;width: 25px;margin-left: 25px;" width="auto" onchange="toggleOtrosMedios($(this).is(':checked'))" type="checkbox" name="ver-otros-medios">
                                </td>
                            </tr>

                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">T.Débito</td><td id='paga_debito'><input name="paga_debito" onkeyup="calcularCambio()" class="form-control curr"/></td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">T.Crédito</td><td id='paga_credito'><input name="paga_credito" onkeyup="calcularCambio()" class="form-control curr"/></td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">Transferencia</td><td id='paga_transferencia'><input name="paga_transferencia" onkeyup="calcularCambio()" class="form-control curr"/></td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">Plataforma</td><td id='paga_plataforma'><input name="paga_plataforma" onkeyup="calcularCambio()" class="form-control curr"/></td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">Documento</td><td id='num_documento'><input name="num_documento" class="form-control"/></td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td class="label">Banco</td>
                                <td id='banco'>
                                    <select name="banco" id="banco" class="form-control font bebas">
                                        <option value="">--</option>
                                        <option value="1">Bancolombia</option>
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
                                </td>
                            </tr>
                            <tr>
                                <td class="label" style='display: inline-block; text-align: left'>Cambio</td>
                                <td id='cambio_cambio'><input readonly class="form-control curr"/></td>
                            </tr>
                            <tr>
                                <td class="label" style='display: inline-block; text-align: left'>Descuento</td>
                                <td id='descuento'>
                                    <table>
                                        <tr>
                                            <td>
                                                <input onClick="this.select();" onkeyup="calcularDescuento()" value="0" typeof="number" max="100" min="0" class="form-control percent"/>
                                            </td>
                                            <td>
                                                <input name="descuento2" onkeyup="calcularDescuento2()" class="form-control curr"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="label" style='display: inline-block; text-align: left'>Propina</td>
                                <td id='propina'>
                                    <table>
                                        <tr>
                                            <td>
                                                <input disabled nonClick="this.select();" value="0" onkeyup="calcularPropina()" typeof="number" max="100" min="0" class="form-control percent"/>
                                            </td>
                                            <td>
                                                <input name="propina2" onkeyup="calcularPropina2()" class="form-control curr"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="otros-medios-pago" style="display: none">
                                <td id="debiendo" colspan="2">
                                    <div class="alert alert-danger">
                                        Quiere marcar el pago como pendiente?
                                        Sí <label style="width: 25px; padding-left: 0px" class="checkbox-inline">
                                            <input style="height: 25px;" width="auto" type="radio" name="pago-pendiente" value="1">
                                        </label>
                                        No <label style="width: 25px; padding-left: 0px" class="checkbox-inline">
                                            <input style="height: 25px;" width="auto" type="radio" checked name="pago-pendiente" value="0">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group centrado">
                    @if(Auth::user())
                    @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
                    <button style="font-size:30px;padding: 4px 6px;" type="button" onclick="togglePagarDomicilio(false)" class = "fuente bebas btn btn-warning btn-lg imprimir"><span class="fa fa-usd"></span> Domicilio</button>
                    @endif
                    <button style="font-size:30px;padding: 4px 6px;" type="button" onclick="preFactura()" class = "fuente bebas btn btn-primary btn-lg imprimir"><span class="fa fa-print"></span> Resumen Cuenta</button>
                    @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
                    <button style="font-size:30px;padding: 4px 6px;" type="button" onclick="preEnviarFormPagar()" class = "fuente bebas btn btn-success btn-lg imprimir"><span class="fa fa-usd"></span> Pagar</button>
                    <button style="font-size:30px;padding: 4px 6px;" type="button" onclick="gaveta()" class = "fuente bebas btn btn-danger btn-lg imprimir"><span class="fa fa-inbox"></span> Cajón</button>
                    @endif
                    @endif
                </div>
                <br>
                <br>
                <button style="font-size:30px;padding: 4px 6px;" type="button" class="btn btn-default btn-lg fuente bebas" data-dismiss="modal"><span class="fa fa-close"></span> Salir</button>
            </div>
        </div>
        <div class="modal-content" id='content-domicilio' style="display: none;">
        <div class="modal-body">
            <table style='width: 100%'>
                <tr>
                    <td>
                        <h2 class="fuente bebas ml-2" style="margin-top: 0px; margin-bottom: 0px;">Domicilio</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-md-12">
                            <label class="xl">Tipo de documento: </label>   
                        </div>
                        <div class="col-md-12">
                            <select required class="xl form-control" name="" id="" ng-model="domicilioDocumento.tipodoc">
                                <option ng-repeat='tipo in tipoDocumentos' value="@{{tipo.codigo}}">@{{tipo.descripcion}}</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="xl">Valor: </label>   
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-success" ng-click='domicilioDocumento.valor = domicilioDocumento.valor + 500' style="font-size: 25px">
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </div>
                                <input required type="number" min="0" ng-model="domicilioDocumento.valor" class="xl form-control centrado">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger" ng-click='domicilioDocumento.valor = domicilioDocumento.valor - 500' style="font-size: 25px">
                                        <i class="fa fa-minus-square"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="xl">Observaciones: </label>   
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control xl" ng-model="domicilioDocumento.observacion" name="obs" style="height: 120px"></textarea>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
            <div class="modal-footer">
                <button type="button" ng-disabled="saving || !(domicilioDocumento.tipodoc&&domicilioDocumento.valor&&domicilioDocumento.observacion)" class="btn btn-success min" ng-click="saveDomicilioDocumento()"> <i class="fa fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-default min" onclick="togglePagarDomicilio()"> <i class="fa fa-door-open"></i> Salir</button>
            </div>
        </div>
    </div>
</div>

