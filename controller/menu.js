var productoPedidoId = 0;
function editarItemPedido(id){
    mostrarFullLoading();
    productoPedidoId = id;
    document.getElementById('initLoadPP').click();
}
var app = angular.module('myApp', ['ngAnimate']);
app.controller('menuController', function($scope, $http) {
    $scope.id = 0;
    $scope.productoPedido = {};
    $scope.intercambio = {
        origen: null,
        destino: null,
    };
    $scope.loadPP = function(){
        $scope.showIntercambiar = false;
        if(productoPedidoId === 0 || isNaN(productoPedidoId)){
            return false;
        }
        $scope.intercambio = {
            origen: null,
            destino: null,
        };
        $scope.id = productoPedidoId;
        $http.get("/producto-pedido/full/"+$scope.id)
        .then(
            function(response){
                ocultarFullLoading();
                if(response.status == 200){
                    if(response.data.code == 200){
                        $scope.productoPedido = response.data;
                        if(response.data.producto.length > 0){
                            $scope.productoPedido.producto = response.data.producto[0];
                        }
                        $scope.productoPedido.ingredientesAgrupados = agruparIngredientes($scope.productoPedido.ingredientes);
                        $scope.productoPedido.adicionalesAgrupados = agruparIngredientes($scope.productoPedido.adicionales);
                        $scope.setIntercambioValid(null);
                        normalizeObs();

                        $("div#modalEditarItemPedido").modal('show');
                    }
                    else{
                        mostrarWarning('Error cargando el item');
                    }
                }
                else{
                    mostrarWarning('Error cargando el item');
                }
            }
        );
    }
    function agruparIngredientes(ingredientes){
        let agrupados = {};
        for(let i = 0; i<ingredientes.length; i++){
            let item = ingredientes[i];
            if(item.grupo == '' || !item.grupo){
                item.grupo = 'SIN GRUPO';
            }
            if(!(item.grupo in agrupados)){
                agrupados[item.grupo] = [];
            }
            agrupados[item.grupo].push(item);
        }
        let agrupados_ = [];
        for (var k in agrupados){
            if (agrupados.hasOwnProperty(k)) {
                agrupados_.push({nombre: k, ingredientes: agrupados[k]});
            }
        }
        return agrupados_;
    }
    $scope.setIntercambioValid = function(grupo){
        $scope.intercambio.destino = null;
        $scope.productoPedido.adicionales.forEach(function (item) {
            item.valid = item.grupo == grupo;
        });
    }
    function normalizeObs(){
        try{
            $scope.productoPedido.producto_pedido.obs = JSON.parse($scope.productoPedido.producto_pedido.obs);
        }
        catch (e) {
            $scope.productoPedido.producto_pedido.obs = {};
        }
    }
    $scope.guardarCambiosProductoPedido = function(){

        $scope.saving = true;
        let ingrediente = {};
        let adicional = {adicional: 0, cantidad: 0};
        if($scope.intercambio.origen && $scope.intercambio.destino){
            $scope.productoPedido.ingredientes.forEach(function (item) {
                if(item.id == $scope.intercambio.origen){
                    ingrediente = item;
                }
            });
            $scope.productoPedido.producto_pedido.obs.sin_ingredientes.push(
                {
                    id: ingrediente.id,
                    descripcion: ingrediente.descripcion,
                    cantidad: ingrediente.cant,
                    unidad: ingrediente.unidad,
                    intercambio: 1,
                }
            );
            $scope.productoPedido.adicionales.forEach(function (item) {
                if(item.id == $scope.intercambio.destino){
                    adicional = item;
                }
            });
            if (!('intercambios' in $scope.productoPedido.producto_pedido.obs)){
                $scope.productoPedido.producto_pedido.obs.intercambios = [];
            }
            $scope.productoPedido.producto_pedido.obs.intercambios.push(ingrediente.descripcion + ' por ' + adicional.descripcion);

        }
        $scope.intercambio = {};
        $http.patch("/producto-pedido/"+$scope.id+"/obs", 
        {
            data: $scope.productoPedido.producto_pedido.obs, 
            adicional: adicional.adicional, 
            cantidad: adicional.cantidad,
            ppcantidad: $scope.productoPedido.producto_pedido.cant
        })
        .then(
            function(response){
                if(response.status == 200 && response.data.code == 200){
                    actualizarDivPedido();
                    mostrarSuccess('Intercambio realizado.');
                    setTimeout(() => {
                        $scope.saving = false;
                        $('#modalEditarItemPedido').modal('hide');
                    }, 1000);
                }
                else{
                    mostrarWarning('No se pudo realizar el intercambio.');
                    $scope.saving = false;
                }
            }
        );
    }

});