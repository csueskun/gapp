$(function () {
    $("select#tipo_producto_id").change(function (){
        var tipo_id = $(this).val();
        if(tipo_id==0||tipo_id==""||tipo_id==null){
            $("input#tipo_producto_id_").val("");
            $("section.con-tamanos").hide();
            $("section.sin-tamanos").hide();
        }
        else{
            $("input#tipo_producto_id_").val(tipo_id);
            if($(this).find("option:selected").attr("aplica_tamanos")==="1"){
                //$("section.con-tamanos").show();
                //$("section.sin-tamanos").hide();
                cargarAdicionalesPorTipoProductoConTamano(tipo_id);
            }
            else{
                //$("section.con-tamanos").hide();
                //$("section.sin-tamanos").show();
                cargarAdicionalesPorTipoProducto(tipo_id);
            }
        }
        $("input#tipo_producto_id_").blur();
    });
    $(document).on("change", "form#form-adicionales input:checkbox", function (){
        var ingrediente_id = $(this).val();
        $("input#valor-"+ingrediente_id).prop('required', function(i, v) { return !v; });
        $("input#valor-"+ingrediente_id).prop('disabled', function(i, v) { return !v; });
        
        var attr = $("input#valor-" + ingrediente_id).attr('disabled');
        if (typeof attr !== typeof undefined && attr !== false) {
            var input = $("form#form-adicionales").find("input#valor-" + ingrediente_id);
            input.val("");
            input.closest("td").removeClass("has-error has-danger has-feedback has-success");
            input.closest("td").find("div.help-block").html('');
        }
    });
    // $("form#form-adicionales input[type='number']").on("keyup",function (){
    //     $("form#form-adicionales").validator('validate');
    //     $(this).focus();
    //     if(!$("form#form-adicionales").find('.has-error').length){
    //        $(this).closest("td").addClass("has-feedback has-success");
    //         $(this).closest("td").find("div.help-block").html('');
    //     }
    // });
    
    
});

function cargarAdicionalesPorTipoProducto(id){
    $("button").prop('disabled', true);
    $.get( "/adicional/tipo_producto/"+id)
    .done(function (data) {
        cargarAdicionalesConSinTamano(data);
        //cargarAdicionales(data);
        $("button").prop('disabled', false);
    });
}
function cargarAdicionalesPorTipoProductoConTamano(id){
    $("button").prop('disabled', true);
    $.get( "/adicional/tipo_producto/"+id)
    .done(function (data) {
        cargarAdicionalesConSinTamano(data);
        $("button").prop('disabled', false);
        //cargarAdicionalesConTamano(data);
    });
}

function cargarAdicionales(adicionales){
    for(var i=0;i<adicionales.length;i++){
        $("form#form-adicionales input#ingrediente-"+adicionales[i].ingrediente_id).prop('checked', true);
        var input = $("form#form-adicionales input#valor-"+adicionales[i].ingrediente_id);
        input.val(Math.floor(adicionales[i].valor));
        input.prop('required', function(i, v) { return !v; });
        input.prop('disabled', function(i, v) { return !v; });
    }
}
function cargarAdicionalesConTamano(adicionales){
    for(var i=0;i<adicionales.length;i++){
        var tamano = 's';
        if(adicionales[i].tamano==='GRANDE'){
            tamano = 'g';
        }
        if(adicionales[i].tamano==='EXTRAG'){
            tamano = 'xg';
        }
        else if(adicionales[i].tamano==='MEDIANO'){
            tamano = 'm';
        }
        else if(adicionales[i].tamano==='PEQUEÑO'){
            tamano = 'p';
        }
        $("form#form-adicionales input#"+adicionales[i].ingrediente_id+"-"+tamano).prop('checked', true);
        var input = $("form#form-adicionales input#valor-"+adicionales[i].ingrediente_id+"-"+tamano);
        input.val(Math.floor(adicionales[i].valor));
        input.prop('required', function(i, v) { return !v; });
        input.prop('disabled', function(i, v) { return !v; });
    }
}

