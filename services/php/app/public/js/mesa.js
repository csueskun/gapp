var menu = [];
var divTipoProductoSeleccionado = $('div#tipo_producto_seleccionado');
var tamanos = [
    {id:'unico', des:'Único'},
    {id:'grande', des:'Grande'},
    {id:'extrag', des:'Extra Grande'},
    {id:'mediano', des:'Mediano'},
    {id:'pequeno', des:'Pequeño'},
    {id:'porcion', des:'Porción'}
];
var loadingGifSize = 60;

$(function () {
    $.get( "/carta", function( data ) {
        getMenuCallback(data);
    });
    openPedido();
});

function getMenuCallback(data){
    menu = data;
    loadTiposProductos();
    selectTipoPoducto(0);
}

function loadTiposProductos(){
    var i=0;
    window.menu.forEach(function(element) {
        $('section#tipo_productos>ul').append(
            paintTipoProducto(element, i,i==0)
        );
        i++;
    });
}

function selectTipoPoducto(index){
    disableAcciones();
    resetCantidad();
    var tipoProducto = menu[index];
    divTipoProductoSeleccionado.attr('tipo-producto-id', tipoProducto.id);
    divTipoProductoSeleccionado.attr('tipo-producto-index', index);
    loadTamanos(tamanos, tipoProducto.aplica_tamanos==1);
    loadFracciones(JSON.parse(tipoProducto.fracciones));
    preLoadProductos(tipoProducto);
    addButtonAdicionalesDefault();
    if(tipoProducto.aplica_ingredientes){
        $('section.ingredientes-adicionales-buttons').show();
    }
    else{
        $('section.ingredientes-adicionales-buttons').hide();
    }
}

function loadTamanos(tamanos, load=false){
    $('section#tamanos div').html('');
    if(!load){
        tamanos = [
            {id: '', des: ''},
            {id:'unico', des:'Único'}
        ];
    }
    $('section#tamanos div').hide();
    var i=0;
    tamanos.forEach(function (element) {
        if(i>0){
            $('section#tamanos div').append(
                paintTamano(element, i==1)
            );
        }
        i++;
    });
    if(!load){
        $('section#tamanos').hide();
    }
    else{
        $('section#tamanos').show();
    }
    $('section#tamanos div').fadeIn('slow');
}
function loadFracciones(fracciones){
    var i =0;
    $('section#fracciones div').hide();
    $('section#fracciones div').html('');
    fracciones.forEach(function (element) {
        $('section#fracciones div').append(
            paintFraccion(element, i==0)
        );
        i++;
    });
    if(i>0 && i<2){
        $('section#fracciones').hide();
    }
    else{
        $('section#fracciones').show();
    }
    $('section#fracciones div').fadeIn('slow');
}

function preLoadProductosIndex(index){
    preLoadProductos(menu[index]);
}
function preLoadProductos(tipoProducto){
    // $('section.fraccion div.row').html(paintLoadingGif());
    $('section.fraccion div.row button.producto').remove();
    $('section.fraccion div.row button.ingrediente').remove();
    $('section.fraccion div.row button.adicional').remove();

    $('section.fraccion').hide();
    var fracciones = getFraccionesSeleccionadas();

    if(fracciones==1){
        $('section.fraccion#0').fadeIn('slow');
        loadProductos(tipoProducto.productos, 0);
    }
    else{
        for(var i=1; i<=fracciones; i++){
            loadProductos(tipoProducto.productos, i);
        }
    }
}
function loadProductos(productos, fraccion){
    var i=0;
    productos.forEach(function (element) {
        $(`section.fraccion#${fraccion} td>div.row`).append(
            paintProducto(element, i, fraccion,false)
        );
        i++;
    });
    $(`section.fraccion#${fraccion}`).fadeIn('slow');
}

function loadIngredientes(ingredientes, fraccion, index){
    var div = $(`section.fraccion#${fraccion} section.ingredientes>div`);
    div.find('.loading-gif').addClass('hidden');
    addButtonIngredientes(fraccion, ingredientes.length);
    var i=0;
    ingredientes.forEach(function (element) {
        div.append(paintIngredientes(element.ingrediente, i, fraccion, false));
        i++;
    });
}
function loadAdicionales(adicionales, fraccion, index){
    var div = $(`section.fraccion#${fraccion} section.adicionales>div`);
    div.find('.loading-gif').addClass('hidden');
    var i=0;
    addButtonAdicionales(fraccion, adicionales.length);
    adicionales.forEach(function (element) {
        div.append(paintAdicionales(element.ingrediente, element.valor, i, fraccion, false));
        i++;
    });
}

