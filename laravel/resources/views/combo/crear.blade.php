
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
        <h1 class="titulo">Nuevo Combo
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
                    <input type = "text"  class = "form-control" id = "nombre" name = "nombre" required value = "{{ old('nombre') }}">
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('nombre') }}</div>
                </div>
            </div>
            <div class = "col-md-3 col-sm-5">
                <div class = "form-group has-feedback {{ ($errors->first('precio')) ? 'has-error'  :''}}">

                    <label for = "precio" class = "control-label">Precio *</label>

                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input readonly type = "number"  class = "form-control curr" id = "precio" name = "precio" required value = "{{ old('precio')?:0 }}">
                    </div>

                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <div class = "help-block with-errors">{{ $errors->first('precio') }}</div>
                </div>
            </div>
            <div class = "col-md-12">
                <div class = "form-group has-feedback">
                    <label for = "_imagen" class = "control-label" >Imagen *</label><br/>
                    <div id="image-container" onclick="$('#archivo1').trigger('click')" style="border: thin solid #616161;width: 290px; height: 140px;padding: 4px;cursor:pointer;border-radius: 4px;background-color:white">
                        <div style="background-position:center;background-size: cover;width: 100%;height: 100%;background-image: url('/images/combo/default.jpg')"></div>
                    </div>
                    <span class = "glyphicon form-control-feedback" aria-hidden = "true"></span>
                    <input type="hidden" id="imagen" name="imagen" value="default.jpg">

                </div>
            </div>
            <div class = "col-md-12">
                <table width="100%">
                    <thead>
                        <tr>
                            <th>
                                Producto
                                <button class="btn btn-primary" href="diario" onclick="agregarProductoTr()">
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Agregar producto al combo
                                </button>
                            </th>
                            <th width="150">Valor</th>
                            <th width="120">Tamano</th>
                            <th width="70">Cantidad</th>
                            <th width="1"></th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <input type = "number" min="0" class = "form-control curr" name = "pc_precio" required value = "0" onkeyup="updatePrecio($(this))">
                            </div>
                        </td>
                        <td>
                            <select class = "form-control has-feedback tamano" id="tamano">
                                <option value="unico">Único</option>
                            <select/>
                        </td>
                        <td>
                            <select class = "form-control has-feedback cantidad" id="cantidad" onchange="updatePrecio($(this))">
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
                        <td><button onclick='' class='btn btn-danger disabled'><span class='glyphicon glyphicon-trash'></span></button></td>
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
                    url: "/combo/imagen",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                })
                    .done(function(res){
                        $('img#img_imagen').attr('src', `/images/combo/${res}`);
                        $('div#image-container>div').css('background-image', `url('/images/combo/${res}')`);
                        $('input#imagen').val(res);
                        ocultarFullLoading();
                    });
            });
        });
    </script>




</section>
<section class="borde-inferior form fondo-comun">
    <br>
    <div class="container">
        <div class = "col-md-12">
            <button class="btn btn-success" href="diario" onclick="guardarCombo()">
                <h3 class="titulo" style="color: white; margin: 4px"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Guardar Combo</h3>
            </button>
        </div>
    </div>
    <br>
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
    });

    var tamanosDict = {
        'unico': 'Único',
        'pequeno': 'Pequeño',
        'mediano': 'Mediano',
        'grande': 'Grande',
        'extrag': 'Extra Gr.',
        'porcion': 'Porción',
    }

    function guardarCombo(){
        $("form").submit();
        var valid = true;
        var error = '';
        var nombre = $('input#nombre').val();
        var precio = $('input#precio').val();
        var imagen = $('input#imagen').val();
        if(!nombre || nombre == ''){
            return false;
        }
        if(!precio || precio == ''){
            return false;
        }
        if($("table tbody tr").length < 1){
            valid = false;
            error = ('No se agregaron productos al combo.');
        }
        $("table tbody tr").each(function (e, i) {
            if($(this).find('select:first-child').val() == ""){
                valid = false;
                error = ('Debe asignar todos los productos.');
            }
        });
        if(!valid){
            mostrarError(error);
            return false;
        }
        var data = {
            nombre: nombre,
            imagen: imagen,
            precio: precio,
            productos: [],
        }
        $("table tbody tr").each(function (e, i) {
            var producto_id = $(this).find('select.producto').val();
            var cantidad = $(this).find('select.cantidad').val();
            var tamano = $(this).find('select.tamano').val();
            var valor = $(this).find('input[name=pc_precio]').val();
            data.productos.push({producto: producto_id, tamano: tamano, cantidad: cantidad, valor: valor});
        });
        mostrarFullLoading();
        $.post('/combo', data, function(data){
            if(data.status && data.status  == 200){
                mostrarSuccess('Combo creado');
                reset();
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

    function reset(){
        $('input#nombre').val('');
        $('input#precio').val('');
        $('input#imagen').val('default.jpg');
        $('div#image-container>div').css('background-image', "url('/images/combo/default.jpg')");
        $('select#producto_lista').val('');
        $('select#cantidad').val(1);
        while($("table tbody tr").length > 1){
            $("table tbody tr:last-child").remove();
        }
    }

    function agregarProductoTr(){
        $("table tbody").append("" +
            "<tr class='added' style='display: none'>" +
            "<td><select class='form-control has-feedback producto' onchange='setTamanos($(this))'></select></td>" +
            "<td>" +
                '<div class="input-group">\n' +
            '                                <span class="input-group-addon">$</span>\n' +
            '                                <input min="0" onkeyup="updatePrecio($(this))" type = "number"  class = "form-control curr" name = "pc_precio" required value = "0">\n' +
            '                            </div>' +
            "</td>" +
            "<td><select class='form-control has-feedback tamano'><option value='unico'>Único</option></select></td>" +
            "<td><select class='form-control has-feedback cantidad'></select></td>" +
            "<td><button onclick='borrarTr($(this))' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span></button></td>" +
            "</tr>");
        $("table tbody tr:last-child td:nth-child(1)>select").html($('select#producto_lista').html());
        $("table tbody tr:last-child td:nth-child(4)>select").html($('select#cantidad').html());
        $("table tbody tr:last-child").fadeIn();
        $("input.curr").inputmask("currency", { digits: 0 });
    }

    function borrarTr($e){
        $e.closest('tr').remove();
    }

    function setTamanos($select){
        if($select.val()==''){
            return false;
        }
        updatePrecio();
        var tamanos = $select.find('option:selected').attr('tamanos');
        tamanos = JSON.parse(tamanos);
        $selectT = $select.closest('tr').find('td:nth-child(2)>select');
        $selectT.html('');
        tamanos.forEach(function (e) {
            $selectT.append(`<option value="${e.tamano}">${tamanosDict[e.tamano]}</option>`)
        });
    }
    function updatePrecio(){
        var total = 0;
        $('table tbody tr').each(function(e,i){
            if($(this).find('select.producto').val()!=''){
                var cantidad = $(this).find('select.cantidad').val();
                var valor = $(this).find('input[name=pc_precio]').val();
                total += parseInt(cantidad) * parseFloat(valor);
            }
        })
        $('input#precio').val(total);
    }
</script>
<style>
    table td{
        padding: 4px;
    }
    table td:first-child{
        padding-left: 0px;
    }
    table td:last-child{
        padding-right: 0px;
    }
</style>
@endsection