@extends('template.general')
@section('titulo', 'Cocina H-Software')


@section('lib')
@endsection

@section('contenido')
    <br/>
    <section class="borde-inferior">
        <div class="container">
            @foreach($pedido_lista as $pedido)
            <div class="panel panel-default pedido">
                <div class="panel-heading">
                    <h2 class="panel-title" style="color: grey">
                        <div class="row">
                            <div class="col-md-4">Pedido #{{$pedido->id}}</div>
                            <div class="col-md-4">Mesa: {{$pedido->mesa_id}}</div>
                            <div class="col-md-4">Fecha y Hora: {{ date_format(date_create($pedido->fecha), 'g:i A') }}</div>
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
                        id="{{$producto->pivot->id}}">
                        <label class="" id="preparado-label">
                            <input type="checkbox"
                                   id="preparado-checkbox"
                                   name="preparado-checkbox"
                                   @if($producto->pivot->preparado != null && $producto->pivot->preparado != '')
                                   checked
                                   @endif
                                   onchange="entregadoCheckbox($(this), {{$producto->pivot->id}})"/>
                            {{$producto->tipo_producto->descripcion}} {{$producto->descripcion}}
                            @if($producto->tipo_producto->descripcion." ".$producto->descripcion != $producto->detalle)
                                ({{$producto->detalle}})
                            @endif
                        </label>
                        <span class="sin"></span>
                        <span class="extra"></span>
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
    <script>
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
            });
            $("li.producto").on("click", function(){
                $(this).find('input').click();
            })
        })
    </script>
@endsection