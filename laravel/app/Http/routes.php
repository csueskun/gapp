<?php
    
use Illuminate\Html\HtmlFacade;
use Illuminate\Support\Facades\Redirect;


Route::get('/', 'UsuarioController@inicio');
Route::get('/push', 'ConfigController@push');

Route::post('/hash', 'UsuarioController@hash');
Route::get('/id', 'LoginController@doAuthLogin');
Route::get('/cmd', 'ConfigController@vistaListar');


Route::get('/estadomesas', 'ConfigController@estado_mesas2');
Route::get('/caja/cuadre', 'DocumentoController@cuadreView')->middleware('tiene.roles:Administrador.Cajero');
// Route::get('/caja/cuadre', function () {
//     return view("caja.cuadre");
// })->middleware('auth')->middleware('tiene.roles:Administrador.Cajero');

Route::get('/dashboard', function () {
    return view("varios.dashboard");
})->middleware('auth')->middleware('tiene.roles:Administrador.Cajero');

Route::get('/dashboard/report0/data', 'DocumentoController@getMonthData');
Route::get('/dashboard/report1/data', 'DocumentoController@getMonthFV');
Route::get('/dashboard/report2/data', 'DocumentoController@getMonthExpenses');
Route::get('/dashboard/pedidos/data', 'PedidoController@dashboardInfo');
Route::get('/dashboard/vendedores/data', 'DocumentoController@getVenderores');

Route::get('/menu2', function () {
    return view("mesa.menu2");
});
Route::get('/carta', 'TipoProductoController@mostrarMenu');

Route::post('/caja/cuadre', 'DocumentoController@cuadre');
Route::post('/caja/reporte-tipodoc', 'DocumentoController@reporteTipodoc');
Route::get('/caja/reporte-ventas', 'DocumentoController@preReporteVentas');
Route::post('/caja/cuadre-post', 'DocumentoController@preCuadrePos');
Route::post('/caja/siguiente-dia-operativo', 'DocumentoController@setSiguienteDiaOperativo');
Route::post('/documento/print-post', 'DocumentoController@posPrint');

Route::get('/producto/buscarConTamano/{buscar}', 'ProductoTamanoController@todosConProducto');
Route::get('/ingrediente/buscar/{buscar}', 'IngredienteController@buscarModal');

Route::get('hacerlogin', array('uses' => 'LoginController@hacerLogin'));
Route::get('logout', array('uses' => 'LoginController@hacerLogout'));
Route::get('/login', function () {
    return view("varios.login");
});
Route::get('/usuario/crear', function () {
    return view('usuario.crear');
})->middleware('auth')->middleware('tiene.rol:Hsoftware');

Route::post('usuario/crear', 'UsuarioController@crear');
Route::get('tipos', 'TipoProductoController@todos');

Route::get('/usuario/listar', function () {
    return view('usuario.listar')->with("users_lista",app('App\Http\Controllers\UsuarioController')->todos());
})->middleware('auth')->middleware('tiene.rol:Hsoftware');

Route::get('usuario/editar-password', 'UsuarioController@viewEditarPass')->middleware('auth');
Route::get('usuario/editar', 'UsuarioController@viewEditar')->middleware('auth');

Route::post('usuario/editar', 'UsuarioController@editar');
Route::post('usuario/editarpass', 'UsuarioController@editarpass');

Route::post('usuario/borrar', 'UsuarioController@borrar');

Route::get('/mesa-v2/{id}', function ($id = 0) {
    $propina = 0;
    if($id!=0){
        $propina = app('App\Http\Controllers\ConfigController')->getPropina();
    }
    try {
        if(env('DIA_OPERATIVO')){
            $dia_operativo = app('App\Http\Controllers\DocumentoController')->esDiaOperativoActivo();
        }
        else{
            $dia_operativo = true;
        }
    } catch (\Throwable $th) {
        $dia_operativo = true;
    }
    return view("mesa.menu-v2")->with('mesa', $id)
                            ->with('tipos_producto', app('App\Http\Controllers\TipoProductoController')->mostrarMenu())
                            ->with('mesa_alias', app('App\Http\Controllers\ConfigController')->getMesaAlias($id))
                            ->with('dia_operativo_valido', $dia_operativo)
                            ->with('valida_inventario', app('App\Http\Controllers\ConfigController')->getValidaInventario())
                            ->with('propina', $propina)
                            ->with('conn', app('App\Http\Controllers\TipoProductoController')->mesaMenu())
                            ->with('combos', app('App\Http\Controllers\ComboController')->menu());
})->middleware('auth')->middleware('tiene.roles:Mesero.Administrador.Cajero');


