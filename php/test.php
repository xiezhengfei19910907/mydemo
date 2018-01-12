<?php

function xrange($start, $limit, $step = 1) {
    if ($start < $limit) {
        if ($step <= 0) {
            throw new LogicException('Step must be +ve');
        }

        for ($i = $start; $i <= $limit; $i += $step) {
            yield $i;
        }
    } else {
        if ($step >= 0) {
            throw new LogicException('Step must be -ve');
        }

        for ($i = $start; $i >= $limit; $i += $step) {
            yield $i;
        }
    }
}

/*
 * 注意下面range()和xrange()输出的结果是一样的。
 */

$a = microtime(true);
echo memory_get_usage(), PHP_EOL;
echo 'Single digit odd numbers from range():  ';
foreach (range(1, 1000000) as $number) {
    // echo "$number ";
}
echo "\n";
echo memory_get_usage(), PHP_EOL;
$b = microtime(true);
echo $b - $a, PHP_EOL;


$c = microtime(true);
echo memory_get_usage(), PHP_EOL;
echo 'Single digit odd numbers from xrange(): ';
foreach (xrange(1, 1000000) as $number) {
    // echo "$number ";
}
echo "\n";
echo memory_get_usage(), PHP_EOL;
$d = microtime(true);
echo $d - $c, PHP_EOL;