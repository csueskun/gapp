var comboSelected = {};
var addingCombo = false;
var comboCampos = ['id', 'nombre', 'precio'];
$(function () {
    $('combo').click(function () {
        setComboActive(this);
        abstraerCombo(this);
        resetProductosCombo(this);
        resetAcciones();
        setSpinnerValue();
        focusDetalles();
    })
})

function setComboActive(e){
    $('combo').removeClass('active');
    $(e).toggleClass('active');
}

function abstraerCombo(e){
    comboCampos.forEach(function (campo) {
        comboSelected[campo] = $(e).attr(campo);
    })
}
function resetAcciones(){
    $('table#orden-accion').fadeIn(500);
    $('table#orden-accion input#cantidad').val(1);
}
function resetProductosCombo(e){
    $('div.contenedor-productos-combo').hide();
    $(`div#${comboSelected.id}.contenedor-productos-combo`).fadeIn(500);
}
function focusDetalles(){
    var aTag = $("#"+comboSelected.id+".contenedor-productos-combo");
    $('html,body').animate({scrollTop: aTag.offset().top - 50},'slow');
}
function addCombo($button){
    var $comboSelected = $button.closest('div#collapse-combos').find('combo.active');
    var selectedComboNombre = $comboSelected.find('div>span').html();
    var selectedComboId = $comboSelected.attr('id');
    var $productosCombo = $(`productos-combo[combo-id=${selectedComboId}]`);
    var productosCombo = [];
    var comboId = new Date().getTime();
    $productosCombo.find('producto').each(function (i) {
        var sabor = $(this).find('div.sabores>label.sabor>input:checked').attr('nombre');
        productosCombo.push({
            comboId: comboId,
            comboPrecio: comboSelected.precio,
            comboNombre: selectedComboNombre,
            comboCantidad: $('div#collapse-combos .number-spinner input').val(),
            esComboProducto: true,
            nombre: $(this).find('li.nombre-producto').attr('nombre'),
            ingredientes: $.map($(this).find('div.ingredientes>label.ingrediente>input:checked'), function(ingrediente){
                return {
                    id: ingrediente.getAttribute('id'),
                    descripcion: ingrediente.getAttribute('des'),
                    cantidad: ingrediente.getAttribute('cantidad'),
                    unidad: ingrediente.getAttribute('unidad'),
                }
            }),
            adicionales: [],
            alias: "",
            cantidad: $('div#collapse-combos .number-spinner input').val(),
            force: true,
            producto: {
                id: $(this).attr('producto-id'),
                nombre_tipo: $(this).attr('nombre-tipo'),
                nombre: $(this).attr('nombre'),
                valor: $(this).attr('valor')
            },
            obs: {
                tamano: $(this).attr('tamano'),
                tipo: "NORMAL",
                mix: [],
                sin_ingredientes: $.map($(this).find('div.ingredientes>label.ingrediente>input:not(:checked)'), function(ingrediente){
                    return {
                        id: ingrediente.getAttribute('id'),
                        descripcion: ingrediente.getAttribute('des'),
                        cantidad: ingrediente.getAttribute('cantidad'),
                        unidad: ingrediente.getAttribute('unidad'),
                    }
                }),
                compuesto: [],
                sabor: sabor?sabor:null
            },
        });
    });
    /*
    for(var ii=0; ii<productosCombo.length; ii++ ){
        mostrarFullLoading();
        addProductoPedido(productosCombo[ii], $button, ii == productosCombo.length - 1, ii == 0);
    }
    */
   addProductoPedido(productosCombo, $button, false, true, true);
}
function cancelarCombo(ref){
    var ppCount = $('ul#ul-pedido>li').length;
    mostrarFullLoading();
    if(ppCount>1){
        $.post("/producto-pedido/borrar-combo/"+ref, {_token: $('meta[name=csrf-token]').attr('content')}, function (data) {
            if(data>0){
                mostrarSuccess('Combo eliminado');
            }
            else{
                mostrarError('No se pudo borrar el combo')
            }
            actualizarDivPedido();
            ocultarFullLoading();
        });
    }
    else{
        cancelarPedido();
    }
}
function setSpinnerValue(){
    $('div#collapse-combos .number-spinner-valor input').val(comboSelected.precio * 100 / 100);
}
function printCombo(combo, pedido_activo){
    var detalles = [];
    for(i in combo.productosCombo){
        var comboProducto = combo.productosCombo[i];
        if(comboProducto.sin.length > 0){
            detalles.push(`${comboProducto.nombre} SIN ${comboProducto.sin.join(', ')}`);
        }
        if(comboProducto.sabor && comboProducto.sabor!=''){
            detalles.push(`${comboProducto.nombre} SABOR ${comboProducto.sabor}`);
        }
    }
    if(detalles.length>0){
        detalles = detalles.join(' ) ( ');
        detalles = `<span class="detalles">( ${detalles} )</span>`;
    }
    else{
        detalles = '';
    }
    var cantidad = '';
    if(parseInt(combo.cantidad)>1){
        cantidad = `<span style="color: #5cb85c">${combo.cantidad}x </span>`;
        combo.precio = parseFloat(combo.precio) * parseInt(combo.cantidad);
    }
    var html = `
                <li class="list-group-item">
                    <span class="producto">${cantidad}${combo.nombre_combo} </span>
                    ${detalles}
                    <div class="spaceholder-valor-item">___________________</div>
                    <div class="btn-group items">`;
    if(pedido_activo){
        html += `<span onclick="cancelarCombo(${combo.ref});" class="btn btn-danger total fa fa-trash boton-cancelar"></span>`;
    }
    return html += `
                    <span class="btn btn-success valor" valor="${parseFloat(combo.precio)}">${accounting.formatMoney(combo.precio,'$',0)}</span></div></li>`;
}