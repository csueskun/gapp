<?php

namespace App\Http\Controllers;
use App\DetalleDocumento;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Auth;

class DetalleDocumentoController extends Controller
{
    /*public function __construct(){
        DB::setDefaultConnection(Auth::user()->conn);
    }*/
    function buscarPorDocumento($documento_id){
        return DetalleDocumento::where('documento_id',$documento_id)->get();
    }
}