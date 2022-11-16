<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDocumento(){
        $controller = app('App\Http\Controllers\DocumentoController');
        $controller->inventarioImportRowToDocumento([]);
        $controller;
        $this->assertTrue(true);
    }
}
