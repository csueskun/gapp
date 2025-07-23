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


class HtmlPrinterService
{
    public function print(array $rows, array $options = [])
    {
        try {
            $html = '<html>
                <head><style>html{font-size: 14px;}</style><meta charset="utf-8"></head>
                <body style="font-family: Courier New, monospace; background-color: #939393;">
                <div class="container" style="padding: 16px; max-width: 800px; margin: auto; display: block; background-color: #e1e1e1; margin-top: 24px">';

            if (!empty($options['logo']) && $options['logo'] == true) {
                $html .= '<div style="display: block; height: 30px; text-align: center; margin-bottom: 8px;"><img src="/images/logo-gapp-2025.png" style="height: 30px; width: auto;"></div>';
            }

            // Body rows
            foreach ($rows as $formatedRow) {
                if(is_array($formatedRow)){
                    $formatedRow = "UNSOPPORTED";
                }
                $style = '';

                if(str_contains($formatedRow, '|||')){
                    $action = explode('|||', $formatedRow);
                    $formatedRow = $action[1];
                    $action = $action[0];

                    switch ($action) {
                        case 'RIGHT':
                            $style = 'text-align: right;';
                            break;
                        case 'LEFT':
                            $style = 'text-align: left;';
                            break;
                        case 'CENTER':
                            $style = 'text-align: center;';
                            break;
                        case 'B':
                            $formatedRow = '<strong>' . htmlspecialchars($formatedRow, ENT_QUOTES, 'UTF-8') . '</strong>';
                            $style = 'font-weight: bold; font-size: 1.2em;';
                            break;
                    }
                }
                if($style != ''){
                    $formatedRow = '<div style="' . $style . '">' . $formatedRow . '</div>';
                }
                $html .= $formatedRow;
                
            }

            // QR Code
            if (!empty($options['qr'])) {
                $html .= '<img src="data:image/png;base64,' . base64_encode($options['qr']) . '" style="width: 100%; height: auto;">';
            }

            // Barcode
            if (!empty($options['barcode'])) {
                $html .= '<img src="data:image/png;base64,' . base64_encode($options['barcode']) . '" style="width: 100%; height: auto;">';
            }

            if(isset($options['export_format']) && $options['export_format'] == 'pdf'){
                $html = $this->preparePdf($html);
            }

            return $html . '</container></body></html>';
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function preparePdf($html){
        $html = str_replace('/images/logo-gapp-2025.png', base_path().'/public/images/logo-gapp-2025.jpg', $html);
        $html = str_replace('939393', "ffffff", $html);
        $html = str_replace('<div class="container"', '<div class="container" style="page-break-after: always;"', $html);
        return $html;
    }

    public function formatColumnsRow(array $columns){
        return ['NOT SUPPORTED'];
    }

    public function createTableWithHeader(array $header, array $body, array $width, int $maxPrinterChars=0, array $align = [], string $divider = ' ')
    {
        $html = [];
        $html[] = '<table style="width: 100%; border-collapse: collapse; font-size: 14px;">';
        if($header && is_array($header) && count($header) > 0){
            $html[] = '<tr>';
            $rows = $header[0];
            $align = $header[2] ?? [];

            foreach ($rows as $row) {
                foreach ($row as $key => $col) {
                    $col = htmlspecialchars($col, ENT_QUOTES, 'UTF-8');
                    $style = '';
                    if (isset($align[$key])) {
                        $style .= 'text-align: ' . ($align[$key] ?? 'left') . ';';
                    }
                    $html[] = '<th style="border: 1px solid black; padding: 5px; ' . $style . '">' . $col . '</th>';
                }
            }
            $html[] = '</tr>';
        }
        $html = array_merge($html, $this->createTable($body, $width, $align, $divider, false));
        $html[] = '</table>';

        return $html;

    }
    public function createTable(array $table, array $width, array $align = [], string $divider = ' ', $noHeader=true)
    {
        $html = [];
        if($noHeader){
            $html[] = '<table style="width: 100%; border-collapse: collapse;">';
        }
        $html[] = '<tbody>';
        foreach ($table as $row) {
            $html[] = '<tr>';
            foreach ($row as $key => $col) {
                $col = htmlspecialchars($col, ENT_QUOTES, 'UTF-8');
                $style = '';
                if (isset($align[$key])) {
                    $style .= 'text-align: ' . ($align[$key] ?? 'left') . ';';
                }
                $html[] = '<td style="border: 1px solid black; padding: 5px; ' . $style . '">' . $col . '</td>';
            }
            $html[] = '</tr>';
        }
        $html[] = '</tbody>';
        if($noHeader){
            $html[] = '</table>';
        }

        return $html;
    }
    public function createSeparator(int $width = 0, string $char = ''): string
    {
        return '<br/>';
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

    public function createGeneratedAtRow(int $maxPrinterChars = 0): array
    {
        return [
            "<div style='text-align: center; font-size: 0.9em; color: #666;'>Generado el: " . date('d/m/Y g:ia') . "</div>"
        ];
    }
    public function createSignature(int $maxPrinterChars = 0): array
    {
        return [
            "<div style='text-align: center; font-size: 0.9em; color: #666;'>PedidosGapp ® 2025</div>"
        ];
    }

}
