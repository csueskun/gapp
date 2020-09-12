var servicio_impresion = "";
var observaciones = JSON.parse("{}");
var role = $('meta[name=rol]').attr('content');
var esDomicilio = $('meta[name=mesa]').attr('content');
var validaInventario = $('meta[name=valida_inventario]').attr('content') == '1';
esDomicilio = (esDomicilio==""||esDomicilio==null||esDomicilio==0||esDomicilio=="0");
var altDown = false;

$(function () {
    $(document).keydown(function(e) {
        if (e.keyCode == 18) altDown = true;
    }).keyup(function(e) {
        if (e.keyCode == 18) ctrlDown = false;
    });

    $("span.producto-express").on("click", function(){
        var tar = $(this).attr('scroll-to');
        if(tar!=''){
            var aTag = $(tar);
            $('html,body').animate({scrollTop: aTag.offset().top - 50},'slow');
        }
    });


    $("div#cambio input.curr").inputmask("currency", { digits: 0 });
    $("div#cambio input.percent").inputmask("currency", { digits: 2, prefix: '% ', min: 0, max: 100});
    $("div.contenedor-adicionales-fraccion").hide();
    $('input[name=fraccion]').change(function () {
        var valor = $(this).val();
        if(valor === "1/1"){
            $(this).closest("form.producto").find("div.row.fraccion").hide();
            $(this).closest("form.producto").find("div.row._0").show();
            $(this).closest("form.producto").find("div.contenedor-adicionales-fraccion").hide();
            $(this).closest("form.producto").find("div.contenedor-adicionales.completa").show();
        }
        else{
            var re = /(.*)?\/.*/  ;
            var fraccion = valor.replace(re, "$1");
            fraccion = parseInt(fraccion);
            $(this).closest("form.producto").find("div.row.tamano").show();
            $(this).closest("form.producto").find("div.row._0").hide();
            $(this).closest("form.producto").find("div.contenedor-adicionales").hide();
            for(var i = 1; i<=fraccion; i++ ){
                $(this).closest("form.producto").find("div.row._"+i).show();
                $(this).closest("form.producto").find("div#adicionales-fraccion"+i).show();
                $(this).closest("form").find("div.row._"+i+".ingredientes").show();
            }
            for(var i=fraccion+1;i<=4;i++){
                $(this).closest("form.producto").find("div.row._"+i).hide();
            }
        }

        validarFormAgregarProducto($(this).closest("form"));
    });
    
    $('input[name=tamano-fraccion]').change(function () {
        filtrarTamanos($(this));
        var val = $(this).val().toUpperCase();
        var form = $(this).closest("form");
        form.find("div.row.ingredientes").hide();
        $("input[name=fraccion][value='1/1']").trigger('click');

        if(val==="porcion"){
            form.find("div.row.fracciones[id*='fracciones-']").hide();
            form.find("div.row.contenedor-adicionales[id*='adicionales-']").hide();
        }
        else{
            form.find("div.row.fracciones[id*='fracciones-']").show();
            form.find("div.row.contenedor-adicionales[id*='adicionales-']").show();
        }

        form.find("div.contenedor-adicionales>label>input").removeAttr("checked");
        // form.find("div.contenedor-adicionales>label").hide();
        form.find("div.contenedor-adicionales>label").css({'position': 'absolute', 'z-index': -1, 'display': 'none'});
        // form.find("div.contenedor-adicionales-fraccion>label").hide();
        form.find("div.contenedor-adicionales-fraccion>label").css({'position': 'absolute', 'z-index': -1, 'display': 'none'});
        // form.find("div.contenedor-adicionales>label.adicional-"+val).show();
        form.find("div.contenedor-adicionales>label.adicional-"+val).removeAttr('style');
        // form.find("div.contenedor-adicionales-fraccion>label.adicional-"+val).show();
        form.find("div.contenedor-adicionales-fraccion>label.adicional-"+val).removeAttr('style');

        form.find("input[name=tamano-fraccion][value="+val+"]").prop('checked',true);
        form.find("select").val("0");
        
        validarFormAgregarProducto($(this).closest("form"));
    });

    
    var mesa = $('meta[name=mesa]').attr('content');
    var mesero = !(mesa==""||mesa==null||mesa==0||mesa=="0");
    if(mesero){
        // filtrarTodosTamanos();
    }
    filtrarTodosTamanos();
    
    actualizarDivPedido();
    $("select.sabor-comida").not(".fraccion").change(function () {
        
        var producto = $(this).val();
        var productoCompuesto = $(this).find('option:selected').attr('compuesto');
        var terminado = $(this).find('option:selected').attr('terminado');
        var tipo = $(this).attr("tipo");
        if (producto==0){
            return false;
        }
        else{
            $(this).closest("form").find("div.row._0.ingredientes").show();
            
        }
        var botones = $(this).closest("form").find("button").addClass('disabled');
        
        var tamanoElegido = $(this).closest("form").find("input[name=tamano-fraccion]:checked").val();
        var closestForm = $(this).closest("form");

        if(terminado=='1'){
            closestForm.find("div.ingredientes").css({'position': 'absolute', 'z-index': -1, 'display': 'none'});
        }
        else{
            closestForm.find("div.ingredientes").removeAttr('style');
        }

        closestForm.find("div.ingredientes label").remove();
        closestForm.find("div.ingredientes .ingrediente-grupo-nombre").remove();
        closestForm.find(".loading-ingredientes").removeClass('hidden');
        closestForm.find("h3").removeClass('hidden');

        $.get("/producto/ver/" + producto, function (data) {
            closestForm.find(".loading-ingredientes").addClass('hidden');
            $("div#ingredientes-" + tipo).append(impIngredientesProducto(data.ingredientes,tamanoElegido,productoCompuesto));
            if(data.ingredientes.length > 0){
                $("div#ingredientes-" + tipo + " >button").show();
                closestForm.find("div.ingredientes").removeAttr('style');
            }
            else{
                closestForm.find("div.ingredientes").css({'position': 'absolute', 'z-index': -1, 'display': 'none'});
                $("div#ingredientes-" + tipo + " >button").hide();
            }
            $("div#sabores-" + tipo).html(impSaboresProducto(data.sabores));

            botones.removeClass('disabled');
        });
        $.get("/producto/tamanos/" + producto, function (data) {
            $("div#tamanos-" + tipo).html(impTamanosProducto(data));
        }).fail(function () {
            $.get("/producto/tamanos/" + producto, function (data) {
                $("div#tamanos-" + tipo).html(impTamanosProducto(data));
            }).fail(function () {
                //alert("error");
            });
        });
    }).trigger("change");
    
    $("select[name=producto]").change(function () {
        validarFormAgregarProducto($(this).closest("form"));
    });
    $("select.fraccion").change(function () {
        var producto = $(this).val();
        var fraccionNum = $(this).closest("div").attr("fraccion");
        var productoCompuesto = $(this).find('option:selected').attr('compuesto');
        if(producto==="0"){
            validarFormAgregarProducto($(this).closest("form"));
            return false;
        }
        var tipo = $(this).attr("tipo");
        var div_ingredientes = $(this).attr("div_ingredientes");
        if (producto=='0'){
            $(this).closest("form").find("button.agregar-orden").addClass("disabled");
            return false;
        }
        else{
            $(this).closest("div").find(".row.fraccion.cargar-ingredientes._"+fraccionNum).hide();
            validarFormAgregarProducto($(this).closest("form"), "_"+fraccionNum);
        }
        var closestForm = $(this).closest("form");
        var tamanoElegido = closestForm.find("input[name=tamano-fraccion]:checked").val();

        var div = $("div#ingredientes-"+div_ingredientes+ "-" + tipo);
        div.find("label").remove();
        div.find(".ingrediente-grupo-nombre").remove();
        div.find(".loading-ingredientes").removeClass('hidden');
        div.find("h3").removeClass('hidden');

        $.get("/producto/ver/" + producto, function (data) {
            div.find(".loading-ingredientes").addClass('hidden');
            div.append(impIngredientesProducto(data.ingredientes,tamanoElegido, productoCompuesto));
        });
    }).trigger("change");


    $("div.ingredientes").on('click', '.compuesto', function (e) {
        if($(this).siblings('.checked.compuesto').length >= parseInt($(this).attr('max'))){
            return false;
        }
    })

    $('body').not("input").on('keyup', function(event) {
        if(!$(event.target).is(':input')){
            hotKey(event.keyCode);
        }
        else{
            hotKey(event.keyCode);
        }
    });

    $('div.tamano>label>input[value=grande]').prop('checked', true);

});


