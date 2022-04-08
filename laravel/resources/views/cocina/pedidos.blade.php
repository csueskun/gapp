@extends('template.general')
@section('titulo', 'Cocina H-Software')


@section('lib')
{{ Html::style('css/cocina.css') }}
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
                            <div class="col-md-12">Pedido: {{$pedido->id}}</div>
                            <div class="col-md-6">{{$pedido->mesa_id == 0 ? 'Domicilio': 'Mesa: '.$pedido->mesa_id}}</div>
                            <div class="col-md-6">Turno: {{$pedido->turno}}</div>
                            <div class="col-md-12">{{ date_format(date_create($pedido->fecha), 'g:i:s A d/m/Y') }}</div>
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
                        if(obs.obs && obs.obs != ''){
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
    <section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    </section>
    <audio id="piano_chord" src="/audio/piano_chord.wav" preload="auto"></audio>
    {{ Html::script('js/cocina.js') }}
    <script>
        
    </script>
@endsection