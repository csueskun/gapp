<div class="modal fade" id="modalEditarItemPedido" tabindex="-1" role="dialog" aria-labelledby="obervacionesModal">
    <div class="modal-dialog" role="document" ng-app="myApp" ng-controller="menuController">
        <div class="modal-content">
            <div class="modal-body">
                <table>
                    <tr>
                        <td rowspan="3">
                            <img height="120" src="/images/producto/@{{productoPedido.producto.imagen}}" class="thumbnail mb-2" alt="">
                        </td>
                        <td>
                            <h2 class="fuente bebas ml-2" style="margin-top: 0px; margin-bottom: 0px;">@{{productoPedido.producto.tipo_producto}}&nbsp;@{{productoPedido.producto.descripcion}}</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="input-group ml-2" style="width: 130px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-success" ng-click='productoPedido.producto_pedido.cant = productoPedido.producto_pedido.cant + 1' ng-disabled='productoPedido.producto_pedido.cant > 998'>
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </div>
                                <input type="number" min="1" step="1" max="999" ng-model="productoPedido.producto_pedido.cant" 
                                    class="form-control font bebas centrado" style="padding: 0; font-weight: bold; font-size: 28px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger" ng-click='productoPedido.producto_pedido.cant = productoPedido.producto_pedido.cant - 1' ng-disabled='productoPedido.producto_pedido.cant < 2'>
                                        <i class="fa fa-minus-square"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        
                        <td>
                            <button class="btn btn-warning min squared ml-2" ng-click="showIntercambiar = !showIntercambiar">
                                <i class="fa fa-cogs"></i> Intercambiar ingredientes
                            </button>
                        </td>
                    </tr>

                </table>
                <hr style="margin-top: 0px">
                <div class="row intercambio" ng-if='showIntercambiar'>
                    <div class="col-md-6">
                        <h4 class="font bebas">Ingredientes</h4>
                        <ul>
                            <li ng-repeat="grupo in productoPedido.ingredientesAgrupados">
                                @{{ grupo.nombre }}
                                <ul>
                                    <li ng-repeat="ingrediente in grupo.ingredientes">
                                        <label class="" >
                                            <input type="radio" ng-model="intercambio.origen" name="ingrediente1" value="@{{ ingrediente.id }}" ng-change="setIntercambioValid(grupo.nombre)"> @{{ ingrediente.descripcion }}
                                        </label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4 class="font bebas">Adicionales</h4>
                        <ul>
                            <li ng-repeat="grupo in productoPedido.adicionalesAgrupados">
                                @{{ grupo.nombre }}
                                <ul>
                                    <li ng-repeat="ingrediente in grupo.ingredientes">
                                        <label class="" >
                                            <input ng-disabled="!ingrediente.valid" type="radio" ng-model="intercambio.destino" name="ingrediente2" value="@{{ ingrediente.id }}"> @{{ ingrediente.descripcion }}
                                        </label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                    </div>
                    <!-- <div class="col-md-12">
                        <button ng-disabled="saving || !intercambio.origen || !intercambio.destino" type="button" class="font bebas btn btn-success min" ng-click="intercambiar()" style="font-size: 24px">
                            <i class="fa fa-save" ng-class="{'fa-spin': saving}"></i> Intercambiar
                        </button>
                    </div> -->
                </div>
            </div>

            <div id="pp-observaciones">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="ma-0 font bebas">Observaciones</h3>
                        <textarea style="font-size: 1.5em;" class="font roboto form-control" ng-model='productoPedido.producto_pedido.obs.obs'>
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button ng-disabled="saving" ng-click='guardarCambiosProductoPedido()' type="button" class="btn btn-success min"> <i class="fa fa-save" ng-class="{'fa-spin': saving}"></i> Guardar</button>
                <button type="button" class="btn btn-default min" data-dismiss="modal"> <i class="fa fa-door-open"></i> Salir</button>
            </div>
        </div>
        <button type="button" style="display: none;" ng-click="loadPP()" id="initLoadPP"></button>
    </div>
</div>
<style>
    ._0.ingredientes label{
        font-family: 'bebas_neuebold';
        text-align: center;
    }
</style>
<script>
    $(function () {

    })
</script>
