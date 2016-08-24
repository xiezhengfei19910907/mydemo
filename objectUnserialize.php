<?php

register_shutdown_function("faltalErrorProcess");
function faltalErrorProcess() {


    $errorLastInfo = error_get_last();

    file_put_contents('./t.log', print_r($errorLastInfo, true));
}

class Test {
    public $a;
    public $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function say() {
        echo $this->a, " ", $this->b, PHP_EOL;
    }
}

$test = file_get_contents('./test.log');

$t = unserialize($test);

var_dump($t);

$t->say();


