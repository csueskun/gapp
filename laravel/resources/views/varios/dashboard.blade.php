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
        <h1 class="titulo">INFORMACIÓN GENERAL</h1>
    </div>
</section>

<section class="borde-inferior lista fondo-comun">
    <br/>
    <!--
    <div class="col-md-12">
        <canvas id="canvas0"></canvas>
    </div>
-->
    <div class="col-md-6">
        <canvas id="canvas"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="canvas2"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="canvas3"></canvas>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6 ficha-db mb-2">
                <div class="col-md-12 centrado fuente bebas" style="background-color: #5cb85c;">
                    <h3>TOTAL PEDIDOS HOY</h3>
                    <h1></h1>
                </div>
            </div>
            <div class="col-md-6 ficha-db mb-2">
                <div class="col-md-12 centrado fuente bebas" style="background-color: #337ab7">
                    <h3>TOTAL PEDIDOS ACTIVOS</h3>
                    <h1></h1>
                </div>
            </div>
            <div class="col-md-6 ficha-db mb-2">
                <div class="col-md-12 centrado fuente bebas" style="background-color: rgb(255, 159, 64)">
                    <h3>TOTAL DOMICILIOS ACTIVOS</h3>
                    <h1></h1>
                </div>
            </div>
            <div class="col-md-6 ficha-db mb-2">
                <div class="col-md-12 centrado fuente bebas" style="background-color: #d9534f">
                    <h3>OCUPACIÓN DE MESAS</h3>
                    <h1></h1>
                </div>
            </div>
        </div>
        <!-- <canvas id="canvas4"></canvas> -->
    </div>
    <br/>
</section>


