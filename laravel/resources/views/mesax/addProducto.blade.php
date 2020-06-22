<div id="addProductoModal" class="modal fade" role="dialog" style='max-width: 720px;margin: auto;'>
    <div class="modal-dialog">
        <div class="modal-content" style="padding-top: 0px;">
            <div class="modal-header">
                <table>
                    <tr>
                        <td style="padding-right: 8px;">
                            <img src="/images/producto/@{{producto.imagen}}" alt="Producto" height="100" class="thumbnail no-margin">
                        </td>
                        <td>
                            <h3 class="no-margin">@{{producto.tipo_producto}} @{{ producto.descripcion }}</h3>
                            <h4 class="">@{{ producto.detalle }}</h4>
                            <button class="btn btn-warning min squared" ng-click="personalizar = !personalizar">
                                <i class="fa fa-cogs"></i> Personalizar
                            </button>
                        </td>
                    </tr>
                </table>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <div class="centrado">
                    <h4>Cantidad</h4>
                    <div class="input-group" style="margin: auto; width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-success" ng-click='nuevoProducto.cantidad = nuevoProducto.cantidad + 1' ng-disabled='nuevoProducto.cantidad > 998'>
                                <i class="fa fa-plus-square"></i>
                            </button>
                        </div>
                        <input type="number" min="1" step="1" max="999" ng-model="nuevoProducto.cantidad" 
                            class="form-control font bebas centrado" style="padding: 0; font-weight: bold; font-size: 28px;">
                        <div class="input-group-btn">
                            <button class="btn btn-danger" ng-click='nuevoProducto.cantidad = nuevoProducto.cantidad - 1' ng-disabled='nuevoProducto.cantidad < 2'>
                                <i class="fa fa-minus-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="tamanos" ng-if="producto.tamanos.length > 1">
                    <h4>Tamanos</h4>
                    <button type="button" class="btn btn-default fuente bebas" ng-repeat="tamano in producto.tamanos" ng-click="selectTamano(tamano.tamano)">
                        @{{ tamano.tamano }}
                    </button>
                </div>

                <div class="animated-item-simple centrado" ng-if="personalizar">
                    <!-- <h3 data-toggle='collapse' data-target='#ingredientes' data-close='div#adicionales' 
                        class='centrado' ng-if='ingredientesProducto.length > 0'>
                        Ingredientes <span class="fa fa-caret-down"></span>
                    </h3> -->
                    <h3>
                        <button class="btn min squared" ng-click="showIngredientesF()" ng-if='ingredientesProducto.length > 0'
                            ng-class="{'btn-default': !showIngredientes, 'btn-success': showIngredientes}">
                            <i class="fa fa-dot-circle-o"></i> Ingredientes
                        </button>
                        <button class="btn btn-default min squared" ng-click="showAdicionalesF()" ng-if='adicionalesProducto.length > 0'
                            ng-class="{'btn-default': !showAdicionales, 'btn-success': showAdicionales}">
                            <i class="fa fa-dot-circle-o"></i> Adicionales
                        </button>
                    </h3>
                    <div id="ingredientes" class='animated-item-simple' ng-if="showIngredientes">
                        <div ng-repeat="grupo in ingredientesProducto">
                            <span class="group">@{{ grupo.nombre }}</span>
                            <button type="button" class="btn btn-default" ng-repeat="ingrediente in grupo.ingredientes"
                                    ng-if="ingrediente.tamano == tamanoElegido"  ng-class="{'active': ingrediente.active}"
                                    ng-click="clickIngrediente(ingrediente)">
                                <div style="background-image: url('/images/ingrediente/@{{ ingrediente.imagen }}')">
                                    <span>@{{ ingrediente.descripcion }}</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <!-- <h3 class='centrado' data-toggle='collapse' data-target='#adicionales' 
                        data-close='div#ingredientes' ng-if='adicionalesProducto.length > 0'>
                        Adicionales <span class="fa fa-caret-down"></span>
                    </h3> -->
                    <div id="adicionales" class='animated-item-simple' ng-if="showAdicionales">
                        <div ng-repeat="grupo in adicionalesProducto">
                            <span class="group">@{{ grupo.nombre }}</span>
                            <button type="button" class="btn btn-default" ng-repeat="ingrediente in grupo.ingredientes"
                                    ng-class="{'active': ingrediente.active}" ng-click="ingrediente.active = !ingrediente.active">
                                <div style="background-image: url('/images/ingrediente/@{{ ingrediente.imagen }}')">
                                    <span>@{{ ingrediente.descripcion }}</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" style="text-align: center;padding: 12px 0px;border-top: thin solid #d6d6d6;">
                <button class="btn btn-success min squared" ng-click="addProducto()" style='width: 200px'>
                    Agregar
                </button>
                <button type="button" class="btn btn-default min squared" data-dismiss="modal" style='width: 200px'>
                    Cancelar
                </button>
            </div>
        </div>

    </div>
</div>

<script>

    $(function(){
        $('[data-toggle=collapse]').on('click', function(e){
            var target = ($(this).attr('data-target'));
            var close = ($(this).attr('data-close'));
            $(close).removeClass("in");
            $(this).children(".fa-caret-down, .fa-caret-up").toggleClass("fa-caret-down fa-caret-up");
        });

    })
</script>