const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
$(function() {

    
    $("a.pd").click(function (event) {
        event.preventDefault();
    });
 
    $("select#por_pagina").change(function (){
        reemplazarParamEnURL("por_pagina",$(this).val());
    });

    $("th.agregar_ordenar_por").each(function (i) {
        var campo = $(this).attr("campo");
        var buscar = getParameterByName('buscar');
        if (buscar == null || buscar == "") {
            buscar = "";
        } else {
            buscar = "&buscar=" + buscar;
        }
        $(this).append(' <div class="contenedor ordenar_por"><a href="?ordenar_por=' + campo + '&sentido=desc' + buscar + '"><span class="glyphicon glyphicon-chevron-down"></span></a> <a href="?ordenar_por=' + campo + '&sentido=asc' + buscar + '"><span class="glyphicon glyphicon-chevron-up"></span></a></div>');
    });
    
    $('.manualDisabled').on('click', function(e) {
        return false;
    });
    
});

function reemplazarParamEnURL(param, valor, eager = true, removePage = false){
    var link = window.location.href;
    if((link.indexOf("?") !== -1)){
        valor = "&"+param+"="+valor;
    }
    else{
        valor = "?"+param+"="+valor;
    }
    if(removePage){
        link = removeParam('page', link);
    }
    if(eager){
        link = removeParam(param, link)+valor;
        window.location.href = link;
    }
}

