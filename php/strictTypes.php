<?php
declare(strict_types=1);
namespace demo;


var_dump(test(1, 2));

$x = 3;

$x = 'xxx';

var_dump($x);




function test(int $x, int $y) : int {

//     $x = (string) $x;
    return $x + $y;
}