function cargarAdicionalesConSinTamano(adicionales){
    $("table#adicionales input").val('');
    for(var i=0;i<adicionales.length;i++){
        $("table#adicionales tr#"+adicionales[i].ingrediente_id+" td[tamano="+adicionales[i].tamano+"] input.valor").val(Math.floor(adicionales[i].valor));
        $("table#adicionales tr#"+adicionales[i].ingrediente_id+" td[tamano="+adicionales[i].tamano+"] input.cantidad").val((adicionales[i].cantidad));
    }
}

function abstraerAdicionales(){
    var form_adicionales = $("form#form-adicionales");
    var adicionales = $.map(form_adicionales.find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    var adicional = [];
    for(var i=0;i<adicionales.length;i++){
        var valor = $("form#form-adicionales").find("input#valor-"+adicionales[i]).val();
        var descripcion = "ADICIONAL DE "+$("form#form-adicionales").find("input#valor-"+adicionales[i]).attr("nombre")+" PARA "+$("select#tipo_producto_id option:selected").attr("nombre");
        adicional.push({ingrediente_id:adicionales[i], tipo_producto_id:$("select#tipo_producto_id").val(), descripcion:descripcion, valor:valor});
    }
    return adicional;
}
function abstraerAdicionales2(){
    var form_adicionales = $("form#form-adicionales");
    var adicionales = $.map(form_adicionales.find('input:checkbox:checked'), function (e, i) {
        return e.value;
    });
    var adicional = [];
    for(var i=0;i<adicionales.length;i++){
        var valor = $("form#form-adicionales").find("input#valor-"+adicionales[i]).val();
        var descripcion = "ADICIONAL DE "+$("form#form-adicionales").find("input#valor-"+adicionales[i]).attr("ingrediente-nombre")+" PARA "+$("select#tipo_producto_id option:selected").attr("nombre") + " "+$("form#form-adicionales").find("input#valor-"+adicionales[i]).attr("nombre");
        adicional.push({ingrediente_id:adicionales[i].split("-")[0], tipo_producto_id:$("select#tipo_producto_id").val(), descripcion:descripcion, valor:valor, tamano: $("form#form-adicionales").find("input#valor-"+adicionales[i]).attr("nombre")});
    }
    return adicional;
}
function abstraerAdicionales3(){
    var adicionales = [];
    var tamanos = $("table#adicionales tbody tr td input.valor");
    tamanos.each(function(){
        if($(this).val() != ''){
            var valor = $(this).val();
            var tamano = $(this).closest('td').attr('tamano');
            var cantidad = $(this).closest('td').find('input.cantidad').val();
            if(cantidad == ''){
                cantidad = 0;
            }
            var descripcion = "ADICIONAL DE "+$(this).attr("nombre")+" PARA "+$("select#tipo_producto_id option:selected").attr("nombre")+" "+tamano;
            adicionales.push({ingrediente_id:$(this).closest('tr').attr('id_ingrediente'), tipo_producto_id:$("select#tipo_producto_id").val(), descripcion:descripcion, valor:valor, cantidad:cantidad, tamano: tamano});
        }
    });
    return adicionales;
}

function guardarAdicionales(){
    $("button").prop('disabled', true);
    mostrarWarning("Guardando adicionales.");
    if(!validarTodo()){
        mostrarError("No se guardaron los ingredientes.");
        return false;
    }

    var adicionales = abstraerAdicionales3();

    $.post( "/adicionales/guardar", {data: adicionales})
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
        }
        else{
        }
        mostrarSuccess("Guardados.");
        $("button").prop('disabled', false);
    });
    // $.get( "/adicionales/guardar/"+JSON.stringify(adicionales))
    // .done(function (data) {
    //     if(data["errors"]==null || data["errors"]==""){
    //     }
    //     else{
    //     }
    //     mostrarSuccess("Guardados.");
    // });
}

