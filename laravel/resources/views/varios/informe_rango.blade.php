@extends('template.general')
@section('titulo', 'Pedidos H-Software')
@section('contenido')

@section('lib')
{{ Html::script('js/moment.js') }}
{{ Html::script('js/moment.es.js') }}
{{ Html::script('js/Chart.bundle.min.js') }}
{{ Html::script('js/accounting.min.js') }}
@endsection

<section class="borde-inferior lista fondo-rojo">
    <div class="container_ centrado">
        <h1 class="titulo">Resumen</h1>
    </div>
</section>

<section class="borde-inferior lista fondo-comun">
    <br/>
    <div class="container">
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('ano')) ? 'has-error'  :''}}">
                <label for = "ano" class = "control-label">Año *</label>
                <select class = "form-control" id = "ano" name = "ano">
                    @foreach($anos as $ano_)
                    <option>{{$ano_->ano}}</option>
                    @endforeach
                </select>
                <script>$("select#ano").val({{$ano}});</script>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('ano') }}</div>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('mes')) ? 'has-error'  :''}}">
                <label for = "mes" class = "control-label">Mes *</label>
                <select class = "form-control" id = "mes" name = "mes">
                </select>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('mes') }}</div>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('ano2')) ? 'has-error'  :''}}">
                <label for = "ano2" class = "control-label">Año *</label>
                <select class = "form-control" id = "ano2" name = "ano2">
                    @foreach($anos as $ano_)
                    <option>{{$ano_->ano}}</option>
                    @endforeach
                </select>
                <script>$("select#ano").val({{$ano2}});</script>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('ano2') }}</div>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('mes')) ? 'has-error'  :''}}">
                <label for = "mes2" class = "control-label">Mes *</label>
                <select class = "form-control" id = "mes2" name = "mes2">
                </select>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('mes2') }}</div>
            </div>
        </div>
        <div class = "col-md-12" style="text-align: center">
            <button type="button" class="btn btn-success" style="font-size: 1.5em" onclick="go()"><i class="glyphicon glyphicon-search"></i> Ver</button>
        </div>
    </div>
    <div id="grafica">
        <div class="col-md-12" style="text-align: center">
            <h2 class="titulo"><span class="mes-num-a-nombre">{{$mes}}</span> {{$ano!=0||$ano!=null?$ano:''}} - <span class="mes-num-a-nombre">{{$mes2}}</span> {{$ano2!=0||$ano2!=null?$ano2:''}}</h2>
        </div>
        <div class="col-md-5">

            <div id="canvas-holder"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <canvas id="chart-area" class="chartjs-render-monitor" style="display: block; width: 636px; height: 318px;"></canvas>
            </div>
        </div>
        <div class = "col-md-7">
                <br/><br/>
            <table id="general" class="etiquetas-resumen" style="">
                @foreach($sql as $tipo)
                <tr><td><h1 class="pintar">&nbsp;&nbsp;&nbsp;&nbsp;</h1></td><td><h2> {{$tipo->descripcion}} </h2></td><td><h2>$</h2></td><td><h1 style="float: right;">{{number_format($tipo->total)}}</h1></td></tr>
                @endforeach
                <tr>
                    <td colspan="2"><br/></td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <canvas  id="canvas_barras_anos"></canvas>
        </div>
    </div>
    
    <div class="col-md-6">
        
    </div>
    <div class="col-md-6">
        <br/>
        <br/>
        
    </div>

    <br/>
</section>


<script>
        
    var colores = [
        "rgb(54, 162, 235)",
        "rgb(255, 99, 132)",
        "rgb(255, 159, 64)",
        "rgb(153, 102, 255)",
        "rgb(75, 192, 192)",
        "rgb(255, 205, 86)",
        "rgb(54, 162, 235)",
        "rgb(255, 99, 132)",
        "rgb(255, 159, 64)",
        "rgb(153, 102, 255)",
        "rgb(75, 192, 192)",
        "rgb(255, 205, 86)"
    ];

    var data_ = [
        @foreach($sql as $tipo)
            {{$tipo->total}},
        @endforeach
    ]


    var config1 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: data_,
                backgroundColor: [
                @for ($i = 0; $i < count($sql); $i++)
                    colores[{{$i}}],
                @endfor
                ],
                label: 'Dataset 1'
            }],
            labels: [
                    @foreach($sql as $tipo)
                    '{{$tipo->descripcion}}',
                    @endforeach
            ]
        },
        options: {
            responsive: true,
            legend: {
                display: 0,
            },
            title: {
                display: true,
                text: ''
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.labels[tooltipItem.index] + ' = ' + accounting.formatMoney(data.datasets[0].data[tooltipItem.index], '$', 0);
                    }   
                }
            }
        }
    };


        
    window.onload = function() {
        var ctx1 = document.getElementById('chart-area').getContext('2d');
        window.myDoughnut = new Chart(ctx1, config1);

        
        
        
        
    };
    


</script>



<script>
    function go(){
        window.location.href = '/informe_rango/'+$("select#ano").val()+'/'+$("select#mes").val()+'/'+$("select#ano2").val()+'/'+$("select#mes2").val()+'';
    }
    function cargarMeses(){
        $("select#mes").html("");
        for(var i=0; i<12; i++){
            var selected = '';
            if({{$mes}}==(i+1)){
                selected = 'selected ';
            }
            $("select#mes").append("<option "+selected+"value='"+(i+1)+"'>"+(monthNames[i])+"</option>")
        }
        $("select#mes2").html("");
        for(var i=0; i<12; i++){
            var selected = '';
            if({{$mes2}}==(i+1)){
                selected = 'selected ';
            }
            $("select#mes2").append("<option "+selected+"value='"+(i+1)+"'>"+(monthNames[i])+"</option>")
        }
    }
    $(function(){
        cargarMeses();
        $(".mes-num-a-nombre").each(function(){
            if($(this).html()=='0'){
                $(this).html('');
            }
            else{
                var nom_mes_ = monthNames[parseInt($(this).html())-1];
                $(this).html(nom_mes_);
            }
        });
        $("h1.pintar").each(function(i){
            $(this).css('background-color', colores[i]);
        });
        
    });
        
</script>


@endsection