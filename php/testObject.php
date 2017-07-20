<?php

$startTime = microtime(true);
for ($i = 0; $i <= 10000000; $i++) {
    $a = (object)null;
}
$endTime = microtime(true);
echo '结束时间 (object)null: ', ($endTime - $startTime), PHP_EOL;


$startTime1 = microtime(true);
for ($j = 0; $j <= 10000000; $j++) {
    $a1 = new stdClass;
}
$endTime1 = microtime(true);
echo '结束时间 new stdClass: ', ($endTime1 - $startTime1), PHP_EOL;