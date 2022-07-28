<div class="modal fade" id="dividirCuentaModal" tabindex="-1" role="dialog" aria-labelledby="dividirCuentaModal">
    <div class="modal-dialog" role="document" style='width: 700px'>
        <div class="modal-content">
            <div class="modal-body">
                <div class="cantidad-cuentas">
                    <span>Cantidad de cuentas: </span>
                    <div class="btn-group" role="group" aria-label="...">
                        <button ng-class="{'active': cuenta.cuentas.length == 2}" ng-click='setCuentasNumber(2)' type="button" class="btn btn-default">2</button>
                        <button ng-class="{'active': cuenta.cuentas.length == 3}" ng-click='setCuentasNumber(3)' type="button" class="btn btn-default">3</button>
                        <button ng-class="{'active': cuenta.cuentas.length == 4}" ng-click='setCuentasNumber(4)' type="button" class="btn btn-default">4</button>
                    </div>
                </div>
                <br>
                <table class="table normal table-condensed vertical-n">
                    <thead>
                        <tr>
                            <td>Pedido</td>
                            <td width="1">Cant.</td>
                            <td class="text-right pr-2">Total</td>
                            <td width="100" ng-repeat="n in cuenta.cuentas track by $index">
                                <input style="width: 100%" type="text" ng-model="cuenta.cuentas[$index].alias">
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in cuenta.pedido"
                            ng-class="{'danger':item.valid===false, 'success':item.valid}"
                            ng-init="itemIndex = $index">
                            <td class="va-i nombre">@{{item.nombre}}</td>
                            <td class="text-center va-i">@{{item.cantidad}}</td>
                            <td class="text-right va-i pr-2">
                                <span class=''>$@{{item.total | number:0 }}</span>
                            </td>
                            <td class="text-right" ng-repeat="itemCuenta in item.cuentas" ng-init="cuentaIndex = $index">
                                <select ng-options="o for o in itemCuenta.options" 
                                    ng-change="updateItemCuenta(itemIndex, cuentaIndex)"
                                    class = "form-control px-1 f20" ng-model="itemCuenta.cantidad">
                                </select>
                                <span class="">
                                    $@{{itemCuenta.subtotal | number:0}}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr >
                            <td></td>
                            <td></td>
                            <td class="text-right">
                                <!-- <span class="total">
                                    $@{{cuenta.total | number:0}}
                                </span> -->
                            </td>
                            <td class="text-right" ng-repeat="itemCuenta in cuenta.cuentas">
                                <span class="total">
                                    $@{{itemCuenta.total | number:0}}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>       
            <div class="modal-footer">
                <button type="button" class="btn btn-default f24" data-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary f24" ng-disabled='!cuenta.valid' ng-click="printCuenta()">
                    <i class="fa fa-print"></i> Imprimir
                </button>
                <button style="display: none" id="prepareDividirCuentaButton" ng-click="prepareDividirCuenta()">obs</button>
            </div>
        </div>
    </div>
</div>
<script>
</script>
<style>
    #dividirCuentaModal{
        font-family: 'bebas_neuebold';
        font-size: 22px;
        color: #4c4c4c;
    }
    #dividirCuentaModal table{
        /* border: thin solid gray; */
    }
    #dividirCuentaModal table tbody tr{
        border-left: solid 4px #1b809e;
    }
    #dividirCuentaModal td.nombre{
        color: #1b809e;
    }
    #dividirCuentaModal td span.total,
    #dividirCuentaModal td span.subtotal{
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
        padding: 2px 4px;
    }
    #dividirCuentaModal td span.subtotal{
        background-color: #f0ad4e;
    }

</style>
