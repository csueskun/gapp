<?php

// para produccion:
//require __DIR__.'/../../../../laravel/vendor/mike42/escpos-php/autoload.php';
//para desarrollo:
require __DIR__.'/../../../vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */

$connector = new WindowsPrintConnector($config->impresora);
$printer = new Printer($connector);
$carpeta_imagenes = $_SERVER['DOCUMENT_ROOT']."\\images\\";

$imagen = EscposImage::load('c://hen.png', false);
$printer -> bitImage($imagen);

$printer->feed(3);
$printer->cut();
$printer->close();