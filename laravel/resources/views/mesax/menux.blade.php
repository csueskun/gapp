@extends('template.general')

@section('titulo', 'pedidos H-Software')

@section('contenido')

@section('lib')
    {{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
    {{ Html::script('js/accounting.min.js') }}
    
    {{ Html::style('css/bootstrap-datetimepicker.css') }}
    {{ Html::style('css/jquery-confirm.min.css') }}
    {{ Html::style('css/menu.css') }}
    {{ Html::style('css/combos.css') }}
    {{ Html::script('js/moment-with-locales.js') }}
    {{ Html::script('js/bootstrap-datetimepicker.js') }}
    {{ Html::script('js/jquery-confirm.min.js') }}
    {{ Html::script('js/typeahead.min.js') }}
    {{ Html::script('js/combos.js') }}
    {{ Html::script('/controller/menux.js') }}

@endsection
<div ng-app="myApp" ng-controller="menuxController" ng-init="init({{$mesa}})">
    <section class="borde-inferior lista fondo-comun" style="border-bottom: none">
        <div class="row min" id="menu20">

            <div class="col-md-3" id="tiposPH">
                &nbsp;
            </div>
            <div class="col-md-3" id="tipos">
                <ul style="margin-top: 4px;">
                    <li ng-repeat="tipo in menu">
                        <button ng-click="scrollToProducts(tipo.descripcion)" type="button" role="button"
                            aria-pressed="true" class="btn btn-default btn-lg btn-block">
                            @{{ tipo.descripcion }}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="col-md-6" id="productos">
                <ul>
                    <li ng-repeat="tipo in menu">
                        <ul>
                            <h4 name="@{{ tipo.descripcion }}">@{{ tipo.descripcion }}</h4>
                            <li ng-if="tipo.fracciones_.length > 1">
                                <button type="button" class="btn btn-default producto" ng-click="addFraccionado($index)">
                                    <div style="background-image: url('/images/producto/producto.jpg')">
                                        <span class="nombre">Fraccionado</span>
                                    </div>
                                </button>
                            </li>
                            <li ng-repeat="producto in tipo.productos">
                                <button type="button" class="btn btn-default producto" ng-click="addProduct($parent.$index, $index)">
                                    <div style="background-image: url('/images/producto/@{{ producto.imagen }}')">
                                        <span class="nombre">@{{ producto.descripcion }}</span>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-md-3" id="cuentaPH">
                &nbsp;
            </div>
            <div class="col-md-3" id="cuenta">
                <ul style="margin-top: 4px;">
                    <li ng-repeat="productoPedido in pedido.productos_pedido">
                        <table class="item-factura font bebas">
                            <tr>
                                <td>
                                    <h3 class="no-margin">
                                        <span ng-if="productoPedido.cant > 1">@{{ productoPedido.cant }} </span>
                                        <span style="color: #1b809e;">
                                            @{{ productoPedido.producto.tipo_producto.descripcion }} @{{ productoPedido.producto.descripcion }}
                                        </span>
                                        <span style="float: right; font-size: 0.9em; margin-top: 2px" 
                                            ng-if="productoPedido.valor_ != productoPedido.total_ && showFacturaDetails" class="animated-item-simple">
                                            @{{ productoPedido.valor_ }}
                                        </span>
                                    </h3>
                                    <ul>
                                        <li ng-if="showFacturaDetails" class="observation @{{observation.tipo}} animated-item-simple" ng-repeat="observation in productoPedido.observationList">
                                        @{{observation.texto}} <span style="float: right">@{{ observation.valor }}</span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr ng-if="showFacturaDetails">
                                <td colspan="2" class="align-right font roboto">@{{ productoPedido.total_ }}</td>
                            </tr>
                        </table>
                    </li>
                </ul>
                <div id="total">
                    <h3 class="no-margin">Total <span style="float: right; color: gray">@{{ pedido.total_ }}</span></h3>
                </div>
                <div class="row min" style="margin-top: 4px;">
                    <div class="col-md-12">
                        <button class="btn-block btn btn-warning min squared" ng-click="showFacturaDetails = !showFacturaDetails">
                            <i class="fa fa-pencil"></i> Editar
                        </button>
                    </div>
                    <div class="col-md-12">
                        <button class="btn-block btn btn-default min squared" ng-click="showFacturaDetails = !showFacturaDetails">
                            <i class="fa fa-eye@{{showFacturaDetails?'-slash':''}}"></i> Detalles
                        </button>
                    </div>
                    <div class="col-md-12">
                        <button class="btn-block btn btn-purple min squared">
                            <i class="fa fa-print"></i> Comanda
                        </button>
                    </div>
                    <div class="col-md-12">
                        <button class="btn-block btn btn-primary min squared">
                            Prefactura
                        </button>
                    </div>

                    <div class="col-md-12">
                        <button class="btn-block btn btn-purple min squared">
                            <i class="fa fa-print"></i> Comanda completa
                        </button>
                    </div>
                    <div class="col-md-12">
                        <button class="btn-block btn btn-success min squared">
                            <i class="fa fa-wpforms"></i> Pagar
                        </button>
                    </div>
                    <div class="col-md-12">
                        <button class="btn-block btn btn-danger min squared">
                            <i class="fa fa-inbox"></i> Cajón
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @include('mesa.addProducto')
    @include('mesa.addFraccionado')

</div>
<script>
    $(window).scroll(function(event) {
        var initialOS = 72;
        var oS = $('#tipos').offset().top;
        var scrollTop = $(this).scrollTop();
        var top = initialOS - scrollTop;
        
        if(top < 1){
            top = 0;
        }
        top = '' + top + 'px';
        var h = `calc(100vh - ${top})`;
        $('#tipos').css('height', h);
        $('#cuenta').css('height', h);
        $('#tipos').css('top', top);
        $('#cuenta').css('top', top);
    });
</script>
@endsection
