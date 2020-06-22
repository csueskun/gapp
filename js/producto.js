$(function () {
    $("select#tipo_producto_id").change(function (){
        var tipo_id = $(this).val();
        if(tipo_id==0||tipo_id==""||tipo_id==null){
            $("input#tipo_producto_id_").val("");
        }
        else{
            $("input#tipo_producto_id_").val(tipo_id);
            
        }
        $("input#tipo_producto_id_").blur();
        $("#form-producto").validator('destroy');
    });
    $(document).on("change", "form#form-tamanos input:checkbox", function (){
        var tamano = $(this).val();
        $("input#tamano-"+tamano).prop('required', function(i, v) { return !v; });
        $("input#tamano-"+tamano).prop('disabled', function(i, v) { return !v; });
        
        if($(this).is(":checked")){
            if (tamano === "u") {
                $("form#form-tamanos input").removeAttr("required");
                $("form#form-tamanos input").removeAttr("disabled");
                $("form#form-tamanos input").not(this).removeAttr("checked");
                $("form#form-tamanos input[type=number]:not(#tamano-u)").attr("disabled", "disabled");
                $("form#form-tamanos input[type=number]:not(#tamano-u)").val("");
            } else {
                $("form#form-tamanos input#u").not(this).removeAttr("checked");
                $("form#form-tamanos input#tamano-u").removeAttr("required");
                $("form#form-tamanos input#tamano-u").val("");
                $("form#form-tamanos input#tamano-u").attr("disabled", "disabled");
            }
        }
        else{
            if (tamano === "u") {
                $("form#form-tamanos input#u").prop("checked", true);
                $("form#form-tamanos input#tamano-u").removeAttr("disabled");
                $("form#form-tamanos input#tamano-u").attr("required", "required");
            }
        }
        var attr = $("input#tamano-" + tamano).attr('disabled');
        if (typeof attr !== typeof undefined && attr !== false) {
            var input = $("form#form-tamanos").find("input#tamano-" + tamano);
            input.val("");
            input.closest("td").removeClass("has-error has-danger has-feedback has-success");
            input.closest("td").find("div.help-block").html('');
        }
    });
    $("form#form-tamanos input[type='number']").on("keyup",function (){
        $("form#form-tamanos").validator('validate');
        $(this).focus();
        if(!$("form#form-tamanos").find('.has-error').length){
//            $(this).closest("td").addClass("has-feedback has-success");
            $(this).closest("td").find("div.help-block").html('');
        }
    });
    
    
});

function abstraerProducto(){
    var form_producto = $("#form-producto");
    var form_adicionales = $("form#form-tamanos");
    var tamanos = $.map(form_adicionales.find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    var producto_json = '{}';
    producto_json = JSON.parse(producto_json);
    producto_json._token = form_producto.find("input[name=_token]").val();
    // producto_json.valor = form_producto.find("input[name=valor]").val();
    producto_json.descripcion = form_producto.find("input[name=descripcion]").val();
    producto_json.grupo = form_producto.find("input[name=grupo]").val();
    producto_json.detalle = form_producto.find("#detalle").val();
    producto_json.imagen = form_producto.find("input[name=imagen]").val();
    producto_json.tipo_producto_id = $("select#tipo_producto_id").val();
    producto_json.terminado = $("input#terminado").is(":checked");
    producto_json.comanda = $("input#imprime").is(":checked");
    producto_json.compuesto = $("select#compuesto").val();
    producto_json.iva = $("input#iva").val();
    producto_json.impco = $("input#impco").val();
    producto_json.tipo_producto_nombre = $("select#tipo_producto_id option:selected").attr("nombre");
    producto_json.ingredientes = $.map($("div#ingredientes").find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });

    for(var i=0;i<producto_json.ingredientes.length;i++){
        producto_json.ingredientes[i] = ingredienteInventarioJson(producto_json.ingredientes[i]);
    }


    producto_json.sabores = $.map($("div#sabores").find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    
    producto_json.tamanos = [];
    for(var i=0;i<tamanos.length;i++){
        var valor = $("form#form-tamanos").find("input#tamano-"+tamanos[i]).val();
        var nombre = $("form#form-tamanos").find("input#tamano-"+tamanos[i]).attr("nombre");
        producto_json.tamanos.push({tamano:nombre   , valor:valor});
    }
    return producto_json;
}

function ingredienteInventarioJson(id){
    var tamanos = [];
    $("table#unidades_ingredientes tr#in_"+id+" input").each(function(){
        
        if($(this).val()=='' || $(this).val() == null){
            tamanos.push({tamano:$(this).attr('nombre'),cantidad: 0.0});
        }
        else{
            tamanos.push({tamano:$(this).attr('nombre'),cantidad: $(this).val()});
        }
    });
    return {ingrediente: id, inventario: tamanos};
}

function guardarNuevoProduto(){

    if(!validarTodo()){
        mostrarWarning('Hay campos pendientes por diligenciar.')
        return false;
    }
    var producto_json = abstraerProducto();
    mostrarFullLoading();
    $.get( "/producto/crearCompleto/"+JSON.stringify(producto_json))
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            window.location.href = '/producto/agregar'
        }
        else{
            validarFormModal($("form#form-producto"), data);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $(".boton-agregar-producto").prop('disabled', false);
            ocultarFullLoading();
        }
    });
}
function editarProduto(){
    if(!validarTodo()){
        mostrarWarning('Hay campos pendientes por diligenciar.')
        return false;
    }
    mostrarFullLoading();
    var producto_json = abstraerProducto();
    producto_json.id = $("input#id").val();
    $.post("/producto/editarCompleto/"+JSON.stringify(producto_json))
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            $("html, body").animate({ scrollTop: 0 }, "slow");
            location.reload();
        }
        else{
            validarFormModal($("form#form-producto"), data);
            ocultarFullLoading();
        }
    });
}

