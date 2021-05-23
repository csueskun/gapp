
@extends('template.general')
@section('titulo', 'Pedidos H-Software')

@section('lib')
    <meta name="csrf-token" content="{{ Session::token() }}"> 
    <meta name="pedido_id" content="{{$pedido->id}}">
    <meta name="ver" content="1">
    <meta name="drawer" content="{{Auth::user()->rol=='Administrador'?1:0}}">
    <script src="/js/jquery.inputmask.bundle.js"></script>
    {{ Html::script('bootstrap-3.3.6-dist/js/confirmation.js') }}
    {{ Html::style('css/combos.css') }}
    {{ Html::script('js/combos.js') }}
@endsection
@section('contenido')
<section class="borde-inferior fondo-blanco">
    <div class="container">
        <br/>
        @if($pedido->mesa_id == 0)
            <h1 class="titulo titulo-boton">Detalles del domicilio</h1>
            <a class="btn btn-default" href="../../domicilios?ordenar_por=id&sentido=desc">
                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Volver a domicilios activos
            </a>
        @else
            <h1 class="titulo titulo-boton">Detalles del pedido</h1>
            <a class="btn btn-default font bebas" href="../listar?ordenar_por=id&sentido=desc">
                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a pedidos activos
            </a>
            <a class="btn btn-default font bebas" href="../archivados?ordenar_por=id&sentido=desc">
                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a pedidos archivados
            </a>
        @endif
        <br/>
    </div>
</section>

<section class="borde-inferior lista fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
        <table width="100%">
            <tr>
                <th width="140"><h4 class="titulo">Número de Orden: </h4></th>
                <td><h2 class="titulo">{{$pedido->id}}</h2></td>
                <th><h4 class="titulo">Creación: </h4></th>
                <td><h2 class="titulo">{{ $pedido->fechaC }}</h2></td>
                @if($pedido->estado == 2)
                <th><h4 class="titulo">Pagado: </h4></th>
                <td><h2 class="titulo">{{ $pedido->fechaU }}</h2></td>
                @endif
            </tr>
        </table>
        <table width="100%">
            <tr>
                <th width="140"><h4 class="titulo">Mesero: </h4></th>
                <td><h2 class="titulo">{{$pedido->usuario->nombres}} {{$pedido->usuario->apellidos}}</h2></td>
                @if($pedido->mesa_id != 0)
                <th><h4 class="titulo">Mesa Número: </h4></th>
                <td><h2 class="titulo">{{$pedido->mesa_id}}</h2></td>
                @endif
                <th><h4 class="titulo">Turno: </h4></th>
                <td><h2 class="titulo">{{$pedido->turno}}</h2></td>
                <th><h4 class="titulo">Caja: </h4></th>
                <td><h2 class="titulo">{{$pedido->caja_id}}</h2></td>
            </tr>
        </table>

        <table width="100%" style="border-bottom: thin solid #bdbdbd;">
            <tr>
                @if($pedido->mesa_id == 0)
                @if(($pedido->obs != null && $pedido->obs != ''))
                <th width="1"><h4 class="titulo">Entregar en: </h4></th>
                <td><h2 class="titulo">
                @if(($pedido->obs != null && $pedido->obs != ''))
                @if(isset(json_decode($pedido->obs)->entregar_en))
                    @if(json_decode($pedido->obs)->entregar_en=='CAJA')
                    CAJA ({{ isset(json_decode($pedido->obs)->entregar_obs)?json_decode($pedido->obs)->entregar_obs : '' }})
                    @else
                    {{ isset(json_decode($pedido->obs)->entregar_obs)?json_decode($pedido->obs)->entregar_obs : '' }}
                    @endif
                @endif
                @endif
                @endif
                @else
                <th width="1"><h4 class="titulo">Observación: </h4></th>
                <td><h2 class="titulo">
                @if(($pedido->obs != null && $pedido->obs != ''))
                {{isset(json_decode($pedido->obs)->para_llevar)?(" ".json_decode($pedido->obs)->para_llevar.". "):""}}
                {{isset(json_decode($pedido->obs)->observacion)?(json_decode($pedido->obs)->observacion." "):""}}
                @endif
                </h2></td>
                @endif
            </tr>
        </table>
        <br>
        <br>
        <div class="col-xs-12" id="pedido">
        </div>
    </div>
</section>
<style>

th{
    padding: 4px;
    vertical-align: bottom;
    border: thin solid #bdbdbd;
    border-bottom: none;
}
td{
    padding: 4px;
    vertical-align: middle;
    border: thin solid #bdbdbd;
    border-bottom: none;

}
h4.titulo{
    color: #adadad;

}
button#boton-observaciones{
    display: none;
}
ul.items_pedido>li>span.detalles{
    display: inline-block;
}
</style>


    <div class="modal fade" tabindex="-1" role="dialog" id='modal_pagar' aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 420px">
                <!-- <div class="modal-header" style="">
                    <h1 class="modal-title" id="exampleModalLabel">Observaciones</h1>
                </div> -->
                <div class="modal-body">
                    <div id="cambio">
                        <table>
                            <tr>
                                <td class="label">Total</td><td id='cambio_total'><input class="form-control" readonly/></td>
                            </tr>
                            <tr>
                                <td class="label">Paga</td><td id='cambio_pagar-con'><input onkeyup="calcularCambio()" class="form-control"/></td>
                            </tr>
                            <tr>
                                <td class="label" style='width: 170px;display: inline-block; text-align: left'>Cambio</td><td id='cambio_cambio'><input class="form-control"/></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group centrado">
                        <button style="font-size:34px;padding: 4px 6px;" type="button" onclick="preEnviarFormPagar()" class = "font bebas btn btn-success btn-lg"><span class="fa fa-usd"></span> Pagar</a>
                            @if(Auth::user()->rol=='Administrador')
                        <button style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-danger btn-lg font bebas" onclick="gaveta()"><span class="fa fa-inbox"></span> Cajón</button>
                            @endif
                        <button style="font-size:34px;padding: 4px 6px;" type="button" class="btn btn-default btn-lg font bebas" data-dismiss="modal"><span class="fa fa-close"></span> Salir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{ Html::script('js/accounting.min.js') }}
{{ Html::script('js/ordenar.js') }}
@endsection
