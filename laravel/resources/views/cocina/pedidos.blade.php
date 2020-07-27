@extends('template.general')
@section('titulo', 'Cocina H-Software')


@section('lib')
@endsection

@section('contenido')
    <br/>
    <section class="borde-inferior">
        <div class="container" id="container-pedidos">
            @foreach($pedido_lista as $pedido)
            <div class="panel panel-default pedido" id="{{$pedido->id}}">
                <div class="panel-heading">
                    <h2 class="panel-title" style="color: grey">
                        <div class="row">
                            <div class="col-md-3">Pedido: {{$pedido->id}}</div>
                            <div class="col-md-3">{{$pedido->mesa_id == 0 ? 'Domicilio': 'Mesa: '.$pedido->mesa_id}}</div>
                            <div class="col-md-3">Turno: {{$pedido->turno}}</div>
                            <div class="col-md-3">{{ date_format(date_create($pedido->fecha), 'g:i:s A d/m/Y') }}</div>
                        </div>
                    </h2>
                </div>
                <ul class="list-group">
                    @foreach($pedido->productos as $producto)
                        @if($producto->terminado == 1)
                            @continue
                        @endif
                    <li class="list-group-item
                        @if($producto->pivot->preparado != null && $producto->pivot->preparado != '')
                        list-group-item-success
                        @endif
                        producto"
                        date="{{$producto->pivot->created_at}}"
                        id="{{$producto->pivot->id}}">
                        <label class="" id="preparado-label">
                            <input type="checkbox"
                                   id="preparado-checkbox"
                                   name="preparado-checkbox"
                                   @if($producto->pivot->preparado != null && $producto->pivot->preparado != '')
                                   checked
                                   @endif
                                   onchange="entregadoCheckbox($(this), {{$producto->pivot->id}})"/>
                                   {{$producto->pivot->cant}} X {{$producto->tipo_producto->descripcion}} - {{$producto->descripcion}}
                            @if($producto->tipo_producto->descripcion." ".$producto->descripcion != $producto->detalle)
                                ({{$producto->detalle}})
                            @endif
                        </label>
                        <span class="sin"></span>
                        <span class="extra"></span>
                        <span class="obs"></span>
                    </li>
                    <script>
                        var jsonString = "{{$producto->pivot->obs}}".replace(/&quot;/g, '"');
                        var obs = JSON.parse(jsonString);
                        if(obs.sin_ingredientes.length){
                            var span = $("li#{{$producto->pivot->id}} span.sin");
                            var spanHtml = ' SIN ( ';
                            obs.sin_ingredientes.forEach(function(element) {
                                spanHtml+=(element.descripcion+', ');
                            });
                            spanHtml+=('*');
                            spanHtml=spanHtml.replace(', *', '');
                            span.append(spanHtml+' )');
                        }
                        if(obs.adicionales.length){
                            var span = $("li#{{$producto->pivot->id}} span.extra");
                            var spanHtml = ' EXTRA ( ';
                            obs.adicionales.forEach(function(element) {
                                spanHtml+=(element.nombre+', ');
                            });
                            spanHtml+=('*');
                            spanHtml=spanHtml.replace(', *', '');
                            span.append(spanHtml+' )');
                        }
                        if(obs.obs.length){
                            var span = $("li#{{$producto->pivot->id}} span.obs");
                            var spanHtml = ' **OBS ( ' + obs.obs;
                            //spanHtml+=('*');
                            //spanHtml=spanHtml.replace(', *', '');
                            span.append(spanHtml+' )');
                        }
                    </script>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </section>
    <style>
        li.producto{
            font-weight: bold;
            font-size: 1.2em;
            font-family: 'robotolight';
            cursor: pointer;
        }
        li.producto>label>input[type=checkbox]{
            /* Double-sized Checkboxes */
            -ms-transform: scale(2); /* IE */
            -moz-transform: scale(2); /* FF */
            -webkit-transform: scale(2); /* Safari and Chrome */
            -o-transform: scale(2); /* Opera */
            margin-right: 10px;
        }
        span.sin, span.extra{
            display: block;
            margin-left: 24px;
        }
        h2.panel-title{
            font-family: 'bebas_neuebold';
            font-size: 30px;
            display: block;
            margin-left: 24px;
        }
    </style>
    <section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    </section>
    <audio id="piano_chord" src="/audio/piano_chord.wav" preload="auto"></audio>
    <script>
        var lastId = '0';
        var pedidosIds = [];
        var productosPedidosIds = [];
        var lastDate = '2000-01-01 00:00:00';
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
            $("div.pedido").each(function(){
                if($(this).find("li").length == 0){
                    $(this).hide();
                }
                pedidosIds.push(parseInt($(this).attr('id')));
            });
            $("li.producto").on("click", function(){
                $(this).find('input').click();
            });
            if($("div.pedido").length){
                lastId = $("div.pedido:first-child").attr('id');
            }

            $('.producto.list-group-item').each(function(i, j){
                if($(this).attr('date')>lastDate){
                    lastDate = $(this).attr('date');
                }
                productosPedidosIds.push(parseInt($(this).attr('id')));
            });
            // console.log(lastDate)
            setInterval(buscarNuevos, 7000);
            // buscarNuevos();
        });

        function buscarNuevos(){
            $.get("/cocina/nuevos/"+lastDate.replace(" ", "_"), function (data) {
            // $.get("/cocina/nuevos/801", function (data) {
                mostrarNuevos(data.novedades);
                borrarPedidos(data.pedidos);
                borrarProductos(data.productos);
            });
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
        function mostrarNuevos(pedidos){
            var print = false;
            var play = false;
            pedidos.forEach(pedido => {
                lastId = pedido.id;
                pedido.productos.forEach(producto => {
                    if(producto.terminado != 1){
                        print = true;
                        if(productosPedidosIds.includes(producto.pivot.id)){}
                        else{
                            play = true;
                        }
                    }
                    if(producto.pivot.created_at>lastDate){
                        lastDate = producto.pivot.created_at;
                    }
                });
                if(play){
                    play_piano_chord();
                }
                if(print){
                    $('div.panel#'+pedido.id).remove();
                    $("#container-pedidos").prepend(plantillaPedido(pedido));
                }
            });
        }
        function plantillaPedido(pedido){
            var productosHtml = '';
            pedido.productos.forEach(producto => {
                productosHtml += plantillaProducto(producto);
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
                            <div class="col-md-3">Pedido: ${pedido.id}</div>
                            <div class="col-md-3">${mesa}</div>
                            <div class="col-md-3">Turno: ${pedido.turno}</div>
                            <div class="col-md-3">${pedido.fecha}</div>
                        </div>
                    </h2>
                </div>
                <ul class="list-group">${productosHtml}</ul>
            </div>
            `;
            return html;
        }
        function plantillaProducto(producto){
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
            <li class="list-group-item 
                ${preparado?'list-group-item-success ':''}
                producto"
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
                return spanHtml;
            }
            return '';
        }

        function plantillaObs(obs){
            if(obs.obs.length){
                var spanHtml = ' **OBS ( ' + obs.obs + ')';
                //spanHtml+=('*');
                //spanHtml=spanHtml.replace(', *', '');
                return spanHtml;
            }
            return '';
        }

        function play_piano_chord() {
            document.getElementById('piano_chord').play();
        }
    </script>
@endsection