Route::get('/mesa/{id}', function ($id = 0) {
    $propina = 0;
    if($id!=0){
        $propina = app('App\Http\Controllers\ConfigController')->getPropina();
    }
    try {
        if(env('DIA_OPERATIVO')){
            $dia_operativo = app('App\Http\Controllers\DocumentoController')->esDiaOperativoActivo();
        }
        else{
            $dia_operativo = true;
        }
    } catch (\Throwable $th) {
        $dia_operativo = true;
    }
    return view("mesa.menu")->with('mesa', $id)
                            ->with('tipos_producto', app('App\Http\Controllers\TipoProductoController')->mostrarMenu())
                            ->with('mesa_alias', app('App\Http\Controllers\ConfigController')->getMesaAlias($id))
                            ->with('dia_operativo_valido', $dia_operativo)
                            ->with('valida_inventario', app('App\Http\Controllers\ConfigController')->getValidaInventario())
                            ->with('propina', $propina)
                            ->with('conn', app('App\Http\Controllers\TipoProductoController')->mesaMenu())
                            ->with('combos', app('App\Http\Controllers\ComboController')->menu());
})->middleware('auth')->middleware('tiene.roles:Mesero.Administrador.Cajero');

Route::get('menu-test', 'TipoProductoController@mostrarMenu');
Route::get('/producto-ingredientes', 'ProductoIngredienteController@getIngredientes');
Route::get('/adicionales', 'AdicionalController@getAdicionales');


//Route::get('/mesa/{id}','TipoProductoController@mesaMenu')->middleware('auth')->middleware('tiene.roles:Mesero.Administrador');

Route::get('/pedido/{id}/editar', 'PedidoController@vistaEditar')->middleware('auth')->middleware('tiene.roles:Mesero.Administrador.Cajero');
Route::post('/pedido/{id}/patch', 'PedidoController@patchPedido')->middleware('auth');
Route::post('/documento/{id}/patch', 'DocumentoController@patchDocumento')->middleware('auth');
Route::post('/pedido/{id}/save-propina', 'PedidoController@savePropina')->middleware('auth');

Route::get('/producto/ver/{id}', function ($id = 0) {
    return app('App\Http\Controllers\ProductoController')->buscarConIngredientesYAdicionales($id);
});

Route::get('/adicional/producto/{id}', function ($id = 0) {
    return app('App\Http\Controllers\AdicionalController')->buscarPorProducto($id);
});
Route::get('/adicional/tipo_producto/{id}', function ($id = 0) {
    return app('App\Http\Controllers\AdicionalController')->buscarPorTipoProducto($id);
});


/***************************************************
          Pedido
*****************************************/

Route::get('/pedidos/cancelar', function () {
    Session::set('pedidos', null);
});
Route::get('/pedido/archivados', function () {
    return view('pedido.archivados')->with('pedido_lista',app('App\Http\Controllers\PedidoController')->paginar());
})->middleware('auth')->middleware('tiene.roles:Mesero.Administrador');

Route::get('/producto/buscar/{buscar}', 'ProductoController@buscarModal')->middleware('auth');

Route::post('/producto-pedido/agregar', 'PedidoController@preAgregarProductoPedido')->middleware('auth');
Route::post('/combo-producto-pedido/agregar', 'PedidoController@preAgregarComboProductoPedido')->middleware('auth');
Route::post('/producto-pedido/borrar-combo/{combo}', 'ProductoPedidoController@borrarPorCombo')->middleware('auth');
//Route::post('/pedidos/agregar/{producto_pedido}/{mesa}/{pedido}', 'PedidoController@agregarProductoPedido')->middleware('auth');
/****** debug ****/
Route::get('/pedidos/agregar/{producto_pedido}/{mesa}/{pedido}', 'PedidoController@agregarProductoPedido')->middleware('auth');

Route::get('/pedidos/ver', function () {
    $pedidos = Session::get('pedidos');
    if($pedidos==null){
        return '[]';
    }
    else{
        return json_encode($pedidos);
    }
});
Route::post('/pedidos/mesa/{mesa}', 'PedidoController@entrarMesa');
Route::get('/pedidos/mesa/{mesa}', 'PedidoController@entrarMesa');

Route::post('/pedidos/pedido/{id}', function ($id = null) {
    $controller = app('App\Http\Controllers\PedidoController');
    $pedido = $controller->buscar($id);
    if($pedido == null){
        return '{"productos_pedido":[]}';
    }
    $controller = app('App\Http\Controllers\DocumentoController');
    $documento = $controller->encontrarPorPedido($id);
    if($documento){
        $pedido->descuento = $documento->descuento;
    }

    $controller = app('App\Http\Controllers\ProductoPedidoController');
    $productos_pedido = $controller->buscarPorPedido($pedido->id);
    
    $pedido->productos_pedido = $productos_pedido;
    
    $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');
    foreach($productos_pedido as $producto_pedido){
        $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
    }
    $pedido->productos_pedido = $productos_pedido;
    return $pedido;
});

