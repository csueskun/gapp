var jc = null;
function preImportarInventario(){
    importarModal();    
}
function preUploadFile(element){
    var fileFormData = new FormData();
    var fileData = $('#importar-button').prop('files')[0];   
    fileFormData.append('file', fileData);
    uploadFile('file/upload/inventario', fileFormData, function (data) {
        if(data.code==200){
            mostrarSuccess('Inventario actualizado');
        }
        else{
            mostrarError('No se actualizaron');
        }
        document.getElementById("importar-button").value = null;
        closeJc();
    }, function (xhr, status) {
        closeJc();
    });
}

function generarDetallado(){
    window.open('/inventario/detallado/?'+$("#detallado form").serialize(), '_blank')
}
function generateExcel(){
    $('button.busy').attr('disabled', true);
    $('button.busy span').addClass('fa-spin');
    $.get('/saldos_producto/excel', function (data) {
        if(data.code == 200){
            $('form#excel input').val(JSON.stringify(data.msg));
            $('form#excel').submit();
        }
        $('button.busy').attr('disabled', false);
        $('button.busy span').removeClass('fa-spin');
    });
}
function printPos(){
    $('button.busy').attr('disabled', true);
    $('button.busy span').addClass('fa-spin');
    $.get('/config/servicio-impresion', function (data) {
        servicio_impresion = data;
        $.post("/inventario/pos", {}, function (data) {
            enviarAServicioImpresionPost(servicio_impresion, data);
            $('button.busy').attr('disabled', false);
            $('button.busy span').removeClass('fa-spin');
        });
    });
}
function printPosx(){
    $.get('/config/servicio-impresion', function (data) {
        servicio_impresion = data;
        $.post("/caja/cuadre-post", {}, function (data) {
            console.log(data);
            //enviarAServicioImpresion(servicio_impresion+'?stack='+JSON.stringify(data))
        });
    });
}

function enviarAServicioImpresion(url){
    $.ajax({
        url: url,
        headers: {"Access-Control-Allow-Origin":"*","Access-Control-Allow-Credentials":"true"},
        type: 'GET',
        // This is the important part
        crossDomain: true,
        dataType: "jsonp",
        xhrFields: {
            withCredentials: true,

        },
        // This is the important part
        success: function (response) {
            // handle the response
        },
        error: function (xhr, status) {
            // handle errors
        }
    });
}
$(function() {
    $('[data-toggle=confirmation]').confirmation(
    {
        buttons: [
            {
                class: 'btn btn-primary',
                label: 'Detallado',
                icon: 'glyphicon glyphicon-pencil',
                onClick: function() {
                $("#detallado form input[name=id]").val($(this).closest("tr").attr("producto_id"));
                $("#detallado form input[name=tipo]").val($(this).closest("tr").attr("tipo"));
                $('#detallado').modal('toggle');
                }
            },
        ]
        }
    );
});
function importarModal(activo){
    var data = {
        title: '',
        type: 'orange',
        typeAnimated: true,
        // columnClass: 'col-md-8 col-md-offset-2',
        content: '',
        // width: 'auto',  
        boxWidth: '500px',
        useBootstrap: false,
        icon: 'fa fa-warning',
        buttons: {
            info: {
                btnClass: 'btn-primary',
                text: 'Descargar plantilla',
                action: function(){
                    window.open('/file/download/inventario', '_blank')
                    return false;
                }
            },
            confirm: {
                btnClass: 'btn-success',
                text: 'Cargar archivo',
                action: function(){
                    $('#importar-button').click();
                    return false;
                }
            },
            cancel: {
                btnClass: 'btn-danger',
                text: 'Cancelar',
                action: function(){
                    ocultarFullLoading();
                }
            },
        }
    };
    data.title = 'Importar inventario';
    data.content = 'Por favor descargar la plantilla del '+
        'inventario actual para realizar el cargue.<br><br>'+
        'Por favor editar unicamente la columna "ENTREGADO" '+
        'de la plantilla descargada.';
    jc = $.confirm(data);
}

function closeJc(){
    jc.close();
}