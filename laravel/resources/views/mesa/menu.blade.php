@extends('template.general')

@section('titulo', 'pedidos H-Software')

@section('contenido')



@section('lib')
    <script src="/js/jquery.inputmask.bundle.js"></script>
    <meta name="csrf-token" content="{{ Session::token() }}"> 
    <meta name="mesa" content="{{$mesa}}"> 
    <meta name="valida_inventario" content="{{$valida_inventario}}">
    <meta name="propina" content="{{$propina}}">
    <meta name="pedido_id" content="{{isset($pedido_id)?($pedido_id?$pedido_id:0):0}}">
    <meta name="mesa_alias" content="{{$mesa_alias}}">
    <meta name="rol" content="{{Auth::user()->rol}}">
    {{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}    
    {{ Html::style('css/bootstrap-datetimepicker.css') }}
    {{ Html::style('css/jquery-confirm.min.css') }}
    {{ Html::style('css/menu.css') }}
    {{ Html::style('css/combos.css') }}
    {{ Html::script('js/moment-with-locales.js') }}
    {{ Html::script('js/bootstrap-datetimepicker.js') }}
    {{ Html::script('js/jquery-confirm.min.js') }}
    {{ Html::script('js/typeahead.min.js') }}
    {{ Html::script('js/combos.js') }}
    {{ Html::script('/controller/menu.js') }}
    <!-- {{ Html::script('/controller/domicilio.js') }} -->

@endsection

<section class="borde-inferior lista fondo-rojo">
    <div class="container_ centrado">
        @if($mesa == 0)
        <h1 class="titulo moverconnavcuenta"><i class="fa fa-motorcycle" aria-hidden="true"></i> Domicilio <span id="botonescerrarabrir" estado="1" onclick="abrirCerrarNavCuenta()" style="position: absolute;"><i class="glyphicon glyphicon-transfer"></i></span></h1>
        @else
        <h1 class="titulo moverconnavcuenta"><i class="glyphicon glyphicon-cutlery" aria-hidden="true"></i> Mesa {{$mesa_alias}}: Menú<span id="botonescerrarabrir" estado="1" onclick="abrirCerrarNavCuenta()" style="position: absolute;"><i class="glyphicon glyphicon-transfer"></i></span></h1>
        @endif
    </div>
</section>
<section class="borde-inferior lista fondo-comun" style="border-bottom: none">
    <div class="container_ centrado">
        <div class="row moverconnavcuenta" id='ParentContainer'>
            <table>
                <tr>
                    <td style="font-size: 20px; color: #909090" class="font bebas" id="responsable-pedido">
                    </td>
                    <td>
                        <input type="text" class="form-control font bebas" placeholder="Filtrar tipo o producto..." id="filtro">
                    </td>
                </tr>
            </table>
            <div class="col-sm-12"  style='padding: 2px'>
                <div class="btn-group-vertical _100pc sub-menu-comidas" role="group" aria-label="...">
                    @include('mesa.combos')
                    @foreach($tipos_producto as $tipo)
                        @if($tipo->codigo=='00')
                        @continue
                        @endif
                        <a data-toggle="collapse" href="#collapse{{$tipo->id}}" class="titulo btn btn-default" role="button">{{$tipo->descripcion}}  <span class="fa fa-caret-down"></span><span class="fa fa-caret-up"></span></a>
                        <div id="collapse{{$tipo->id}}" class="panel-collapse collapse" valor-editable="{{$tipo->valor_editable}}">
                            <form class="producto" tipo_producto_id='{{$tipo->id}}'>
                                @if($tipo->aplica_tamanos!=1 && $tipo->aplica_sabores!=1 && $tipo->aplica_ingredientes!=1 && $tipo->valor_editable!=1 )
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
                                        scroll-to="" 
                                      
                                    @foreach($producto->tamanos as $tamano)
                                    {{htmlentities($tamano->tamano)}}="{{$tamano->valor}}"
                                    @endforeach onclick="agregarProductoExpress($(this))">
                                    <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                    <span class="producto-nombre">{{$producto->descripcion}}</span>
                                </span>
                                @endforeach
                                <div class="row submit" id='submit-{{$tipo->id}}'>
                                    <table style="margin: auto">
                                        <td>
                                            <span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>
                                        </td>
                                        <td>
                                            <div class="input-group number-spinner" style="width: 160px; margin: auto; padding-bottom: 10px;">
                                                <span class="input-group-btn">
                                                    <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                                </span>
                                                        <input type="text" id="cantidad" class="form-control text-center" value="1" style="font-size: 30px; height: 48px;">
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
                                        html+='<label class="checkbox-inline"><input value="'+fracciones.fracciones[i]+'" '+checked+' type="radio" name="fraccion"><img src="/images/'+(fracciones.fracciones[i]).replace("/","s").replace("/","s")+'.png"/></label>';
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
                                          scroll-to="#ingredientes-{{$tipo->id}}" 
                                          value="{{$producto->id}}">

                                        <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                        <span class="producto-grupo">{{$grupo->nombre}}</span>
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
                                @foreach($tipo->adicionalesg as $key => $grupo)
                                    <div class="ingrediente-grupo-nombre adiciona-grupo" style="display: none">{{$key}}</div>
                                @foreach($grupo as $adicional)
                                    <label class="adicional-{{$adicional->pivot->tamano}}"
                                           @if($tipo->aplica_tamanos=='1')
                                           style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-sition:absolute;z-index:-1':''}}">
                                        @else
                                            style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                        @endif
                                        <img src="/images/ingrediente/{{$adicional->imagen}}" onerror="if (this.src != '/images/ingrediente/ingrediente.jpg') this.src = '/images/ingrediente/ingrediente.jpg';" height="80"/><br/>
                                        {{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})<br/>
                                        <input value="{'id':'{{$adicional->pivot->id}}','nombre':'{{$adicional->descripcion}}',
                                                'ingrediente':'{{$adicional->id}}','valor':'{{$adicional->pivot->valor}}',
                                                'cantidad':'{{$adicional->pivot->cantidad}}', 'unidad':'{{$adicional->unidad}}'}"
                                                class="checkbox-grande" type="checkbox" name="adicional" value="">
                                    </label>
                                @endforeach
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
                                          scroll-to="#ingredientes-1-{{$tipo->id}}" 
                                          value="{{$producto->id}}">
                                        <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                        <span class="producto-grupo">{{$grupo->nombre}}</span>
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
                                @foreach($tipo->adicionalesg as $key => $grupo)
                                <div class="ingrediente-grupo-nombre adiciona-grupo" style="display: none">{{$key}}</div>
                                @foreach($grupo as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                     <img src="/images/ingrediente/{{$adicional->imagen}}" onerror="if (this.src != '/images/ingrediente/ingrediente.jpg') this.src = '/images/ingrediente/ingrediente.jpg';" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">
                                        {{$adicional->descripcion}} {{$adicional->pivot->tamano}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
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
                                          scroll-to="#ingredientes-2-{{$tipo->id}}" 
                                          value="{{$producto->id}}">
                                        <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                        <span class="producto-nombre">{{$producto->descripcion}}</span>
                                        <span class="producto-grupo">{{$grupo->nombre}}</span>
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
                                @foreach($tipo->adicionalesg as $key => $grupo)
                                    <div class="ingrediente-grupo-nombre adiciona-grupo" style="display: none">{{$key}}</div>
                                @foreach($grupo as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                    <img src="/images/ingrediente/{{$adicional->imagen}}" onerror="if (this.src != '/images/ingrediente/ingrediente.jpg') this.src = '/images/ingrediente/ingrediente.jpg';" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
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
                                          scroll-to="#ingredientes-3-{{$tipo->id}}" 
                                          value="{{$producto->id}}">
                                          <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                          <span class="producto-nombre">{{$producto->descripcion}}</span>
                                          <span class="producto-grupo">{{$grupo->nombre}}</span>
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
                                @foreach($tipo->adicionalesg as $key => $grupo)
                                    <div class="ingrediente-grupo-nombre adiciona-grupo" style="display: none">{{$key}}</div>
                                @foreach($grupo as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                    <img src="/images/ingrediente/{{$adicional->imagen}}" onerror="if (this.src != '/images/ingrediente/ingrediente.jpg') this.src = '/images/ingrediente/ingrediente.jpg';" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
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
                                          scroll-to="#ingredientes-4-{{$tipo->id}}" 
                                          style="background-image: url('/images/producto/{{$producto->imagen}}')"
                                          value="{{$producto->id}}">
                                          <div style="background-image: url('/images/producto/{{$producto->imagen}}')"></div>
                                          <span class="producto-nombre">{{$producto->descripcion}}</span>
                                          <span class="producto-grupo">{{$grupo->nombre}}</span>
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
                                @foreach($tipo->adicionalesg as $key => $grupo)
                                    <div class="ingrediente-grupo-nombre adiciona-grupo" style="display: none">{{$key}}</div>
                                @foreach($grupo as $adicional)
                                <label class="checkbox-inline adicional-{{$adicional->pivot->tamano}}"
                                     @if($tipo->aplica_tamanos=='1')
                                     style="display: none; {{$adicional->pivot->tamano!='GRANDE'?'position:absolute;z-index:-1':''}}">
                                     @else
                                     style="display: none; {{$adicional->pivot->tamano!='UNICO'?'position:absolute;z-index:-1':''}}">
                                     @endif
                                     <img src="/images/ingrediente/{{$adicional->imagen}}" onerror="if (this.src != '/images/ingrediente/ingrediente.jpg') this.src = '/images/ingrediente/ingrediente.jpg';" height="80"/><br/>
                                    <input value="{'id':'{{$adicional->pivot->id}}','unidad':'{{$adicional->unidad}}','ingrediente':'{{$adicional->id}}','nombre':'{{$adicional->descripcion}}','valor':'{{$adicional->pivot->valor}}','cantidad':'{{$adicional->pivot->cantidad}}'}" type="checkbox" name="adicional" value="">{{$adicional->descripcion}}<br/>$({{number_format($adicional->pivot->valor)}})
                                </label>
                                @endforeach
                                @endforeach
                            </div>
                            <!-- </div> -->
                            @endif
                            <hr/>
                            <div class="row" id="sabores-{{$tipo->id}}">
                            </div>
                            <br/>
                            <div class="row submit" id='submit-{{$tipo->id}}'>
                                <table style="margin: auto">
                                    <td>
                                        <span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>
                                    </td>
                                    <td>
                                        <div class="input-group number-spinner" style="width: 160px; margin: auto;">
                                            <span class="input-group-btn">
                                                <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                            </span>
                                                    <input type="text" class="form-control text-center" value="1" style="font-size: 30px; height: 48px;">
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
                                            <input type="text" class="form-control text-center" value="1" style="font-size: 30px; height: 48px; width: 125px;" readonly>
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

            <div id="navCuentaPH">

            </div>
            <div id="navCuenta">
            <!-- <h1 class="titulo fondo-rojo" id="tit-mov"><i class="fa fa-motorcycle" aria-hidden="true"></i> <span onclick="openNavCuenta()">abrir</span><span onclick="closeNavCuenta()">cerrar</span></h1> -->
                <div class="col-md-12 btn-group-vertical _100pc sub-menu-comidas" role="group" aria-label="..." style='padding: 2px;'>
                    <a class="titulo btn btn-default no-tipo" data-toggle="collapse" href="#ul-pedido" aria-expanded="true"><font id="tit">Detalles del Pedido   </font><span class="fa fa-caret-down"></span><span class="fa fa-caret-up"></span></a>
                </div>
                <div class="col-md-12" id="otros" style='padding: 2px'>
                    @if($mesa == 0)
                    @else
                    <label class="checkbox-inline"><input type="checkbox" id="llevar-mesa"> PARA LLEVAR</label>
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

    <div ng-app="myApp" ng-controller="menuController">
        @include('mesa.observaciones')
        @include('mesa.editarItemPedido')
        @include('mesa.domicilio')
    </div>
    <input type="hidden" name="old_propina" value="0">
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
        $("a[tipo_producto_id=combo]").removeClass('hidden');
        $('form').each(function(rowE) {
            var grupoNombre = '9156';
            var row = $(this);
            row.find('span .producto-grupo').each(function(i, g) {
                
                if($(this).html()==grupoNombre){
                    $(this).remove();
                }
                else{
                    grupoNombre = $(this).html();
                }
            });
        });

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
            var content = '';
            if($(this).hasClass('reflejar-select-producto')){
                var option = $(this).closest('div').find('option[value='+$(this).attr('value')+']');
                try{
                    content += '<span class="valor">';
                    content += accounting.formatMoney(option.attr('unico'), '$', 0);
                    content += '</span>';
                }catch (error) {}
            }
            else{
                try{
                    content += '<span class="valor">';
                    content += accounting.formatMoney($(this).attr('unico'), '$', 0);
                    content += '</span>';
                }catch (error) {}
            }
            try{
                var bg = $(this).find('div').css('background-image');
                bg = bg.split('url(')[1].split(')')[0].replace('"', '');
                content += '<div class="img"><img src="';
                content += bg;
                content += '"/></div>';
            }catch (error) {}
            content += '<span class="detalle">';
            content += $(this).attr('detalle');
            content += '</span>';
            $.alert({
                title: $(this).attr('nombre'),
                icon: 'fa fa-info',
                type: 'blue',
                typeAnimated: false,
                content: content
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
            $(this).closest('div').find('.ingrediente-grupo-nombre').fadeToggle("slow");
        });
        $('#modal_pagar').on('hidden.bs.modal', function () {
            savePropina();
        });
    });



    function openNavCuenta() {
        document.getElementById("navCuenta").style.right = "0px";
        //$(".moverconnavcuenta").css("padding-right","320px");
        $("#botonescerrarabrir").css("padding-right","320px");
        $("#content-fix").css("padding-right","320px");
        $(".moverconnavcuenta_margin").css("margin-right","320px");
        $("ul>a.usuario").css("padding-right","320px");
        $("ul>a.usuario").addClass("openCuenta");
        $("#botonescerrarabrir").css("z-index","1033");
    }

    /* Set the width of the side navigation to 0 */
    function closeNavCuenta() {
        document.getElementById("navCuenta").style.width = "320px";
        document.getElementById("navCuenta").style.right = "-320px";
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

    function filtrarTipos(filtro){
        filtro = filtro.val().toUpperCase();
        if(filtro == ''){
            $("a.btn.titulo").not('.no-tipo').each(function(){
                var divid = $(this).attr('href');
                mostrarTipo(divid);
            });
            return false;
        }
        $("a.btn.titulo").not('.no-tipo').each(function(){
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
        $("a.btn.titulo[href='"+id+"']").attr('aria-expanded', false).hide();
        $(id).removeClass('in').attr('aria-expanded', false);
    }
    function mostrarTipo(id){
        $("a.btn.titulo[href='"+id+"']").attr('aria-expanded', false).show();
    }

</script>
<style>
    .moverconnavcuenta,ul>a.usuario {
        transition: 0.5s;
    }
    #botonescerrarabrir {
        transition: 0.5s;
    }
    #content-fix {
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
        z-index: 1034;
        border-left: 4px solid #dd4b39;
        color: white;
    }
    #botonescerrarabrir{
        float: right;
        padding-right: 4px;
        cursor: pointer;
        position: absolute;
        right: 0;
        background-color: #dd4b39;
        height: 38px;
        /* margin-top: -4px; */
        padding-left: 4px;
        z-index: 1033;
    }
    hr{
        margin-bottom: 5px;
    }
    
</style>

{{ Html::script('js/accounting.min.js') }}
{{ Html::script('js/ordenar.js') }}
@endsection