Route::post('/pedidos/cancelar/{id}', function ($id = null) {
    try {
        $controller = app('App\Http\Controllers\PedidoController');
        $pedido = $controller->buscar($id);
        $controller->borrarPorId($pedido->id);
    } catch (Exception $exc) {
    }
});

Route::post('/pedidos/cancelarProductoPedido/{producto_pedido_id}', function ($producto_pedido_id = null, $mesa = null) {
    $controller = app('App\Http\Controllers\ProductoPedidoController');
    $controller->borrarPorId($producto_pedido_id);
});

Route::post('/producto/cargar_imagen', 'ProductoController@subirImagen');
Route::post('/producto/cambiar_imagen', 'ProductoController@cambiarImagen');
Route::post('/producto/guardar-como/{id}', 'ProductoController@guardarComo');

function buscarArrayKey($array, $llave, $valor){
    if ($array == null) {
        return -1;
    } else {
        foreach ($array as $key => $pedido) {
            if ($pedido[$llave] == $valor) {
                return $key;
            }
        }
        return -1;
    }
}


/*
|--------------------------------------------------------------------------
| Adicional Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/adicional/listar', function () {
    return view('adicional.listar')
                                ->with("adicional_lista",app('App\Http\Controllers\AdicionalController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/adicional/editar/{id}', function ($id) {
    return view('adicional.editar')
                                ->with("adicional",app('App\Http\Controllers\AdicionalController')->buscar($id))
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todos())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');

Route::get('/adicional/crear', function () {
    return view('adicional.crear')
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todos())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');

Route::post('/adicional/crear', 'AdicionalController@crear');
Route::post('/adicional/editar', 'AdicionalController@editar');
Route::post('/adicional/borrar', 'AdicionalController@borrar');
Route::get('/adicional/agregar', function () {
    return view('adicional.agregar')
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todosAZ())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todosAZ());
})->middleware('auth')->middleware('tiene.rol:Administrador');

Route::post('/adicionales/guardar/', 'AdicionalController@guardar');
Route::get('/adicionales/guardar/{data}', 'AdicionalController@guardar');

/*
|--------------------------------------------------------------------------
| Config Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/config/listar', function () {
    return view('config.listar')
                                ->with("config_lista",app('App\Http\Controllers\ConfigController')->todos());
});


Route::get('/configuracion/', function () {
    return app('App\Http\Controllers\ConfigController')->first();
});
Route::get('/config/editar', 'ConfigController@vistaEditar')->middleware('auth')->middleware('tiene.rol:Administrador');

Route::get('/config/crear', function () {
    return view('config.crear');
});
Route::post('/config/crear', 'ConfigController@crear');
Route::post('/config/editar', 'ConfigController@editar');
Route::post('/config/borrar', 'ConfigController@borrar');
Route::get('/config/servicio-impresion', 'ConfigController@servicioImpresion');

/*
|--------------------------------------------------------------------------
| Documento Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/documento/listar', 'DocumentoController@vistaLista')->middleware('auth')->middleware('tiene.rol:Administrador');

Route::get('/documento/{id}/editar', 'DocumentoController@vistaEditar')->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/documento/crear', function () {
    return view('documento.crear')
    ->with("tercero_lista", app('App\Http\Controllers\TerceroController')->todos())
    ->with("producto_lista",app('App\Http\Controllers\ProductoTamanoController')->todosConProducto(''))
    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::post('/documento/crear', 'DocumentoController@crear');
Route::post('/documento/editar/{id}', 'DocumentoController@editar');
Route::post('/documento/{id}/editar-post', 'DocumentoController@editarPost');
Route::post('/documento/borrar', 'DocumentoController@borrar');

/*
|--------------------------------------------------------------------------
| Ingrediente Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/ingrediente/listar', function () {
    return view('ingrediente.listar')
                                ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/ingrediente/editar/{id}', function ($id) {
    return view('ingrediente.editar')
                                ->with("ingrediente",app('App\Http\Controllers\IngredienteController')->buscar($id));
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/ingrediente/crear', function () {
//    return view('ingrediente.crear');
    return view('ingrediente.crear')->with('_imagen','ingrediente.jpg');
})->middleware('auth')->middleware('tiene.rol:Administrador');

Route::post('/ingrediente/crear', 'IngredienteController@subirImagen');
Route::get('/ingrediente/subirImagen', 'IngredienteController@subirImagen');
Route::post('/ingrediente/editar', 'IngredienteController@editar');
Route::post('/ingrediente/borrar', 'IngredienteController@borrar');
Route::post('/ingrediente/borrar-post/{id}', 'IngredienteController@borrarPost');
Route::post('/ingrediente/crearModal', 'IngredienteController@crearModal');
Route::get('/ingrediente/lista', 'IngredienteController@todosAZ');

/*
|--------------------------------------------------------------------------
| Pedido Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/pedido/listar', function () {
    return view('pedido.listar')
                                ->with("activos",app('App\Http\Controllers\PedidoController')->todosActivos());
});
*/
Route::get('/pedido/listar', function () {
    return view('pedido.listar')->with('pedido_lista',app('App\Http\Controllers\PedidoController')->paginaractivos());
})->middleware('auth')->middleware('tiene.roles:Administrador.Mesero.Cajero');
Route::get('/pedido/listar/cancelado', function () {
    return Redirect::to('pedido/listar')->with('status', ["success" => "Registro borrado."]);
})->middleware('auth')->middleware('tiene.roles:Administrador.Mesero.Cajero');

