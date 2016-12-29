<?php

/**
 * 测试 执行exit后，后续的 析构方法是否还会执行
 */
register_shutdown_function('process');
function process () {
    $info = error_get_last();
    var_dump($info);
}

class Test {

    public function __construct() {
        echo '构造方法', PHP_EOL;
    }

    public function process() {
        echo "process", PHP_EOL;

//        exit();
    }

    public function __destruct() {
//        exit();
        echo "析够方法", PHP_EOL;
    }

}


$test = new Test();
$test->process();

exit();