$("form.producto").submit(function (event) {
        
    event.preventDefault();
//
    
    var producto = JSON.parse("{}");
    var producto_pedido = JSON.parse('{"ingredientes":[], "adicionales":[], "obs":{"tamano":"", "tipo":"NORMAL", "mix":[], "sin_ingredientes":[]}}');
    
    var fraccion = $(this).find('input[name=fraccion]:checked').val();
    if(fraccion == null){
        fraccion = "0/0";
    }
    var re = /(.*)?\/.*/;
    fraccion = fraccion.replace(re, "$1");
    fraccion = parseInt(fraccion);
    if(fraccion>1){
        interruptorBotonOcupado($(this).find("button[type=submit]"));
        producto_pedido.obs.tipo = "MIXTA";
        var producto_modelo = 0;
        var valor_max = 0;
        var adicionales_ = [];
        for(var i=1; i<=fraccion; i++){
            var attr = $(this).find("input[type='radio'][name='tamano-fraccion']:checked").val();
            attr=attr.toLowerCase().replace("ñ","&ntilde;").replace("ó","&oacute;").replace("ú","&uacute;");
            
            var ing = [];
            var sin_ingredientes = $.map($(this).find('div.row.fraccion._'+i+' input:checkbox:not(:checked)[name="ingrediente"]'), function (e, i) {
                return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
            });
            var ingredientes = $.map($(this).find('div.row.fraccion._'+i+' input:checkbox(:checked)[name="ingrediente"]'), function (e, i) {
                return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
            });
            var valor = $(this).find("select.fraccion[name=producto-f"+i+"] option:selected").attr(attr);
            valor = parseFloat(valor);
            if(valor>valor_max){
                valor_max = valor;
                producto_modelo = i;
            }
            var adicionales = $.map($(this).find('div#adicionales-fraccion'+i+' input:checkbox:checked[name="adicional"]'), function (e, ii) {
                return e.value;
            });
            var adicional = "";
            for (var k in adicionales) {
                adicional = adicionales[k].replace(/'/g, '"');
                adicional = JSON.parse(adicional);
                adicionales[k] = adicional;
                adicionales_.push(adicional);
            }
            var compuesto = $(this).find("select.fraccion[name=producto-f"+i+"] option:selected").attr("compuesto");
            var compuestos = [];
            if(compuesto != '0'){
                compuestos = $.map($(this).find('div.row.fraccion._'+i+' label.compuesto input:checkbox:checked[name="ingrediente"]'), function (e, i) {
                    return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
                });
            }

            producto_pedido.obs.mix.push({
                nombre:$(this).find("select.fraccion[name=producto-f"+i+"] option:selected").attr("nombre"),
                valor:valor,
                adicionales:adicionales,
                ingredientes:ingredientes,
                sin_ingredientes:sin_ingredientes,
                compuesto:compuestos
            });
            for(var k in adicionales){
                producto_pedido.adicionales.push(adicionales[k]);
            }
            for(var k in ingredientes){
                producto_pedido.ingredientes.push(ingredientes[k]);
            }
            for(var k in sin_ingredientes){
                producto_pedido.obs.sin_ingredientes.push(sin_ingredientes[k]);
            }
        }

        producto.id = $(this).find("select.fraccion[name=producto-f"+producto_modelo+"]").val();
        if (producto.id==0){
            return false;
        }
        producto.nombre_tipo = $(this).find("select.fraccion[name=producto-f"+producto_modelo+"]").attr("nombre");
        producto.nombre = $(this).find("select.fraccion[name=producto-f"+producto_modelo+"] option:selected").attr("nombre");
        
        var attr = $(this).find("input[type='radio'][name='tamano-fraccion']:checked").val();
        producto_pedido.obs.tamano = attr;
        attr=attr.replace("Ñ","&ntilde;").toLowerCase();
        var valor = $(this).find("select.fraccion[name=producto-f"+producto_modelo+"] option:selected").attr(attr);
        valor = parseFloat(valor);
        producto.valor = valor;
    }
    else{
        interruptorBotonOcupado($(this).find("button[type=submit]"));
        producto.id = $(this).find("select.sabor-comida").val();
        producto_pedido.obs.compuesto = $(this).find("select.sabor-comida option:selected").attr('compuesto');

        if (producto.id==0){
            return false;
        }
        producto.nombre_tipo = $(this).find("select.sabor-comida").attr("nombre");
        producto.nombre = $(this).find("select.sabor-comida option:selected").attr("nombre");
        var sin_ingredientes = $.map($(this).find('input:checkbox:not(:checked)[name="ingrediente"]'), function (e, i) {
            return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
        });
        producto_pedido.obs.sin_ingredientes = sin_ingredientes;

        producto_pedido.ingredientes = [];
        var ingredientes = $.map($(this).find('input:checkbox(:checked)[name="ingrediente"]'), function (e, i) {
            return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
        });
        producto_pedido.ingredientes = ingredientes;

        if(producto_pedido.obs.compuesto != '0'){
            ingredientes = $.map($(this).find('label.compuesto input:checkbox:checked[name="ingrediente"]'), function (e, i) {
                return {id: e.getAttribute("ingrediente_id"), descripcion: e.value, cantidad: e.getAttribute("cantidad"), unidad: e.getAttribute("unidad")};
            });
            producto_pedido.obs.compuesto = ingredientes;
        }


        var attr = $(this).find("input[type='radio'][name='tamano-fraccion']:checked").val();
        if (typeof attr === 'undefined' || !attr){
            var valor = $(this).find("select[name=producto] option:selected").attr("unico");
        }
        else{
            producto_pedido.obs.tamano = attr;
            attr = attr.toLowerCase();
            var valor = $(this).find("select[name=producto] option:selected").attr(attr);
        }
        valor = parseFloat(valor);
        producto.valor = valor;

        if(isValorEditable($(this))){
            producto.valor = getValorUpdated($(this));
        }

        var adicionales = $.map($(this).find('input:checkbox:checked[name="adicional"]'), function (e, i) {
            return e.value;
        });
        var adicional = "";
        for (var i in adicionales) {
            adicional = adicionales[i].replace(/'/g, '"');
            adicional = JSON.parse(adicional);
            adicionales[i] = adicional;
        }
        producto_pedido.adicionales = adicionales;
    }
    
    var sabor = $(this).find("input[type='radio'][name='sabor']:checked").val();
    
    producto_pedido.producto = producto;
    if(sabor!=null){
        producto_pedido.obs.sabor = sabor;
    }
    
    producto_pedido.cantidad = $(this).find('div.number-spinner>input').val();
    producto_pedido.force = !validaInventario;

    // console.log(producto_pedido);
    // return false;
    addProductoPedido(producto_pedido, $(this));
    event.preventDefault();
});

function getValorUpdated($form){
    var valor = $form.find('.input-group.number-spinner-valor input').val();
    try{
        return parseFloat(valor);
    }
    catch (e) {
        return 0.0;
    }
}
function getValorSelected($form){
    var fracciones = $form.find("input[name=fraccion]:checked").val();
    if(fracciones === "1/1"){
        return getValorSelectedFracciones($form);
    }
    return getValorSelectedTamanoUnico($form);
}

function getValorSelectedFracciones($form){
    return null;
}
function getValorSelectedTamanoUnico($form){
    var attr = $form.find("input[type='radio'][name='tamano-fraccion']:checked").val();
    if (typeof attr === 'undefined' || !attr){
        var valor = $form.find("select[name=producto] option:selected").attr("unico");
    }
    else{
        attr = attr.toLowerCase();
        var valor = $form.find("select[name=producto] option:selected").attr(attr);
    }
    valor = parseFloat(valor);
    return parseFloat(valor);
}
function isValorEditable($form){
    var attr = $form.closest("div.panel-collapse.collapse").attr('valor-editable');
    if(attr=='1'){
        return true;
    }
    return false;
}

function terminarAgregarPedido(form){
    var tamano_selected = form.find('.row.tamano input:checked').val();
    var fraccion_selected = form.find('.row.fracciones input:checked').val();
    interruptorBotonOcupado(form.find("button[type=submit]"));
    form.find("input[name=fraccion][value='1/1']").trigger('click');
    form.trigger("reset");
    form.find("button[type=submit]").attr("disabled", "disabled").addClass("disabled");
    form.find("div.row.contenedor-adicionales.completa").show();

    form.find('.row.tamano input[value='+tamano_selected+']').trigger('click');
    form.find('.row.fracciones input[value="'+fraccion_selected+'"]').trigger('click');
    validarFormAgregarProducto(form);
}

function filtrarTodosTamanos(){
    $("form").each(function(i){
        $(this).find("input[name=tamano-fraccion]:checked").each(function(j){
            filtrarTamanos($(this));
        });
        if($(this).find("input[name=tamano-fraccion]").length==1){
            if($(this).find("input[name=tamano-fraccion]").val()=='unico'){
                $(this).find("div.row.tamano").hide();
            }
        }
    });
}

function filtrarTamanos(e){
    var valor = e.val();
    try {
        valor=valor.toLowerCase();
    } catch (error) {
        valor = 0;
    }
    e.closest("form").find("option").each(function () {
        if($(this).val()==="0"){
        }
        else{
            if($(this).attr(valor)==null){
                $(this).hide();
            }
            else{
                $(this).show();
            }
        }
    });
}


function validarFracciones(form){
    var fraccion = form.find('input[name=fraccion]:checked').val();
    if (typeof fraccion === "undefined") {
        return false;
    }
    var re = /(.*)?\/.*/;
    fraccion = fraccion.replace(re, "$1");
    fraccion = parseInt(fraccion);
    var valido = true;
    for (var i = 1; i <= fraccion; i++) {
        if (form.find("select[name=producto-f" + i + "]").val() === "0") {
            esconderIngredientesAdicionales(form,"#adicionales-fraccion"+i);
            esconderIngredientesAdicionales(form,".row.fraccion.ingredientes._"+i);
            esconderIngredientesAdicionales(form,".row.fraccion.cargar-ingredientes._"+i);
            valido = false;
        }
        else{
            mostrarIngredientesAdicionales(form,"#adicionales-fraccion"+i);
            mostrarIngredientesAdicionales(form,".row.fraccion.ingredientes._"+i);
            mostrarIngredientesAdicionales(form,".row.fraccion.cargar-ingredientes._"+i);
        }
    }
    return valido;
}

function validarFormAgregarProducto(form, cssClass='row'){
    form.find("."+cssClass+" span.reflejar-select-producto").css('background-color','white');
    form.find("."+cssClass+" span.reflejar-select-producto span.producto-nombre").css('background-color','white');
    var valido = false;
    var fracciones = form.find("input[name=fraccion]:checked").val();
    if (typeof fracciones === "undefined") {
        if(form.find("select[name=producto]").val()!=="0"){
            mostrarIngredientesAdicionales(form,".contenedor-adicionales.completa");
            mostrarIngredientesAdicionales(form,"._0.ingredientes");
            valido = true;
        }
        else{
            esconderIngredientesAdicionales(form,".contenedor-adicionales.completa");
            valido = false;
        }
    }
    else{
        if(fracciones === "1/1"){
            
            if(form.find("select[name=producto]").val()!=="0"){
                mostrarIngredientesAdicionales(form,".row.ing-adic");
                valido = true;
            }
            else {
                esconderIngredientesAdicionales(form,".row.ing-adic");
            }
        }
        else{
            esconderIngredientesAdicionales(form,".row.ing-adic");
            valido = validarFracciones(form);
        }
        
    }
    if(valido){
        form.find("button[type=button]").removeClass("disabled").removeAttr("disabled");
        if(isValorEditable(form)){
            form.find("table.editar-valor-spinner").show();
            form.find("table.editar-valor-spinner input").val(getValorSelected(form));
        }
    }
    else{
        form.find("button[type=button]").addClass("disabled").attr("disabled", "disabled");
        form.find("table.editar-valor-spinner").hide();
    }
}
function mostrarIngredientesAdicionales(form,clase){
    form.find(clase).show();
}
function esconderIngredientesAdicionales(form, clase, loading){
    form.find(clase).hide();
}

function actualizarDivPedido() {
    mostrarFullLoading();
    getPedido(function (pedido) {
        $("div#pedido").html(impItemPedido(pedido));
        ocultarFullLoading();
    });
}

function addProductoPedido(producto, form, last = true, first = true, multi=false) {
    var token = $('meta[name=csrf-token]').attr('content');
    var mesa = $('meta[name=mesa]').attr('content');
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    if(multi){
        otrosProductos = producto;
        producto = otrosProductos.pop();
        last = otrosProductos.length==0;
    }
    producto.alias = $('meta[name=mesa_alias]').attr('content');
    mostrarFullLoading();
    var productoPedidoData = {
        _token: token,
        producto_pedido_json: JSON.stringify(producto),
        mesa: mesa,
        pedido: pedido_id,
        first: first
    };
    //console.log(JSON.stringify(producto))
    $.ajax({
        type: 'POST',
        url: "/producto-pedido/agregar",
        // url: "/pedidos/agregar/" + JSON.stringify(producto)+"/"+mesa+"/"+pedido_id,
        data: productoPedidoData,
        async: true
    }).done(function (data) {
        data = JSON.parse(data);
        
        if(data.id==-1){
            ocultarFullLoading();
            var html = "<ul class='inventario validacion'>";
            for (var i =0; i<data.errores.length; i++){
                html += "<li>";
                html += data.errores[i].mensaje;
                html += "</li>";
            }
            html += "</ul><strong>¿Desea agregar el producto, sin tener en cuenta el inventario?</strong>";
            $.confirm({
                title: 'Inventario insuficiente',
                type: 'red',
                typeAnimated: true,
                columnClass: 'col-md-8 col-md-offset-2',

                content: html,
                boxWidth: '600px',
                icon: 'fa fa-warning',
                buttons: {
                    confirm: {
                        btnClass: 'btn-blue',
                        text: 'Agregar el producto',
                        action: function(){
                            producto.force=true;
                            addProductoPedido(producto, form);
                        }
                    },
                    cancel: {
                        btnClass: 'btn-red',
                        text: 'Cancelar',
                        action: function(){
                            ocultarFullLoading();
                        }
                    },
                }
            });
            return false;
        }
        else{
            $('meta[name=pedido_id]').attr('content', data.id);
            if(last){
                mostrarSuccess('<strong>Listo!</strong> Producto Agregado');
                actualizarDivPedido();
                terminarAgregarPedido(form);
            }
            else{
                addProductoPedido(otrosProductos, form, false, first = false, multi=true);
            }
        }
    });
}



function getPedido(callback) {

    var token = $('meta[name=csrf-token]').attr('content');
    var mesa = $('meta[name=mesa]').attr('content');
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    if(pedido_id==""||pedido_id==null||pedido_id==0){
        $.post("/pedidos/mesa/"+mesa, {_token: token}, function (data) {
            callback(data);
        });
    }
    else{
        $.post("/pedidos/pedido/"+pedido_id, {_token: token}, function (data) {
            callback(data);
        });
    }
}

function cancelarItemPedido(itemId) {
    
    if($('ul#ul-pedido.items_pedido>li').length > 1 ){
        var valor = $('ul#ul-pedido.items_pedido>li#'+itemId).find('span.valor').attr("valor");
        var token = $('meta[name=csrf-token]').attr('content');
        var mesa = $('meta[name=mesa]').attr('content');
        $.post("/pedidos/cancelarProductoPedido/"+itemId, {_token: token}, function (data) {
            sumarTotalPedido(parseFloat(valor)*-1);

            $('ul#ul-pedido.items_pedido>li#'+itemId).fadeOut(function(){
                $('ul#ul-pedido.items_pedido>li#'+itemId).remove();
            });
		});
    }
    else{
        cancelarPedido();
    }
}

function sumarTotalPedido(suma){
    var total = $('ul#total.items_pedido span.total.valor');
    var valor = parseFloat(total.attr("total"))+suma;
    total.attr("total",valor);
    total.html(accounting.formatMoney(valor));
}

function impItemPedido(productos_pedido){
    mostrarFullLoading();
    var descuento = productos_pedido.descuento;
    var propina = productos_pedido.propina;
    var turno = productos_pedido.turno;
    if(productos_pedido.usuario_){
        $("#responsable-pedido").html('Pedido creado por: '+productos_pedido.usuario_+"&nbsp;");
    }
    if(turno){
        $('font#tit').html('Turno #' + turno + '  ');
    }
    var obs_pedido = productos_pedido.obs;
    if(obs_pedido!="{}"&&obs_pedido!=null){
        observaciones = JSON.parse(obs_pedido);
        obs_pedido = JSON.parse(obs_pedido);
        if(obs_pedido.para_llevar != null){
            $("input#llevar-mesa").attr("checked", "checked");
        }
        if(obs_pedido.observacion != null){
            $("textarea#observacion-mesa").val(obs_pedido.observacion);
        }
        if(obs_pedido.entregar_en != null){
            $("textarea#observacion-domicilio").val(obs_pedido.entregar_obs);
            if(obs_pedido.entregar_en == 'CAJA'){
                $('input[value=CAJA]').prop('checked', true);
            }
            else{
                $("label>input[value=DOMICILIO]").prop('checked', true);
                $('#observacionesModal #domicilio').val(obs_pedido.domicilio);
            }
        }

    }
    if(productos_pedido.programado  != null && productos_pedido.programado != ''){
        $("#hora-programa>input").val(horaMilitarAAPM((productos_pedido.programado).split(' ')[1]));
    }
    if(productos_pedido.estado == 1){
        $("label#entregado-label").show();
    }
    if(productos_pedido.entregado  != null && productos_pedido.entregado != ''){
        $("input#entregado-checkbox").attr("checked", "checked");
    }
    var pedido_id = productos_pedido.id;
    $('meta[name=pedido_id]').attr('content', pedido_id);
    var html2 = "";
    if(productos_pedido.estado === 4){
        $(".number-spinner").closest(".row").hide();
        $(".producto-express").removeAttr('onclick');
        html2+= '<a data-toggle=\"modal\" data-target=\"#modal_pagar\" onclick=\"actualizarCambio()\" onclick="actualizarCambio()" class = "boton-inline-grande btn btn-success"><span class="fa fa-usd"></span> Pagar</a>';
    }
    var mesa = $('meta[name=mesa]').attr('content');
    var mesero = !(mesa==""||mesa==null||mesa==0||mesa=="0");
    var pedido_id = 0;
    var pedido_activo = false;
    var multiples_items = false;
    var valorTotal = 0;
    var esCompuesto = false;
    var vacio = true;
    var html="";
    var obs = "";
    if(productos_pedido!="{}"&&productos_pedido!=null){
        pedido_id = productos_pedido.id;
        pedido_activo = (productos_pedido.estado == 1 || productos_pedido.estado == 3);
        productos_pedido = productos_pedido.productos_pedido;
        html +="<ul class=\"list-group items_pedido panel-collapse in collapse\" id=\"ul-pedido\">";
        var valorProducto = 0;
        if(productos_pedido != null){
            multiples_items = (productos_pedido.length > 1);
        }
        var combos = [];
        for(var i in productos_pedido){
            var ppp = productos_pedido[i];
            var combo = productos_pedido[i].combo;
            if(productos_pedido[i].combo != null){
                combo = JSON.parse(combo);
                combo = JSON.parse(combo);
                var obs = JSON.parse(productos_pedido[i].obs);
                var sin = [];
                for (var k = 0; k < obs.sin_ingredientes.length; k++) {
                    sin.push(obs.sin_ingredientes[k].descripcion);
                }

                var productoCombo = {
                    nombre: combo.nombre_producto,
                    sin: sin,
                    sabor: obs.sabor,
                }
                var pushed = false;
                for(x in combos){
                    if(combos[x].ref == combo.ref){
                        combos[x].productosCombo.push(productoCombo);
                        pushed = true;
                        break;
                    }
                }
                if(pushed){
                }
                else{
                    combo.productosCombo = [];
                    combo.productosCombo.push(productoCombo);
                    combos.push(combo);
                }
                continue;
            }
        }
        for(var i in combos){
            var valorCombo = isNaN(combos[i].precio)?0:parseFloat(combos[i].precio);
            var cantidad = isNaN(combos[i].cantidad)?0:parseInt(combos[i].cantidad);
            valorTotal += valorCombo * cantidad;
            html+= printCombo(combos[i]);
            vacio = false;
        }
        for(var i in productos_pedido){
            var combo = productos_pedido[i].combo;
            if(productos_pedido[i].combo != null){
                continue;
            }
            var obs = JSON.parse(productos_pedido[i].obs);
            var x_cantidad = '';
            if(productos_pedido[i].cant>1){
                x_cantidad = "<span style='color: #5cb85c'>"+productos_pedido[i].cant+"</span><span style='color:#5cb85c'>x </span>";
            }
            valorProducto = Math.floor(productos_pedido[i].total);
            if(obs.tipo === "MIXTA"){
                html += '       <li class="list-group-item">' +
                        '            <span class="producto">'+ x_cantidad + productos_pedido[i].producto.tipo_producto.descripcion + ' ' + '' + (getTamanosLabelMin(obs.tamano))+  '</span>';
                var fracciones = obs.mix.length;
                for(var j = 0; j<fracciones; j++){
                    html+='<span class="detalles">[ ';
                    html+='1/'+fracciones+' '+obs.mix[j].nombre;

                    esCompuesto = obs.mix[j].compuesto != null &&  obs.mix[j].compuesto != '' &&  obs.mix[j].compuesto != '0';
                    if(esCompuesto){
                        for (var k = 0; k < obs.mix[j].compuesto.length; k++) {
                            html += '<span class="detalles">';
                            html += k==0?' CON ':'';
                            html += obs.mix[j].compuesto[k].descripcion;
                            html += k<obs.mix[j].compuesto.length-1?', ':'.';
                            html += '</span>';
                        }
                    }
                    else{
                        for (var k = 0; k < obs.mix[j].sin_ingredientes.length; k++) {
                            html+='<span class="detalles">';
                            html += ' SIN ' + obs.mix[j].sin_ingredientes[k].descripcion;
                            html += '</span>';
                        }
                    }
                    for(var k=0;k<obs.mix[j].adicionales.length;k++){
                        var val_adicional_fraccion = obs.mix[j].adicionales[k].valor/fracciones;
                        val_adicional_fraccion = Math.ceil(val_adicional_fraccion/100)*100;
                        html+=' EXTRA '+obs.mix[j].adicionales[k].nombre+' ('+accounting.formatMoney(val_adicional_fraccion,'$',0)+')';
                    }
                    html+=' ]<br/></span>';
                }
            }
            else{
                html += '       <li id="'+productos_pedido[i].id+'" class="list-group-item">' +
                        '            <span class="producto">'+ x_cantidad + productos_pedido[i].producto.tipo_producto.descripcion + ' ' + productos_pedido[i].producto.descripcion + ' ' + (getTamanosLabelMin(obs.tamano)) + '</span>';
                esCompuesto = obs.compuesto != null && obs.compuesto != '' && obs.compuesto != '0';
                if(esCompuesto){
                    for (var k = 0; k < obs.compuesto.length; k++) {
                        html += '<span class="detalles">';
                        html += k==0?'CON ':'';
                        html += obs.compuesto[k].descripcion;
                        html += k<obs.compuesto.length-1?', ':'.';
                        html += '</span>';
                    }
                }
                else{
                    for (var k = 0; k < obs.sin_ingredientes.length; k++) {
                        if(!obs.sin_ingredientes[k].intercambio){
                            html+='<span class="detalles">';
                            html += 'SIN ' + obs.sin_ingredientes[k].descripcion;
                            html += '</span>';
                        }
                    }
                }
                if(obs.intercambios && Array.isArray(obs.intercambios)){
                    for(var ii=0; ii<obs.intercambios.length;ii++){
                        html+='<span class="detalles">';
                        html += obs.intercambios[ii];
                        html += '</span>';
                    }
                }
            }
            if(obs.obs){
                html+='<span class="detalles">';
                html += obs.obs;
                html += '</span>';
            }
            var funcionCancelar = 'cancelarItemPedido(\'' + productos_pedido[i].id + '\')';
            var editarItemPedido = 'editarItemPedido(\'' + productos_pedido[i].id + '\')';
            vacio = false;
            if(obs.tipo !== "MIXTA"){
                var valorAdicional = 0;
                for(var j in productos_pedido[i].producto_pedido_adicionales){
                    var ppa = productos_pedido[i].producto_pedido_adicionales[j];
                    if(!ppa.cambio){
                        valorAdicional = Math.floor(ppa.adicional.valor);
                        html+='            <span class="detalles">EXTRA '+ppa.adicional.ingrediente.descripcion+' ('+accounting.formatMoney(valorAdicional,'$',0)+')</span>';
                    }
                }
            }
            
            html+='<span class="detalles">'+(obs.sabor?obs.sabor:"")+'</span>';
            
            html+='            <br/><div class="spaceholder-valor-item">&nbsp;</div><div class="btn-group items">';

            if(pedido_activo){
                html+= '        <span onclick="'+editarItemPedido+';" class="btn btn-warning total fa fa-pencil"></span>';
            }
            if(pedido_activo && (isAdmin() || isCajero() || productos_pedido[i].comanda === 0)){
                html+= '        <span onclick="'+funcionCancelar+';" class="btn btn-danger total fa fa-trash boton-cancelar"></span>';
            }
            html+= '        <span class="btn btn-success valor" valor="'+valorProducto+'">'+accounting.formatMoney(valorProducto, '$ ', 0)+'</span></div>'+
             '        </li>';
            valorTotal+=valorProducto;
        }
        html+="</ul>";
    }
    html+='    <ul class="list-group items_pedido" id="total">'+
            '        <li class="list-group-item" style="height: 48px">'+
            '            <span class="producto">Total Pedido:</span>'+
            '            <div class="btn-group">';
    if(!vacio){
        if(pedido_activo && (isAdmin() || isCajero())){
            html += "<button style='padding: 2px 8px' class='btn btn-danger' onclick='preCancelarPedido(cancelarPedido)'><span style='font-size: 32px' class='fa fa-trash'></span></button>"
        }
    }
    html+=''+
            '            <span class="btn btn-success total valor" total="'+valorTotal+'">'+accounting.formatMoney(valorTotal, '$ ', 0)+'</span>'+
            '            </div>'+ 
            '        </li>'+
            '    </ul>';

    html += descuentoHtml(descuento);
    
    if(!vacio){
        html += '' +
                        '<form id="form-pagar" data-toggle = "validator" role = "form" action = "/pedido/pagar" method = "POST">' +
                        '<input type = "hidden" name = "_token" value = "' + $('meta[name=csrf-token]').attr('content') + '" >' +
                        '<input type = "hidden" name = "id" value = "' + pedido_id + '" / >' +
                        '<input type="hidden" name="_method" value="POST">' +
                        '<input type = "hidden" name = "paga_efectivo" value = "" / >' +
                        '<input type = "hidden" name = "paga_debito" value = "" / >' +
                        '<input type = "hidden" name = "paga_credito" value = "" / >' +
                        '<input type = "hidden" name = "num_documento" value = "" / >' +
                        '<input type = "hidden" name = "banco" value = "" / >' +
                        '<input type = "hidden" name = "debe" value = "" / >' +
                        '<input type = "hidden" name = "descuento" value = "" / >' +
                        '</form>  ';
        html+="<button id='boton-observaciones' class=\"btn btn-warning boton-inline-grande\" onclick='abrirObservaciones()' type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i> Observaciones</button>";
        if(mesa != '0'){

            // html+="<button id='boton-observaciones' class=\"btn btn-warning boton-inline-grande\" data-toggle=\"modal\" data-target=\"#modal_observaciones\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i> Observaciones</button>";
        }
        else{
            html+="<button id='boton-observaciones-domicilio'  class=\"btn btn-success boton-inline-grande\" data-toggle=\"modal\" data-target=\"#modal_detalles_domicilio\" type=\"button\"><i class=\"glyphicon glyphicon-calendar\"></i> PROGRAMAR</button>";
        }
        if(pedido_activo){
            html+='<button class="boton-inline-grande btn btn-purple imprimir" onclick="impPos('+pedido_id+')"><span class="fa fa-print"/> Comanda</button>';
            // html+='<button class="boton-inline-grande btn btn-primary imprimir" onclick="pagarImprimirPedido('+pedido_id+')"><span class="fa fa-print"/> Facturar</button>';
            html+='<button class="boton-inline-grande btn btn-primary imprimir" onclick="preFactura('+pedido_id+')"><span class="fa fa-file-powerpoint-o"/> Prefactura</button>';
            if(isAdmin() || isCajero()){
                html+= '<a data-toggle=\"modal\" data-target=\"#modal_pagar\" onclick="actualizarCambio()" class = "boton-inline-grande btn btn-success"><span class="fa fa-usd"></span> Pagar</a>';
            }
            if(isAdmin()){
                html+='<button class="boton-inline-grande btn btn-danger imprimir" onclick="gaveta()"><span class="fa fa-inbox"/> ABRIR CAJÓN</button>';
                html+='<button class="boton-inline-grande btn btn-purple imprimir" onclick="reImprimirComanda('+pedido_id+')"><span class="fa fa-print"/> Comanda Completa</button>';
            }
        }
        if(!mesero){
            if(pedido_activo){
            }
            else{
                html+='<button class="boton-inline-grande btn btn-default imprimir" onclick="impPosFactura('+pedido_id+')"><span class="fa fa-print"/> IMPRIMIR FACTURA</button>';
                html+='<button class="boton-inline-grande btn btn-purple imprimir" onclick="reImprimirComanda('+pedido_id+')"><span class="fa fa-print"/> Comanda Completa</button>';
                //FACTURA+COMANDA
                html+='<button class="boton-inline-grande btn btn-success imprimir" onclick="reImprimirComanda('+pedido_id+');impPosFactura('+pedido_id+')"><span class="fa fa-print"/> Factura+Comanda</button>';
                //
                if(isAdmin()){
                    html+='<button class="boton-inline-grande btn btn-danger imprimir" onclick="gaveta()"><span class="fa fa-inbox"/> ABRIR CAJÓN</button>';
                }
            }
        }
        html+=html2+'</div>';
    }
    if(!vacio){
        if(propina == null){
            propina = parseFloat(valorTotal) * 0.07;
        }
        $('input[name=old_propina]').val(propina);
        $('input[name=propina2]').val(propina);
        $('td#propina .percent').val(propina * 100 / parseFloat(valorTotal));
    }

    ocultarFullLoading();
    return html;
}
function descuentoHtml(descuento = 0){
    var style = 'display: none';
    if(descuento == 0 || descuento == null){
        descuento = '$ 0';
    }
    else{
        descuento = accounting.formatMoney(descuento, '$', 0);
        style = ''
    }
    var html = `<ul class="list-group items_pedido" id="descuento" style="${style}">
        <li class="list-group-item" style="height: 48px">
        <span class="producto">Descuento:</span>
        <div class="btn-group">
            <span class="btn btn-success total valor" total="">${descuento}</span>
        </div>
        </li>
        </ul>`;
    return html;
}
function impIngredientesProducto(ingredientes, tamano, compuesto = 0){

    var html = "";
    var agrupados = {};
    for(var i in ingredientes){
        if(ingredientes[i].grupo == null || ingredientes[i].grupo == ''){
            ingredientes[i].grupo = 'SIN GRUPO';
        }
    }
    ingredientes.sort( function( a, b ) {
        if ( a.grupo < b.grupo){
            return -1;
        }
        if ( a.grupo > b.grupo){
            return 1;
        }
        if ( a.descripcion < b.descripcion ){
            return -1;
        }
        if ( a.last_nom > b.last_nom ){
            return 1;
        }
        return 0;
    });
    vacio = false;
    for(var i in ingredientes){
        var invisible = false;
        if(ingredientes[i].pivot.tamano != tamano){
            continue;
        }
        if(ingredientes[i].visible != 1){
            invisible = true;
        }
        vacio = false;
        var checked = "checked";
        var classCompuesto = "";
        if(!invisible){
            if(compuesto!=0){
                checked = "";
                classCompuesto = " compuesto";
            }
        }

        ingredientes[i].compuesto = compuesto;
        ingredientes[i].checked = checked;
        ingredientes[i].classCompuesto = classCompuesto;
        ingredientes[i].invisible = invisible;

        if(ingredientes[i].grupo == null || ingredientes[i].grupo == ''){
            ingredientes[i].grupo = 'SIN GRUPO';
        }
        if(agrupados[ingredientes[i].grupo] == null){
            agrupados[ingredientes[i].grupo] = [];
        }
        agrupados[ingredientes[i].grupo].push(ingredientes[i]);


        // html+='<label max="'+compuesto+'" class="'+checked+classCompuesto+'" style="margin-right:-1px; '+(invisible?'display: none':'')+' "><img height="80" src="/images/ingrediente/'+
        // ingredientes[i].imagen+'"/><br/>'+ingredientes[i].descripcion+'<br/><input value="'+
        // ingredientes[i].descripcion+'" ingrediente_id="'+ingredientes[i].id+
        //     '"  cantidad="'+ingredientes[i].pivot.cantidad+'"  unidad="'+ingredientes[i].unidad+
        // '"  class="checkbox-grande" type="checkbox" '+checked+' name="ingrediente" value=""></label>';
    }
    Object.keys(agrupados).forEach(function(key) {
        html += `<div class="ingrediente-grupo-nombre">${key}</div>`;
        for(var i=0; i<agrupados[key].length; i++){

            html+='<label max="'+agrupados[key][i].compuesto+'" class="'+agrupados[key][i].checked+agrupados[key][i].classCompuesto+'" style="margin-right:-1px; '+(agrupados[key][i].invisible?'display: none':'')+' "><img height="80" src="/images/ingrediente/'+
                agrupados[key][i].imagen+'"/><br/>'+agrupados[key][i].descripcion+'<br/><input value="'+
                agrupados[key][i].descripcion+'" ingrediente_id="'+agrupados[key][i].id+
                '"  cantidad="'+agrupados[key][i].pivot.cantidad+'"  unidad="'+agrupados[key][i].unidad+
                '"  class="checkbox-grande" type="checkbox" '+agrupados[key][i].checked+' name="ingrediente" value=""></label>';
        }
    });
    if(vacio){
        return "";
    }
    else{
        return html;
    }
}

function impSaboresProducto(sabores){
    var html = "<h3>Sabores:</h3>";
    var vacio = true;
    var checked = "";
    for(var i in sabores){
        checked = "";
        if(i==0){
            checked = "checked";
        }
        vacio = false;
        html+='<label class="checkbox-inline"><input value="'+sabores[i].descripcion+'" '+checked+' type="radio" name="sabor" value="'+sabores[i].descripcion+'">'+sabores[i].descripcion+'</label>';
    }
    if(vacio){
        return "";
    }
    else{
        return html;
    }
}
function impTamanosProducto(tamanos){
    var html = "<h3>Tamaño:</h3>";
    var vacio = true;
    var checked = "";
    for(var i in tamanos){
        checked = "";
        if(i==0){
            checked = "checked";
        }
        vacio = false;
        html+='<label class="checkbox-inline"><input value="'+tamanos[i].tamano+'" '+checked+' valor="'+tamanos[i].valor+'" type="radio" name="tamano" value="'+tamanos[i].tamano+'">'+tamanos[i].tamano+' '+accounting.formatMoney(tamanos[i].valor)+'</label>';
    }
    if(vacio){
        return "";
    }
    else{
        return html;
    }
}
function impAdicionalesProducto(adicionales){
    var html = "<h3>Adicionales:</h3>";
    var vacio = true;
    for(var i in adicionales){
        vacio = false;
        html+='<label class="checkbox-inline"><input value="{\'id\':'+adicionales[i].id+',\'nombre\':\''+adicionales[i].ingrediente.descripcion+'\',\'valor\':'+adicionales[i].valor+'}" type="checkbox" name="adicional" value="">'+adicionales[i].ingrediente.descripcion+' (+'+accounting.formatMoney(adicionales[i].valor)+')</label>';
    }
    if(vacio){
        return "";
    }
    else{
        return html;
    }
}
function cancelarPedido(){
    mostrarFullLoading();
    var token = $('meta[name=csrf-token]').attr('content');
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    var mesa = $('meta[name=mesa]').attr('content');
    var ver = $('meta[name=ver]').attr('content');
    var mesero = !(mesa==""||mesa==null||mesa==0||mesa=="0");
    $.post("/pedidos/cancelar/"+pedido_id, {_token: token}, function (data) {
        $('meta[name=pedido_id]').attr('content', "0");
        if(ver=='1'){
            window.location.href = '/pedido/listar/cancelado';
        }
        else{
            actualizarDivPedido();
        }
        ocultarFullLoading();
    });
}
function pagarPedido(id){
    var token = $('meta[name=csrf-token]').attr('content');
    $.post("/pedido/pagar/"+id, {_token: token}, function (data) {
        actualizarDivPedido();
    });
}
function pagarImprimirPedido(id){

    var token = $('meta[name=csrf-token]').attr('content');
    $.post("/pedido/pagarImprimir/"+id, {_token: token}, function (data) {
        impPosFactura(id);
        actualizarDivPedido();
    });
    
}
function preFactura(id=false){
    savePropina();
    if(!id){
        var id = $('meta[name=pedido_id]').attr('content');
    }
    var propina = $('td#propina .percent').inputmask('unmaskedvalue');
    var descuento = $('td#descuento .percent').inputmask('unmaskedvalue');
    if(servicio_impresion=="" || servicio_impresion==null){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.get("/pedido/preFactura/"+id+"?propina="+propina+"&descuento="+descuento, function (data) {
                enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
            });
        });
    }
    else{
        $.get("/pedido/preFactura/"+id+"?propina="+propina+"&descuento="+descuento, function (data) {
            enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
        });
    }
}