function validarTodo(){
    var form_producto = $("#form-producto");
    form_producto.validator('validate');
    var valida = !form_producto.find('.has-error').length;
    var form_tipo_producto = $("#form-tipo-producto");
    form_tipo_producto.validator('validate');
    valida = valida&&(!form_tipo_producto.find('.has-error').length);
    
    /***********Validando Adicionales****************/
    var form_adicionales = $("form#form-tamanos");
    form_adicionales.validator('destroy');
    var tamanos = $.map(form_adicionales.find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    if(tamanos.length === 0){
        return false;
    }
    for(var i=0;i<tamanos.length;i++){
        var input = form_adicionales.find("input#tamano-"+tamanos[i]);
        var valor = input.val();
        if(valor==null||valor==""){
            input.closest("td").addClass("has-error has-danger has-feedback");
            input.closest("td").find("div.help-block").html('<ul class="list-unstyled"><li>Completa este campo</li></ul>');
            valida = false;
        }
    }
    form_adicionales.validator('validate');
    valida = valida && (!form_adicionales.find('.has-error').length);
    if(!valida){
        return false;
    }
    return true;

}


function crearTipoProducto(form){
    var fracciones = JSON.parse("{}");
    fracciones = $.map($("div#fracciones").find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    $.post( "/tipo_producto/crearModal", $("div#modal-tipo-producto form").serialize()+"&fracciones="+JSON.stringify(fracciones) )
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            agregarTipoProducto(data);
        }
        validarFormModal($("div#modal-tipo-producto form"), data);
    });
}

function crearIngrediente(form){
    $.post( "/ingrediente/crearModal", $("div#modal-ingrediente form").serialize() )
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            agregarIngredienteYAdicional(data);
        }
        validarFormModal($("div#modal-ingrediente form"), data);
    });
}

function crearSabor(form){
    $.post( "/sabor/crearModal", $("div#modal-sabor form").serialize() )
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            agregarSabor(data);
        }
        validarFormModal($("div#modal-sabor form"), data);
    });
}

function validarFormModal(form, data){
    if(data["errors"]==null || data["errors"]==""){
        form.find("div.mensaje").append('<div class="alert alert-success" role="alert"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong><span class = "glyphicon glyphicon-ok-sign" > </span></strong>Registro Agregado.</div>');
        form.trigger("reset");
    }
    else{
        form.find("div.mensaje").html("");
        var mensajes = { 
            "validation.required":"Campo Obligatorio.",
            "validation.unique":"Campo Repetido." 
        }
        
        data = data["errors"];
        var keys = [];
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                keys.push(key);
            }
        }
        for(var i=0;i<keys.length;i++){
            var input = form.find("input[name="+keys[i]+"]");
            var mensaje = mensajes[data[keys[i]][0]];
            input.closest("div.form-group").addClass("has-error");
            input.parent().find("div.help-block").html(mensaje);
        }
    }
}

