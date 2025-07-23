<div id="addFraccionadoModal" class="modal fade" role="dialog" style='max-width: 800px;margin: auto;'>
    <div class="modal-dialog">
        <div class="modal-content" style="padding-top: 0px;">
            <div class="modal-header">
                <table>
                    <tr>
                        <td style="padding-right: 8px;">
                            <img src="/images/producto/producto.jpg" alt="Producto" height="100" class="thumbnail no-margin">
                        </td>
                        <td>
                            <h3 class="no-margin">@{{tipo.descripcion}}</h3>
                            <h4 class="">Fraccionado</h4>
                            <button class="btn btn-warning min squared" ng-click="personalizar = !personalizar">
                                <i class="fa fa-cogs"></i> Personalizar
                            </button>
                        </td>
                    </tr>
                </table>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding-top: 0px;">

                <div id="fracciones" ng-if="tipo.fracciones_.length > 1" class="centrado">
                    <h4>Fracciones-@{{tipo.fracciones_.length}}-@{{nFracciones}}</h4>
                    <button type="button" class="btn btn-default fuente bebas" ng-repeat="fraccion in tipo.fracciones_" 
                            ng-click="selectNFracciones(fraccion.value)">
                        @{{ fraccion.des }}
                    </button>
                </div>
                <div class='centrado'>
                    <div class="btn-group" role="group" aria-label="...">
                        <button class='btn font bebas min' ng-class="{'btn-default': !fraccion.done, 'btn-success': fraccion.done}" ng-repeat="fraccion in fracciones" type="button" class="btn btn-default" ng-click='showFraccion($index)'>
                        @{{fraccion.title.substr(0,12)}}
                        </button>
                    </div>
                </div>

                <div id="fraccionElegida">
                    <h4>Productos @{{fraccion.title}}</h4>
                    <button ng-repeat="producto in fraccion.productos" type="button" class="btn btn-default producto" 
                            ng-click="selectFraccionProducto(producto)" ng-class="{'active': producto.selected}">
                        <div style="background-image: url('/images/producto/@{{ producto.imagen }}')">
                            <span class="nombre">@{{ producto.descripcion }}</span>
                        </div>
                    </button>
                </div>
                <!--
                <div ng-repeat="fraccionTipo in fracciones">
                    <h3>@{{$index+1}}</h3>
                    <button ng-repeat="producto in fraccionTipo.productos" type="button" class="btn btn-default producto" 
                            ng-click="producto.done = !producto.done">
                        <div style="background-image: url('/images/producto/@{{ producto.imagen }}')">
                            <span class="nombre">@{{ producto.descripcion + ' -- ' + producto.done }}</span>
                        </div>
                    </button>
                </div>
                -->
            </div>
            <div class="" style="text-align: center;padding: 12px 0px;border-top: thin solid #d6d6d6;">
                <button class="btn btn-success min squared" ng-click="addFraccionado()" style='width: 200px'>
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