function validarTodo(){
    var valida = true;
    var form_tipo_producto = $("#form-tipo-producto");
    form_tipo_producto.validator('validate');
    valida = valida&&(!form_tipo_producto.find('.has-error').length);
    
    /***********Validando Adicionales****************/
    
    var form_adicionales = $("form#form-adicionales");
    form_adicionales.validator('destroy');
    var adicionales = $.map(form_adicionales.find('input:checkbox:checked'), function (e, i) {
            return e.value;
    });
    for(var i=0;i<adicionales.length;i++){
        var input = form_adicionales.find("input#valor-"+adicionales[i]);
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


function crearTipoProducto(){
    $.post( "/tipo_producto/crearModal", $("div#modal-tipo-producto form").serialize() )
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            agregarTipoProducto(data);
        }
        validarFormModal($("div#modal-tipo-producto form"), data);
    });
}

function crearIngrediente(){
    $.post( "/ingrediente/crearModal", $("div#modal-ingrediente form").serialize() )
    .done(function (data) {
        if(data["errors"]==null || data["errors"]==""){
            console.log(data);
            var html = "<tr style=\"\" id="+data.id+" id_ingrediente="+data.id+">\n" +
                "    <td class=\"font bebas\" style=\"font-size: 16px\">\n" +
                "        "+data.descripcion+"\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"UNICO\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"GRANDE\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"EXTRAG\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"MEDIANO\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"PEQUENO\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "    <td class=\"form-group\" tamano=\"PORCION\" style=\"padding-left: 8px;\">\n" +
                "        <div class=\"input-group\">\n" +
                "            <span class=\"input-group-addon\">$</span>\n" +
                "            <input type=\"number\" min=\"0\" max=\"999999999\" class=\"align-right form-control valor\" id=\"valor-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "            <span class=\"input-group-addon\">"+data.unidad+"</span>\n" +
                "            <input type=\"number\" step=\"0.01\" min=\"0\" max=\"999999999\" class=\"align-right form-control cantidad\" id=\"cantidad-"+data.id+"\" nombre=\""+data.descripcion+"\" name=\"valor\" style=\"max-width: 95px;\">\n" +
                "        </div>\n" +
                "    </td>\n" +
                "</tr>";
            $('table#adicionales tbody').prepend(html);
        }
        validarFormModal($("div#modal-ingrediente form"), data);
    });
}

function crearSabor(){
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
        form.find("div.mensaje").append('<div class="alert alert-success" role="alert"> <strong><span class = "glyphicon glyphicon-ok-sign" > </span></strong>Ingrediente Agregado.</div>');
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
    var html = '<option value="'+tipo_producto.id+'" nombre="'+tipo_producto.descripcion+'">'+tipo_producto.descripcion+'</option>';
    $("select#tipo_producto_id").append(html);
}
function agregarIngredienteYAdicional(ingrediente){
    var html = '';
    var html_adicional = '';

    html+= '<label class="radio-inline">';
    html+= '    <input type="checkbox" name="ingrediente" value="'+ingrediente.id+'"/>'+ingrediente.descripcion;
    html+= '</label>';

    html_adicional += '<table class="adicional"><tbody><tr style="height: 43px"><td>';
    html_adicional += '<div class="form-group"><label class="radio-inline control-label">';
    html_adicional += '<input type="checkbox" value="' + ingrediente.id + '" name="ingrediente">' + ingrediente.descripcion;
    html_adicional += '</label></div></td><td class="form-group" style="padding-left: 8px;">';
    html_adicional += '<div class="input-group"><span class="input-group-addon">$</span>';
    html_adicional += '<input disabled type = "number" min="0" max="999999999" class = "align-right form-control" nombre="' + ingrediente.descripcion + '" id = "valor-' + ingrediente.id + '" name = "valor" style="max-width: 95px;">';
    html_adicional += '</div><div class="help-block with-errors"></div></td></tr>';
    html_adicional += '</tbody></table>';
        
    $("div#ingredientes").append(html);
    $("form#form-adicionales").append(html_adicional);
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


function cargarSabores(sabores){
    for(var i=0;i<sabores.length;i++){
        $("div#sabores input#sabor-"+sabores[i].id).prop('checked', true);
    }
}
