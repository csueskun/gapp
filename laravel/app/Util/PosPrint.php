<?php

namespace App\Util;
class PosPrint{

    const NBSP = ' ';
    
    public static function getStackFromTable($values, $widths, $align){
        $rows = [];
        foreach ($values as $value) {
            $rows[] = self::printTableRow($value, $widths, $align);
        }
        return self::tableToStack($rows);    
    }
    public static function printTable($values, $widths, $align){
        $rows = [];
        foreach ($values as $value) {
            $rows[] = self::printTableRow($value, $widths, $align);
        }
        return self::tableToLines($rows);    
    }
    static function printTableRow($values, $widths, $align){
        $columns = [];
        $lines = 0;
        for ($i=0; $i < count($values); $i++) {
            $column = str_split($values[$i], $widths[$i]);
            if($lines<=count($column)){
                $lines = count($column);
            }
            $columns[] = $column;
        }
        $columns = self::alignTableRow($columns, $widths, $lines, $align);
        return self::tableRowToLines($columns);
    }
    static function alignTableRow($columns, $widths, $lines, $align){
        for ($i=0; $i < count($columns); $i++) {
            if(count($columns[$i])<$lines){
                array_unshift($columns[$i], str_repeat(self::NBSP, $widths[$i]));
                $i--;
            }
            $lastLine = count($columns[$i])-1;
            $columns[$i][$lastLine] = str_pad(
                trim($columns[$i][$lastLine]), $widths[$i], self::NBSP, $align[$i]);
        }
        return $columns;
    }
    static function tableRowToLines($columns){
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

    static function tableToLines($table){
        $lines = [];
        foreach ($table as $row){
            foreach ($row as $line){
                $lines[] = $line;
            }
        }
        return $lines;
    }

    static function tableToStack($table){
        $stack = [];
        foreach ($table as $row){
            foreach ($row as $line){
                $stack[] = ["i"=>"texto","v"=>$line];
            }
        }
        return $stack;
    }
}