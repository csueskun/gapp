
@extends('template.general')
@section('titulo', 'Reporte de ventas')

@section('lib')
    {{ Html::script('js/validator.min.js') }}
    {{ Html::style('css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('js/bootstrap-datetimepicker.es.js') }}

@endsection
@section('contenido')

    <section class="borde-inferior fondo-blanco">
        <div class="container">
            <h1 class="titulo">Meseros</h1>
            <br/>
        </div>
    </section>
    <section class="borde-inferior form fondo-comun">
        <div class="container">
            <h2 class="titulo">Informe de ventas por usuario<br/></h2>

            <form id="reporte" action="reporte" method="POST" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="POST"/>
                <div class="row mt-2">
                    <div class = "col-md-12">
                        <label>Usuario</label>
                        <select name="mesero" class="form-control">
                            <option value="0">Todos</option>
                            @foreach($meseros as $mesero)
                                <option value="{{$mesero->id}}">{{$mesero->nombres}} {{$mesero->apellidos}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
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
                    <div class = "col-md-4">
                    </div>
                </div>
                <div class="row mt-2">
                    <button type="button" onclick="preReporte()" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Generar informe</button>
                </div>

                <input type="hidden" name="fecha_inicio"/>
                <input type="hidden" name="fecha_fin"/>
                <br>
                <br>
            </form>
        </div>
    </section>
    <form id="reporte_" action="/mesero/informe" method="POST" target="_blank">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="POST"/>
        <input type="hidden" name="fecha_inicio"/>
        <input type="hidden" name="fecha_fin"/>
        <input type="hidden" name="mesero"/>
    </form>


    <script>
        function preReporte(){
            $("form#reporte_ input[name=mesero]").val($("form#reporte select[name=mesero]").val());
            $("form#reporte_").submit();
        }

        $(function(){
            var fecha = new Date();

            $("button#diario").append(formatearFecha(fecha, "MM dd, yyyy"));
            $("button#mensual").append(formatearFecha(fecha, "MM, yyyy"));
            $("form#reporte_ input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00"));
            $("form#reporte_ input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59"));

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
                $("form#reporte_ input[name=fecha_inicio]").val(formatearFecha(fecha, "yyyy-mm-dd 00:00:00"));
            });
            $('div#fecha_fin').on('changeDate', function() {
                var fecha = new Date($('div#fecha_fin').datetimepicker('getFormattedDate'));
                $("form#reporte_ input[name=fecha_fin]").val(formatearFecha(fecha, "yyyy-mm-dd 23:59:59"));
            });

            $(".icon-arrow-left").addClass("glyphicon-chevron-left");
            $(".icon-arrow-right").addClass("glyphicon-chevron-right");
        });
    </script>
    <style>
        .table-condensed{
            border: thin solid gray;
            background-color: white;
        }
        .table-condensed tbody{
            border-bottom: thin solid gray;
        }
        h2>button{
            font-size: 18px !important;
        }
        .is-busy{
            display: none;
        }
    </style>
@endsection