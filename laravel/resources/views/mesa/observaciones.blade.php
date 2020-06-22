<div class="modal fade" id="observacionesModal" tabindex="-1" role="dialog" aria-labelledby="obervacionesModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row restaurante">
                    <label ><input type="checkbox" id="para-llevar" value="LLEVAR"> Para llevar</label>
                </div>
                <div class="row">
                    <label >Observaciones</label>
                </div>
                <div class="row">
                    <textarea class="w100 form-control" name="observacion" id="observacion" rows="4"></textarea>
                </div>
                <div class="row domicilio">
                    <label><input type="radio" name="entregar-en" value="DOMICILIO" checked> Enviar a domicilio</label>
                </div>
                <div class="row">
                    <label >Cliente</label>
                </div>
                <div class="row">
                    <input type="hidden" id="cliente_id">
                    <input type="text" name="cliente" placeholder="Nombres" id="cliente" class="w100 form-control" value="VARIOS" autocomplete="off">
                </div>
                <div class="row">
                    <label >Identificación</label>
                </div>
                <div class="row">
                    <input type="text" name="identificacion" id="identificacion" class="w100 form-control" autocomplete="off">
                </div>
                <div class="row">
                    <label >Teléfono</label>
                </div>
                <div class="row">
                    <input type="text" name="telefono" id="telefono" class="w100 form-control" autocomplete="off">
                </div>
                <div class="row">
                    <label >Dirección</label>
                </div>
                <div class="row">
                    <input type="text" name="domicilio" placeholder="Dirección" id="domicilio" class="w100 form-control">
                </div>
                <div class="row domicilio">
                    <label><input type="radio" name="entregar-en" value="CAJA"> Recojer en caja</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-success" onclick="saveObs()">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $(function () {

        var oldValue = {nombre: '', telefono: '', identificacion: ''};
        $(function () {
            $("div.row").on('keyup', 'input#cliente', function () {
                var val = $(this).val();
                if(val.replace(/ /g, '')===""){
                    fillClientInfo({});
                }
                if(val.length<1||val.replace(/ /g, '')===""||val==oldValue.nombre){
                    return false;
                }
                $x = $("input#cliente");
                $x.addClass('input-loading');
                $x.typeahead("destroy");
                delay(function(){
                    oldValue.nombre = val;
                    var params = `[["nombrecompleto", "like", "%${val}%"]]`;
                    $.get("/api/terceros?limit=8&params="+encodeURIComponent(params), function(data){
                        data.forEach(function (e, i) {
                            e.name = e.nombrecompleto;
                        });
                        $x.typeahead({
                            source:data,
                            afterSelect: function (e) {
                                $x.typeahead("destroy");
                                oldValue.nombre = e.name;
                                oldValue.telefono = e.telefono;
                                oldValue.identificacion = e.identificacion;
                                fillClientInfo(e);
                            }
                        });
                        $x.typeahead("lookup");
                        $x.removeClass('input-loading');
                    },'json');
                }, 500 );
            });
            $("div.row").on('keyup', 'input#identificacion', function () {
                var val = $(this).val();
                if(val.replace(/ /g, '')===""){
                    fillClientInfo({});
                }
                if(val.length<1||val.replace(/ /g, '')===""||val==oldValue.identificacion){
                    return false;
                }
                $x = $("input#identificacion");
                $x.addClass('input-loading');
                $x.typeahead("destroy");
                delay(function(){
                    oldValue.identificacion = val;
                    var params = `[["identificacion", "like", "%${val}%"]]`;
                    $.get("/api/terceros?limit=8&params="+encodeURIComponent(params), function(data){
                        data.forEach(function (e, i) {
                            e.name = e.identificacion + ' ' + e.nombrecompleto;
                        });
                        $x.typeahead({
                            source:data,
                            afterSelect: function (e) {
                                $x.typeahead("destroy");
                                oldValue.nombre = e.name;
                                oldValue.telefono = e.telefono;
                                oldValue.identificacion = e.identificacion;
                                fillClientInfo(e);
                            }
                        });
                        $x.typeahead("lookup");
                        $x.removeClass('input-loading');
                    },'json');
                }, 500 );
            });
            $("div.row").on('keyup', 'input#telefono', function () {
                var val = $(this).val();
                if(val.replace(/ /g, '')===""){
                    fillClientInfo({});
                }
                if(val.length<1||val.replace(/ /g, '')===""||val==oldValue.telefono){
                    return false;
                }
                $x = $("input#telefono");
                $x.addClass('input-loading');
                $x.typeahead("destroy");
                delay(function(){
                    oldValue.telefono = val;
                    var params = `[["telefono", "like", "%${val}%"]]`;
                    $.get("/api/terceros?limit=8&params="+encodeURIComponent(params), function(data){
                        data.forEach(function (e, i) {
                            e.name = e.telefono + ' ' + e.nombrecompleto;
                        });
                        $x.typeahead({
                            source:data,
                            afterSelect: function (e) {
                                $x.typeahead("destroy");
                                oldValue.nombre = e.name;
                                oldValue.telefono = e.telefono;
                                oldValue.identificacion = e.identificacion;
                                fillClientInfo(e);
                            }
                        });
                        $x.typeahead("lookup");
                        $x.removeClass('input-loading');
                    },'json');
                }, 500 );
            });
        })

        function fillClientInfo(e){
            $("input#cliente_id").val(e.id);
            $("input#cliente").val(e.nombrecompleto);
            $("input#telefono").val(e.telefono);
            $("input#domicilio").val(e.direccion);
            $("input#identificacion").val(e.identificacion);
        }

    })
</script>

<style>
    .input-loading {
        background-image: url("/images/f.gif");
        background-size: 25px 25px;
        background-position:right center;
        background-repeat: no-repeat;
    }
</style>
