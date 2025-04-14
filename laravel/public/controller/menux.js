var productoPedidoId = 0;
var app = angular.module('myApp', ['ngAnimate']);
app.controller('menuxController', function($scope, $http) {
    $scope.menu = [];
    $scope.config = {};
    $scope.pedido = {};
    $scope.mesa = '';
    $scope.compuestoCount = 0;

    $scope.init = function(mesa, reloadMenu=true){
        mostrarFullLoading();
        $scope.mesa = mesa;
        $http.get("/preMenu/"+mesa)
            .then(
                function(response){
                    if(response.status == 200){
                        $scope.config = response.data.config;
                        $scope.pedido = response.data.pedido;
                        cleanPedido();
                    }
                    if(reloadMenu){
                        initMenu();
                    }
                    else{
                        ocultarFullLoading();
                    }
                }
            );
    }
    function cleanPedido(){
        if($scope.pedido){
            $scope.pedido.total_ = accounting.formatMoney($scope.pedido.total,'$',0);
            if(Array.isArray($scope.pedido.productos_pedido)){
                $scope.pedido.productos_pedido.forEach(productoPedido => {
                    var obs = [];
                    productoPedido.valor_ = accounting.formatMoney(productoPedido.valor,'$',0);
                    productoPedido.total_ = accounting.formatMoney(productoPedido.total,'$',0);
                    try {
                        obs = JSON.parse(productoPedido.obs);
                    } catch (error) {
                        
                    }
                    productoPedido.observationList = createObsList(obs);
                });
            }
        }
    }
    function createObsList(observation){
        var list = [];
        if(Array.isArray(observation.compuesto) && observation.compuesto.length > 0){
            observation.compuesto.forEach(ingrediente => {
                list.push({
                    tipo: 'adicional',
                    texto: 'Con ' + ingrediente.descripcion
                });
            });
        }
        else{
            if(Array.isArray(observation.sin_ingredientes)){
                observation.sin_ingredientes.forEach(ingrediente => {
                    list.push({
                        tipo: 'sin-ingrediente',
                        texto: 'Sin ' + ingrediente.descripcion 
                    });
                });
            }
        }
        if(Array.isArray(observation.adicionales)){
            observation.adicionales.forEach(ingrediente => {
                list.push({
                    tipo: 'adicional',
                    texto: 'Extra ' + ingrediente.nombre,
                    valor: ' ' + accounting.formatMoney(ingrediente.valor,'$',0)
                });
            });
        }
        return list;
    }

    function initMenu(){
        mostrarFullLoading();
        getMenu();
    }
    function getMenu(){
        $http.get("/carta")
            .then(
                function(response){
                    if(response.status == 200){
                        $scope.menu = response.data;
                        formatFracciones();
                    }
                    ocultarFullLoading();
                }
            );
    }
    $scope.scrollToProducts = function(id){
        var aTag = $("#productos h4[name='"+ id +"']");
        $('#productos h4').removeClass('active');
        $('html,body').animate({scrollTop: aTag.offset().top - 50},'slow');
        aTag.addClass('active');
    }

    $scope.producto = {};
    $scope.addProduct = function(tipo, producto){
        $scope.personalizar = false;
        $scope.showIngredientes = false;
        $scope.showAdicionales = false;
        $scope.compuestoCount = 0;
        mostrarFullLoading();
        var tipoProducto = $scope.menu[tipo];
        $scope.producto = tipoProducto.productos[producto];
        $scope.producto.tipo_producto = tipoProducto.descripcion;
        $scope.producto.tamanos = tipoProducto.tamanos;
        $http.get("/adding-producto/" + $scope.producto.id)
            .then(
                function(response){
                    if(response.status == 200){
                        $scope.producto.tamanos = response.data.tamanos;
                        $scope.producto.ingredientes = response.data.ingredientes;
                        $scope.producto.adicionales = tipoProducto.adicionales;
                        if(Array.isArray($scope.producto.tamanos)){
                            let l = $scope.producto.tamanos.length;
                            if(l>0){
                                $scope.selectTamano($scope.producto.tamanos[0].tamano);
                            }
                        }
                    }
                    $('#addProductoModal').modal('show');
                    ocultarFullLoading();
                }
            );
    }
    $scope.tamanoElegido = '';
    $scope.fraccionesElegidas = 1;
    $scope.ingredientesProducto = [];
    $scope.adicionalesProducto = [];

    function formatFracciones(){
        if(Array.isArray($scope.menu)){
            $scope.menu.forEach(tipo => {
                tipo.fracciones_ = [];
                var fracciones = JSON.parse(`{"fracciones":${tipo.fracciones} }`.replace(/&quot;/g,'"'));
                if(Array.isArray(fracciones.fracciones)){
                    fracciones.fracciones.forEach(fraccion => {
                        tipo.fracciones_.push({
                            des: fraccion,
                            value: parseInt(fraccion.substring(0,1))
                        });
                    });
                }
            });
        }
    }
    $scope.fracciones = [];
    $scope.selectNFracciones = function(val){
        $scope.fracciones = [];
        $scope.nFracciones = val;
        for (let i = 0; i < val; i++) {
            var productos = [];
            $scope.tipo.productos.forEach(producto => {
                productos.push(Object.assign({}, producto));
            });
            $scope.fracciones.push({
                title: ""+(i+1),
                done: false,
                productos: productos,
                productoElegido: null
            });
        }
        $scope.showFraccion(0);
    }
    $scope.showFraccion = function(val){
        $scope.showingFraccion = val;
        $scope.fraccion = $scope.fracciones[val];
    }
    $scope.selectFraccionProducto = function(producto){
        $scope.fraccion.title = producto.descripcion;
        $scope.fraccion.done = true;
        $scope.fraccion.productos.forEach(producto => {
            producto.selected = false;
        });
        producto.selected = true;
    }

    $scope.selectTamano = function(tamano){
        $scope.tamanoElegido = tamano;
        $scope.ingredientesProducto = $scope.producto.ingredientes.filter(function (item) {
            return item.tamano == tamano;
        });
        $scope.adicionalesProducto = $scope.producto.adicionales.filter(function (item) {
            return item.pivot.tamano.toUpperCase() == tamano.toUpperCase();
        });
        if($scope.producto.compuesto > 0){
            var compuesto = true;
            $scope.showIngredientes = true;
            $scope.personalizar = true;
        }
        $scope.ingredientesProducto.forEach(function (item) {
            item.active = !compuesto;
        });
        $scope.adicionalesProducto.forEach(function (item) {
            item.active = false;
        });
        $scope.producto.valor = 0;
        $scope.producto.tamanos.forEach(function (item) {
            if(item.tamano == tamano){
                $scope.producto.valor = item.valor;
            }
        });
        $scope.ingredientesProducto = agruparIngredientes($scope.ingredientesProducto);
        $scope.adicionalesProducto = agruparIngredientes($scope.adicionalesProducto);
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

    $scope.nuevoProducto = {
        cantidad: 1
    };
    $scope.addProducto = function(){

        $scope.nuevoProducto.producto = {
            id: $scope.producto.id,
            nombre: $scope.producto.descripcion,
            nombre_tipo: $scope.producto.tipo_producto,
            valor: $scope.producto.valor,
        };
        $scope.nuevoProducto = getSimpleOrder();
        $scope.nuevoProducto.force = false;
        $scope.nuevoProducto.force = $scope.config.valida_inventario;
        $scope.nuevoProducto.alias = $scope.mesa;
        let data = {
            _token: '',
            producto_pedido_json: JSON.stringify($scope.nuevoProducto),
            mesa: $scope.config.mesa_alias,
            pedido: $scope.pedido.id?$scope.pedido.id:0,
            first: true
        }
        mostrarFullLoading();
        $http.post("/producto-pedido/agregar", data)
            .then(
                function(response){
                    if(response.status == 200){
                        if(response.data.id == -1){
                            alert('Mensaje validar inventario');
                            ocultarFullLoading();
                        }
                        else{
                            $scope.init($scope.mesa, false);
                        }
                    }
                }
            );
    }
    function getSimpleOrder(){
        $scope.nuevoProducto.ingredientes = getIngredientesSelected();
        $scope.nuevoProducto.adicionales = getAdicionalesSelected();
        var compuesto = [];
        if($scope.producto.compuesto > 0){
            compuesto = $scope.nuevoProducto.ingredientes;
        }
        $scope.nuevoProducto.obs = {
            tamano: $scope.tamanoElegido,
            tipo: 'NORMAL',
            mix: [],
            sin_ingredientes: getIngredientesSelected(false),
            compuesto: compuesto,
        };
        return $scope.nuevoProducto;
    }
    function getIngredientesSelected(mode = true){
        let ingredientes = [];
        $scope.ingredientesProducto.forEach(function (grupo) {
            grupo.ingredientes.forEach(function (ingrediente) {
                if(ingrediente.active === mode){
                    ingredientes.push({
                        id: ingrediente.ingrediente_id,
                        descripcion: ingrediente.descripcion,
                        cantidad: ingrediente.cantidad,
                        unidad: ingrediente.unidad
                    });
                }
            });
        });
        return ingredientes;
    }
    function getAdicionalesSelected(){
        let ingredientes = [];
        $scope.adicionalesProducto.forEach(function (grupo) {
            grupo.ingredientes.forEach(function (ingrediente) {
                if(ingrediente.active){
                    ingredientes.push({
                        id: ingrediente.pivot.id,
                        nombre: ingrediente.descripcion,
                        ingrediente: ingrediente.id,
                        valor: ingrediente.pivot.valor,
                        cantidad: ingrediente.pivot.cantidad,
                        unidad: ingrediente.unidad
                    });
                }
            });
        });
        return ingredientes;
    }
    $scope.clickIngrediente = function(ingrediente){
        if($scope.producto.compuesto>0){
            if(ingrediente.active){
                ingrediente.active = false;
                $scope.compuestoCount--;
            }
            else{
                if($scope.compuestoCount < $scope.producto.compuesto){
                    ingrediente.active = true;
                    $scope.compuestoCount++;
                }
            }
        }
        else{
            ingrediente.active = !ingrediente.active;
        }
    }
    $scope.showIngredientesF = function(){
        $scope.showIngredientes = !$scope.showIngredientes;
        if($scope.showIngredientes){
            $scope.showAdicionales = false;
        }
    }
    $scope.showAdicionalesF = function(){
        $scope.showAdicionales = !$scope.showAdicionales;
        if($scope.showAdicionales){
            $scope.showIngredientes = false;
        }
    }
    $scope.addFraccionado = function(tipo){
        $scope.tipo = $scope.menu[tipo];
        $scope.selectNFracciones(1);
        $('#addFraccionadoModal').modal('show');
    }
});