<script>
    var loaded = [false, false, false, false];

    var report1Data = {};
        
    var colores = [
        "rgb(54, 162, 235)",//azul
        "rgb(255, 99, 132)",//rojo
        "rgb(255, 159, 64)",//naranja
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

    function barReportConfig(data){
        return {
            type: 'horizontalBar',
            data: data,
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Horizontal Bar Chart'
                    }
                },
                tooltips: {
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return accounting.formatMoney(data.datasets[0].data[tooltipItem.index], '$', 0);
                        }
                    }
                }
            },
        }
    }

    function lineReportConfig(data){
        return {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                    }
                },
                tooltips: {
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return accounting.formatMoney(data.datasets[0].data[tooltipItem.index], '$', 0);
                        }
                    }
                }
            },
        }
    }

    function transparentice(s, o='0.5'){
        var i = s.length-1;
        s=s.slice(0, i) + ", "+o+ s.slice(i);
        return s;
    }
        
    var loadReport0 = function(config) {
        loadReport('canvas0', config, 0);
    };
    var loadReport1 = function(config) {
        if(loaded[1]){
            console.log(777);
            loaded[1].update(config);
            return false;
        }
        loadReport('canvas', config, 1);
    };
    var loadReport2 = function(config) {
        loadReport('canvas2', config, 2);
    };
    var loadReport3 = function(config) {
        loadReport('canvas3', config, 3);
    };
    var loadReport = function(id, config, i) {
        var ctx1 = document.getElementById(id).getContext('2d');
        loaded[i] = new Chart(ctx1, config);
    };


    $(function(){
        loadReport1Data();
        loadReport2Data();
        // loadReport0Data();
        loadPedidoData();
        loadVendedoresData();
    });
    
    function loadReport0Data(){
        var days = [];
        var ventas = [];
        var allDays = [];
        var gastos = [];
        $.get('/dashboard/report0/data', function (data) {
            try {
                data.ventas.forEach(function(r){
                    if(allDays.includes(r.day)){}
                    else{
                        allDays.push(r.day);
                    }
                });
            } catch (error) {}
            try {
                data.gastos.forEach(function(r){
                    if(allDays.includes(r.day)){}
                    else{
                        allDays.push(r.day);
                    }
                });
            } catch (error) {}
            allDays.sort(function(a, b) {return a - b;});
            var vd = false;
            var gd = false;
            allDays.forEach(function(d){
                vd = false;
                gd = false;
                days.push(diaDelMesActual(d)+' '+d);
                data.ventas.forEach(function(r){
                    if(d==r.day){
                        ventas.push(r.total);
                        vd = true;
                    }
                });
                data.gastos.forEach(function(r){
                    if(d==r.day){
                        gastos.push(-1*r.total);
                        gd = true;
                    }
                });
                if(!gd){
                    gastos.push(0);
                }
                if(!vd){
                    ventas.push(0);
                }
            });
            var data = {
                labels: days,
                datasets: [{
                    label: 'VENTAS DEL MES',
                    data: ventas,
                    borderColor: colores[0],
                    backgroundColor: transparentice(colores[0], '1.0')
                },{
                    label: 'GASTOS DEL MES',
                    data: gastos,
                    borderColor: colores[1],
                    backgroundColor: transparentice(colores[1], '1.0')
                },]
            };
            loadReport0(report0Config(data));
        });
    }

    function getMesParam(){
        var mes = getParameterByName('mes');
        if(!mes){
            return '';
        }
        return '?mes='+mes;
    }
    
    function loadReport1Data(){
        var days = [];
        var totals = [];
        $.get('/dashboard/report1/data'+getMesParam(), function (data) {
            var sum = 0;
            try {
                data.forEach(function(r){
                    days.push(diaDelMesActual(r.day)+' '+r.day);
                    totals.push(r.total);
                    sum += parseFloat(r.total);
                });
            } catch (error) {}

            sum = accounting.formatMoney(sum, '$', 0)
            var data = {
                labels: days,
                datasets: [{
                    label: 'VENTAS DEL MES ' + sum,
                    data: totals,
                    borderColor: colores[0],
                    backgroundColor: transparentice(colores[0])
                }]
            };
            loadReport1(lineReportConfig(data));
        });
    }
    
    function loadReport2Data(){
        var days = [];
        var totals = [];
        $.get('/dashboard/report2/data'+getMesParam(), function (data) {
            var sum = 0;
            try {
                data.forEach(function(r){
                    days.push(diaDelMesActual(r.day)+' '+r.day);
                    totals.push(r.total);
                    sum+= parseFloat(r.total);
                });
            } catch (error) {}
            sum = accounting.formatMoney(sum, '$', 0);
            var data = {
                labels: days,
                datasets: [{
                    label: 'GASTOS DEL MES '+ sum,
                    data: totals,
                    borderColor: colores[1],
                    backgroundColor: transparentice(colores[1])
                }]
            };
            loadReport2(lineReportConfig(data));
        });
    }
    
    function loadVendedoresData(){
        var users = [];
        var total = [];
        $.get('/dashboard/vendedores/data'+getMesParam(), function (data) {
            var sum = 0;
            try {
                data.forEach(function(r){
                    users.push(r.usuario);
                    total.push(r.total);
                });
            } catch (error) {}
            var data = {
                labels: users,
                datasets: [{
                    label: 'VENTAS POR USUARIO DEL MES',
                    data: total,
                    backgroundColor: colores,
                }]
            };
            loadReport3(barReportConfig(data));
        });
    }

    function loadPedidoData(){
        $.get('/dashboard/pedidos/data', function (data) {
            try {$($(".ficha-db")[0]).find('h1').html(data.hoy.total);} catch (error) {}
            try {$($(".ficha-db")[1]).find('h1').html(data.activos.total);} catch (error) {}
            try {$($(".ficha-db")[2]).find('h1').html(data.domicilios.total);} catch (error) {}
            try {$($(".ficha-db")[3]).find('h1').html(data.mesas.ocupadas.length+' / '+data.mesas.total);} catch (error) {}
        });
    }
</script>
<style>
    .ficha-db{}
    .ficha-db h1,.ficha-db h3{color: white}
    .ficha-db h1{margin-top: 0}
</style>


@endsection