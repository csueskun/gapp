@extends('template.general')
@section('titulo', 'Pedidos H-Software')


@section('lib')
{{ Html::script('js/validator.min.js') }}
@endsection

@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class = "titulo">EDITANDO Tipo Producto
        <a href="../crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a>
        <a href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <br/>
        @include('template.status', ['status' => session('status')])
        
        <form data-toggle = "validator" role = "form" action = "../editar" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $tipo_producto->id }}">
            <input type="hidden" name="fracciones" value="">
            <input type="hidden" name="tamanos" value="">

            <div class = "col-md-6">
                <div class = "form-group has-feedback">
                    <label for = "descripcion" class = "control-label">Descripcion *</label>
                    <input type = "text"  class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion')?old('descripcion'):$tipo_producto->descripcion }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                </div>
            </div>
            <div class = "col-md-6">
                <div class = "form-group has-feedback">
                    <label for = "impresora" class = "control-label">Impresora Dedicada</label>
                    <input type = "text"  class = "form-control" id = "impresora" name = "impresora" value = "{{ old('impresora')?old('impresora'):$tipo_producto->impresora }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('impresora') }}</div>
                </div>
            </div>

            <div class = "col-md-12">
                <div class = "form-group campo-radios">
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="1" name="aplica_tamanos" {{ $tipo_producto->aplica_tamanos == '1'?'checked':'' }}>Aplica Tamaños
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="1" name="aplica_ingredientes" {{ $tipo_producto->aplica_ingredientes == '1'?'checked':'' }}>Aplica Ingredientes
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="1" name="aplica_sabores" {{ $tipo_producto->aplica_sabores == '1'?'checked':'' }}>Aplica Sabores
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="1" name="valor_editable" {{ $tipo_producto->valor_editable == '1'?'checked':'' }}>Valor editable
                    </label>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group campo-radios" id="tamanos">
                    <label for = "descripcion" class = "control-label">Tamaños *</label><br/>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="grande" name="tamano">Grande
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="extrag" name="tamano">Extra Grande
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="mediano" name="tamano">Mediano
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="pequeno" name="tamano">Pequeño
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="porcion" name="tamano">Porción
                    </label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group campo-radios" id="fracciones">
                    <label for = "descripcion" class = "control-label">Fracciones *</label><br/>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input disabled checked type="checkbox" value="1/1" name="fraccion">1/1(Completa)
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="2/2" name="fraccion">2/2(Mitades)
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="3/3" name="fraccion">3/3(Tercios)
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="4/4" name="fraccion">4/4(Cuartos)
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="checkbox" value="3/4+1/4" name="fraccion">3/4 + 1/4
                    </label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group campo-radios" id="cobro">
                    <label for = "descripcion" class = "control-label">Cobro con fracciones</label><br/>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input checked type="radio" value="0" name="cobro_fraccion"> &nbsp; Mayor valor
                    </label>
                    <label class="radio-inline" style="margin-bottom: 10px">
                        <input type="radio" value="1" name="cobro_fraccion"> &nbsp; Promedio
                    </label>
                </div>
            </div>
        
                    
            <div class = "col-xs-12">
                <div class = "form-group">
                    <button type = "submit" class = "btn btn-primary">Guardar</button>
                </div>
            </div>
    
        </form>
    </div>
</section>
<script type='text/javascript'>
    $(function(){
        var aplica_tamanos = "{{$tipo_producto->aplica_tamanos}}" == 1;
        if(!aplica_tamanos){
            $("div#tamanos").hide();
        }

        $("input[name=aplica_tamanos]").on("change",function(e){
            if($(this).is(':checked')){
                $("div#tamanos").fadeIn();
            }
            else{
                $("div#tamanos").fadeOut();
            }
        });

        $('input[name=cobro_fraccion][value="{{$tipo_producto->cobro_fraccion}}"]').prop('checked', true)


        var json_fracciones = '{{$tipo_producto->fracciones}}'.replace(/&quot;/g,'"');
        if(json_fracciones!==''){
            json_fracciones=JSON.parse(json_fracciones);
            for(var i=0;i<json_fracciones.length;i++){
                $("input[name=fraccion][value='"+json_fracciones[i]+"']").prop('checked', 'checked');
            }
        }
        var json_tamanos = '{{$tipo_producto->tamanos}}'.replace(/&quot;/g,'"');
        if(json_tamanos!==''){
            json_tamanos=JSON.parse(json_tamanos);
            for(var i=0;i<json_tamanos.length;i++){
                $("input[name=tamano][value='"+json_tamanos[i]+"']").prop('checked', 'checked');
            }
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
        $('input[name=cobro_fraccion]').val($('div#cobro').find('input:radio:checked').val());
    });
</script>
@endsection