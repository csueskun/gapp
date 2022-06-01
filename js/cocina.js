var lastId = '0';
var pedidosIds = [];
var productosPedidosIds = [];
var lastDate = '0';


function entregadoCheckbox(checkbox, id){
    if(checkbox.attr("disabled") == "disabled"){
        return false;
    }
    checkbox.attr("disabled", "disabled");
    $.get("/producto_pedido/preparado/"+id, function (data) {
        checkbox.removeAttr("disabled");
        var c = 'checked';
        if(checkbox.attr(c)==c){
            checkbox.removeAttr(c);
            checkbox.closest('li').removeClass('list-group-item-success');
        }
        else{
            checkbox.attr(c, c);
            checkbox.closest('li').addClass('list-group-item-success');
        }
    });
}
$(function () {
    buscarNuevos();
    // $("div.pedido").each(function(){
    //     if($(this).find("li").length == 0){
    //         $(this).hide();
    //     }
    //     pedidosIds.push(parseInt($(this).attr('id')));
    // });
    $("li.producto").on("click", function(){
        $(this).find('input').click();
    });
    // if($("div.pedido").length){
    //     lastId = $("div.pedido:first-child").attr('id');
    // }

    // $('.producto.list-group-item').each(function(i, j){
    //     if($(this).attr('date')>lastDate){
    //         lastDate = $(this).attr('date');
    //     }
    //     productosPedidosIds.push(parseInt($(this).attr('id')));
    // });
    // console.log(lastDate)
    // buscarNuevos();
    setInterval(buscarNuevos, 5000);
    // socket.on('pedido', function(arg){
    //     buscarNuevos();
    // });
});

function buscarNuevos(){
    if(!lastDate){
        lastDate='0';
    }
    $.get("/cocina/nuevos/"+lastDate.replace(" ", "_"), function (data) {
    // $.get("/cocina/nuevos/801", function (data) {
        mostrarNuevos(data.novedades);
        if(lastDate!='0'){
            highlightNew();
        }
        lastDate = data.max.date;
        borrarPedidos(data.pedidos);
        borrarProductos(data.productos);
    });
}
function highlightNew(){
    var play = false;
    $('li.producto').each(function(index,e){
        var date = e.getAttribute('date');
        if(date>lastDate){
            play = true;
            $(e).addClass('highlight');
        }
    })
    if(play){
        play_piano_chord();
    }
}
function borrarPedidos(pedidos){
    pedidosIds.forEach(id => {
        if(pedidos.includes(id)){}
        else{
            $('div.panel#'+id).remove();
        }
    });
    pedidosIds = pedidos;
}
function borrarProductos(productos){
    productosPedidosIds.forEach(id => {
        if(productos.includes(id)){}
        else{
            $('li.list-group-item#'+id).remove();
        }
    });
    productosPedidosIds=productos;
}
function mostrarNuevos(pedidos, itemClass){
    var print = false;
    pedidos.forEach(pedido => {
        lastId = pedido.id;
        pedido.productos.forEach(producto => {
            if(producto.terminado != 1){
                print = true;
            }
        });
        if(print){
            $('div.panel#'+pedido.id).remove();
            $("#container-pedidos").prepend(plantillaPedido(pedido, itemClass));
        }
    });
}
function plantillaPedido(pedido, itemClass){
    var productosHtml = '';
    pedido.productos.forEach(producto => {
        productosHtml += plantillaProducto(producto, itemClass);
    });
    var fecha = pedido.fecha;
    try {
        fecha = fecha.split(' ')[1];
    } catch (error) {
        
    }
    pedidosIds.push(pedido.id);
    var mesa = `Mesa: ${pedido.mesa_id}`;
    if(pedido.mesa_id==0){
        mesa = 'Domicilio';
    }
    var html = `
    <div class="panel panel-default pedido" id="${pedido.id}">
        <div class="panel-heading">
            <h2 class="panel-title" style="color: grey">
                <div class="row">
                    <div class="col-md-12">Pedido: ${pedido.id}</div>
                    <div class="col-md-6">${mesa}</div>
                    <div class="col-md-6">Turno: ${pedido.turno}</div>
                    <div class="col-md-12">${pedido.fecha}</div>
                </div>
            </h2>
        </div>
        <ul class="list-group">${productosHtml}</ul>
    </div>
    `;
    return html;
}
function plantillaProducto(producto, itemClass){
    if(producto.terminado == 1){
        return '';
    }

    productosPedidosIds.push(producto.pivot.id);
    var sin = '';
    var extra = '';
    var obs = '';
    var preparado = producto.pivot.preparado != null && producto.pivot.preparado != '';
    var detalle = producto.tipo_producto.descripcion+" "+producto.descripcion != producto.detalle ? producto.detalle : '';
    if(producto.pivot.obs){
        producto.pivot.obs = JSON.parse(producto.pivot.obs);
        sin = plantillaSin(producto.pivot.obs);
        extra = plantillaExtra(producto.pivot.obs);
        obs = plantillaObs(producto.pivot.obs);
    }
    var html = `
    <li class="list-group-item producto 
        ${preparado?'list-group-item-success ':''}"
        date="${producto.pivot.created_at}"
        id="${producto.pivot.id}">
        <label class="" id="preparado-label">
            <input type="checkbox"
                    id="preparado-checkbox"
                    name="preparado-checkbox"
                    ${preparado?'checked ':''}
                    onchange="entregadoCheckbox($(this), ${producto.pivot.id})"/>
            ${producto.pivot.cant} X ${producto.tipo_producto.descripcion} - ${producto.descripcion} ${detalle} 
        </label>
        <span class="sin">${sin}</span>
        <span class="extra">${extra}</span>
        <span class="extra">${obs}</span>
        
    </li>
    `;
    return html;
}

function plantillaSin(obs){
    if(obs.sin_ingredientes.length){
        var spanHtml = ' SIN ( ';
        obs.sin_ingredientes.forEach(function(element) {
            spanHtml+=(element.descripcion+', ');
        });
        spanHtml+=('*');
        spanHtml=spanHtml.replace(', *', '');
        return spanHtml;
    }
    return '';
}
function plantillaExtra(obs){
    if(obs.adicionales.length){
        var spanHtml = ' EXTRA ( ';
        obs.adicionales.forEach(function(element) {
            spanHtml+=(element.nombre+', ');
        });
        spanHtml+=('*');
        spanHtml=spanHtml.replace(', *', '');
        return spanHtml+' )';
    }
    return '';
}

function plantillaObs(obs){
    if(obs.obs && obs.obs != ''){
        var spanHtml = ' **OBS ( ' + obs.obs + ' )';
        return spanHtml;
    }
    return '';
}

function play_piano_chord() {
    document.getElementById('piano_chord').play();
}