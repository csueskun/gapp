var productoPedidoId = 0;
function editarItemPedido(id){
    mostrarFullLoading();
    productoPedidoId = id;
    document.getElementById('initLoadPP').click();
}
var app = angular.module('myApp', ['ngAnimate']);
app.controller('menuController', function($scope, $http) {
    $scope.id = 0;
    $scope.observaciones = {};
    $scope.productoPedido = {};
    $scope.intercambio = {
        origen: null,
        destino: null,
    };
    $scope.mesa_id = 0;
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


    $scope.tipoDocumentos = [];
    $scope.domicilioDocumento = {valor: 4000, observacion: 'Domicilio'};
    $scope.pagoCompra = {valor: 0, tipo: 'PAGO'};

    $scope.loadTipoDocumentos = function(){
        mostrarFullLoading();
        $http.get("/prepare-domicilio-documento")
        .then(
            function(response){
                ocultarFullLoading();
                if(response.status == 200){
                    $scope.tipoDocumentos = [];
                    response.data.data.forEach(function(t){
                        if(['RC', 'RT', 'CE', 'CI', 'BI', 'PN'].includes(t.codigo)){
                            $scope.tipoDocumentos.push(t);
                        }
                    });                    
                }
                else{
                }
            }
        );
    }
    $scope.saveDomicilioDocumento = function(){
        var pedidoId = getPedidoId();
        if(isNaN(pedidoId)){
            return false;
        }
        var data = $scope.domicilioDocumento;
        data.pedido_id = pedidoId;
        data.mesa_id = getMesaId();
        $scope.saving = true;
        $http.post("/domicilio-documento", data)
        .then(
            function(response){
                $scope.saving = false;
                $scope.domicilioDocumento = {valor: 4000, observacion: 'Domicilio'};
                if(response.status == 200){
                    mostrarSuccess('Documento registrado.');
                }
                else{
                    mostrarWarning('No se pudo registrar el documento.');
                }
            }
        );
    }
    $scope.savePagoCompra = function(){
        var data = $scope.pagoCompra;
        $scope.saving = true;
        $http.post("/pago-compra", data)
        .then(
            function(response){
                $scope.saving = false;
                $scope.pagoCompra = {valor: 0, tipo: 'PAGO'};;
                if(response.status == 200){
                    $('#modal_pagar').modal('hide');
                    mostrarSuccess('Documento registrado.');
                }
                else{
                    mostrarWarning('No se pudo registrar el documento.');
                }
            }
        );
    }

    $scope.loadObservaciones = function(){
        $scope.mesa_id = getMesaId();
        try {
            $scope.observaciones = getPedidoObservaciones();
            if(!$scope.observaciones.entregar_en){
                if($scope.mesa_id==0){
                    $scope.observaciones.entregar_en = 'DOMICILIO';
                }
                else{
                    $scope.observaciones.entregar_en = 'MESA';
                }
            }
            try {
                $scope.observaciones.para_llevar = $scope.observaciones.para_llevar === true;
            } catch (error) {
                $scope.observaciones.para_llevar = false;
            }
        } catch (error) {
            console.error(error);
        }
    }

    $scope.saveObs = function(){
        var pedidoId = getPedidoId();
        mostrarFullLoading();
        $http.post("/new-tercero", $scope.observaciones)
        .then(
            function(response){
                $http.post(`/pedido/${pedidoId}/patch`, {obs:JSON.stringify($scope.observaciones)})
                .then(
                    function(response){
                        $scope.saving = false;
                        $scope.pagoCompra = {valor: 0, tipo: 'PAGO'};;
                        if(response.status == 200){
                            $('#modal_pagar').modal('hide');
                            mostrarSuccess('Observación guardada.');
                        }
                        ocultarFullLoading();
                    }
                );
            }
        );
    }

    $scope.loadClienteData = function(){
        var clienteData = JSON.parse($('#cliente_data').val());
        $scope.observaciones.cliente_id = clienteData.id;
        $scope.observaciones.cliente = clienteData.nombrecompleto;
        $scope.observaciones.telefono = clienteData.telefono;
        $scope.observaciones.domicilio = clienteData.direccion;
        $scope.observaciones.identificacion = clienteData.identificacion;
    }

    $scope.cuenta = {
        pedido: [], 
        cuentas: [
            {alias: 'Cuenta_1', total: 0, propina: 0, twp:0},
            {alias: 'Cuenta_2', total: 0, propina: 0, twp:0},
        ],
        total: 0,
        propina: 0,
        valid: false};
    $scope.prepareDividirCuenta = function(cantidad=2){
        $scope.cuenta.pedido = [];
        $scope.cuenta.total = 0;
        $scope.cuenta.propina = 0;
        $scope.cuenta.valid = false;
        resetCuentas(cantidad);
        try {
            $scope.cuenta.propina = parseFloat($('ul.propina-html span.valor').attr('total'));
        } catch (error) {
        }
        $('#ul-pedido>li').each(function(i, e){
            var item = prepareCuentaItem($(e));
            item.cuentas = prepareItemCuentas(item, $scope.cuenta.cuentas.length);
            $scope.cuenta.pedido.push(item);
        });
        $scope.cuenta.cuentas.forEach(element => {
            element.propina = $scope.cuenta.propina/$scope.cuenta.cuentas.length
            element.twp = element.twp+element.propina;
        });
    }

    $scope.loadTipoDocumentos();

    function prepareCuentaItem(itemE){
        var item = {
            id: itemE.attr('id'),
            cantidad: itemE.find('.producto .cantidad').html(),
            nombre: itemE.find('.producto .nombre').html(),
            total: itemE.find('span.valor').attr('valor'),
            valid: null
        };
        if(!item.cantidad){
            item.cantidad = 1;
        }
        item.cantidad = parseInt(item.cantidad);
        item.total = parseFloat(item.total);
        $scope.cuenta.total += item.total;
        return item;
    }
    
    function prepareItemCuentas(item, cant){
        var cuentas = [];
        var options = [];
        for (var index = 0; index < cant; index++) {
            if(item.cantidad>1){
                options = [];
                for (var y = 0; y <= item.cantidad; y++) {
                    options.push(''+y);
                }
            }
            else{
                options = ['0', '1/'+cant, '1'];
            }
            cuentas.push({
                cantidad: '0',
                subtotal: 0,
                options: options,
            });
        }
        return cuentas;
    }
    $scope.setCuentasNumber = function(cantidad){
        $scope.prepareDividirCuenta(cantidad);
    }
    function resetCuentas(cantidad){
        $scope.cuenta.cuentas = [];
        for (var i = 0; i < cantidad; i++) {
            $scope.cuenta.cuentas.push({
                alias: 'Cuenta_'+(i+1),
                total: 0,
                propina: 0,
                twp: 0
            });
        }
    }
    $scope.updateItemCuenta = function(itemIndex, cuentaIndex){
        var item = $scope.cuenta.pedido[itemIndex];
        var cuenta = item.cuentas[cuentaIndex];
        cuenta.subtotal = fractionToFloat(cuenta.cantidad)*item.total/item.cantidad;
        validateItemCuenta(item);
    }
    $scope.printCuenta = function(){
        $http.post("/pedido/reporte-cuenta-dividida", $scope.cuenta)
        .then(
            function(data){
                enviarAServicioImpresionPost(data.data.servicio, data.data.print);
            }
        );
    }

    function validateItemCuenta(item){
        var cantidad = 0
        item.cuentas.forEach(cuenta => {
            cantidad+=fractionToFloat(cuenta.cantidad);
        });
        item.valid = cantidad == item.cantidad;
        validateCuenta();
    }

    function validateCuenta(){
        $scope.cuenta.valid = true;
        $scope.cuenta.cuentas.forEach(element => {
            element.total = 0;
        });
        $scope.cuenta.pedido.forEach(item => {
            if(!item.valid){
                $scope.cuenta.valid = false;
            }
            for (var i = 0; i < item.cuentas.length; i++) {
                const cuenta = item.cuentas[i];
                $scope.cuenta.cuentas[i].total += cuenta.subtotal; 
                $scope.cuenta.cuentas[i].twp = $scope.cuenta.cuentas[i].total + $scope.cuenta.cuentas[i].propina; 
            }
        });
    }

    function fractionToFloat(f){
        switch (f) {
            case '1/4': return 0.25;
            case '1/3': return 1/3;
            case '1/2': return 0.5;
            default: return parseFloat(f);
        }
    }
    function cantidadFH(fraction){
        var gcd = function(a, b) {
            if (b < 0.0000001) return a;
            return gcd(b, Math.floor(a % b));
        };
        var len = fraction.toString().length - 2;
        var denominator = Math.pow(10, len);
        var numerator = fraction * denominator;
        var divisor = gcd(numerator, denominator);
        numerator /= divisor;
        denominator /= divisor;
        return Math.floor(numerator) + '/' + Math.floor(denominator);
    }
});