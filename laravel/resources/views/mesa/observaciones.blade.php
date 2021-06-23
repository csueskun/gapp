<div class="modal fade" id="observacionesModal" tabindex="-1" role="dialog" aria-labelledby="obervacionesModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row restaurante">
                    <div class= "col-md-12">
                    <label ><input type="checkbox" ng-model="observaciones.para_llevar" id="para-llevar"> Para llevar</label>
                    </div>
                </div>
                <div class="row domicilio">
                    <div class= "col-md-6">
                    <label><input type="radio" ng-model="observaciones.entregar_en" name="entregar-en" value="DOMICILIO" checked> Enviar a domicilio</label>
                    </div>
                    <div class= "col-md-6">
                    <label><input type="radio" ng-model="observaciones.entregar_en" name="entregar-en" value="CAJA"> Recojer en caja</label>
                    </div>
                </div>
                <div class="row">
                    <div class= "col-md-12">
                    <label >Observaciones</label>
                    </div>
                    <div class= "col-md-12">
                    <textarea class="w100 form-control" ng-model="observaciones.observacion" name="observacion" id="observacion" rows="4"></textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class= "col-md-12">
                      <label >Cliente</label>    
                      <input type="hidden" id="cliente_id">
                    </div>
                    <div class= "col-md-12">
                      <input type="text" ng-keyup="autocompleteCliente($event)" ng-model="observaciones.cliente" name="cliente" placeholder="Nombres" id="cliente" class="w100 form-control" value="VARIOS" autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class= "col-md-6">
                    <label >Identificación</label>
                    </div>
                    <div class= "col-md-6">
                    <label >Teléfono</label>
                    </div>   
                </div>
                <div class="row">
                    <div class= "col-md-6">
                    <input type="text" ng-model="observaciones.identificacion" name="identificacion" id="identificacion" class="w100 form-control" autocomplete="off">
                    </div>
                    <div class= "col-md-6">
                    <input type="text" ng-model="observaciones.telefono" name="telefono" id="telefono" class="w100 form-control" autocomplete="off">                
                    </div>
                </div>
                
                <div class="row">
                    <div class= "col-md-12">
                    <label >Dirección</label>
                    </div>
                    <div class= "col-md-12">
                    <input type="text" name="domicilio" ng-model="observaciones.domicilio" placeholder="Dirección" id="domicilio" class="w100 form-control">
                </div>
            </div>       
        </div>
        <div class="modal-footer">
                <input type="hidden" name="cliente_id" id="cliente_id" ng-model="observaciones.cliente_id">
                <input type="text" name="cliente_data" id="cliente_data">
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-success" ng-click="saveObs()">Guardar</button>
                <button style="display: none" id="loadObservacionesButton" ng-click="loadObservaciones()">obs</button>
                <button style="display: none" id="loadClienteData" ng-click="loadClienteData()"></button>
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
            $("div.row").on('keyup', 'input#cliente', function (event) {
                if(event.keyCode==13){
                    return false;
                }
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
                    $.get("/api/terceros?limit=8&params="+val, function(data){
                        data.forEach(function (e, i) {
                            e.name = e.identificacion + ' ' + e.nombrecompleto + ' ' + (e.telefono?e.telefono:'');
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
            // $("div.row").on('keyup', 'input#identificacion', function () {
            //     var val = $(this).val();
            //     if(val.replace(/ /g, '')===""){
            //         fillClientInfo({});
            //     }
            //     if(val.length<1||val.replace(/ /g, '')===""||val==oldValue.identificacion){
            //         return false;
            //     }
            //     $x = $("input#identificacion");
            //     $x.addClass('input-loading');
            //     $x.typeahead("destroy");
            //     delay(function(){
            //         oldValue.identificacion = val;
            //         var params = `[["identificacion", "like", "%${val}%"]]`;
            //         $.get("/api/terceros?limit=8&params="+encodeURIComponent(params), function(data){
            //             data.forEach(function (e, i) {
            //                 e.name = e.identificacion + ' ' + e.nombrecompleto;
            //             });
            //             $x.typeahead({
            //                 source:data,
            //                 afterSelect: function (e) {
            //                     $x.typeahead("destroy");
            //                     oldValue.nombre = e.name;
            //                     oldValue.telefono = e.telefono;
            //                     oldValue.identificacion = e.identificacion;
            //                     fillClientInfo(e);
            //                 }
            //             });
            //             $x.typeahead("lookup");
            //             $x.removeClass('input-loading');
            //         },'json');
            //     }, 500 );
            // });
            // $("div.row").on('keyup', 'input#telefono', function () {
            //     var val = $(this).val();
            //     if(val.replace(/ /g, '')===""){
            //         fillClientInfo({});
            //     }
            //     if(val.length<1||val.replace(/ /g, '')===""||val==oldValue.telefono){
            //         return false;
            //     }
            //     $x = $("input#telefono");
            //     $x.addClass('input-loading');
            //     $x.typeahead("destroy");
            //     delay(function(){
            //         oldValue.telefono = val;
            //         var params = `[["telefono", "like", "%${val}%"]]`;
            //         $.get("/api/terceros?limit=8&params="+encodeURIComponent(params), function(data){
            //             data.forEach(function (e, i) {
            //                 e.name = e.telefono + ' ' + e.nombrecompleto;
            //             });
            //             $x.typeahead({
            //                 source:data,
            //                 afterSelect: function (e) {
            //                     $x.typeahead("destroy");
            //                     oldValue.nombre = e.name;
            //                     oldValue.telefono = e.telefono;
            //                     oldValue.identificacion = e.identificacion;
            //                     fillClientInfo(e);
            //                 }
            //             });
            //             $x.typeahead("lookup");
            //             $x.removeClass('input-loading');
            //         },'json');
            //     }, 500 );
            // });
        })

        function fillClientInfo(e){
            $("input#cliente_data").val(JSON.stringify(e));
            $("#loadClienteData").trigger('click');
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
