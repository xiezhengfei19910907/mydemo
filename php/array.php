<?php

$arr = [
    1=> ['status' => 1],
    '0'=>['status' => 3],
];

foreach ($arr as $key => $value) {
    var_dump($key). PHP_EOL;
    if ($value['status'] == 1) {
        //unset($arr[$key]);
    }
}


echo json_encode($arr), PHP_EOL;



$c = [[1,2,3], [1,2,3], ['status' => 1]];
echo json_encode($c);