@extends('template.general')
@section('titulo', 'Más vendidos - Pedidos H-Software')
@section('contenido')

@section('lib')
{{ Html::script('js/moment.js') }}
{{ Html::script('js/moment.es.js') }}
{{ Html::script('js/Chart.bundle.min.js') }}
{{ Html::script('js/accounting.min.js') }}
{{ Html::script('/controller/informe.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
@endsection
<div ng-app="myApp" ng-controller="informeController" ng-init='loadData()'>
    
    <section class="borde-inferior lista fondo-comun">
        <button id='loadData' ng-click='loadData()' style="display: none;">LoadData</button>
        <input ng-model='filter.fecha_inicio' type="text" name="fecha_inicio" style="visibility: hidden;">
        <input ng-model='filter.fecha_fin' type="text" name="fecha_fin" style="visibility: hidden;">
        
        <div class="container">
            
            <a href="/informes" class="link font roboto" style="font-size: 1.2em"><strong>Volver</strong></a>
            <p class="link font roboto" style="font-size: 1.2em">
                <a href="#" class="link" data-toggle='collapse' data-target='#filtro'>
                    <strong>Desde @{{filter.fecha_inicio?filter.fecha_inicio.substring(0, 10):'el inicio'}} Hasta @{{filter.fecha_fin?filter.fecha_fin.substring(0, 10):'el final'}}</strong>
                </a>
            </p>
            
                
            <div id="filtro" class='collapse'>
                <div class = "col-md-4">
                    <label>Fecha de Inicio</label>
                    <div class = "campo tipo fecha form-group has-feedback">
                        <div id="fecha_inicio"></div>
                    </div>
                </div>
                <div class = "col-md-4">
                    <label>Fecha Final</label>
                    <div class = "campo tipo fecha form-group has-feedback">
                        <div id="fecha_fin"></div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="col-md-12" id="canvas-container">
        <canvas id="canvas" style="width: 100%;"></canvas>
        </div>
    </section>
    <script>
        $(function(){
            var fecha = new Date();

            $('div#fecha_inicio').datetimepicker({
                endDate: new Date(),
                language:  'es',
                autoclose: 1,
                todayHighlight: 1,
                minView: 2,
                forceParse: 0
            });
            $('div#fecha_fin').datetimepicker({
                language:  'es',
                autoclose: 1,
                todayHighlight: 1,
                minView: 2,
                forceParse: 0
            });
            
            $('div#fecha_inicio').on('changeDate', function() {
                var fecha = new Date($('div#fecha_inicio').datetimepicker('getFormattedDate'));
                $("input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00")).trigger('change');
                $('#loadData').click();

            });
            $('div#fecha_fin').on('changeDate', function() {
                var fecha = new Date($('div#fecha_fin').datetimepicker('getFormattedDate'));
                $("input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59")).trigger('change');
                $('#loadData').click();
            });
            
            $(".icon-arrow-left").addClass("glyphicon-chevron-left");
            $(".icon-arrow-right").addClass("glyphicon-chevron-right");
        });
    </script>
    <style>
        #canvas{
            width: 100%;
        }
        .table-condensed{
            border: thin solid #e4e4e4;
        }
        .table-condensed th{
            background-color: white;
            border: thin solid #e4e4e4;
        }
        .table-condensed tbody{
            background-color: white;
            border-bottom: thin solid #e4e4e4;
        }
        h2>button{
            font-size: 18px !important;
        }
        .is-busy{
            display: none;
        }
    </style>
</div>

@endsection