function guardarDetallesDomicilio(){
    if($('meta[name=mesa]').attr('content')=='0'){
        var entregar_en = $('input#entregar_en_').val();
        var pedido_id = $('meta[name=pedido_id]').attr('content');
        var observacion = $("textarea#observacion-domicilio").val();
        if(observacion == ''){
            if(entregar_en=='DOMICILIO'){
                //alert('Agregue la dirección de Domicilio para continuar');

                $.alert({
                    title: 'Espere!',
                    icon: 'fa fa-warning',
                    type: 'red',
                    typeAnimated: true,
                    content: 'Agregue la dirección de Domicilio para continuar',
                });
                return false;
            }
            else{
                observacion = '-';
            }
        }

        mostrarFullLoading();
        observacion = (encodeURI(observacion).replace(new RegExp('#', 'g'), '%23'));

        $.get("/pedido/entregar/"+pedido_id+"/"+entregar_en+"/"+observacion, function (data) {
            ocultarFullLoading();
            mostrarSuccess('Observaciones guardadas');
        });

        var fecha = '-';
        if($("#hora-programa input").val()!=''){
            fecha = formatearFecha(fechaHoyHora($("#hora-programa input").val()),'yyyy-mm-dd hh:ii:00');
        }
        $.get("/pedido/programar/"+pedido_id+"/"+fecha, function (data) {
        });
    }

}