/*
Route::get('/domicilios', function () {
    return view('pedido.domicilios')
                                ->with("activos",app('App\Http\Controllers\PedidoController')->domiciliosActivos())
                                ->with("inactivos",app('App\Http\Controllers\PedidoController')->domiciliosInactivos());
});
*/
Route::get('/domicilios', function () {
    return view('pedido.domicilios')->with('pedido_lista',app('App\Http\Controllers\PedidoController')->paginardomicilios());
})->middleware('auth')->middleware('tiene.roles:Administrador.Mesero.Cajero');

Route::get('/pedido/editar/{id}', function ($id) {
    return view('pedido.editar')
                                ->with("pedido",app('App\Http\Controllers\PedidoController')->buscar($id));
});
Route::get('/pedido/crear', function () {
    return view('pedido.crear');
});
Route::get('/pedido/ver/{id}', function ($id = null) {
    $controller = app('App\Http\Controllers\PedidoController');
    $pedido = $controller->buscar($id);
    if($pedido==null){
        return Redirect::to('pedido/listar')
                        ->with('status', ["warning" => "El pedido no existe."]);
    } 
    else {
        $controller = app('App\Http\Controllers\DocumentoController');
        $documento = $controller->encontrarPorPedido($id);
        if($documento){
            $pedido->descuento = $documento->descuento;
        }
        return view('pedido.ver')->with("pedido", $pedido);
    }
})->middleware('auth');
Route::post('/pedido/crear', 'PedidoController@crear');
Route::post('/pedido/editar', 'PedidoController@editar');
Route::post('/pedido/borrar', 'PedidoController@borrar');
Route::post('/pedido/borrar_api', 'PedidoController@borrar_api');
Route::post('/pedido/borrardomicilio', 'PedidoController@borrarDomicilio');

Route::post('/pedido/pagar/','PedidoController@pagarPorId');
Route::post('/pedido/pagarImprimir/{id}','PedidoController@pagarImprimirPorId');
Route::get('/pedido/preFactura/{id}','PedidoController@preFacturar');
Route::post('/pedido/parallevar/{id}','PedidoController@parallevar');
Route::post('/pedido/guardar-observacion/{id}/{ob}','PedidoController@guardarObservacion');

Route::get('/pedido/factura2/{id}', function ($id = null) {
    $controller = app('App\Http\Controllers\PedidoController');
    $pedido = $controller->buscar($id);
    if($pedido != null){
        $controller = app('App\Http\Controllers\ProductoPedidoController');
        $productos_pedido = $controller->buscarPorPedido($pedido->id);

        $pedido->productos_pedido = $productos_pedido;

        $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');
        foreach($productos_pedido as $producto_pedido){
            $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
        }
        $pedido->productos_pedido = $productos_pedido;
    }
    $pdf = App::make('dompdf.wrapper');
    $html = App\Util\PDF::ImpFacturaPedido($pedido);
    $html = str_replace("\n", "", $html);
    $html = str_replace("\r", "", $html);
    $html = preg_replace('/>\s+</', '><', $html);
    $pdf->loadHTML($html)->setPaper(array(0,0,230,841));
    return $pdf->stream();
});

Route::get('/pedido/factura/{id}', 'PedidoController@impFactura');

Route::get('/pedido/factura/{id}/pos', 'PedidoController@impFacturaPos');

Route::get('/documento/imprimir/{id}', 'DocumentoController@impDocumento');

