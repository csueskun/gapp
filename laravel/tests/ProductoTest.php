<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Producto;
use Carbon\Carbon;

class ProductoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        //$productos = Producto::all()->keyBy('id');
        // $producto = $productos[0];
        // app('App\Http\Controllers\SaldosProductoController')->exportTemplate();
        // $cases = [
        //     ['assert'=>false, 'value'=>'-1'],
        //     ['assert'=>true, 'value'=>'1000'],
        //     ['assert'=>false, 'value'=>true],
        //     ['assert'=>true, 'value'=>'aaa'],
        // ];
        // $n = Carbon::now();
        // foreach ($cases as $case) {
        //     if($case['assert']){
        //         $this->assertTrue(is_numeric($case['value']));
        //     }
        // }
        $this->assertTrue(true);
    }
}
