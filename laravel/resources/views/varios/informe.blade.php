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
        <div class = "col-md-12">
            <a href='/producto_vendido' class='link font roboto' style='font-size: 1.2em'>Productos más vendidos</a>
            <br>
            <a href='/informe_rango' class='font roboto' style='font-size: 1.2em'>Ver resumen por rango</a>
            <br/>
            <br/>
        </div>
        <div class = "col-md-6">
            
            <div class = "form-group has-feedback {{ ($errors->first('ano')) ? 'has-error'  :''}}">
                <label for = "ano" class = "control-label">Año *</label>
                <select class = "form-control" id = "ano" name = "ano">
                    <option value="0">Todos</option>
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
    </div>
    <div id="grafica">
        <div class="col-md-12" style="text-align: center">
            <h2 class="titulo"><span class="mes-num-a-nombre">{{$mes}}</span> {{$ano!=0||$ano!=null?$ano:''}}</h2>
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
        "rgb(255, 205, 86)",
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
        "rgb(255, 205, 86)",
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
        "rgb(255, 205, 86)",
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

    @if($sql2)
    
    var data_sql2_ = [0,0,0,0,0,0,0,0,0,0,0,0];
    var data_sql2 = [
                @foreach($sql2 as $barra)
                { mes: {{$barra->mes}}, suma:{{$barra->suma}} },
                @endforeach
            ];
    
    for(var i=0; i<data_sql2.length; i++){
        data_sql2_[data_sql2[i].mes-1] = data_sql2[i].suma
    }
    console.log(data_sql2_);
    console.log(monthNames);

    var grafica_barras_anos = {
        labels: monthNames,
        datasets: [{
            label: '',
            backgroundColor: colores,
            borderColor: colores,
            borderWidth: 1,
            data: data_sql2_
        }]

    };
    @endif
        
    window.onload = function() {
        var ctx1 = document.getElementById('chart-area').getContext('2d');
        window.myDoughnut = new Chart(ctx1, config1);

        @if($sql2)
        var ctx_barras_anos = document.getElementById('canvas_barras_anos').getContext('2d');
        window.myBar = new Chart(ctx_barras_anos, {
            type: 'bar',
            data: grafica_barras_anos,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                    display: 0
                },
                title: {
                    display: true,
                    text: ''
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return accounting.formatMoney(data.datasets[0].data[tooltipItem.index], '$', 0);
                        }   
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                return accounting.formatMoney(value);
                            }
                        }
                    }]
                },
                
                hover: {
                    onHover: function(e) {
                        var point = this.getElementAtEvent(e);
                        if (point.length) e.target.style.cursor = 'pointer';
                        else e.target.style.cursor = 'default';
                    }
                }

            }
        });
        document.getElementById('canvas_barras_anos').onclick = function(evt) {
            var activePoints = window.myBar.getElementsAtEvent(evt);
            if (activePoints[0]) {
                var chartData = activePoints[0]['_chart'].config.data;
                var idx = activePoints[0]['_index'];

                var label = chartData.labels[idx];
                var value = chartData.datasets[0].data[idx];
                
                $('div#grafica').fadeOut();
                window.location.href = '{{url("/informe")}}/{{$ano}}/'+nombreMesNumero(label);
            }
        };
        @endif
/*
        var ctx2 = document.getElementById('canvas-2').getContext('2d');
            window.myLine = new Chart(ctx2, config2);
            */


            
        
    };
    


</script>



<script>
    $(function(){
        @if($ano!=0)
            cargarMes();
            $("select#mes").val({{$mes}});
        @endif

        $("h1.pintar").each(function(i){
            $(this).css('background-color', colores[i]);
        });
        var suma = 0;
        for(var i=0; i<data_.length; i++){
            suma = suma + parseFloat(data_[i]);
        }
        $("#general").append('<tr><td><h1 class="">&nbsp;&nbsp;&nbsp;&nbsp;</h1></td><td><h2> TOTAL </h2></td><td><h2>$</h2></td><td><h1 style="float: right;">'+accounting.formatMoney(suma,'', 0)+'</h1></td></tr>')

        $("select#ano").on("change", function(){
            if($(this).val()==0){
                window.location.href = '{{url("/informe")}}';
            }
            else{
                cargarMes();
            }
        });

        $("select#mes").on("change", function(){
            var ano = $("select#ano").val();
            var mes = $("select#mes").val();
            if(mes == ''){
                return false;
            }
            $('div#grafica').fadeOut();
            if(mes == '0'){
                window.location.href = '{{url("/informe")}}/'+ano;
            }
            else{
                window.location.href = '{{url("/informe")}}/'+ano+"/"+mes;
            }
        });

        if($(".mes-num-a-nombre").html()=='0'){
            $(".mes-num-a-nombre").html('');
        }
        else{
            var nom_mes_ = monthNames[parseInt($(".mes-num-a-nombre").html())-1];
            $(".mes-num-a-nombre").html(nom_mes_);
        }
        function cargarMes(){
            $("select#mes").html("<option value=''>--------</option><option value='0'>Todos</option>")
            for(var i=0; i<12; i++){
                $("select#mes").append("<option value='"+(i+1)+"'>"+(monthNames[i])+"</option>")
            }
        }
    });
        
</script>


@endsection