Route::get('/pedido/comanda/{id}', function ($id = null) {
    $controller = app('App\Http\Controllers\PedidoController');
    $pedido = $controller->buscar($id);
    if($pedido != null){
        $controller = app('App\Http\Controllers\ProductoPedidoController');
        $productos_pedido = $controller->buscarPorPedido($pedido->id);

        $pedido->productos_pedido = $productos_pedido;

        $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');
        foreach($productos_pedido as $producto_pedido){
            $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
        }
        $pedido->productos_pedido = $productos_pedido;
    }
    $pdf = App::make('dompdf.wrapper');
    $html = App\Util\PDF::ImpComandaPedido($pedido);
    $html = str_replace("\n", "", $html);
    $html = str_replace("\r", "", $html);
    $html = preg_replace('/>\s+</', '><', $html);
//    die($html);
//    $pdf->set_option('dpi', 58);
    $pdf->loadHTML($html)->setPaper(array(0,0,230,841));
    //app('App\Http\Controllers\PedidoController')->actualizarComanda($pedido->id);
    return $pdf->stream();
});

Route::get('/pedido/comanda/{id}/pos', function ($id = null) {
    $controller = app('App\Http\Controllers\PedidoController');
    $pedido = $controller->buscar($id);
    if($pedido != null){
        $controller = app('App\Http\Controllers\ProductoPedidoController');
        $productos_pedido = $controller->buscarPorPedido($pedido->id);

        $pedido->productos_pedido = $productos_pedido;

        $controller = app('App\Http\Controllers\ProductoPedidoAdicionalController');
        foreach($productos_pedido as $producto_pedido){
            $producto_pedido["producto_pedido_adicionales"] = $controller->buscarPorProductoPedido($producto_pedido->id);
        }
        $pedido->productos_pedido = $productos_pedido;
    }
    app('App\Http\Controllers\PedidoController')->actualizarComanda($pedido->id);
    return view('imp.comanda')->with("pedido", $pedido)->with('config', app('App\Http\Controllers\ConfigController')->first());
});

