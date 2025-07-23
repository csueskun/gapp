<?php

namespace App\Http\Controllers;
use App\ProductoPedidoIngrediente;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Services\DIANServices;
use App\Services\PrinterService;

class ProductoPedidoIngredienteController extends Controller
{
    protected $dianServices;
    protected $printerService;
    public function __construct(DIANServices $dianServices, PrinterService $printerService)
    {
        $this->printerService = $printerService;
        $this->dianServices = $dianServices;
    }
    public function guardar($datos) {
        $producto_pedido_ingrediente = new ProductoPedidoIngrediente;
        $producto_pedido_ingrediente->producto_pedido_id = $datos->producto_pedido_id;
        $producto_pedido_ingrediente->ingrediente_id = $datos->ingrediente_id;
        $producto_pedido_ingrediente->cant = $datos->cant;
        $producto_pedido_ingrediente->save();
        return $producto_pedido_ingrediente;
    }

    public function getConversionRate($number)
    {
        // return $this->dianServices->convertWithRawXml($number);
        $printerConfig = [
            'impresora' => 'xprinter',
        ];
        $title = 'My Store';
        $rows = [
            ['Latte', '1', '$3.00'],
            ['Muffin', '2', '$2.50'],
        ];

        $options = [
            'logo_path' => storage_path('app/public/pos_logo.png'),
            'qr' => 'https://mystore.com/order/12345',
            'barcode' => 'ABC12345',
        ];
        try {
            $this->printerService->print($printerConfig, $title, $rows, 42, $options);
            return response()->json(['message' => 'Printed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    
    }

}