function interruptorBotonOcupado(boton, mensaje){
    if(boton.attr("disabled")==="disabled"){
        boton.removeAttr("disabled").removeClass("ocupado").html('<span class="fa fa-plus-circle" aria-hidden="true"></span> Agregar a la Orden');
    }
    else{
        boton.attr("disabled", "disabled").addClass("ocupado").html('<span class="fa" aria-hidden="true"><img class="button-loading" src="/images/loading.gif"/></span> Agregando Producto');
    }
}

function submitFix(form){
    mostrarFullLoading();
    setTimeout(function () {
        form.submit();
    }, 500);
    
}

function actualizarCambio(){
    var input_total = $("div#cambio td#cambio_total>input");
    var total = $('ul#total.items_pedido span.total.valor');
    if(observaciones.cliente!=null) $("div#cambio td#tercero_des input").val(observaciones.cliente+' '+observaciones.identificacion);
    else $("div#cambio td#tercero_des input").val('VARIOS');
    input_total.val(total.attr('total'));
    calcularCambio();
    calcularPropina();
}


function paraLlevar(){
    var campo = $("input#llevar-mesa");
    if(campo.attr("disabled") == "disabled"){
        return false;
    }
    campo.attr("disabled", "disabled");
    var token = $('meta[name=csrf-token]').attr('content');
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    $.post("/pedido/parallevar/"+pedido_id, {_token: token}, function (data) {
        campo.removeAttr("disabled");
        campo.prop('checked', !campo.is(':checked'));
    });
}

