function preSiguienteDiaOperativo(activo){
    var data = {
        title: '',
        type: 'orange',
        typeAnimated: true,
        // columnClass: 'col-md-8 col-md-offset-2',
        content: '',
        // boxWidth: '600px',
        icon: 'fa fa-warning',
        buttons: {
            confirm: {
                btnClass: 'btn-blue',
                text: 'Continuar',
                action: function(){
                    siguienteDiaOperativo(activo);
                }
            },
            cancel: {
                btnClass: 'btn-red',
                text: 'Cancelar',
                action: function(){
                    ocultarFullLoading();
                }
            },
        }
    };
    data.title = activo?'Cerrar día operativo':'Abrir siguiente día operativo';
    data.content = activo?'Está seguro que quiere cerrar el día operativo?':
        'Está seguro que quiere abrir el siguiente día operativo? Se cerrará el actual';
    $.confirm(data);
}

function siguienteDiaOperativo(){
    mostrarFullLoading();
    $.post( "/caja/siguiente-dia-operativo", {})
    .done(function (data) {
        if(data.code==201){
            mostrarWarning('El siguiente día operativo ya está establecido.')
            ocultarFullLoading();
        }
        else if(data.code==200){
            window.location.href = '/caja/cuadre?updated=1';
        }
        else{
            mostrarError('No se pudo establecer el día operativo');
            ocultarFullLoading();
        }
    });
}
