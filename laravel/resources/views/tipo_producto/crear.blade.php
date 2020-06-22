@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">CREANDO Tipo Producto
        <a href="listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        <br/>
    <form data-toggle = "validator" role = "form" action = "crear" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="fracciones" value="">
        <input type="hidden" name="tamanos" value="">
        <div class = "col-md-6">
            <div class = "form-group has-feedback">
                <label for = "descripcion" class = "control-label">Descripcion *</label>
                <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion') }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
            </div>
        </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback">
                <label for = "impresora" class = "control-label">Impresora Dedicada</label>
                <input type = "text"  class = "form-control" id = "impresora" name = "impresora" value = "{{ old('impresora') }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('impresora') }}</div>
            </div>
        </div>

        <div class = "col-md-12">
            <div class = "form-group campo-radios">
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="1" name="aplica_tamanos">Aplica Tamaños
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="1" name="aplica_ingredientes">Aplica Ingredientes
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="1" name="aplica_sabores">Aplica Sabores
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="1" name="valor_editable">Valor editable
                </label>
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group campo-radios" id="fracciones">
                <label for = "descripcion" class = "control-label">Fracciones *</label><br/>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input disabled checked type="checkbox" value="1/1" name="fraccion">1/1(Completa)
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="2/2" name="fraccion">1/2(Mitades)
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="3/3" name="fraccion">1/3(Tercios)
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input type="checkbox" value="4/4" name="fraccion">1/4(Cuartos)
                </label>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group campo-radios" id="tamanos">
                <label for = "descripcion" class = "control-label">Tamaños *</label><br/>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input checked type="checkbox" value="grande" name="tamano">Grande
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input checked type="checkbox" value="extrag" name="tamano">Extra Grande
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input checked type="checkbox" value="mediano" name="tamano">Mediano
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input checked type="checkbox" value="pequeno" name="tamano">Pequeño
                </label>
                <label class="radio-inline" style="margin-bottom: 10px">
                    <input checked type="checkbox" value="porcion" name="tamano">Porción
                </label>
            </div>
        </div>
                    
        <div class = "col-xs-12">
            <div class = "form-group">
                <button type = "submit" class = "btn btn-primary">Crear</button>
            </div>
        </div>
    
        </form>
    </div>
</section>

<script type='text/javascript'>
    $(function(){
        $("div#tamanos").hide();
    });
    $("input[name=aplica_tamanos]").on("change",function(e){
        if($(this).is(':checked')){
            $("div#tamanos").fadeIn();
        }
        else{
            $("div#tamanos").fadeOut();
        }
    });
    $("form").on("submit",function(e){

        var fracciones = JSON.parse("{}");
        fracciones = $.map($("div#fracciones").find('input:checkbox:checked'), function (e, i) {
                return e.value;
        });
        var tamanos = JSON.parse("{}");
        tamanos = $.map($("div#tamanos").find('input:checkbox:checked'), function (e, i) {
                return e.value;
        });

        if($('input[name=aplica_tamanos]').prop('checked')){

            if(!tamanos.length>0){
                mostrarError('Debe seleccionar al menos un tamaño')
                $('div#tamanos label').css('color', 'red');
                e.preventDefault();
            }
            else{
                mostrarFullLoading();
            }
        };

        $("input[name=fracciones]").val(JSON.stringify(fracciones));
        $("input[name=tamanos]").val(JSON.stringify(tamanos));

    });
</script>
@endsection