Route::get('/pedido/comanda/{id}/pos-stack', 'PedidoController@precomandaPosStack');
Route::get('/pedido/re-comanda/{id}/pos-stack', 'PedidoController@preFullcomandaPosStack');
Route::get('/pedido/factura/{id}/pos-stack', 'PedidoController@prefacturaPosStack');
Route::get('/gaveta', 'PedidoController@gaveta');
Route::get('/impresora/test', 'ConfigController@testImpresora');
Route::get('/impresora/stack', function(){
    return view('imp.stack');
});
/*
|--------------------------------------------------------------------------
| Producto Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/producto/listar', function () {
    return view('producto.listar')
                                ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
//Route::get('/producto/editar/{id}', function ($id) {
//    return view('producto.editar')
//                                ->with("producto",app('App\Http\Controllers\ProductoController')->buscar($id))
//                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todos());
//});
Route::get('/producto/crear', function () {
    return view('producto.crear')
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/producto/agregar', function () {
    return view('producto.agregar')
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todosAZ())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todosAZ())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todosAZ());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/producto/editar/{id}', function ($id=null) {
    return view('producto.editar')
                                    ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todosAZ())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todosAZ())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todosAZ())
                                    ->with("tamanos",app('App\Http\Controllers\ProductoTamanoController')->buscarPorProducto($id))
                                    ->with("producto",app('App\Http\Controllers\ProductoController')->buscarCompleto($id))
                                    ->with("producto_ingredientes",app('App\Http\Controllers\ProductoIngredienteController')->buscarPorProducto($id));
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::post('/producto/crear', 'ProductoController@crear');
Route::post('/producto/crearCompleto', 'ProductoController@crearCompleto');
Route::get('/producto/crearModal', 'ProductoController@crearModal');
Route::post('/producto/editar', 'ProductoController@editar');
Route::post('/producto/editarCompleto', 'ProductoController@editarCompleto');
Route::get('/producto/tamanos/{id}', 'ProductoTamanoController@buscarPorProducto');
Route::post('/producto/borrar', 'ProductoController@borrar');
Route::post("/producto/{id}", "ProductoController@patchProducto");
Route::patch("/producto/{id}", "ProductoController@patchProducto");
Route::get("/producto/{id}", "ProductoController@getProducto");



/*
|--------------------------------------------------------------------------
| ProductoIngrediente Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/producto_ingrediente/listar', function () {
    return view('producto_ingrediente.listar')
                                ->with("producto_ingrediente_lista",app('App\Http\Controllers\ProductoIngredienteController')->todos());
});
Route::get('/producto_ingrediente/editar/{id}', function ($id) {
    return view('producto_ingrediente.editar')
                                ->with("producto_ingrediente",app('App\Http\Controllers\ProductoIngredienteController')->buscar($id))
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
});
Route::get('/producto_ingrediente/crear', function () {
    return view('producto_ingrediente.crear')
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("ingrediente_lista",app('App\Http\Controllers\IngredienteController')->todos());
});
Route::post('/producto_ingrediente/crear', 'ProductoIngredienteController@crear');
Route::post('/producto_ingrediente/editar', 'ProductoIngredienteController@editar');
Route::post('/producto_ingrediente/borrar', 'ProductoIngredienteController@borrar');
*/
/*
|--------------------------------------------------------------------------
| ProductoPedido Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/producto_pedido/listar', function () {
    return view('producto_pedido.listar')
                                ->with("producto_pedido_lista",app('App\Http\Controllers\ProductoPedidoController')->todos());
});
Route::get('/producto_pedido/editar/{id}', function ($id) {
    return view('producto_pedido.editar')
                                ->with("producto_pedido",app('App\Http\Controllers\ProductoPedidoController')->buscar($id))
                                    ->with("pedido_lista",app('App\Http\Controllers\PedidoController')->todos())
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos());
});
Route::get('/producto_pedido/crear', function () {
    return view('producto_pedido.crear')
                                    ->with("pedido_lista",app('App\Http\Controllers\PedidoController')->todos())
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos());
});
Route::post('/producto_pedido/crear', 'ProductoPedidoController@crear');
Route::post('/producto_pedido/editar', 'ProductoPedidoController@editar');
Route::post('/producto_pedido/borrar', 'ProductoPedidoController@borrar');
*/
/*
|--------------------------------------------------------------------------
| ProductoPedidoAdicional Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/producto_pedido_adicional/listar', function () {
    return view('producto_pedido_adicional.listar')
                                ->with("producto_pedido_adicional_lista",app('App\Http\Controllers\ProductoPedidoAdicionalController')->todos());
});
Route::get('/producto_pedido_adicional/editar/{id}', function ($id) {
    return view('producto_pedido_adicional.editar')
                                ->with("producto_pedido_adicional",app('App\Http\Controllers\ProductoPedidoAdicionalController')->buscar($id))
                                    ->with("producto_pedido_lista",app('App\Http\Controllers\ProductoPedidoController')->todos())
                                    ->with("adicional_lista",app('App\Http\Controllers\AdicionalController')->todos());
});
Route::get('/producto_pedido_adicional/crear', function () {
    return view('producto_pedido_adicional.crear')
                                    ->with("producto_pedido_lista",app('App\Http\Controllers\ProductoPedidoController')->todos())
                                    ->with("adicional_lista",app('App\Http\Controllers\AdicionalController')->todos());
});
Route::post('/producto_pedido_adicional/crear', 'ProductoPedidoAdicionalController@crear');
Route::post('/producto_pedido_adicional/editar', 'ProductoPedidoAdicionalController@editar');
Route::post('/producto_pedido_adicional/borrar', 'ProductoPedidoAdicionalController@borrar');
*/
/*
|--------------------------------------------------------------------------
| ProductoPedidoDocumento Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/producto_pedido_documento/listar', function () {
    return view('producto_pedido_documento.listar')
                                ->with("producto_pedido_documento_lista",app('App\Http\Controllers\ProductoPedidoDocumentoController')->todos());
});
Route::get('/producto_pedido_documento/editar/{id}', function ($id) {
    return view('producto_pedido_documento.editar')
                                ->with("producto_pedido_documento",app('App\Http\Controllers\ProductoPedidoDocumentoController')->buscar($id));
});
Route::get('/producto_pedido_documento/crear', function () {
    return view('producto_pedido_documento.crear');
});
Route::post('/producto_pedido_documento/crear', 'ProductoPedidoDocumentoController@crear');
Route::post('/producto_pedido_documento/editar', 'ProductoPedidoDocumentoController@editar');
Route::post('/producto_pedido_documento/borrar', 'ProductoPedidoDocumentoController@borrar');
*/
/*
|--------------------------------------------------------------------------
| TipoDocumento Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/prepare-domicilio-documento', 'TipoDocumentoController@prepareDomicilioDocumento');
Route::post('/domicilio-documento', 'DocumentoController@saveDomicilioDocumento');
Route::post('/pago-compra', 'DocumentoController@savePagoCompra');
/*
Route::get('/tipo_documento/listar', function () {
    return view('tipo_documento.listar')
                                ->with("tipo_documento_lista",app('App\Http\Controllers\TipoDocumentoController')->todos());
});
Route::get('/tipo_documento/editar/{id}', function ($id) {
    return view('tipo_documento.editar')
                                ->with("tipo_documento",app('App\Http\Controllers\TipoDocumentoController')->buscar($id));
});
Route::get('/tipo_documento/crear', function () {
    return view('tipo_documento.crear');
});
Route::post('/tipo_documento/crear', 'TipoDocumentoController@crear');
Route::post('/tipo_documento/editar', 'TipoDocumentoController@editar');
Route::post('/tipo_documento/borrar', 'TipoDocumentoController@borrar');
*/
/*
|--------------------------------------------------------------------------
| TipoProducto Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/tipo_producto/listar', function () {
    return view('tipo_producto.listar')
                                ->with("tipo_producto_lista",app('App\Http\Controllers\TipoProductoController')->todos());
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/tipo_producto/editar/{id}', function ($id) {
    return view('tipo_producto.editar')
                                ->with("tipo_producto",app('App\Http\Controllers\TipoProductoController')->buscar($id));
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get('/tipo_producto/crear', function () {
    return view('tipo_producto.crear');
})->middleware('auth')->middleware('tiene.rol:Administrador');
Route::post('/tipo_producto/crear', 'TipoProductoController@crear');
Route::post('/tipo_producto/editar', 'TipoProductoController@editar');
Route::post('/tipo_producto/borrar', 'TipoProductoController@borrar');
Route::post('/tipo_producto/crearModal', 'TipoProductoController@crearModal');
Route::get('/auth-hsoftware', 'LoginController@doAuthLogin');
/*
Route::get('/tipo_producto/crearModal', 'TipoProductoController@crearModal');
Route::get('/tipo_producto/lista', 'TipoProductoController@todosAZ');
*/

