<?php



//echo "<img src='http://pedidos.h-software.co/images/tux.png'/>";

require __DIR__.'/../../../../laravel/vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */



$connector = new WindowsPrintConnector("POS-58");
$printer = new Printer($connector);
$printer -> text("\n");
$printer -> text(" __  __      __  __  __  __ \n");
$printer -> text("|__]|  ||_/ |__||  \|  |[__ \n");
$printer -> text("|__]|__|| \_|  ||__/|__|___]\n");
$printer -> text("\n");
$printer -> text("\n");
$printer -> cut();
$printer -> close();