function entregadoCheckbox(checkbox){

    if(checkbox.attr("disabled") == "disabled"){
        return false;
    }
    checkbox.attr("disabled", "disabled");
    var token = $('meta[name=csrf-token]').attr('content');
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    $.post("/pedido/entregado/"+pedido_id, {_token: token}, function (data) {
        checkbox.removeAttr("disabled");
        var c = 'checked';
        if(checkbox.attr(c)==c){
            checkbox.removeAttr(c);
        }
        else{
            checkbox.attr(c, c);
        }
    });
}
function impPos(id){
    imprimiendo();
    if(servicio_impresion=="" || servicio_impresion==null){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.get("/pedido/comanda/"+id+"/pos-stack", function (data) {
                enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
            });
        });
    }
    else{
        $.get("/pedido/comanda/"+id+"/pos-stack", function (data) {
            enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
        });
    }
}
function reImprimirComanda (id){
    imprimiendo();
    if(servicio_impresion=="" || servicio_impresion==null){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.get("/pedido/re-comanda/"+id+"/pos-stack", function (data) {
                enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
            });
        });
    }
    else{
        $.get("/pedido/re-comanda/"+id+"/pos-stack", function (data) {
            enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
        });
    }
}
function impPosFactura(id){
    imprimiendo();
    if(servicio_impresion=="" || servicio_impresion==null){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.get("/pedido/factura/"+id+"/pos-stack", function (data) {
                enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
            });
        });
    }
    else{
        $.get("/pedido/factura/"+id+"/pos-stack", function (data) {
            enviarAServicioImpresion(servicio_impresion+"?stack="+encodeURIComponent(JSON.stringify(data)));
        });
    }
}
function gaveta(){
    imprimiendo();
    if(!isAdmin()){
        mostrarError('Acción no permitida');
        return false;
    }
    if(servicio_impresion=="" || servicio_impresion==null){
        $.get('/config/servicio-impresion', function (data) {
            servicio_impresion = data;
            $.get("/gaveta", function (data) {
                enviarAServicioImpresion(servicio_impresion+"?drawer=1&stack="+JSON.stringify(data));
                // enviarAServicioImpresion(servicio_impresion+"?drawer=1&stack="+JSON.stringify(data));
            });
        });
    }
    else{
        $.get("/gaveta", function (data) {
            enviarAServicioImpresion(servicio_impresion+"?drawer=1&stack="+JSON.stringify(data));
            // enviarAServicioImpresion(servicio_impresion+"?drawer=1&stack="+JSON.stringify(data));
        });

    }
}
function imprimiendo(){
    $('.imprimir').attr("disabled", "disabled");
    $('.imprimir .fa').addClass('fa-spin');
}
function doneImprimiendo(){
    $('.imprimir').removeAttr("disabled").removeClass('disabled');
    $('.imprimir .fa').removeClass('fa-spin');
}
function enviarAServicioImpresion(url){
    $.ajax({
        url: url,
        headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
        type: 'GET',
        // This is the important part
        crossDomain: true,
        dataType: "jsonp",
        xhrFields: {
            withCredentials: true,
            
        },
        // This is the important part
        success: function (response) {
            mostrarSuccess('Comanda enviada');
            doneImprimiendo();
            // handle the response
        },
        error: function (xhr, status) {
            // handle errors
            // mostrarError('Error al intentar imprimir');
            doneImprimiendo();
        }
    });
}
function enviarAServicioImpresionPost(url,data){
    $.ajax({
        url: url,
        headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
        type: 'POST',
        crossDomain: true,
        dataType: "jsonp",
        data: {stack: data},
        xhrFields: {
            withCredentials: true,
        },
        success: function (response) {
        },
        error: function (xhr, status) {
        }
    });
}