function agregarTipoProducto(tipo_producto){
    var html = '<option value="'+tipo_producto.id+'" aplica_tamanos="'+tipo_producto.aplica_tamanos+'" aplica_sabores="'+tipo_producto.aplica_sabores+'" aplica_ingredientes="'+tipo_producto.aplica_ingredientes+'" nombre="'+tipo_producto.descripcion+'">'+tipo_producto.descripcion+'</option>';
    $("select#tipo_producto_id").append(html);
}
function agregarIngredienteYAdicional(ingrediente){
    var html = '';
    var html_adicional = '';

    html+= '<div class="col-lg-3 col-md-4 col-sm-6">';
    html+= '<label class="radio-inline font bebas" style="margin-bottom: 10px">';
    html+= '    <input type="checkbox" name="ingrediente" value="'+ingrediente.id+'" id="ingrediente-'+ingrediente.id+'"/>'+ingrediente.descripcion;
    html+= '</label>';
    html+= '</div>';
    
    
    html_inventario = '' +
        '<tr id=\'in_'+ingrediente.id+'\' style="display: none">' +
        '                        <th class="font bebas" style="vertical-align: initial; font-size: 1.2em; padding-right: 4px; text-align: right">' +
        '                            '+ingrediente.descripcion+'' +
        '                        </th>' +
        '                        <td class="form-group tamano unico" style="padding-left: 8px; display: none" style=" display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="unico" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-unico" name = "'+ingrediente.id+'-unico" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                        <td class="form-group tamano grande" style="padding-left: 8px; display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="grande" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-grande" name = "'+ingrediente.id+'-grande" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                        <td class="form-group tamano extrag" style="padding-left: 8px; display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="extrag" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-extrag" name = "'+ingrediente.id+'-extrag" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                        <td class="form-group tamano mediano" style="padding-left: 8px; display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="mediano" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-mediano" name = "'+ingrediente.id+'-mediano" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                        <td class="form-group tamano pequeno" style="padding-left: 8px; display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="pequeno" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-pequeno" name = "'+ingrediente.id+'-pequeno" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                        <td class="form-group tamano porcion" style="padding-left: 8px; display: none">' +
        '                            <div class="input-group">' +
        '                                <input nombre="porcion" type = "number" max="999999999" step="0.01" class = "align-right form-control" id = "tamano-porcion" name = "'+ingrediente.id+'-porcion" style="max-width: 95px;">' +
        '                                <span class="input-group-addon">'+ingrediente.unidad+'</span>' +
        '                            </div>' +
        '                            <div class="help-block with-errors"></div>' +
        '                        </td>' +
        '                    </tr>';

    html_adicional += '<table class="adicional"><tbody><tr style="height: 43px"><td>';
    html_adicional += '<div class="form-group"><label class="radio-inline control-label">';
    html_adicional += '<input type="checkbox" value="' + ingrediente.id + '" name="ingrediente">' + ingrediente.descripcion;
    html_adicional += '</label></div></td><td class="form-group" style="padding-left: 8px;">';
    html_adicional += '<div class="input-group"><span class="input-group-addon">$</span>';
    html_adicional += '<input disabled type = "number" min="0" max="999999999" class = "align-right form-control" nombre="' + ingrediente.descripcion + '" id = "valor-' + ingrediente.id + '" name = "valor" style="max-width: 95px;">';
    html_adicional += '</div><div class="help-block with-errors"></div></td></tr>';
    html_adicional += '</tbody></table>';
        
    $("div#ingredientes").append(html);
    $("table#unidades_ingredientes").append(html_inventario);
    $("form#form-adicionales").append(html_adicional);
    mostrarColumnasInventario();
}
function agregarSabor(sabor){
    var html = '<label class="radio-inline">';
    html+= '    <input type="checkbox" value="'+sabor.id+'" name="sabor">'+sabor.descripcion;
    html+= '</label>';
    $("div#sabores").append(html);
}

function cargarIngredientes(ingredientes){
    for(var i=0;i<ingredientes.length;i++){
        $("div#ingredientes input#ingrediente-"+ingredientes[i].id).prop('checked', true);
    }
}

function cargarTamanos(tamanos){
    for(var i=0;i<tamanos.length;i++){
        $("form#form-tamanos input[nombre="+tamanos[i].tamano+"_]").prop('checked', true);
        var input = $("form#form-tamanos input[nombre="+tamanos[i].tamano+"]");
        input.val(Math.floor(tamanos[i].valor));
        input.prop('required', function(i, v) { return !v; });
        input.prop('disabled', function(i, v) { return !v; });
    }
    mostrarInventario();
    mostrarColumnasInventario();
}

function cargarSabores(sabores){
    for(var i=0;i<sabores.length;i++){
        $("div#sabores input#sabor-"+sabores[i].id).prop('checked', true);
    }
}
function updateTipo(id,element, actual) {
    mostrarFullLoading();
    var selected = $(element).find('option:selected').val();
    if(selected == actual){
        return false;
    }
    $.post('/producto/'+id,{tipo_producto_id: selected},function (data) {
        location.reload();
    });
}