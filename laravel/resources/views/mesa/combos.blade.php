<a data-toggle="collapse" href="#collapse-combos" class="titulo btn btn-default" role="button">
    Combos
    <span class="fa fa-caret-down"></span>
    <span class="fa fa-caret-up"></span>
</a>
<div id="collapse-combos" class="panel-collapse collapse" style="padding-top: 10px">
    @foreach($combos as $combo)
        @if($combo->estado != 1)
            @continue
        @endif
        <combo id="{{$combo->id}}" nombre="{{$combo->nombre}}" precio="{{$combo->precio}}">
            <div class="background" style="background-image: url('/images/combo/{{$combo->imagen}}')">
                <span class="nombre">{{$combo->nombre}}</span>
            </div>
        </combo>
    @endforeach
    @foreach($combos as $combo)
        <div class="contenedor-productos-combo" id="{{$combo->id}}" style="display: none">
            @foreach($combo->comboProductos as $comboProducto)
                <productos-combo combo-id="{{$combo->id}}">
                @for ($i = 0; $i < $comboProducto->cantidad; $i++)
                    <producto id="{{$comboProducto->id}}-{{$i}}"
                              producto-id="{{$comboProducto->producto->id}}"
                              nombre="{{$comboProducto->producto->descripcion}}"
                              nombre-tipo="{{$comboProducto->producto->tipo_producto->descripcion}}"
                              tipo="{{$comboProducto->producto->tipo_producto->id}}"
                              tamano="{{$comboProducto->tamano}}"
                              valor="{{$comboProducto->valor}}">
                        <ul class="nav nav-pills">
                            <li class="nombre-producto">{{$comboProducto->producto->tipo_producto->descripcion}} {{$comboProducto->producto->descripcion}}{{$comboProducto->cantidad>1?(' #'.($i+1)):''}}</li>
                            @if(count($comboProducto->producto->ingredientes))
                            <li class="active"><a data-toggle="tab" href="#ingredientes-{{$comboProducto->id}}-{{$i}}" class="active">Ingredientes</a></li>
                            @endif
                            {{--<li><a data-toggle="tab" href="#adicionales-{{$comboProducto->id}}-{{$i}}">Adicionales</a></li>--}}
                            @if(count($comboProducto->producto->sabores))
                            <li class="{{count($comboProducto->producto->ingredientes)?:'active'}}"><a class="{{count($comboProducto->producto->ingredientes)?:'active'}}" data-toggle="tab" href="#sabores-{{$comboProducto->id}}-{{$i}}">Sabores</a></li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if($comboProducto->producto->ingredientes)
                            <div id="ingredientes-{{$comboProducto->id}}-{{$i}}" class="tab-pane fade in active ingredientes">
                                @foreach($comboProducto->producto->ingredientes as $ingrediente)
                                    @if($comboProducto->tamano == $ingrediente->pivot->tamano && $ingrediente->visible == 1)
                                        <label class="form-check-label ingrediente">
                                            <input type="checkbox" class="form-check-input" checked id="{{$ingrediente->id}}" des="{{$ingrediente->descripcion}}" cantidad="{{$ingrediente->pivot->cantidad}}" unidad="{{$ingrediente->unidad}}">{{$ingrediente->descripcion}}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            {{--<div id="adicionales-{{$comboProducto->id}}-{{$i}}" class="tab-pane fade">--}}
                                {{--<h3>Menu 1</h3>--}}
                                {{--<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                            {{--</div>--}}
                            @if(count($comboProducto->producto->sabores))
                            <div id="sabores-{{$comboProducto->id}}-{{$i}}" class=" sabores tab-pane fade {{count($comboProducto->producto->ingredientes)?:'in active'}}">
                                {{--foreach($comboProducto->producto->sabores as $sabor)--}}
                                @for ($j = 0; $j < count($comboProducto->producto->sabores); $j++)
                                    <label class="form-radio-label sabor"><input name="sabor-{{$comboProducto->id}}-{{$i}}" type="radio" class="form-check-input" nombre="{{$comboProducto->producto->sabores[$j]->descripcion}}" {{$j==0?'checked':''}}> {{$comboProducto->producto->sabores[$j]->descripcion}}</label>
                                @endfor
                                {{--endforeach--}}
                            </div>
                            @endif
                        </div>
                    </producto>
                @endfor
                </productos-combo>
            @endforeach
        </div>
    @endforeach
    <table style="margin: auto; margin-top: 20px; display: none" id="orden-accion">
        <tbody>
            <tr>
                {{--<td>--}}
                    {{--<span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>--}}
                {{--</td>--}}
                {{--<td>--}}
                    {{----}}
                    {{--<div class="input-group number-spinner" style="width: 160px; margin: auto; padding-bottom: 10px;">--}}
                        {{--<span class="input-group-btn">--}}
                            {{--<button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>--}}
                        {{--</span>--}}
                        {{--<input type="text" id="cantidad" class="form-control text-center" value="1" style="font-size: 40px; height: 48px;">--}}
                        {{--<span class="input-group-btn">--}}
                            {{--<button type="button" class="btn btn-success" data-dir="up" style="font-size: 24px"><span class="glyphicon glyphicon-plus"></span></button>--}}
                        {{--</span>--}}
                    {{--</div>--}}
                    {{----}}
                {{--</td>--}}
                <td class="pa-2">
                    <input type="hidden" name="valor_">
                    <span style="color: #5cb85c; font-size: 24px">Cantidad:&nbsp;&nbsp;</span>
                </td>
                <td>
                    <div class="input-group number-spinner" style="width: 160px; margin: auto;">
                                                <span class="input-group-btn">
                                                    <button type="button" style="font-size: 24px" class="btn btn-danger" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                                </span>
                        <input type="text" id="cantidad" class="form-control text-center" value="1" style="font-size: 40px; height: 48px;">
                        <span class="input-group-btn">
                                                    <button type="button" class="btn btn-success" data-dir="up" style="font-size: 24px"><span class="glyphicon glyphicon-plus"></span></button>
                                                </span>
                    </div>
                </td>
                <td class="pa-2">
                    &nbsp;&nbsp;<button onclick="addCombo($(this))" style="font-size: 24px;" class="btn btn-success"><span class="fa fa-plus-circle" aria-hidden="true"></span> Agregar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>