function preEnviarFormPagar(){
    savePropina();
    var pagaE = parseFloat($("td#paga_efectivo>input").inputmask('unmaskedvalue'));
    var pagaD = parseFloat($("td#paga_debito>input").inputmask('unmaskedvalue'));
    var pagaC = parseFloat($("td#paga_credito>input").inputmask('unmaskedvalue'));
    var cambio = parseFloat($("td#cambio_cambio>input").inputmask('unmaskedvalue'));
    var descuento = parseFloat($("td#descuento table input.curr").inputmask('unmaskedvalue'));
    var num_documento = $("td#num_documento>input").val();
    var banco = $("td#banco>select").val();
    var debe = $("input[name=pago-pendiente]:checked").val();
    cambio = isNaN(cambio)?0:cambio;
    descuento = isNaN(descuento)?0:descuento;
    var viendoOtrosMedios = $('input[name=ver-otros-medios]').is(':checked');
    if($("td#cambio_cambio>input").closest("tr").hasClass('has-error-cambio')){
        cambio = -cambio;
    }
    if(viendoOtrosMedios){
        if(cambio<0){
            if(debe=='1'){

            }
            else{
                alert('Pago inferior al total, marque el pago del pedido como pendiente o revise los pagos.');
                return false;
            }
        }
        else if(cambio>0){
            alert('Pago superior al total, revise los pagos.');
            return false;
        }
    }
    else{
        pagaE = (isNaN(pagaE)?0:pagaE) - (isNaN(cambio)?0:cambio);
    }

    $f = $("#form-pagar");
    $f.find('input[name=paga_efectivo]').val(isNaN(pagaE)?0:pagaE);
    $f.find('input[name=paga_debito]').val(isNaN(pagaD)?0:pagaD);
    $f.find('input[name=paga_credito]').val(isNaN(pagaC)?0:pagaC);
    $f.find('input[name=num_documento]').val(num_documento);
    $f.find('input[name=banco]').val(banco);
    $f.find('input[name=debe]').val(debe);
    $f.find('input[name=descuento]').val(descuento);

    if(esDomicilio){
        var domicilio = 'CAJA';
        var pedido_id = $('meta[name=pedido_id]').attr('content');
        var entregar_en = $('input#entregar_en_').val();
        if(entregar_en=='DOMICILIO'){
            domicilio = $('#observacionesModal #domicilio').val();
            if(domicilio==null||domicilio==''){
                $.alert({
                    title: 'Espere!',
                    icon: 'fa fa-warning',
                    type: 'red',
                    typeAnimated: true,
                    content: '<strong>La dirección es obligatoria para un domicilio!</strong><br/>Haga clic en el botón observaciones para agregar la información faltante',
                });
                return false;
            }
            else{
                domicilio = (encodeURI(domicilio).replace(new RegExp('#', 'g'), '%23'));
                $.get("/pedido/entregar/"+pedido_id+"/"+domicilio, function (data) {
                    mostrarFullLoading();
                    $("#form-pagar").submit();
                });
            }
        }
        else{
            mostrarFullLoading();
            $("#form-pagar").submit();
        }
    }
	else{
        mostrarFullLoading();
		$("#form-pagar").submit();
	}
}

