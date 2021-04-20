<?php

$a = '2:30';
$b = "12:54";

echo $c = strtotime($a) -strtotime($b), PHP_EOL;


echo ($c / 60) , PHP_EOL;



$a = '2:30';
$b = "14:13";

echo $c = strtotime($a) -strtotime($b), PHP_EOL;


echo ($c / 60) , PHP_EOL;



$a = '2:30';
$b = "15:48";

echo $c = strtotime($a) -strtotime($b), PHP_EOL;


echo ($c / 60) , PHP_EOL;

//
//
//$arr = [
//    [
//        1,2,3
//    ],
//    [
//        4,5,6
//    ],
//    [
//        7,8,9
//    ],
//];
//
//$a = "标题 1, 标题 2, 标题 3\n";
//
//foreach ($arr as $v) {
//    foreach ($v as $i) {
//        $a .= "$i,";
//    }
//    $a = substr($a, 0, -1);
//    $a .= "\n";
//}

////header('Content-Type: application/vnd.ms-excel');
//@header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
//@header("Content-Disposition:attachment;filename=". 1 . ".csv");
//@header("Cache-Control:Max-Age=0");
//
//echo $a;


