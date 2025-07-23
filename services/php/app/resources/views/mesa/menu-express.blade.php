@extends('template.general')

@section('titulo', 'pedidos H-Software')

@section('contenido')



@section('lib')
    <script src="/js/jquery.inputmask.bundle.js"></script>
    <meta name="csrf-token" content="{{ Session::token() }}"> 
    <meta name="mesa" content="{{$mesa}}"> 
    <meta name="valida_inventario" content="{{$valida_inventario}}">
    <meta name="pedido_id" content="{{isset($pedido_id)?($pedido_id?$pedido_id:0):0}}">
    <meta name="mesa_alias" content="{{$mesa_alias}}">
    <meta name="rol" content="{{Auth::user()->rol}}">
    {{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
    
    {{ Html::style('css/bootstrap-datetimepicker.css') }}
    {{ Html::style('css/jquery-confirm.min.css') }}
    {{ Html::style('css/menu.css') }}
    {{ Html::script('js/moment-with-locales.js') }}
    {{ Html::script('js/bootstrap-datetimepicker.js') }}
    {{ Html::script('js/jquery-confirm.min.js') }}
    {{ Html::script('js/typeahead.min.js') }}
@endsection

<section class="borde-inferior lista fondo-rojo">
    <div class="container_ centrado">
        @if($mesa == 0)
        <h1 class="titulo moverconnavcuenta"><i class="fa fa-motorcycle" aria-hidden="true"></i> Domicilio <span id="botonescerrarabrir" estado="1" onclick="abrirCerrarNavCuenta()"><i class="glyphicon glyphicon-transfer"></i></span></h1>
        @else
        <h1 class="titulo moverconnavcuenta"><i class="glyphicon glyphicon-cutlery" aria-hidden="true"></i> Mesa {{$mesa_alias}}: Menú<span id="botonescerrarabrir" estado="1" onclick="abrirCerrarNavCuenta()"><i class="glyphicon glyphicon-transfer"></i></span></h1>
        @endif
    </div>
</section>
<section class="borde-inferior lista fondo-comun" style="border-bottom: none">
    <div class="container_ centrado">
        <div class="row moverconnavcuenta" id='ParentContainer'>
            <div class="input-group" style="width: 300px">
                <input type="text" class="form-control" placeholder="Filtrar tipo o producto..." id="filtro">
                {{--<span class="input-group-btn">--}}
                    {{--<button class="btn btn-default" type="button" onclick="filtrarTipos($('#filtro'))">Filtrar</button>--}}
                {{--</span>--}}
            </div>
            <div class="col-sm-12"  style='padding: 2px'>
                <div class="btn-group-vertical _100pc sub-menu-comidas" role="group" aria-label="...">
                    @foreach($tipos_producto as $tipo)
                        @if($tipo->codigo=='00')
                        @continue
                        @endif
                        <a data-toggle="collapse" href="#collapse{{$tipo->id}}" class="titulo btn btn-default" role="button">{{$tipo->descripcion}}  <span class="fa fa-caret-down"></span><span class="fa fa-caret-up"></span></a>
                        <div id="collapse{{$tipo->id}}" class="panel-collapse collapse" valor-editable="{{$tipo->valor_editable}}" cobro-fraccion="{{$tipo->cobro_fraccion}}">
                            <form class="producto">
                                @if($tipo->aplica_tamanos!=1 && $tipo->aplica_sabores!=1 && $tipo->aplica_ingredientes!=1)
                                <br/>
                                @foreach($tipo->productos as $producto)
                                @if($producto->estado!=1)
                                @continue
                                @endif
                                <span class="producto-express btn btn-default"
                                      type="button" id='{{$producto->id}}'
                                      nombre_tipo='{{$tipo->descripcion}}'
                                      nombre='{{$producto->descripcion}}'
                                      detalle="{{$producto->detalle}}"
                                      valor="{{$producto->valor}}"
                                      style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                    @foreach($producto->tamanos as $tamano)
                                    {{htmlentities($tamano->tamano)}}="{{$tamano->valor}}"
                                    @endforeach onclick="agregarProductoExpress($(this))">
                                    {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"/><br/>--}}
                                    <span class="producto-nombre">{{$producto->descripcion}}</span>
                                </span>
                                @endforeach
                                <div class="row">
                                    <table style="margin: auto">
                                        <td>
                                            <span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>
                                        </td>
                                        <td>
                                            <div class="input-group number-spinner" style="width: 160px; margin: auto; padding-bottom: 10px;">
                                                <span class="input-group-btn">
                                                    <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                                </span>
                                                        <input type="text" id="cantidad" class="form-control text-center" value="1" style="font-size: 40px; height: 48px;">
                                                        <span class="input-group-btn">
                                                    <button type="button" class="btn btn-success" data-dir="up" style="font-size: 24px"><span class="glyphicon glyphicon-plus"></span></button>
                                                </span>
                                            </div>
                                        </td>
                                    </table>
                                </div>
                            </form>
                        </div>
                                @continue
                                @endif

                                @if(count($tipo->productos)>0)
                                <div class="row tamano" id="tamano-fracciones-{{$tipo->id}}">
                                @if($tipo->aplica_tamanos=='1')
                                    <h3>Tamaño: </h3>
                                    @if(isset($tipo->tamanos) and count($tipo->tamanos)>0)
                                    @foreach($tipo->tamanos as $_tamano)
                                        <label class="checkbox-inline"><input value="{{$_tamano}}" type="radio" name="tamano-fraccion">
                                            <script>document.write(getTamanosLabel('{{$_tamano}}'))</script>
                                        </label>
                                    @endforeach
                                    @else
                                    <h3>No ha configurado los tamaños para el tipo de producto. <a href="/tipo_producto/editar/{{$tipo->id}}">Clic aquí para configurar los tamaños</a> </h3>
                                    @endif

                                @else
                                <label class="checkbox-inline"><input value="unico" type="radio" checked name="tamano-fraccion">ÚNICO</label>
                                @endif
                                </div>
                                @if($tipo->fracciones != '["1/1"]')
                                <div class="row fracciones" id="fracciones-{{$tipo->id}}">
                                </div>
                                <script>
                                    var fracciones = JSON.parse('{"fracciones":{{$tipo->fracciones}} }'.replace(/&quot;/g,'"'));
                                    var html = "";
                                    for(var i=0; i<fracciones.fracciones.length; i++){
                                        var checked = "";
                                        if(i===0){
                                            checked = "checked";
                                        }
                                        html+='<label class="checkbox-inline"><input value="'+fracciones.fracciones[i]+'" '+checked+' type="radio" name="fraccion"><img src="/images/'+(fracciones.fracciones[i]).replace("/","s")+'.png"/></label>';
                                    }
                                    $("div#fracciones-{{$tipo->id}}").html("<h3>Fracciones:</h3>"+html);
                                </script>
                                @endif
                                <div class="row _0">
                                    <h3></h3>
                                    <select name="producto" tipo="{{$tipo->id}}" nombre="{{$tipo->descripcion}}" class="form-control sabor-comida" style="display: none">
                                        <option value="0">-- Seleccione el Tipo --</option>
                                        @foreach($tipo->productos as $producto)
                                            @if($producto->estado!=1)
                                                @continue
                                            @endif
                                        <option valor="{{$producto->valor}}" terminado="{{$producto->terminado}}" compuesto="{{$producto->compuesto}}"
                                                @foreach($producto->tamanos as $tamano)
                                                {{htmlentities($tamano->tamano)}}="{{$tamano->valor}}"
                                                @endforeach
                                                nombre="{{$producto->descripcion}}" value="{{$producto->id}}">{{$producto->descripcion}}</option>
                                        @endforeach
                                    </select>
                                    @foreach($tipo->grupos as $grupo)
                                        <div class="grupo-nombre">{{$grupo->nombre}}</div>
                                    @foreach($grupo->productos as $producto)
                                        @if($producto->estado!=1)
                                            @continue
                                        @endif
                                    <span target="producto"
                                          class="producto-express reflejar-select-producto btn btn-default"
                                          tipo-producto="{{$tipo->id}}"
                                          type="button"
                                          nombre='{{$producto->descripcion}}'
                                          valor="{{$producto->valor}}"
                                          detalle="{{$producto->detalle}}"
                                          hola="hola"
                                          style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                          value="{{$producto->id}}">

                                        {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"><br>--}}
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                    </span>
                                    @endforeach
                                    @endforeach
                                </div>
                            <div class="row ing-adic">
                                <div class="_0 ingredientes" id="ingredientes-{{$tipo->id}}">
                                    <h3 class="hidden" style="color: #5cb85c"> Ingredientes:</h3>
                                    <img height="60" class="loading-ingredientes hidden" src="/images/f.gif"/>
                                </div>

                                @if(count($tipo->adicionales)>0)
                                <div class="contenedor-adicionales completa" id="adicionales-{{$tipo->id}}">
                                <h3 class="adicionales-header">Mostrar Adicionales:</h3>
                                @foreach($tipo->adicionales as $adicional)
                                    <label class="adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-sition:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                        <img src="/images/ingrediente/{{$adicional->imagen}}" height="80"/><br/>
                                        {{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})<br/>
                                        <input value="{'id':'{{$adicional->pivot->id}}','nombre':'{{$adicional->descripcion}}',
                                        'ingrediente':'{{$adicional->id}}','valor':'{{$adicional->pivot->valor}}',
                                        'cantidad':'{{$adicional->pivot->cantidad}}', 'unidad':'{{$adicional->unidad}}'}"
                                       class="checkbox-grande" type="checkbox" name="adicional" value="">
                                    </label>
                                    @endforeach
                                </div>
                                @endif  
                            </div>
                            <!--fraccion 1-->
                            <div class="row fraccion _1" fraccion="1">
                                <hr/>
                                <h3>Fracción 1:</h3>
                                <select name="producto-f1" tipo="{{$tipo->id}}" div_ingredientes='1' nombre="{{$tipo->descripcion}}" class="form-control sabor-comida fraccion" style="display: none">
                                    <option value="0">-- Seleccione el Tipo --</option>
                                    @foreach($tipo->productos as $producto)
                                        @if($producto->estado!=1)
                                            @continue
                                        @endif
                                    <option valor="{{$producto->valor}}" compuesto="{{$producto->compuesto}}"
                                            @foreach($producto->tamanos as $tamano)
                                            {{htmlentities($tamano->tamano)}}="{{$tamano->valor}}"
                                            @endforeach
                                            nombre="{{$producto->descripcion}}" value="{{$producto->id}}">{{$producto->descripcion}}</option>
                                    @endforeach

                                </select>
                                @foreach($tipo->grupos as $grupo)
                                    <div class="grupo-nombre">{{$grupo->nombre}}</div>
                                    @foreach($grupo->productos as $producto)
                                    @if($producto->estado!=1)
                                        @continue
                                    @endif
                                    <span target="producto-f1"
                                          class="producto-express reflejar-select-producto btn btn-default"
                                          tipo-producto="{{$tipo->id}}"
                                          detalle="{{$producto->detalle}}"
                                          type="button"
                                          nombre='{{$producto->descripcion}}'
                                          valor="{{$producto->valor}}"
                                          style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                          value="{{$producto->id}}">
                                        {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"><br>--}}
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                    </span>
                                @endforeach
                                @endforeach


                            </div>
                            <!-- <div class="row fraccion _1 cargando-ingredientes"">
                                <h3>Ingredientes:</h3><img height="50" src="/images/loading.gif"/>
                            </div> -->
                            <div class="row fraccion _1 ingredientes" id="ingredientes-1-{{$tipo->id}}">
                                <h3 class="hidden" style="color: #5cb85c"> Ingredientes:</h3>
                                <img height="60" class="loading-ingredientes hidden" src="/images/f.gif"/>
                            </div>
                            @if(count($tipo->adicionales)>0)
                            <div class="row contenedor-adicionales-fraccion" id="adicionales-fraccion1">
                                <h3 class='h3-ad-in adicionales-header'>Mostrar Adicionales:</h3>
                                @foreach($tipo->adicionales as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                    <img src="/images/ingrediente/{{$adicional->imagen}}" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
                            </div>
                            @endif

                            <!--fraccion 2-->
                            <div class="row fraccion _2" fraccion="2">
                                <hr/>
                                <h3>Fracción 2:</h3>
                                <select name="producto-f2" style="display: none" tipo="{{$tipo->id}}" div_ingredientes='2' nombre="{{$tipo->descripcion}}" class="form-control sabor-comida fraccion">
                                    <option value="0">-- Seleccione el Tipo --</option>
                                    @foreach($tipo->productos as $producto)
                                        @if($producto->estado!=1)
                                            @continue
                                        @endif
                                    <option valor="{{$producto->valor}}" compuesto="{{$producto->compuesto}}"
                                            @foreach($producto->tamanos as $tamano)
                                            {{html_entity_decode($tamano->tamano)}}="{{$tamano->valor}}"
                                            @endforeach
                                            nombre="{{$producto->descripcion}}" value="{{$producto->id}}">{{$producto->descripcion}}</option>
                                    @endforeach
                                </select>
                                @foreach($tipo->grupos as $grupo)
                                    <div class="grupo-nombre">{{$grupo->nombre}}</div>
                                    @foreach($grupo->productos as $producto)
                                    @if($producto->estado!=1)
                                        @continue
                                    @endif
                                    <span target="producto-f2"
                                          class="producto-express reflejar-select-producto btn btn-default"
                                          tipo-producto="{{$tipo->id}}"
                                          type="button"
                                          nombre='{{$producto->descripcion}}'
                                          valor="{{$producto->valor}}"
                                          detalle="{{$producto->detalle}}"
                                          style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                          value="{{$producto->id}}">
                                        {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"><br>--}}
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                    </span>
                                @endforeach
                                @endforeach
                            </div>
                            <!-- <div class="row ing-adic-f _2"> -->
                            <!-- <div class="row fraccion _2 cargando-ingredientes"">
                                    <h3>Ingredientes:</h3><img height="50" src="/images/loading.gif"/>
                                </div> -->
                            <div class="row fraccion _2 ingredientes" id="ingredientes-2-{{$tipo->id}}">
                                <h3 class="hidden" style="color: #5cb85c"> Ingredientes:</h3>
                                <img height="60" class="loading-ingredientes hidden" src="/images/f.gif"/>
                            </div>
                            @if(count($tipo->adicionales)>0)
                            <div class="row contenedor-adicionales-fraccion" id="adicionales-fraccion2">
                                <h3 class='h3-ad-in adicionales-header'>Mostrar Adicionales:</h3>
                                @foreach($tipo->adicionales as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                    <img src="/images/ingrediente/{{$adicional->imagen}}" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
                            </div>
                            @endif

                            <!--fraccion 3-->
                            <div class="row fraccion _3" fraccion="3">
                                <hr/>
                                <h3>Fracción 3:</h3>
                                <select name="producto-f3" style="display: none" tipo="{{$tipo->id}}" div_ingredientes='3' nombre="{{$tipo->descripcion}}" class="form-control sabor-comida fraccion">
                                    <option value="0">-- Seleccione el Tipo --</option>
                                    @foreach($tipo->productos as $producto)
                                        @if($producto->estado!=1)
                                            @continue
                                        @endif
                                    <option valor="{{$producto->valor}}" compuesto="{{$producto->compuesto}}"
                                            @foreach($producto->tamanos as $tamano)
                                            {{html_entity_decode($tamano->tamano)}}="{{$tamano->valor}}"
                                            @endforeach
                                            nombre="{{$producto->descripcion}}" value="{{$producto->id}}">{{$producto->descripcion}}</option>
                                    @endforeach
                                </select>
                                @foreach($tipo->grupos as $grupo)
                                    <div class="grupo-nombre">{{$grupo->nombre}}</div>
                                    @foreach($grupo->productos as $producto)
                                    @if($producto->estado!=1)
                                        @continue
                                    @endif
                                    <span target="producto-f3"
                                          class="producto-express reflejar-select-producto btn btn-default"
                                          tipo-producto="{{$tipo->id}}"
                                          detalle="{{$producto->detalle}}"
                                          type="button"
                                          nombre='{{$producto->descripcion}}'
                                          valor="{{$producto->valor}}"
                                          style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                          value="{{$producto->id}}">
                                        {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"><br>--}}
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                    </span>
                                @endforeach
                                @endforeach
                            </div>
                            <div class="row fraccion _3 ingredientes" id="ingredientes-3-{{$tipo->id}}">
                                <h3 class="hidden" style="color: #5cb85c"> Ingredientes:</h3>
                                <img height="60" class="loading-ingredientes hidden" src="/images/f.gif"/>
                            </div>

                            @if(count($tipo->adicionales)>0)
                            <div class="row contenedor-adicionales-fraccion" id="adicionales-fraccion3">
                                <h3 class='h3-ad-in adicionales-header'>Mostrar Adicionales:</h3>
                                @foreach($tipo->adicionales as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                <img src="/images/ingrediente/{{$adicional->imagen}}" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
                            </div>
                            @endif                           

                            <!--fraccion 4-->

                            <div class="row fraccion _4"  fraccion="4">
                                <hr/>
                                <h3>Fracción 4:</h3>
                                <select name="producto-f4" style="display: none" tipo="{{$tipo->id}}" div_ingredientes='4' nombre="{{$tipo->descripcion}}" class="form-control sabor-comida fraccion">
                                    <option value="0">-- Seleccione el Tipo --</option>
                                    @foreach($tipo->productos as $producto)
                                        @if($producto->estado!=1)
                                            @continue
                                        @endif
                                    <option valor="{{$producto->valor}}" compuesto="{{$producto->compuesto}}"
                                            @foreach($producto->tamanos as $tamano)
                                            {{html_entity_decode($tamano->tamano)}}="{{$tamano->valor}}"
                                            @endforeach
                                            nombre="{{$producto->descripcion}}" value="{{$producto->id}}">{{$producto->descripcion}}</option>
                                    @endforeach
                                </select>
                                @foreach($tipo->grupos as $grupo)
                                    <div class="grupo-nombre">{{$grupo->nombre}}</div>
                                    @foreach($grupo->productos as $producto)
                                    @if($producto->estado!=1)
                                        @continue
                                    @endif
                                    <span target="producto-f4"
                                          class="producto-express reflejar-select-producto btn btn-default"
                                          tipo-producto="{{$tipo->id}}"
                                          type="button"
                                          nombre='{{$producto->descripcion}}'
                                          valor="{{$producto->valor}}"
                                          detalle="{{$producto->detalle}}"
                                          style="background-image: url('/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}')"
                                          value="{{$producto->id}}">
                                        {{--<img width="120" height="120" src="/images/producto/{{$producto->imagen?$producto->imagen:'producto.jpg'}}"><br>--}}
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                    </span>
                                @endforeach
                                @endforeach
                            </div>
                            <div class="row fraccion _4 ingredientes" id="ingredientes-4-{{$tipo->id}}">
                                <h3 class="hidden" style="color: #5cb85c"> Ingredientes:</h3>
                                <img height="60" class="loading-ingredientes hidden" src="/images/f.gif"/>
                            </div>

                            @if(count($tipo->adicionales)>0)
                            <div class="row contenedor-adicionales-fraccion" id="adicionales-fraccion4">
                                <h3 class='h3-ad-in adicionales-header'>Mostrar Adicionales:</h3>
                                @foreach($tipo->adicionales as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                <img src="/images/ingrediente/{{$adicional->imagen}}" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
                            </div>
                            <!-- </div> -->
                            @endif
                            <hr/>
                            <div class="row" id="sabores-{{$tipo->id}}">
                            </div>
                            <br/>
                            <div class="row">
                                <table style="margin: auto">
                                    <td>
                                        <span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>
                                    </td>
                                    <td>
                                        <div class="input-group number-spinner" style="width: 160px; margin: auto;">
                                            <span class="input-group-btn">
                                                <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                            </span>
                                                    <input type="text" class="form-control text-center" value="1" style="font-size: 40px; height: 48px;">
                                                    <span class="input-group-btn">
                                                <button type="button" class="btn btn-success" data-dir="up" style="font-size: 24px"><span class="glyphicon glyphicon-plus"></span></button>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        &nbsp;&nbsp;<button style="font-size: 24px" onclick="if(!$(this).hasClass('disabled')){submitFix($(this).closest('form'))}" id="boton{{$tipo->id}}" type="button" class="btn btn-success agregar-orden"><span class="fa fa-plus-circle" aria-hidden="true"></span> Agregar</button>
                                    </td>
                                </table>
                                <table style="margin: auto; margin-top: 8px; display: none;" class="editar-valor-spinner">
                                    <td>
                                        <input type="hidden" name="valor_">
                                        <span style="color: #5cb85c; font-size: 24px">Valor individual:&nbsp;&nbsp;</span>
                                    </td>
                                    <td>
                                        <div class="input-group number-spinner-valor" style="margin: auto;">
                                            <span class="input-group-btn">
                                                <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                            </span>
                                            <input type="text" class="form-control text-center" value="1" style="font-size: 40px; height: 48px; width: 125px;" readonly>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success" data-dir="up" style="font-size: 24px"><span class="glyphicon glyphicon-plus"></span></button>
                                            </span>
                                        </div>
                                    </td>

                                </table>
                            </div>
                            @else
                            <h3 class="titulo">Aún Sin Productos</h3>
                            @endif
                            <br/>
                        </form>
                    </div>
                    @endforeach
                </div>
                <br/><br/>
            </div>

            <div id="navCuenta">
            <!-- <h1 class="titulo fondo-rojo" id="tit-mov"><i class="fa fa-motorcycle" aria-hidden="true"></i> <span onclick="openNavCuenta()">abrir</span><span onclick="closeNavCuenta()">cerrar</span></h1> -->
                <div class="col-md-12 btn-group-vertical _100pc sub-menu-comidas" role="group" aria-label="..." style='padding: 2px;'>
                    <a class="titulo btn btn-default no-tipo" data-toggle="collapse" href="#ul-pedido" aria-expanded="true">Detalles del Pedido   <span class="fa fa-caret-down"></span><span class="fa fa-caret-up"></span></a>
                </div>
                <div class="col-md-12" id="otros" style='padding: 2px'>
                    @if($mesa == 0)
                    @else
                    <!-- <label class="checkbox-inline"><input type="checkbox" id="llevar-mesa"> PARA LLEVAR</label> -->
                    @endif
                    
                </div>
                <img id='loading-pedido' class="button-loading" src=""/>
                <div class="col-md-12" id="pedido" style='padding: 2px'>
                    <ul class="list-group items_pedido panel-collapse in collapse" id="ul-pedido"><li id="261" class="list-group-item"><span class="producto">&nbsp;</span><span class="detalles"></span><br><div class="spaceholder-valor-item">&nbsp;</div><div class="btn-group items"></div></li></ul>
                    <ul class="list-group items_pedido" id="total"><li class="list-group-item"><span class="producto">Total Pedido:</span><div class="btn-group"></div></li></ul>
                </div>
                <br/>
                @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Mesero')
                <label class="checkbox-inline grande" id="entregado-label" style="display: none"><input type="checkbox" id="entregado-checkbox" name="entregado-checkbox" onchange="entregadoCheckbox($(this))">Entregado</label>
                @endif
            </div>
        </div>
    </div>


    

    <div class="modal fade" tabindex="-1" role="dialog" id='modal_detalles_domicilio' aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header" style="">
                    <h1 class="modal-title" id="exampleModalLabel">Observaciones</h1>
                </div> -->
                <div class="modal-body">
                    <label class="checkbox-inline grande"><input type="radio" name="domicilio-caja" checked value="DOMICILIO"> ENTREGAR EN DOMICILIO</label>
                    <label class="checkbox-inline grande"><input type="radio" name="domicilio-caja" value="CAJA"> ENTREGAR EN CAJA</label>
                    <input type='hidden' id='entregar_en_' value='DOMICILIO'/>
                    <textarea id="observacion-domicilio"></textarea>
                                    <br/>
                                    <br/>
                    <div class="form-group" id='hora-programa-cont'>
                        <table style='width: 100%; margin: auto'>
                            <tr>
                            <td style="width: 250px; text-align: left">
                                        <label class="grande checkbox-inline" style='cursor: initial'>Programar domicilio:</label>
                                </td>
                                <td>
                                    <div class='input-group date' id='hora-programa'>
                                        
                                        <input type='text' class="form-control" />
                                        <span class="input-group-addon disparador">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div>
                                </td>
                                <td style="width: 1px">
                                    <!-- <button type='button' class='btn btn-success'><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
                                    <button onclick='$("#hora-programa input").val("")' type='button' class='btn btn-danger' style="font-size: 30px;padding: 6px 8px 0px;"><span class="glyphicon glyphicon-remove"></span></button>
                                </td>
                                </tr>
                                </table>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('#hora-programa').datetimepicker({
                                format: 'LT'
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <div class="btn-group centrado">
                        <button onclick="guardarDetallesDomicilio()" style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-success btn-lg font bebas" data-dismiss="modal"><span class="fa fa-edit"></span> Guardar</button>
                        <button style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-danger btn-lg font bebas" data-dismiss="modal"><span class="fa fa-close"></span> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id='modal_observaciones' aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="max-width: 500px" >
                <!-- <div class="modal-header" style="">
                    <h1 class="modal-title" id="exampleModalLabel">Observaciones</h1>
                </div> -->
                <div class="modal-body">
                    <label class="checkbox-inline grande"><input type="checkbox" id="llevar-mesa"> PARA LLEVAR</label>
                    <br/>
                    <br/>
                    <label class="checkbox-inline grande" style="cursor:initial;">Observaciones</label><br/>
                    <textarea id="observacion-mesa" style="height: 150px;width:100%"></textarea>
                </div>
                <div class="btn-group modal-footer centrado">
                    <button style="font-size:34px;padding: 4px 6px;" onclick="borrarObservacion();$('#observacion-mesa').val('')" type="button" class="btn btn-danger btn-lg font bebas"><span class="fa fa-edit"></span> Borrar</button>
                    <button style="font-size:34px;padding: 4px 6px;" onclick="guardarObservacion();" type="button" class="btn btn-success btn-lg font bebas" data-dismiss="modal"><span class="fa fa-save"></span> Guardar</button>
                    <button style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-default btn-lg font bebas" data-dismiss="modal"><span class="fa fa-close"></span> Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id='modal_pagar' aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 600px">
                <!-- <div class="modal-header" style="">
                    <h1 class="modal-title" id="exampleModalLabel">Observaciones</h1>
                </div> -->
                <div class="modal-body">
                    <div id="cambio">
                        <form>
                            <table style="margin: auto;">
                                <tr>
                                    <td class="label">Tercero</td><td id='tercero_des'><input readonly name="tercero_des" class="form-control"/></td>
                                </tr>
                                <tr>
                                    <td class="label">Total</td><td width="400px" id='cambio_total'><input name="" class="form-control curr" readonly/></td>
                                </tr>
                                <tr>
                                    <td class="label">Efectivo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td id='paga_efectivo'><input name="paga_efectivo" onkeyup="calcularCambio()" class="form-control curr"/></td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="label" style='width: 170px;display: inline-block; text-align: left'>
                                        Ver otros medios de pago
                                        <input style="height: 25px;width: 25px;margin-left: 25px;" width="auto" onchange="toggleOtrosMedios($(this).is(':checked'))" type="checkbox" name="ver-otros-medios">
                                    </td>
                                </tr>

                                <tr class="otros-medios-pago" style="display: none">
                                    <td class="label">T.Débito</td><td id='paga_debito'><input name="paga_debito" onkeyup="calcularCambio()" class="form-control curr"/></td>
                                </tr>
                                <tr class="otros-medios-pago" style="display: none">
                                    <td class="label">T.Crédito</td><td id='paga_credito'><input name="paga_credito" onkeyup="calcularCambio()" class="form-control curr"/></td>
                                </tr>
                                <tr class="otros-medios-pago" style="display: none">
                                    <td class="label">Transferencia</td><td id='paga_transferencia'><input name="paga_transferencia" onkeyup="calcularCambio()" class="form-control curr"/></td>
                                </tr>
                                <tr class="otros-medios-pago" style="display: none">
                                    <td class="label">Documento</td><td id='num_documento'><input name="num_documento" class="form-control"/></td>
                                </tr>
                                <tr class="otros-medios-pago" style="display: none">
                                    <td class="label">Banco</td>
                                    <td id='banco'>
                                        <select name="banco" id="banco" class="form-control font bebas">
                                            <option value="">--</option>
                                            <option value="1">Bancolombia</option>
                                            <option value="2">Banco Bogotá</option>
                                            <option value="3">Davivienda</option>
                                            <option value="4">BBVA</option>
                                            <option value="5">Uplace Colombia</option>
                                            <option value="6">Domicilios.com</option>
                                            <option value="7">Rappi</option>
                                            <option value="8">Ifood</option>
                                            <option value="9">Nequi</option>
                                            <option value="10">Otro</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label" style='width: 170px;display: inline-block; text-align: left'>Cambio</td>
                                    <td id='cambio_cambio'><input readonly class="form-control curr"/></td>
                                </tr>
                                <tr>
                                    <td class="label" style='width: 170px;display: inline-block; text-align: left'>Descuento</td>
                                    <td id='descuento'>
                                        <table>
                                            <tr><td width="110"><input onClick="this.select();" onkeyup="calcularDescuento()" typeof="number" max="100" min="0" class="form-control percent"/></td><td><input readonly class="form-control curr"/></td></tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label" style='width: 170px;display: inline-block; text-align: left'>Propina</td>
                                    <td id='propina'>
                                        <table>
                                            <tr><td width="110"><input onClick="this.select();" value="10" onkeyup="calcularPropina()" typeof="number" max="100" min="0" class="form-control percent"/></td><td><input readonly class="form-control curr"/></td></tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="otros-medios-pago" style="display: none">
                                    <td id="debiendo" colspan="2">
                                        <div class="alert alert-danger">
                                            Quiere marcar el pago como pendiente?
                                            Sí <label style="width: 25px; padding-left: 0px" class="checkbox-inline">
                                                <input style="height: 25px;" width="auto" type="radio" name="pago-pendiente" value="1">
                                            </label>
                                            No <label style="width: 25px; padding-left: 0px" class="checkbox-inline">
                                                <input style="height: 25px;" width="auto" type="radio" checked name="pago-pendiente" value="0">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button style="font-size:34px;padding: 4px 6px;" type="button" onclick="preFactura()" class = "fuente bebas btn btn-primary btn-lg"><span class="fa fa-file-powerpoint-o"></span> Resumen Cuenta</button>
                        @if(Auth::user()->rol=='Administrador' || Auth::user()->rol=='Cajero')
                        <button style="font-size:34px;padding: 4px 6px;" type="button" onclick="preEnviarFormPagar()" class = "fuente bebas btn btn-success btn-lg"><span  class="fa fa-usd"></span> Pagar</button>
                        <button style="font-size:34px;padding: 4px 6px;" type="button" onclick="gaveta()" class = "fuente bebas btn btn-danger btn-lg"><span class="fa fa-inbox"></span> Cajón</button>
                        @endif
                        <!--@if(Auth::user()->rol=='Administrador')
                        <button style="font-size:34px;padding: 4px 6px;" type="button" onclick="gaveta()" class = "fuente bebas btn btn-danger btn-lg"><span class="fa fa-inbox"></span> Cajón</button>
                        @endif -->
                        <button style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-default btn-lg fuente bebas" data-dismiss="modal"><span class="fa fa-close"></span> Salir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('mesa.observaciones')
</section>
<script>
    $(document).on('click', '.number-spinner button', function () {    
        var btn = $(this),
            oldValue = btn.closest('.number-spinner').find('input').val().trim(),
            newVal = 0;
        
        if (btn.attr('data-dir') == 'up') {
            newVal = parseInt(oldValue) + 1;
        } else {
            if (oldValue > 1) {
                newVal = parseInt(oldValue) - 1;
            } else {
                newVal = 1;
            }
        }
        btn.closest('.number-spinner').find('input').val(newVal);
    });
    $(document).on('click', '.number-spinner-valor button', function () {
        var btn = $(this),
            oldValue = btn.closest('.number-spinner-valor').find('input').val().trim(),
            newVal = 0;

        if (btn.attr('data-dir') == 'up') {
            newVal = parseInt(oldValue) + 500;
        } else {
            var min = $(this).closest('table').find('input[name="valor_"]').val();
            min = parseInt(min);
            if (oldValue > min) {
                newVal = parseInt(oldValue) - 500;
            } else {
                newVal = min;
            }
        }
        btn.closest('.number-spinner-valor').find('input').val(newVal);
    });
    $(function(){

        $('input[name=domicilio-caja]').on('change', function () {
            $("input#entregar_en_").val($(this).val());
        });

        $('input#llevar-mesa[type="checkbox"]').on('change', function () {
            var ch = $(this);
            if (ch.is(':checked')) {
                ch.prop('checked', false);
            } else {
                ch.prop('checked', true);
            }
            paraLlevar();
        });

        $("div.grid.adicionales>label").on("change", "input",function(){
            if($(this).is(":checked")){
                $(this).closest("label").addClass("checked");
            }
            else{
                $(this).closest("label").removeClass("checked");
            }
            
        });
        $("div.row.fraccion.ingredientes").on("click","label",function(){
            if($(this).find("input").is(":checked")){
                $(this).addClass("checked");
            }
            else{
                $(this).removeClass("checked");
            }
            
        });
        $("div._0.ingredientes").on("click","label",function(){
            if($(this).find("input").is(":checked")){
                $(this).addClass("checked");
            }
            else{
                $(this).removeClass("checked");
            }
        });
        $("div.contenedor-adicionales").on("click","label",function(){
            if($(this).find("input").is(":checked")){
                $(this).addClass("checked");
            }
            else{
                $(this).removeClass("checked");
            }
        });
        $("div.contenedor-adicionales-fraccion").on("click","label",function(){
            if($(this).find("input").is(":checked")){
                $(this).addClass("checked");
            }
            else{
                $(this).removeClass("checked");
            }
        });
        $("span.reflejar-select-producto").on("click",function(){
            var boton = $(this);
            var target = boton.closest('div').find('select[name='+boton.attr('target')+']');
            target.val(boton.attr('value'));
            target.trigger('change');
            $("span[tipo-producto="+boton.attr("tipo-producto")+"]").css('background-color','none');
            boton.css('background-color','#ffe868');
            boton.find('span.producto-nombre').css('background-color','#ffe868');
            $("div.contenedor-adicionales label").removeClass('checked');
            $("div.contenedor-adicionales label input").prop('checked', false);
        });

        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        if(width>1001){
            openNavCuenta();
        }
        else{
            $("#botonescerrarabrir").attr("estado","0");
        }

        $('input#filtro').on('keyup', function(){
            filtrarTipos($('#filtro'));
        });

        $( ".producto-express" ).contextmenu(function(e) {
            e.preventDefault();
            $.alert({
                title: $(this).attr('nombre'),
                icon: 'fa fa-info',
                type: 'blue',
                typeAnimated: true,
                content: $(this).attr('detalle'),
            });
        });

        $('h3.adicionales-header').on('click', function(){
            if($(this).hasClass('showing')){
                $(this).html('Mostrar Adicionales');
            }
            else{
                $(this).html('Ocultar Adicionales');
            }
            $(this).toggleClass('showing');
            $(this).closest('div').find('label').fadeToggle("slow");
        });
    });



    function openNavCuenta() {
        document.getElementById("navCuenta").style.right = "0px";
        $(".moverconnavcuenta").css("padding-right","320px");
        $(".moverconnavcuenta_margin").css("margin-right","320px");
        $("ul>a.usuario").css("padding-right","320px");
    }

    /* Set the width of the side navigation to 0 */
    function closeNavCuenta() {
        document.getElementById("navCuenta").style.width = "320px";
        document.getElementById("navCuenta").style.right = "-320px";
        $(".moverconnavcuenta").css("padding-right","0px");
        $(".moverconnavcuenta_margin").css("margin-right","0px");
        $("#botonescerrarabrir").css("right","0px");
        $("ul>a.usuario").css("padding-right","0px");
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
        $(".btn.titulo[href='"+id+"']").attr('aria-expanded', false).hide();
        $(id).removeClass('in').attr('aria-expanded', false);
    }
    function mostrarTipo(id){
        $(".btn.titulo[href='"+id+"']").attr('aria-expanded', false).show();
    }

</script>
<style>
    .moverconnavcuenta,ul>a.usuario {
        transition: 0.5s;
    }
    #tit-mov {
        margin-bottom: 3px;
    }
    #navCuenta {
        padding: 0px; 
        background-color: #f2f7f8;
        height: 100%; /* 100% Full-height */
        width: 320px; /* 0 width - change this with JavaScript */
        position: fixed; /* Stay in place */
        z-index: 1; /* Stay on top */
        top: 0px;
        right: -320px;
        overflow-x: hidden; /* Disable horizontal scroll */
        overflow-y: auto; /* Disable horizontal scroll */
        transition: 0.5s;
        /* -webkit-box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3);
        -moz-box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3);
        box-shadow: 4px 0px 23px 0px rgba(0,0,0,0.3); */
        z-index: 1031;
        color: white;
    }
    #botonescerrarabrir{
        float: right;
        padding-right: 4px;
        cursor: pointer;
    }
    hr{
        margin-bottom: 5px;
    }
    textarea#direccion, textarea#observacion, textarea#observacion-mesa, textarea#observacion-domicilio{
        font-family: 'bebas_neuebold';
        width: 100%;
        font-size: 31px;
        height: 57px;
        line-height: 26px;
        color: #8a8a8a;
    }
    div.row.fraccion{
        display: none;
    }
    .jconfirm-title{
        /*font-size: 1.5em;*/
    }
    .jconfirm-content-pane{
        font-size: 20px;
    }
    .jconfirm-buttons button{
        font-size: 22px !important;
    }
    h3.adicionales-header{
        cursor: pointer;
        font-size: 40px !important;
    }
    h3.adicionales-header:not(.showing){
        color: #5cb85c !important;
        border: thin solid #bfd8bf;
    }
    h3.adicionales-header.showing{
        color: #eea236;
        background-color: #fbe4c3;
    }
    #cambio select{
        font-size: 40px;
        height: 50px;
        padding: 0 4px;
    }
</style>

{{ Html::script('js/accounting.min.js') }}
{{ Html::script('js/ordenar.js') }}
@endsection