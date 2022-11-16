<?php
const LEFT = STR_PAD_RIGHT;
const RIGHT = STR_PAD_LEFT;
const NBSP = '-';
$width = 35;
$print = [];
$values = [
    ['Fecha tal'],
    ['Generado tal'],
    [''],
];
$print = printTable($values, [$width], [LEFT]);

$values = [
    ['Promociones temporales', 10, 1250000.0],
    ['Hamburguesas', 10, 500000.0],
    ['Perros', 10, 400000.0],
    ['Combos viernes', 10, 998000.0],
    ['Combos dia', 10, 1998000.0],
    ['Combos sabado y domingo', 10, 998000.0],
];
$max = [0, 0, 0];
for ($i=0; $i < count($values); $i++) { 
    $values[$i][2] = number_format($values[$i][2],0);
    if($max[2]<strlen($values[$i][2])){
        $max[2] = strlen($values[$i][2]);
    }
}
$values = array_merge($values, [
    ['Pedidos Gapp', '']
]);
// $max[2] += 1;
// $max[1] = 4;
// $max[0] = $width-$max[2];
$max = [20, 10, 10];

$print = array_merge($print, printTable($values, $max, [LEFT, RIGHT, RIGHT]));

// var_dump($print);

function getStack($values, $widths, $align){
    $rows = [];
    foreach ($values as $value) {
        $rows[] = printTableRow($value, $widths, $align);
    }
    return tableToStack($rows);    
}
function printTable($values, $widths, $align){
    $rows = [];
    foreach ($values as $value) {
        $rows[] = printTableRow($value, $widths, $align);
    }
    return tableToLines($rows);    
}
function printTableRow($values, $widths, $align){
    $columns = [];
    $lines = 0;
    for ($i=0; $i < count($values); $i++) {
        $column = str_split($values[$i], $widths[$i]);
        if($lines<=count($column)){
            $lines = count($column);
        }
        $columns[] = $column;
    }
    $columns = alignTableRow($columns, $widths, $lines, $align);
    return tableRowToLines($columns);
}
function alignTableRow($columns, $widths, $lines, $align){
    for ($i=0; $i < count($columns); $i++) {
        if(count($columns[$i])<$lines){
            array_unshift($columns[$i], str_repeat(NBSP, $widths[$i]));
            $i--;
        }
        $lastLine = count($columns[$i])-1;
        $columns[$i][$lastLine] = str_pad(
            trim($columns[$i][$lastLine]), $widths[$i], NBSP, $align[$i]);
    }
    return $columns;
}
function tableRowToLines($columns){
    $lines = [];
    $columnLines = count($columns[0]);
    for ($j=0; $j < $columnLines; $j++) { 
        $line = '';
        for ($i=0; $i < count($columns); $i++){
            $line .= $columns[$i][$j];
        }
        $lines[] = $line;
    }
    return $lines;
}

function tableToLines($table){
    $lines = [];
    foreach ($table as $row){
        foreach ($row as $line){
            $lines[] = $line;
        }
    }
    return $lines;
}
function tableToStack($table){
    $stack = [];
    foreach ($table as $row){
        foreach ($row as $line){
            $stack[] = ["i"=>"texto","v"=>$line];
        }
    }
    return $stack;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body style="font-family: monospace">
    <?php foreach($print as $line){?>
        <?= $line ?><br>
    <?php } ?>
</body>
</html>