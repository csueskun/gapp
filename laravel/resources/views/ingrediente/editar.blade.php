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
        <h1 class = "titulo">Editando Ingredientes 
            <a style="font-size: 20px;" href="../crear" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Crear Nuevo</a>
            <a style="font-size: 20px;" href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        </h1>
        <br/>
    </div>
</section>
<!--
<section class="borde-inferior fondo-rojo">
    <div class="container">
        <h1 class = "titulo">EDITANDO Ingrediente</h1>
    </div>
</section>
-->
<section class="borde-inferior form fondo-comun"  style="min-height: 80vh;">
    <div class="container">
        <!--<br/>
        <a href="../listar" class="btn btn-default"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Ir a Lista</a>
        <br/>-->
        <br/>
        @include('template.status', ['status' => session('status')])
    
    <form  data-toggle = "validator" method="POST" action="../editar" accept-charset="UTF-8" class="form" novalidate="novalidate" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $ingrediente->id }}">
        <input type="hidden" name="imagen" value="{{ $ingrediente->imagen }}">
        <input type="hidden" name='cargar_imagen' id='cargar_imagen' value='0'/>


                    
            <div class = "col-md-12">
                <div class = "form-group has-feedback {{ ($errors->first('descripcion')) ? 'has-error'  :''}}">
                    <label for = "descripcion" class = "control-label">Descripcion *</label>
                    <input type = "text" class = "form-control" id = "descripcion" name = "descripcion" required value = "{{ old('descripcion')?old('descripcion'):$ingrediente->descripcion }}">
                  <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('descripcion') }}</div>
                </div>
            </div>
        <div class = "col-md-6">
            <div class = "form-group has-feedback {{ ($errors->first('grupo')) ? 'has-error'  :''}}">
                <label for = "grupo" class = "control-label">Grupo</label>
                <input type = "text" class = "form-control" id = "grupo" name = "grupo" value = "{{ old('grupo')?old('grupo'):$ingrediente->grupo }}">
                <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                <div class = "help-block with-errors">{{ $errors->first('grupo') }}</div>
            </div>
        </div>
        <div class = "col-md-4">
            <div class = "form-group has-feedback {{ ($errors->first('unidad')) ? 'has-error'  :''}}">
                <label for = "descripcion" class = "control-label">Unidad *</label>
                <select class = "form-control" id = "unidad" name = "unidad" required>
                    <option value="gr">Gramos</option>
                    <option value="kg">Kilogramos</option>
                    <option value="lt">Litros</option>
                    <option value="ml">Miliitros</option>
                    <option value="und">Unidad</option>
                </select>
                <div class = "help-block with-errors">{{ $errors->first('unidad') }}</div>
                <script>
                    var unidad = "{{ old('unidad')?old('unidad'):$ingrediente->unidad }}";
                    var unidades= {
                        'gr': 'gr',
                        'grs': 'gr',
                        'kg': 'kg',
                        'kgr': 'kg',
                        'ml': 'ml',
                        'mls': 'ml',
                        'lt': 'lt',
                        'lts': 'lt',
                        'und': 'und',
                        'unds': 'und'
                    }
                    $("select#unidad").val(unidades[unidad]);</script>
            </div>
        </div>

        <div class = "col-md-2" style="height: 91px">
            <div class = "form-group">
                <label class="radio-inline control-label" style="font-weight: bold; padding-left: 0px;">
                    Visible <input {{ $ingrediente->visible=='1'?'checked':'' }} id="visible" type="checkbox" value="1" name="visible" style="width: 20px; margin-left: 10px;">
                </label>
                <span class = "form-control-feedback"></span>
                <div class = "help-block"></div>
            </div>
        </div>
        
        <div class = "col-md-12">
                <div class = "form-group has-feedback {{ ($errors->first('_imagen')) ? 'has-error'  :''}}">
                    <img id="img_imagen" src="/images/ingrediente/ingrediente.jpg" height="200" onclick="$('#_imagen').trigger('click')" style="cursor: pointer"/><br/>
                    <label for = "_imagen" class = "control-label" >Imagen *</label>
                    <input type = "file"  class = "form-control" id = "_imagen" name = "_imagen" value = "">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('_imagen') }}</div>
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
<script>
    $(document).ready(function(){
        $('input[type="file"]').change(function(e){
            var fileName = e.target.files[0].name;
            $('#cargar_imagen').val(1);
            $(this).closest('form').validator('destroy');
            $(this).closest('form').submit();
        });
        $('img#img_imagen').attr('src','/images/ingrediente/{{$ingrediente->imagen}}');
    });
</script>
@endsection