/*
|--------------------------------------------------------------------------
| ProductoSabor Routes
|--------------------------------------------------------------------------
|
*/
/*
Route::get('/producto_sabor/listar', function () {
    return view('producto_sabor.listar')
                                ->with("producto_sabor_lista",app('App\Http\Controllers\ProductoSaborController')->todos());
});
Route::get('/producto_sabor/editar/{id}', function ($id) {
    return view('producto_sabor.editar')
                                ->with("producto_sabor",app('App\Http\Controllers\ProductoSaborController')->buscar($id))
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todos());
});
Route::get('/producto_sabor/crear', function () {
    return view('producto_sabor.crear')
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todos());
});
Route::post('/producto_sabor/crear', 'ProductoSaborController@crear');
Route::post('/producto_sabor/editar', 'ProductoSaborController@editar');
Route::post('/producto_sabor/borrar', 'ProductoSaborController@borrar');

*/
/*
|--------------------------------------------------------------------------
| Sabor Routes
|--------------------------------------------------------------------------
|
*/

/*
Route::get('/sabor/listar', function () {
    return view('sabor.listar')
                                ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todos());
});
Route::get('/sabor/editar/{id}', function ($id) {
    return view('sabor.editar')
                                ->with("sabor",app('App\Http\Controllers\SaborController')->buscar($id))
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todos());
});
Route::get('/sabor/crear', function () {
    return view('sabor.crear')
                                    ->with("producto_lista",app('App\Http\Controllers\ProductoController')->todos())
                                    ->with("sabor_lista",app('App\Http\Controllers\SaborController')->todos());
});
Route::post('/sabor/crear', 'SaborController@crear');
Route::post('/sabor/editar', 'SaborController@editar');
Route::post('/sabor/borrar', 'SaborController@borrar');
Route::get('/sabor/lista', 'SaborController@todosAZ');

*/
Route::post('/sabor/crearModal', 'SaborController@crearModal');

Route::get('borrar-sesion', function(){
    Session::forget('status');
});


Route::get('/informe', 'InformeController@ini');
Route::get('/informe/{ano}', 'InformeController@ano');
Route::get('/informe/{ano}/{mes}', 'InformeController@mes');
Route::get('/informe_rango', 'InformeController@iniRango');
Route::get('/informe_rango/{ano}/{mes}/{ano2}/{mes2}', 'InformeController@viewRango');

Route::get("/mesa/listar/ocupadas", "PedidoController@mesasOcupadas");
Route::get("/pedido/cambiarmesa/{o}/{d}", "PedidoController@cambiarMesa");
Route::get("/pedido/entregar/{pedido}/{direccion}", "PedidoController@entregar");
Route::get("/pedido/entregar/{pedido}/{entregar_en}/{direccion}", "PedidoController@entregarEn");
Route::get("/pedido/observacion/{pedido}/{direccion}", "PedidoController@observacion");
Route::get("/pedido/programar/{pedido}/{fecha}", "PedidoController@programar");
Route::post("/pedido/entregado/{pedido}", "PedidoController@entregado");


//ACTIVAR DESACTIVAR
Route::patch("/producto/{id}/estado/{estado}", "ProductoController@patchEstado");
Route::patch("/tipo_producto/{id}/estado/{estado}", "TipoProductoController@patchEstado");


