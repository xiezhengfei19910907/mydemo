<?php

echo '今天是:' . date('Y-m-d'), PHP_EOL;
echo '-100天是:' . date("Y-m-d", strtotime("- 100 day")), PHP_EOL;


/**
 * 测试 字符串 ' 和 " 的区别
 * 结论: 最好使用 ' 拼接 变量的方式, 速度最快
 */
$startTime = microtime(true);
for ($i = 0; $i <= 10000000; $i++) {
    $str1 = 'test';
}
$endTime = microtime(true);

echo '\'没有变量最后时间: ', ($endTime - $startTime), PHP_EOL;

$startTime2 = microtime(true);
for ($i = 0; $i <= 10000000; $i++) {
    $str3 = 'test' . $str1;
}
$endTime2 = microtime(true);

echo '\'拼接变量最后时间: ', ($endTime2 - $startTime2), PHP_EOL;

$startTime1 = microtime(true);
for ($i = 0; $i <= 10000000; $i++) {
    $str2 = "test $str1";
}
$endTime1 = microtime(true);

echo '"有变量最后时间: ', ($endTime1 - $startTime1), PHP_EOL;

$startTime3 = microtime(true);
for ($i = 0; $i <= 10000000; $i++) {
    $str3 = sprintf('test %s', $str1);
}
$endTime3 = microtime(true);

echo '\'sprintf最后时间: ', ($endTime3 - $startTime3), PHP_EOL;