//Clicked
function fraccionClicked(fracciones){
    addButtonAdicionalesDefault();
    if(getFraccionesSeleccionadas()==fracciones){
        return false;
    }
    $("section#fracciones div.row>button").removeClass('active');
    $(`section#fracciones div.row>button[fracciones=${fracciones}]`).addClass('active');
    preLoadProductosIndex(getTipoProductoSeleccionadoIndex());
}
function tamanoClicked(id){
    disableAcciones();
    if(getTamanoSeleccionado()==id){
        return false;
    }
    $("section#tamanos div.row>button").removeClass('active');
    $(`section#tamanos div.row>button[tamano-id=${id}]`).addClass('active');
    $(`section.fraccion button.producto.active`).trigger('click');
}


function productoClicked(fraccion,index){
    disableAcciones();
    $(`section.fraccion#${fraccion} div.row>button`).removeClass('active');
    var selectorClicked = $(`section.fraccion#${fraccion} div.row>button[producto-index=${index}]`);
    selectorClicked.addClass('active');
    var id = selectorClicked.attr('producto-id');
    preLoadIngredientesAdicionales(id, fraccion, index);
    validateAcciones();
}
function preLoadIngredientesAdicionales(id, fraccion, index){
    var div = $(`section.fraccion#${fraccion} section.ingredientes>div`);
    div.find('.loading-gif').removeClass('hidden');
    div.find('button').remove();
    div = $(`section.fraccion#${fraccion} section.adicionales>div`);
    div.find('.loading-gif').removeClass('hidden');
    div.find('button').remove();
    div = $(`section.fraccion#${fraccion} button.adicionales`);
    div.find('.loading-gif').removeClass('hidden');
    div.find('span').remove();
    div = $(`section.fraccion#${fraccion} button.ingredientes`);
    div.find('span').remove();
    div.find('.loading-gif').removeClass('hidden');

    $(`section.fraccion#${fraccion} section.ingredientes>div .loading-gif`).show();
    $.get(`/producto-ingredientes?params={"producto_id":${id},"tamano": "${getTamanoSeleccionado()}"}`, function( data ) {
        loadIngredientes(data, fraccion, index);
    });
    $.get(`/adicionales?params={"tipo_producto_id":${getTipoProductoSeleccionadoId()},"tamano": "${getTamanoSeleccionado().toUpperCase()}"}`, function( data ) {
        loadAdicionales(data, fraccion, index);
    });
}
function ingredienteClicked(fraccion){
    $(`section.fraccion#${fraccion} section.ingredientes button.ingrediente`).toggleClass('active');
}
function ingredientesClicked(fraccion){
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.adicionales>i`).removeClass('fa-chevron-up').addClass('fa-chevron-down');
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.ingredientes>i`).toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
    $(`section.fraccion#${fraccion} section.adicionales`).hide();
    $(`section.fraccion#${fraccion} section.ingredientes`).fadeToggle('slow');
}
function adicionalClicked(fraccion){
    $(`section.fraccion#${fraccion} section.adicionales button.adicional`).toggleClass('active');
}
function adicionalesClicked(fraccion){
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.ingredientes>i`).removeClass('fa-chevron-up').addClass('fa-chevron-down');
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.adicionales>i`).toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
    $(`section.fraccion#${fraccion} section.ingredientes`).hide();
    $(`section.fraccion#${fraccion} section.adicionales`).fadeToggle('slow');
}
function addButtonClicked(){
    $('.busy').toggleClass('hidden');
    $("button#agregar-pp").prop('disabled', function(i, v) { return !v; });
}