Route::get("/saldos_producto", "SaldosProductoController@vistaLista")->middleware('auth')->middleware('tiene.rol:Administrador');
Route::get("/inventario/detallado", "SaldosProductoController@generarDetallado")->middleware('auth')->middleware('tiene.rol:Administrador');
Route::post('/documento/{id}/anular', 'DocumentoController@anular')->middleware('auth')->middleware('tiene.rol:Administrador');



/*-------------------------------------------------------------------------
|           Rutas Tercero
|------------------------------------------------------------------------*/
Route::get('/tercero', 'TerceroController@vistaLista');
Route::get('/api/terceros', 'TerceroController@getTerceros');
Route::get('/tercero/crear', 'TerceroController@vistaCrear');
Route::get('/tercero/{id}/editar', 'TerceroController@vistaEditar');

Route::get('/tercero/buscar/{buscar}', 'TerceroController@buscar');
Route::get('/tercero/paginar', 'TerceroController@paginar_modal');
Route::post('/tercero/', 'TerceroController@crear');
Route::post('/tercero/modal/', 'TerceroController@crearModal');
Route::post('/new-tercero/', 'TerceroController@crearIf');
Route::put('/tercero/', 'TerceroController@editar');
Route::put('/tercero/modal', 'TerceroController@editarModal');
Route::delete('/tercero/', 'TerceroController@borrar');
Route::delete('/tercero/modal', 'TerceroController@borrarModal');


Route::get('/cocina', 'CocinaController@vistaCocina');
Route::get('/cocina/nuevos/{id}', 'CocinaController@nuevosPedidos');
Route::get('/producto_pedido/preparado/{id}', 'ProductoPedidoController@preparado');
/*-------------------------------------------------------------------------
|           Rutas Combo
|------------------------------------------------------------------------*/
Route::get('/combo', 'ComboController@vistaLista');
//Route::get('/combo/terceros', 'TerceroController@getTerceros');
Route::get('/combo/crear', 'ComboController@vistaCrear');
Route::get('/combo/{id}/editar', 'ComboController@vistaEditar');
Route::patch("/combo/{id}/estado/{estado}", "ComboController@patchEstado");
Route::post('/combo/borrar-post/{id}', 'ComboController@borrarPost');
//
//Route::get('/tercero/buscar/{buscar}', 'TerceroController@buscar');
//Route::get('/tercero/paginar', 'TerceroController@paginar_modal');
Route::post('/combo/', 'ComboController@crearCompleto');
Route::post('/combo/imagen', 'ComboController@subirImagen');
Route::post('/combo/{id}/imagen', 'ComboController@editarImagen');
Route::post('/combo/{id}', 'ComboController@editarCombo');
Route::post('/combo/{id}/recalcular', 'ComboController@recalcular');
//Route::post('/tercero/modal/', 'TerceroController@crearModal');
//Route::put('/tercero/', 'TerceroController@editar');
//Route::put('/tercero/modal', 'TerceroController@editarModal');
Route::delete('/tercero/', 'TerceroController@borrar');
//Route::delete('/tercero/modal', 'TerceroController@borrarModal');
//
//
//Route::get('/cocina', 'CocinaController@vistaCocina');
//Route::get('/producto_pedido/preparado/{id}', 'ProductoPedidoController@preparado');

Route::post('/combo-productos/{id}/borrar/', 'ComboProductoController@borrar');
Route::post('/combo-productos/', 'ComboProductoController@crear');

Route::get('/saldos_producto/excel', 'SaldosProductoController@excelContent');
Route::get('/producto-pedido/full/{id}', 'ProductoPedidoController@getFull');
Route::patch('/producto-pedido/{id}/obs', 'ProductoPedidoController@patchObs');

Route::get('/mesero/informe', 'InformeController@vistaMesero');
Route::post('/mesero/informe', 'InformeController@informeMesero');

Route::get('/bancos/informe', 'InformeController@vistaBanco');
Route::post('/bancos/informe', 'InformeController@informeBanco');

Route::get('/mesax/{mesa}', 'ConfigController@vistaMenu');
Route::get('/preMenu/{mesa}', 'ConfigController@preMenu');
Route::get('/adding-producto/{id}', 'ProductoController@adding');

Route::get('/producto_vendido', 'InformeController@vistaMasVendido');
Route::post('/producto_vendido', 'InformeController@sqlMasVendido');
Route::post('/inventario/pos', 'SaldosProductoController@pos');

Route::get('/config/orden', 'TipoProductoController@ordenView')->middleware('auth')->middleware('tiene.roles:Administrador.Cajero');
Route::post('/orden/tipo_producto', 'TipoProductoController@orden')->middleware('auth')->middleware('tiene.roles:Administrador.Cajero');;
Route::post('/orden/combo', 'ComboController@orden')->middleware('auth')->middleware('tiene.roles:Administrador.Cajero');;