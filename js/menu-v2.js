
$(function () {
    $("button[tipo_producto_id=combo]").removeClass('hidden');
    setTimeout(function(){
        try {
            $($('button.tipo_producto.titulo')[1]).trigger('click');
        } catch (error) {        
            $($('button.tipo_producto.titulo')[0]).trigger('click');
        }
    }, 500);
    $('button.tipo_producto.titulo').on('click', function(e){
        // if($(e.currentTarget).attr('aria-expanded')){
        //     return false;
        // }
        $('.sub-menu-comidas div.panel-collapse.tipo_producto.in').toggle();
        $('.sub-menu-comidas div.panel-collapse.tipo_producto.in').removeClass('in');
        $('button.tipo_producto.titulo[aria-expanded=true]').attr('aria-expanded', false);
        $(e.currentTarget).attr('aria-expanded', true);
        var target = $(e.currentTarget).attr('href');
        $(target).toggle();
        if($(target).hasClass('in')){
            $(target).removeClass('in');
        }
        else{
            $(target).addClass('in');
        }

        // $('a.tipo_producto.titulo').addClass('disabled');
        // var targetTipo = $(e.target).attr('tipo_producto_id');
        // var open = $('.sub-menu-comidas div.panel-collapse.tipo_producto.in');
        // open.each(function (index, e){
        //     var tipo = $(e).attr("tipo_producto_id");
        //     if(tipo!=targetTipo){
        //         $('a.tipo_producto.titulo[tipo_producto_id='+tipo+']').trigger('click');
        //     }
        // });
        // var contenidoHeight = $('div.contenido').height();
        // var panelHeight = $('.sub-menu-comidas div.panel-collapse.tipo_producto.in').height()+90;
        // if(panelHeight>contenidoHeight){
        //     $('div.contenido').css('min-height', panelHeight);
        // }
        // $('a.tipo_producto.titulo').removeClass('disabled');
    });
});

// window.onscroll = function (e) {
    // windowOnScroll(e);
// }

function windowOnScroll(e){
    var vertical_position = getY();
    console.log(vertical_position);
    $('.sub-menu-comidas div.panel-collapse.tipo_producto.in').css('top', vertical_position);
}

function getY(){
    var headerHeight = 144;
    var vertical_position = 0;
    if (pageYOffset)//usual
        vertical_position = pageYOffset;
    else if (document.documentElement.clientHeight)//ie
        vertical_position = document.documentElement.scrollTop;
    else if (document.body)//ie quirks
        vertical_position = document.body.scrollTop;
    if(vertical_position<headerHeight){
        return 0;
    }
    return vertical_position - headerHeight;
}


function filtrarTipos(filtro){
    filtro = filtro.val().toUpperCase();
    if(filtro == ''){
        $(".btn.titulo").not('.no-tipo').each(function(){
            var divid = $(this).attr('href');
            mostrarTipo(divid);
        });
        return false;
    }
    $(".btn.titulo").not('.no-tipo').each(function(){
        var divid = $(this).attr('href');
        var t = $(this).text().toUpperCase();
        if(t.includes(filtro)){
            mostrarTipo(divid);
        }
        else{
            var productos = $(divid).find('.producto-nombre');
            var done = false;
            var match = false;
            productos.each(function(e){
                if(!done){
                    var t = $(this).text().toUpperCase();
                    if(t.includes(filtro)){
                        mostrarTipo(divid);
                        done=true;
                        match=true;
                    }
                }
            });
            if(!match){
                ocultarTipo(divid);
            }
        }
    });
}
function ocultarTipo(id){
    $(".btn.titulo[href='"+id+"']").hide();
}
function mostrarTipo(id){
    $(".btn.titulo[href='"+id+"']").show();
}


function openNavCuenta() {
    document.getElementById("navCuenta").style.right = "0px";
    //$(".moverconnavcuenta").css("padding-right","280px");
    $("#botonescerrarabrir").css("padding-right","280px");
    $("#content-fix").css("padding-right","280px");
    $(".moverconnavcuenta_margin").css("margin-right","280px");
    $("ul>a.usuario").css("padding-right","280px");
    $("ul>a.usuario").addClass("openCuenta");
    $("#botonescerrarabrir").css("z-index","1033");
}

/* Set the width of the side navigation to 0 */
function closeNavCuenta() {
    document.getElementById("navCuenta").style.width = "280px";
    document.getElementById("navCuenta").style.right = "-280px";
    $(".moverconnavcuenta").css("padding-right","0px");
    $("#content-fix").css("padding-right","0px");
    $("#botonescerrarabrir").css("padding-right","0px");
    $("#botonescerrarabrir").css("z-index","1");
    $(".moverconnavcuenta_margin").css("margin-right","0px");
    $("#botonescerrarabrir").css("right","0px");
    $("ul>a.usuario").css("padding-right","0px");
    setTimeout(() => {
        $("ul>a.usuario").removeClass("openCuenta");
    }, 500);
}
function abrirCerrarNavCuenta(){
    if($("#botonescerrarabrir").attr("estado")=="0"){
        openNavCuenta();
        $("#botonescerrarabrir").attr("estado","1");
    }
    else{
        closeNavCuenta();
        $("#botonescerrarabrir").attr("estado","0");
    }
}
  