function agregarProductoExpress(jpro){
    var producto_pedido = JSON.parse('{"ingredientes":[], "adicionales":[], "obs":{"tamano":"", "sabor":"", "tipo":"NORMAL", "mix":[], "sin_ingredientes":[]}}');
    producto_pedido.cantidad = jpro.closest("form").find("input#cantidad").val();
    var producto = JSON.parse("{}"); 
    producto.id = jpro.attr("id");
    producto.nombre_tipo = jpro.attr("nombre_tipo");
    producto.nombre = jpro.attr("nombre");
    producto.valor = jpro.attr("unico");
    producto_pedido.producto = producto;
    producto_pedido.force = !validaInventario;
    addProductoPedido(producto_pedido, jpro.closest("form"));
    // actualizarDivPedido();
    // mostrarSuccess('<strong>Listo!</strong> Producto Agregado');
}

function guardarObservacion(){
    var para_llevar = $("#llevar-mesa").is(':checked');
    var campo = $("#observacion-mesa").val();
    if(para_llevar && campo == ''){
        // alert('Si el pedido es para llevar debe establecer la dirección o la persona que recibe.')

        $.alert({
            title: 'Espere!',
            icon: 'fa fa-warning',
            type: 'red',
            typeAnimated: true,
            content: 'Si el pedido es para llevar debe establecer la dirección o la persona que recibe.',
        });
        return false;
    }
    mostrarFullLoading();
    guardarObservacion_(campo,para_llevar);
}

