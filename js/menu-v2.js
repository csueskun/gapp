
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
  