//Painters
function paintProducto(element, index, fraccion, active=false){
    return `<button class="btn btn-default producto col-xs-6 col-sm-4 col-md-3 ${active?'active':''}" producto-id="${element.id}" producto-index="${index}" fraccion="${fraccion}" onclick="productoClicked(${fraccion}, ${index})" >
    <div style="background-image: url('/images/producto/${element.imagen}');">
        <span>${element.descripcion}</span>
    </div>
</button>`;
}
function paintTipoProducto(element, index, active=false){
    return `<li role="presentation" class="${active?'active':''}"><a href="#" role="tab" data-toggle="tab" tipo-producto-id="${element.id}" tipo-producto-index="${index}" onclick="selectTipoPoducto(${index})">${element.descripcion}</a></li>`;
}
function paintTamano(tamano, active=false){
    return `<button class="btn btn-default ${active?'active':''}" tamano-id="${tamano.id}" onclick="tamanoClicked('${tamano.id}')">${tamano.des}</button>`;
}
function paintFraccion(fraccion, active=false){
    return `<button class="btn btn-default ${active?'active':''}" fracciones="${fraccion.substring(0,1)}" onclick="fraccionClicked(${fraccion.substring(0,1)})">${fraccion}</button>`;
}
function paintBotonesIngredientesAdicionales(fraccion, hasAdicionales=false, hasIngredientes=false){
    return `
    <button class="btn btn-default ingredientes" onclick="ingredientesClicked(${fraccion})">Ingredientes <i class="fa fa-chevron-down"></i></button>
    <button class="btn btn-default adicionales" onclick="adicionalesClicked(${fraccion})">Adicionales <i class="fa fa-chevron-down"></i></button>`;
}
function paintBotonAdicionales(fraccion, count){
    return `<button class="btn btn-default adicionales" ${!count?'disabled':''} onclick="adicionalesClicked(${fraccion})">${paintLoadingGif(30)} <span>${count}</span> Adicionales <i class="fa fa-chevron-down"></i></button>`;
}
function paintBotonIngredientes(fraccion, count){
    return `<button class="btn btn-default ingredientes" ${!count?'disabled':''} onclick="ingredientesClicked(${fraccion})">${paintLoadingGif(30)} <span>${count}</span> Ingredientes <i class="fa fa-chevron-down"></i></button>`;
}
function paintIngredientes(element, fraccion, active=false){
    return `<button class="btn btn-default ingrediente active col-xs-6 col-sm-4 col-md-3 ${active?'active':''}" ingrediente-id="${element.id}" onclick="$(this).toggleClass('active')" >
    <div style="background-image: url('/images/ingrediente/${element.imagen}');">
        <span>${element.descripcion}</span>
    </div>
</button>`;
}
function paintAdicionales(element, valor, fraccion){
    return `<button class="btn btn-default adicional col-xs-6 col-sm-4 col-md-3" ingrediente-id="${element.id}" onclick="$(this).toggleClass('active')" >
    <div style="background-image: url('/images/ingrediente/${element.imagen}');">
        <span>${element.descripcion} $${valor}</span>
    </div>
</button>`;
}
function paintLoadingGif(size=loadingGifSize,hidden=true){
    return `<img class="loading-gif ${hidden?'hidden':''}" src="/images/l.gif" width="${size}" height="${size}"/>`;
}

//Helper
function getTipoProductoSeleccionadoIndex(){
    return divTipoProductoSeleccionado.attr('tipo-producto-index');
}
function getTipoProductoSeleccionadoId(){
    return divTipoProductoSeleccionado.attr('tipo-producto-id');
}
function getFraccionesSeleccionadas(){
    return parseInt($("section#fracciones div>button.active").attr('fracciones'));
}
function getTamanoSeleccionado(){
    return $("section#tamanos div>button.active").attr('tamano-id');
}
function getProductoSeleccionadoIndex(fraccion){
    return parseInt($(`section.fraccion#${fraccion} div.row>button.active`).attr('producto-index'));
}
function getAdicionalesActivosFraccion(fraccion){
    var ids = [];
    $(`section.fraccion#${fraccion} section.adicionales button.active`).each(function () {
        ids.push($(this).attr('ingrediente-id'));
    });
    return ids;
}
function getIngredientesActivosFraccion(fraccion){
    var ids = [];
    $(`section.fraccion#${fraccion} section.ingredientes button.active`).each(function () {
        ids.push($(this).attr('ingrediente-id'));
    });
    return ids;
}
function getIngredientesInactivosFraccion(fraccion){
    var ids = [];
    $(`section.fraccion#${fraccion} section.ingredientes button:not(.active)`).each(function () {
        ids.push($(this).attr('ingrediente-id'));
    });
    return ids;
}
function resetCantidad(){
    $('.input-group.number-spinner.cantidad input').val(1);
}
function getProductoSeleccionadoId(fraccion){
    return parseInt($(`section.fraccion#${fraccion} div.row>button.active`).attr('producto-id'));
}
function getProductoIndexFraccion(fraccion){
    var tipoProducto = menu[getTipoProductoSeleccionadoIndex()];
    return tipoProducto.productos[getProductoSeleccionadoIndex(fraccion)]
}
function minimizarFraccion(fraccion){
    $(`section.fraccion#${fraccion} td:nth-child(2)`).toggle();
    $(`section.fraccion#${fraccion} td:nth-child(1)`).toggleClass('pl40');
    // $(`section.fraccion#${fraccion} div.row`).fadeToggle('slow');
    // $(`section.fraccion#${fraccion} section`).fadeToggle('slow');
}
function addButtonIngredientes(fraccion, count){
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.ingredientes`).remove();
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons>div`).prepend(
        paintBotonIngredientes(fraccion, count)
    );
}
function addButtonAdicionales(fraccion, count){
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons button.adicionales`).remove();
    $(`section.fraccion#${fraccion} section.ingredientes-adicionales-buttons>div`).append(
        paintBotonAdicionales(fraccion, count)
    );
}
function addButtonAdicionalesDefault(){
    for(var i=0;i<5;i++){
        $(`section.fraccion#${i} section.ingredientes-adicionales-buttons button`).remove();
        $(`section.fraccion#${i} section.ingredientes-adicionales-buttons>div`).html(
            paintLoadingGif() +
            paintBotonIngredientes(i, 0) +
            paintBotonAdicionales(i, 0)
        );
        $(`section.fraccion#${i} section.ingredientes>div`).html(
            paintLoadingGif()
        );
        $(`section.fraccion#${i} section.adicionales>div`).html(
            paintLoadingGif()
        );
    }
}

