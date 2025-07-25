<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Exception;
use Monolog\Handler\IFTTTHandler;


class PrinterService
{
    public function print(array $printer, array $rows, array $options = [])
    {
        try {
            $printerName = $printer['impresora'];
            $maxPrinterChars = $printer['num_impresora'] ?? 32;

            $connector = $this->createConnector($printerName);
            $printer = new Printer($connector);
            
            // Optional logo
            if (!empty($options['logo']) && $options['logo'] == true) {
                $logoFile = $this->getLogoSize($maxPrinterChars);
                $logo = EscposImage::load(base_path().'/public/images/' . $logoFile);
                $printer->bitImage($logo);
                $printer->feed(1);
            }

            // Body rows
            foreach ($rows as $formatedRow) {
                if(is_array($formatedRow)){
                    $formatedRow = $this->formatColumnsRow($formatedRow, $maxPrinterChars);
                }
                $printable = true;
                $printer -> setFont(Printer::FONT_A);
                $printer -> setTextSize(1, 1);

                if(str_contains($formatedRow, '|||')){
                    $action = explode('|||', $formatedRow);
                    $formatedRow = $action[1];
                    $action = $action[0];

                    switch ($action) {
                        case 'RIGHT':
                            $formatedRow = str_pad($formatedRow, $maxPrinterChars, ' ', STR_PAD_LEFT);
                            break;
                        case 'LEFT':
                            $formatedRow = str_pad($formatedRow, $maxPrinterChars, ' ', STR_PAD_RIGHT);
                            break;
                        case 'CENTER':
                            $formatedRow = str_pad($formatedRow, $maxPrinterChars, ' ', STR_PAD_BOTH);
                            break;
                        case 'B':
                            $printer->setFont(Printer::FONT_B);
                            $printer->setTextSize(2, 2);
                            break;
                    }
                }
                if(!$printable){
                    continue;
                }
                $printer->text($formatedRow);
                $printer->feed(1);
                
            }

            // QR Code
            if (!empty($options['qr'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->qrCode($options['qr'], Printer::QR_ECLEVEL_L, 6);
                $printer->feed(1);
            }

            // Barcode
            if (!empty($options['barcode'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->barcode($options['barcode'], Printer::BARCODE_CODE39);
                $printer->feed(1);
            }

            // Drawer
            if (!empty($options['drawer'])) {
                $printer->pulse();
            }

            $printer->cut();
            $printer->close();

            return true;
        } catch (Exception $e) {
            if ($printer) {
                $printer->close();
            }
            throw $e;
        }
    }

    public function formatColumnsRow(array $columns, int $width){
        if(count($columns) == 0){
            return [];
        }
        if(count($columns) == 1){
            return $columns[0];
        }
        if(count($columns) == 2){
            $widths = [ceil($width/2), floor($width/2)];
            $rows = $this->createTable([$columns], $widths, ['left', 'right']);
            return implode("\n", $rows);
        }
        return 'NO SUPPORTED';
    }

    public function createSeparator(int $width, string $char = '-'): string
    {
        return str_repeat($char, $width);
    }

    public function createTableWithHeader(array $header, array $body, array $width, int $maxPrinterChars, array $align = [], string $divider = ' '): array
    {
        $headerRows = [];
        if(count($header) == 3){
            $headerRows = $this->createTable($header[0], $header[1], $header[2], $divider);
            $headerRows[] = $this->createSeparator($maxPrinterChars);
        }
        $bodyRows = $this->createTable($body, $width, $align, $divider);
        return array_merge($headerRows, $bodyRows);
    }
    public function createTable(array $table, array $width, array $align = [], string $divider = ' '): array
    {
        $rows = [];
        if(count($align) == 0){
            $align = array_fill(0, count($width), STR_PAD_RIGHT);
        }
        else{
            $align = array_map(function($a) {
                if($a == 'right'){
                    return STR_PAD_LEFT;
                }
                elseif($a == 'center'){
                    return STR_PAD_BOTH;
                }
                else{
                    return STR_PAD_RIGHT;
                }
            }, $align);
        }
        for($i=0;$i<count($width);$i++){
            if($width[$i] == 'min'){
                $width[$i] = 0;
                foreach($table as $row){
                    if(isset($row[$i])){
                        $width[$i] = max($width[$i], strlen($row[$i]));
                    }
                }
            }
        }
        for($i=0;$i<count($width);$i++){
            if(starts_with($width[$i], 'max:')){
                $max = explode(':', $width[$i]);
                $max = (int)$max[1];
                for($j=0;$j<count($width);$j++){
                    $w = $width[$j];
                    if(is_numeric($w)){
                        $max = $max - $w;
                    }
                }
                $width[$i] = $max;         
            }
        }
        $maxIndex = array_search(max($width), $width);
        if ($maxIndex !== false) {
            $width[$maxIndex] = max(0, $width[$maxIndex] - count($width) +1);
        }
        
        for($k=0;$k<count($table);$k++){
            $width_lengths = [];
            $str = $table[$k];

            $columns = min(count($str), count($width));
            for($i=0;$i<$columns;$i++){
                //trim might shorten the string
                $str[$i] = str_split(trim($str[$i]), $width[$i]);
                $width_lengths[] = count($str[$i]);
            }
            $max = max($width_lengths);
            for($i=0;$i<$columns;$i++){
                if(count($str[$i]) < $max){
                    $str[$i] = array_merge( array_fill(0, $max - count($str[$i]), ''), $str[$i]);
                }
            }
            for($i=0;$i<max($width_lengths);$i++){
                
                $row = "";
                for($j=0;$j<$columns;$j++){
                    $row.= str_pad(trim($str[$j][$i]),$width[$j], ' ', $align[$j]);
                    if($j < $columns - 1){
                        $row.=$divider;
                    }
                }
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function addBoldText(string $text)
    {
        return $this->addRichText($text, 'B');
    }
    public function addRightAlignText(string $text)
    {
        return $this->addRichText($text, 'RIGHT');
    }
    public function addRichText(string $text, $action)
    {
        return $action . '|||' . $text;
    }

    
    private function createConnector(string $impresora)
    {
        return new CupsPrintConnector($impresora); //All
        if(env('OS') == 'linux'){
            return new CupsPrintConnector($impresora); //linux
        }
        else{
            return new WindowsPrintConnector($impresora); //windows
        }
    }

    public function createGeneratedAtRow(int $maxPrinterChars): array
    {
        return $this->createTable(
            [['Generado el:', date('d/m/Y g:ia')],],
            [12, $maxPrinterChars-12],
            ['left', 'right']
        );
    }
    public function createSignature(int $maxPrinterChars): array
    {
        return ["CENTER|||PedidosGapp ® 2025"];
    }

    private function getLogoSize(int $maxPrinterChars): string
    {
        if ($maxPrinterChars >= 48) {
            return 'logo-gapp-pos-2025.png';
        } elseif ($maxPrinterChars >= 32) {
            return 'logo-gapp-pos-2025x380-padded.png';
        }
        return '';
    }

}
