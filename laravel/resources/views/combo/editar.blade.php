
@extends('template.general')
@section('titulo', 'Crear Combo')

@section('lib')
{{ Html::script('js/validator.min.js') }}
{{ Html::script('js/funciones.js') }}
{{ Html::style('css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('js/bootstrap-datetimepicker.es.js') }}
{{ Html::script('js/accounting.min.js') }}
<script src="/js/jquery.inputmask.bundle.js"></script>

@endsection
@section('contenido')

<section class="borde-inferior fondo-blanco">
    <div class="container">
        <h1 class="titulo">Editando Combo
            <a href="/combo" class="btn btn-default"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Volver a la lista</a>
        </h1>
        <br/>
    </div>
</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
        <br>
        <form data-toggle = "validator" role = "form" action = "crear" method="POST">
            {{ csrf_field() }}
            <div class = "col-md-9">
                <div class = "form-group has-feedback {{ ($errors->first('nombre')) ? 'has-error'  :''}}">
                    <label for = "nombre" class = "control-label">Nombre *</label>
                    <input type = "text"  class = "form-control" id = "nombre" name = "nombre" required value = "{{ old('nombre')?:$combo->nombre }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('nombre') }}</div>
                </div>
            </div>
            <div class = "col-md-3 col-sm-5">
                <div class = "form-group has-feedback {{ ($errors->first('precio')) ? 'has-error'  :''}}">

                    <label for = "precio" class = "control-label">Precio *</label>

                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input original="{{$combo->precio}}" readonly type = "number"  class = "form-control curr" id = "precio" name = "precio" required value = "{{ old('precio')?:($combo->precio * 1000 / 1000) }}">
                    </div>

                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('precio') }}</div>
                </div>
            </div>
            <div class = "col-md-12">
                <div class = "form-group has-feedback">
                    <label for = "_imagen" class = "control-label" >Imagen *</label><br/>
                    <div id="image-container" onclick="$('#archivo1').trigger('click')" style="border: thin solid #616161;width: 290px; height: 140px;padding: 4px;cursor:pointer;border-radius: 4px;background-color:white">
                        <div style="background-position:center;background-size: cover;width: 100%;height: 100%;background-image: url('/images/combo/{{$combo->imagen}}')"></div>
                    </div>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <input type="hidden" id="imagen" name="imagen" value="{{$combo->imagen}}">
                </div>
            </div>
            <div class = "col-md-12">
                <button class="btn btn-success" href="diario" onclick="guardarCombo()">
                    <h3 class="titulo" style="color: white; margin: 4px"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Guardar Combo</h3>
                </button>
            </div>
            <div class = "col-md-12">
                <table width="100%" class="datatable table table-striped table-bordered dataTable no-footer">
                    <thead>
                        <tr>
                            <th>
                                Producto
                            </th>
                            <th width="150">Valor</th>
                            <th width="120">Tamano</th>
                            <th width="100">Cantidad</th>
                            <th width="1"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($combo->comboProductos as $comboProducto)
                    <tr>
                        <td>
                            {{$comboProducto->producto->tipo_producto->descripcion}} {{$comboProducto->producto->descripcion}}
                        </td>
                        <td>
                            $ {{number_format($comboProducto->valor, 0)}}
                        </td>
                        <td>
                            @if(isset(array('unico'=>'Único', 'grande'=>'Grande', 'mediano'=>'Mediano', 'pequeno'=>'Pequeño', 'extrag'=>'Extra Gr.', 'porcion'=>'Porción' )[$comboProducto->tamano]))
                            {{array('unico'=>'Único', 'grande'=>'Grande', 'mediano'=>'Mediano', 'pequeno'=>'Pequeño', 'extrag'=>'Extra Gr.', 'porcion'=>'Porción' )[$comboProducto->tamano]}}
                            @endif
                        </td>
                        <td>
                            {{number_format($comboProducto->cantidad, 0)}}
                        </td>
                        <td><button onclick='borrarComboProducto({{$comboProducto->id}}, $(this).closest("tr"))' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span></button></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            <select class = "form-control has-feedback producto" id="producto_lista" style="" onchange="setTamanos($(this))">
                                <option value="">---</option>
                                @foreach($producto_lista as $producto)
                                    <option tamanos="{{$producto->tamanos}}" value="{{ $producto->id }}">{{ $producto->tipo_producto->descripcion }} {{ $producto->descripcion }}</option>
                                @endforeach
                                <select/>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type = "number" min="0" class = "form-control curr" name = "pc_precio" required value = "0" onkeyup="updatePrecio()">
                            </div>
                        </td>
                        <td>
                            <select class = "form-control has-feedback tamano" id="tamano" style="">
                                <option value="unico">Único</option>
                                <select/>
                        </td>
                        <td>
                            <select class = "form-control has-feedback cantidad" id="cantidad" onchange="updatePrecio()">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <select/>
                        </td>
                        <td><button onclick='addComboProducto($(this).closest("tr"))' disabled class='btn btn-success disabled'><span class='glyphicon glyphicon-plus-sign'></span></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <form enctype="multipart/form-data" id="formuploadajax" method="post">
        <input style="position: absolute; visibility: hidden" type="file" id="archivo1" name="archivo1" onchange="$('#formuploadajax').submit()"/>
    </form>
    <script>
        $(function(){
            $("#formuploadajax").on("submit", function(e){
                if( document.getElementById("archivo1").files.length == 0 ){
                    return false;
                }
                mostrarFullLoading();
                e.preventDefault();
                var f = $(this);
                var formData = new FormData(document.getElementById("formuploadajax"));
                $.ajax({
                    url: "/combo/{{$combo->id}}/imagen",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                })
                    .done(function(res){
                        $('div#image-container>div').css('background-image', `url('/images/combo/${res}')`);
                        $('input#imagen').val(res);
                        ocultarFullLoading();
                    });
            });
        });
    </script>

</section>
<section class="borde-inferior form fondo-comun">
    <div class="container">
    </div>
</section>
<script>
    $(function () {
        $("form").on('submit',function (e) {
            e.preventDefault();
        });
        $("input.curr").inputmask("currency", { digits: 0 });
    })

    var tamanosDict = {
        'unico': 'Único',
        'pequeno': 'Pequeño',
        'mediano': 'Mediano',
        'grande': 'Grande',
        'extrag': 'Extra Gr.',
        'porcion': 'Porción',
    }
    function updatePrecio(){
        var total = $('input#precio').attr('original');
        var cantidad = $('table tbody tr:last-child').find('td>select.cantidad').val();
        var valor = $('table tbody tr:last-child').find('input[name=pc_precio]').val();
        $('input#precio').val(parseFloat(total) + parseInt(cantidad) * parseFloat(valor));
    }
    function guardarCombo(){
        $("form").submit();
        var valid = true;
        var error = '';
        var nombre = $('input#nombre').val();
        var precio = $('input#precio').val();
        if(!nombre || nombre == ''){
            return false;
        }
        if(!precio || precio == ''){
            return false;
        }
        var data = {
            nombre: nombre,
            precio: precio
        }
        mostrarFullLoading();
        $.post('/combo/{{$combo->id}}', data, function(data){
            if(data.status && data.status  == 200){
                mostrarSuccess('Combo guardado');
            }
            else if(data.status && data.status  == 201){
                mostrarWarning(data.message);
            }
            else{
                mostrarError('No se pudo crear el combo');
            }
            ocultarFullLoading();
        });

    }
    function borrarComboProducto($id, $tr){
        mostrarFullLoading();
        $.post('/combo-productos/'+$id+"/borrar", function(data){
            if(data.status && data.status  == 200){
                $tr.remove();
                $.post('/combo/{{$combo->id}}/recalcular', function(datax){
                    $('input#precio').val(datax.precio);
                    mostrarSuccess(data.mensaje);
                    ocultarFullLoading();
                });
            }
            else{
                ocultarFullLoading();
                mostrarWarning(data.mensaje);
            }
        });

    }
    function addComboProducto($tr){
        mostrarFullLoading();
        var comboProducto = {
            producto: $tr.find('td>select.producto').val(),
            tamano: $tr.find('td>select.tamano').val(),
            cantidad: $tr.find('td>select.cantidad').val(),
            valor: $tr.find('td input[name=pc_precio]').val(),
            combo: '{{$combo->id}}',
        };
        var nombreProducto = $tr.find('td>select.producto option:selected').html();
        var nombreTamano = $tr.find('td>select.tamano option:selected').html();
        $.post('/combo-productos', comboProducto, function(data){
            if(data.status && data.status  == 200 && data.producto && data.producto.id){
                $('table tbody tr:last-child').before(`<tr>
                        <td>
                            ${nombreProducto}
                        </td>
                        <td>
                            ${accounting.formatMoney(data.producto.valor, '$ ', 0)}
                        </td>
                        <td>
                            ${nombreTamano}
                        </td>
                        <td>
                            ${data.producto.cantidad}
                        </td>
                        <td><button onclick="borrarComboProducto(${data.producto.id}, $(this).closest('tr'))" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button></td>
                    </tr>`);

                $('table tbody tr:last-child').find('td>button').addClass('disabled');
                $('table tbody tr:last-child').find('td>button').attr('disabled', 'disabled');
                $('table tbody tr:last-child').find('td>select.producto').val('');
                mostrarSuccess('Producto agregado');
                $.post('/combo/{{$combo->id}}/recalcular', function(datax){
                    $('input#precio').val(datax.precio);
                    ocultarFullLoading();
                });
            }
            else{
                ocultarFullLoading();
            }

        });

    }

    function reset(){
        $('input#nombre').val('');
        $('input#precio').val('');
        $('select#producto_lista').val('');
        $('select#cantidad').val(1);
        while($("table tbody tr").length > 1){
            $("table tbody tr:last-child").remove();
        }
    }

    function agregarProductoTr(){
        $("table tbody").append("" +
            "<tr style='display: none'>" +
            "<td><select class='form-control has-feedback producto' onchange='setTamanos($(this))'></select></td>" +
            "<td><select class='form-control has-feedback tamano'><option value='unico'>Único</option></select></td>" +
            "<td><select class='form-control has-feedback cantidad'></select></td>" +
            "<td><button onclick='borrarTr($(this))' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span></button></td>" +
            "</tr>");
        $("table tbody tr:last-child td:nth-child(1)>select").html($('select#producto_lista').html());
        $("table tbody tr:last-child td:nth-child(3)>select").html($('select#cantidad').html());
        $("table tbody tr:last-child").fadeIn();
    }

    function borrarTr($e){
        $e.closest('tr').remove();
    }

    function setTamanos($select){
        if($select.val()==''){
            $('table tbody tr:last-child').find('td>button').addClass('disabled');
            $('table tbody tr:last-child').find('td>button').attr('disabled', 'disabled');
            return false;
        }
        var tamanos = $select.find('option:selected').attr('tamanos');
        tamanos = JSON.parse(tamanos);
        $selectT = $select.closest('tr').find('td:nth-child(2)>select');
        $selectT.html('');
        tamanos.forEach(function (e) {
            $selectT.append(`<option value="${e.tamano}">${tamanosDict[e.tamano]}</option>`)
        });
        $select.closest('tr').find('td>button').removeClass('disabled');
        $select.closest('tr').find('td>button').removeAttr('disabled');
    }
</script>
<style>
    table td{
        padding: 8px;
    }
    table td:first-child{
        padding-left: 0px;
    }
    table td:last-child{
        padding-right: 0px;
    }
</style>
@endsection