function getAllSelected(){
    var fracciones = getFraccionesSeleccionadas();
    var tipoProducto = menu[getTipoProductoSeleccionadoIndex()];
    var ingredientes = [];
    var adicionales = [];

    console.log('tipo producto', tipoProducto);
    console.log('fracciones', fracciones);
    console.log(getTamanoSeleccionado());
    if(fracciones == 1){
        console.log(
            'fraccion 0: ',
            tipoProducto.productos[getProductoSeleccionadoIndex(0)]
        );
        console.log('Ingredientes', getIngredientesActivosFraccion(0));
        console.log('No Ingredientes', getIngredientesInactivosFraccion(0));
        console.log('Adicionales', getAdicionalesActivosFraccion(0));
    }
    else{
        for(var i=1; i<=fracciones; i++){
            console.log(
                `fraccion ${i}: `,
                tipoProducto.productos[getProductoSeleccionadoIndex(i)]
            );
            console.log('Ingredientes', getIngredientesActivosFraccion(i));
            console.log('No Ingredientes', getIngredientesInactivosFraccion(i));
            console.log('Adicionales', getAdicionalesActivosFraccion(i));
        }
    }
}

function isProductoPedidoValid(){
    var fracciones = getFraccionesSeleccionadas();
    var tipoProducto = menu[getTipoProductoSeleccionadoIndex()];

    if(isInvalid(tipoProducto) || isInvalid(fracciones) || isInvalid(getTamanoSeleccionado())){
        return false;
    }

    if(fracciones == 1){
        if(isInvalid(tipoProducto.productos[getProductoSeleccionadoIndex(0)])){
            return false;
        }
    }
    else{
        for(var i=1; i<=fracciones; i++){
            if(isInvalid(tipoProducto.productos[getProductoSeleccionadoIndex(i)])){
                return false;
            }
        }
    }
    return true;
}

function isValid(x){
    return x != null && typeof x != 'undefined';
}
function isInvalid(x){
    return !isValid(x);
}
function validateAcciones() {
    $("section#acciones button").prop("disabled", !isProductoPedidoValid());
}
function disableAcciones() {
    $("section#acciones button").prop("disabled", true);
}


function openPedido() {
    document.getElementById("pedido").style.right = "0px";
    document.getElementById("boton-abrir-cerrar-pedido").style.marginRight = "380px";
}
function closePedido() {
    document.getElementById("pedido").style.right = "-380px";
    document.getElementById("boton-abrir-cerrar-pedido").style.marginRight = "0px";
}
function togglePedido() {
    if(document.getElementById("pedido").style.right == "0px"){
        closePedido();
    }
    else{
        openPedido();
    }
}

function confirmarAlgo(){
    confirmar('Confirmando algo', 'Confirmar de una', onConfirmarAlgo);
}
function onConfirmarAlgo(){
    alert('funciona!!!');
}

function openModalObservaciones(){
    $('#obervacionesModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        modal.find('.modal-title').text('hola');
    })
}