function guardarObservacion_(campo,para_llevar){
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    if(pedido_id == 0){
        return false;
    }
    if(campo == '' || campo == null){
        campo = '-'
    }
    campo = (encodeURI(campo).replace(new RegExp('#', 'g'), '%23'));
    var token = $('meta[name=csrf-token]').attr('content');
    $.post("/pedido/guardar-observacion/"+pedido_id+"/"+campo, {_token: token}, function (data) {
        ocultarFullLoading();
        mostrarSuccess('<strong>Listo!</strong> Observación Guardada');
    });
    
}
function borrarObservacion(){
    guardarObservacion_('-');
}
function toggleOtrosMedios(viendo){
    $('tr.otros-medios-pago').toggle();
    if(viendo){
    }
    else{
        $("td#paga_debito>input").val(0);
        $("td#paga_credito>input").val(0);
        $("td#paga_debito>input").val(0);
        $("td#banco>select").val(null);
    }
    calcularCambio();
}
function calcularCambio(){
    var pagaE = parseFloat($("td#paga_efectivo>input").inputmask('unmaskedvalue'));
    var pagaD = parseFloat($("td#paga_debito>input").inputmask('unmaskedvalue'));
    var pagaC = parseFloat($("td#paga_credito>input").inputmask('unmaskedvalue'));
    var total = parseFloat($("td#cambio_total>input").inputmask('unmaskedvalue'));
    var descuento = parseFloat($("td#descuento table input.curr").inputmask('unmaskedvalue'));
    var propina = parseFloat($("td#propina table input.curr").inputmask('unmaskedvalue'));
    descuento = !isNaN(descuento)?descuento:0;
    propina = !isNaN(propina)?propina:0;
    var cambio = (!isNaN(pagaE)?pagaE:0)+(!isNaN(pagaD)?pagaD:0)+(!isNaN(pagaC)?pagaC:0)-total + descuento - propina;
    if(cambio<0){
        cambio = -cambio;
        $("td#debiendo").show();
        $("td#cambio_cambio>input").closest("tr").addClass('has-error-cambio');
    }
    else{
        $("td#cambio_cambio>input").closest("tr").removeClass('has-error-cambio');
        $("td#debiendo").hide();
    }
    $("td#cambio_cambio>input").inputmask("setvalue", cambio);
}
function calcularDescuento(){
    var pc = parseFloat($('td#descuento .percent').inputmask('unmaskedvalue'));
    if(pc<0){
        $('td#descuento .percent').val(0);
        pc = 0;
    }
    else if(pc>100){
        $('td#descuento .percent').val(100);
        pc = 100;
    }
    var total = parseFloat($("td#cambio_total>input").inputmask('unmaskedvalue'));
    var descuento = total * pc / 100;
    $('td#descuento .curr').val(descuento);
    if(descuento>0){
        $("ul#descuento span.total.valor").html(accounting.formatMoney(descuento,'$',0));
        $("ul#descuento").fadeIn();
    }
    else{
        $("ul#descuento").hide();
    }
    calcularCambio();
}
function calcularDescuento2(){
    var total = parseFloat($("td#cambio_total>input").inputmask('unmaskedvalue'));
    var d2 = $("td#descuento input[name=descuento2]").inputmask('unmaskedvalue');
    d2 = parseFloat(d2);
    $('td#descuento .percent').val(d2 * 100 / total);
    if(d2>0){
        $("ul#descuento span.total.valor").html(accounting.formatMoney(d2,'$',0));
        $("ul#descuento").fadeIn();
    }
    else{
        $("ul#descuento").hide();
    }
    calcularCambio();
}
function calcularPropina(){
    var pc = parseFloat($('td#propina .percent').inputmask('unmaskedvalue'));
    if(pc<0){
        $('td#propina .percent').val(0);
        pc = 0;
    }
    else if(pc>100){
        $('td#propina .percent').val(100);
        pc = 100;
    }
    var total = parseFloat($("td#cambio_total>input").inputmask('unmaskedvalue'));
    $('td#propina .curr').val(total * pc / 100);
    calcularCambio();
}
function calcularPropina2(){
    var total = parseFloat($("td#cambio_total>input").inputmask('unmaskedvalue'));
    var d2 = $("td#propina input[name=propina2]").inputmask('unmaskedvalue');
    d2 = parseFloat(d2);
    $('td#propina .percent').val(d2 * 100 / total);
    calcularCambio();
}
function abrirObservaciones(){
    if(!esDomicilio){
        $('#observacionesModal .row.domicilio').hide();
    }
    else{
        $('#observacionesModal .row.restaurante').hide();
        if(observaciones.entregar_en != '' && observaciones.entregar_en != null){
            if(observaciones.entregar_en == "DOMICILIO" ){
                $('#observacionesModal #domicilio').val(observaciones.entregar_obs);
                $('#observacionesModal input[name=entregar-en][value=DOMICILIO]').prop('checked', true);
            }
            else{
                $('#observacionesModal #cliente').val(observaciones.entregar_obs);
                $('#observacionesModal input[name=entregar-en][value=CAJA]').prop('checked', true);
            }
        }
        else{
            $('#observacionesModal input[name=entregar-en][value=DOMICILIO]').prop('checked', true);
        }
    }
    $('#observacionesModal #domicilio').val(observaciones.domicilio);
    $('#observacionesModal #cliente').val(observaciones.cliente);
    $('#observacionesModal #cliente_id').val(observaciones.clienteId);
    $('#observacionesModal #telefono').val(observaciones.tel);
    $('#observacionesModal #identificacion').val(observaciones.identificacion);
    $('#observacionesModal #para-llevar').prop('checked', observaciones.para_llevar == 'PARA LLEVAR');
    $('#observacionesModal #observacion').val(observaciones.observacion);
    $('#observacionesModal').modal('show');
}
function saveObs() {
    mostrarFullLoading();
    var obs = observaciones;
    var mesa = $('meta[name=mesa]').attr('content');
    var mesero = !(mesa==""||mesa==null||mesa==0||mesa=="0");

    obs.cliente = $('#observacionesModal #cliente').val();
    obs.tel = $('#observacionesModal #telefono').val();
    obs.identificacion = $('#observacionesModal #identificacion').val();
    obs.observacion = $('#observacionesModal #observacion').val();
    obs.domicilio = $('#observacionesModal #domicilio').val();
    obs.cliente = $('#observacionesModal #cliente').val();
    obs.clienteId = $('#observacionesModal #cliente_id').val();
    obs.para_llevar = $('#observacionesModal #para-llevar').is(':checked')?'PARA LLEVAR':'';
    obs.entregar_obs = $('#observacionesModal #domicilio').val();
    if(!mesero){
        obs.entregar_en = $('#observacionesModal input[name=entregar-en]:checked').val();
        if(obs.entregar_en == "DOMICILIO"){

        }
        else{
            obs.entregar_obs = $('#observacionesModal #cliente').val();
        }
    }

    $.post('/pedido/'+$('meta[name=pedido_id]').attr('content')+'/patch',
        {
            tercero_id: obs.clienteId==''?null:obs.clienteId,
            obs: JSON.stringify(obs)
        },
        function (data) {
        $('#observacionesModal').modal('hide');
        ocultarFullLoading();
        mostrarSuccess('Guardado');
    })
}

function isAdmin(admin_role='Administrador'){
    return role == admin_role;
}
function isCajero(cajero_role='Cajero'){
    return role == cajero_role;
}
function savePropina(){
    var propina = $('td#propina .curr').inputmask('unmaskedvalue');
    var old_propina = $('input[name=old_propina]').val();
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    if(parseFloat(propina) == old_propina || !pedido_id){
        return false;
    }
    if(pedido_id){
        $.post('/pedido/'+pedido_id+'/save-propina',
            {
                propina: propina
            },
            function (data) {
                if(data.code == 200){
                    $('input[name=old_propina]').val(propina);
                }
            })
    }
}
function preCancelarPedido(callback){
    confirmar(
        callback,
        'Eliminar pedido',
        'Eliminar pedido',
        'Está seguro que quiere eliminar el pedido? Esta acción es definitiva',
        true
        );
}
function hotKey(key){
    if(!altDown){
        return false;
    }
    var pedido_id = $('meta[name=pedido_id]').attr('content');
    switch (key) {
        case 67:{
            if(isNaN(pedido_id)){
                break;
            }
            impPos(pedido_id);
            break;
        }
        case 82:{
            if(isNaN(pedido_id)){
                break;
            }
            reImprimirComanda(pedido_id);
            break;
        }
        case 71:{
            if(isCajero() || isAdmin()){
                gaveta();
            }
            break;
        }
        case 80:{
            if(isCajero() || isAdmin()){
                $("#modal_pagar").modal('show');
            }
            break;
        }
        case 84:{
            if(isNaN(pedido_id)){
                break;
            }
            preFactura(pedido_id);
            break;
        }
    }
}