function filtrarTabla(){
    reemplazarParamEnURL("buscar",$("input#buscar").val(), true, true);
}
function addParam(param, value, eager){
    reemplazarParamEnURL(param,value,eager);
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function removeParam(key, url) {
    if (!url) url = window.location.href;
    var rtn = url.split("?")[0],
            param,
            params_arr = [],
            queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function agregarClase(id, clase) {
    
    $( id ).addClass( clase );
    
}

function cleanString(string){
    string = string.replace(/[^\w\s]/gi, '');
//    string = string.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, '');
//    string = string.replace(/[^a-zA-Z0-9]/g, '');
    return string;
}

function fechaHoyHora(hora){
    hora = hora.split(" ");
    if(hora.length != 2){
        return false;
    }
    else{
        var horas = 0;
        var minutos = 0;
        if(hora[1] == "PM"){
            horas = 12;
        }
        hora = hora[0].split(":");
        horas += parseInt(hora[0]);
        minutos += parseInt(hora[1]);
    }
    var date = new Date();
    date.setHours(horas);
    date.setMinutes(minutos);
    date.setSeconds(0);
    return date;
}
/**
 * 
 * @param {type} fecha
 * @param {type} formato
 */
function dateToInputValue(date, formato, input){
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    var hora = date.getHours();
    var minutos = date.getMinutes();
    if(hora<10){
        hora = "0"+hora;
    }
    if(minutos<10){
        minutos = "0"+minutos;
    }
    formato = formato.replace("MM",monthNames[monthIndex]);
    formato = formato.replace("dd",day);
    formato = formato.replace("yyyy",year);
    formato = formato.replace("mm",(monthIndex+1));
    formato = formato.replace("hh",hora);
    formato = formato.replace("ii",minutos);
    input.val(formato);
    
}

function horaMilitarAAPM(hora){
    hora = hora.split(':');
    var horas = hora[0];
    var am = true;
    horas = parseInt(horas);
    if(horas>12){
        horas-=12;
        am = false;
    }
    return horas+":"+hora[1]+" "+(am?"A":"P")+"M";
}

function formatearFecha(date, formato){
    var day = date.getDate();
    var num_mes = date.getMonth()+1;
    var year = date.getFullYear();
    var hora = date.getHours();
    var minutos = date.getMinutes();
    if(hora<10){
        hora = "0"+hora;
    }
    if(minutos<10){
        minutos = "0"+minutos;
    }
    if(num_mes<10){
        num_mes = "0"+num_mes;
    }
    if(day<10){
        day = "0"+day;
    }
    formato = formato.replace("MM",monthNames[num_mes-1]);
    formato = formato.replace("dd",day);
    formato = formato.replace("yyyy",year);
    formato = formato.replace("mm",num_mes);
    formato = formato.replace("hh",hora);
    formato = formato.replace("ii",minutos);
    return (formato);
    
}

$.fn.extend({
    treed: function (o) {

        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';

        if (typeof o != 'undefined') {
            if (typeof o.openedClass != 'undefined') {
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined') {
                closedClass = o.closedClass;
            }
        }
        ;

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function () {
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass: 'glyphicon-folder-open', closedClass: 'glyphicon-folder-close'});

$('#tree3').treed({openedClass: 'glyphicon-chevron-right', closedClass: 'glyphicon-chevron-down'});


var monthNames = [
  "Enero", "Febrero", "Marzo",
  "Abril", "Mayo", "Junio", "Julio",
  "Agosto", "Septiembre", "Octubre",
  "Noviembre", "Diciembre"
];

function nombreMesNumero(mes){
    for(var i=0; i<monthNames.length; i++){
        if(monthNames[i]==mes){
            return i+1;
        }
    }
    return 0;
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}


function mostrarSuccess(mensaje){
    toastr.options = {
        "positionClass": "toast-bottom-full-width",
        "newestOnTop": true,
        "escapeHtml ": false,
    }
    toastr.success(mensaje);
}
function mostrarInfo(mensaje){
    toastr.options = {
        "positionClass": "toast-bottom-full-width",
        "newestOnTop": true,
        "escapeHtml ": false,
    }
    toastr.info(mensaje);
}
function mostrarError(mensaje){
    toastr.options = {
        "positionClass": "toast-bottom-full-width",
        "newestOnTop": true,
        "escapeHtml ": false,
    }
    toastr.error(mensaje);
    
}
function mostrarWarning(mensaje){
    toastr.options = {
        "positionClass": "toast-bottom-full-width",
        "newestOnTop": true,
        "escapeHtml ": false,
    }
    toastr.warning(mensaje);
    // mostrarAlerta('warning',mensaje);
    
}
function mostrarAlerta(tipo, mensaje){
    var bot = parseInt($("div#contenedor-alertas-fijas").css('bottom'));
    $("div#contenedor-alertas-fijas").prepend("<div class='alert alert-"+tipo+"'>"+mensaje+"</div>").removeClass('cerrado');
    setTimeout(function(){
        ocultarMensaje();
    }, 4000);
}

function ocultarMensaje(){
    var bot = parseInt($("div#contenedor-alertas-fijas").css('bottom'));
    $("div#contenedor-alertas-fijas").css('bottom',bot-62);
}

function mostrarFullLoading(){
    $('div#fullscreen-loading').removeClass('z-index-100');
    $('div#fullscreen-loading').css('z-index', 9999);
}
function ocultarFullLoading(){
    $('div#fullscreen-loading').css('z-index', -100);
}

function confirmar(onConfirm, title='título', buttonText='texto botón', html="contenido", inverse=false){
    $.confirm({
        title: title,
        type: 'red',
        typeAnimated: true,
        columnClass: 'col-md-8 col-md-offset-2',

        content: html,
        boxWidth: '600px',
        icon: 'fa fa-warning',
        buttons: {
            confirm: {
                btnClass: inverse?'btn-red':'btn-blue',
                text: buttonText,
                action: onConfirm
            },
            cancel: {
                btnClass: inverse?'btn-blue':'btn-red',
                text: 'Volver'
            },
        }
    });
}

function getTamanosLabel(tamano='unico', toUpper=false){
    switch (tamano) {
        case 'unico': tamano = 'único'; break;
        case 'pequeno': tamano = 'pequeño'; break;
        case 'porcion': tamano = 'porción'; break;
    }
    if(toUpper) {
        tamano = tamano.toUpperCase();
    }
    return tamano;
}
function getTamanosLabelMin(tamano='unico', toUpper=false){
    switch (tamano) {
        case 'unico': tamano = ''; break;
        case 'grande': tamano = 'gr.'; break;
        case 'extrag': tamano = 'xgr.'; break;
        case 'mediano': tamano = 'med.'; break;
        case 'pequeno': tamano = 'peq.'; break;
        case 'porcion': tamano = 'por.'; break;
    }
    if(toUpper) {
        tamano = tamano.toUpperCase();
    }
    return tamano;
}
function diaDelMesActual(day){
    var date = new Date();
    date.setDate(day);
    return diaDeLaSemana(date);
}
function diaDeLaSemana(date){
    return diasSemana[date.getDay()];
}
function enviarAServicioImpresionPost(url,data,drawer=0){
    var np = url=='NP';
    var fullUrl = url+'/post.php?drawer='+drawer;
    if(np){
        fullUrl = '/np.php?drawer='+drawer;
    }
    $.ajax({
        url: fullUrl,
        headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
        type: 'POST',
        crossDomain: true,
        dataType: np?"html":"json",
        data: {stack: data},
        xhrFields: {
            withCredentials: true,
        },
        success: function (response) {
            doneImprimiendo();
            if(np){
                var newWindow = window.open("", "new window", "width=500, height=600");
                try {
                    var tagsToDestroy = newWindow.document.querySelector('html');
                    newWindow.document.removeChild(tagsToDestroy);
                } catch (error) {}
                newWindow.document.write(response);
            }
        },
        error: function (xhr, status) {
            doneImprimiendo();
        }
    });
}
function doneImprimiendo(){
    $(".busy").attr('disabled',false);
    $('.imprimir').removeAttr("disabled").removeClass('disabled');
    $('.imprimir .fa').removeClass('fa-spin');
}