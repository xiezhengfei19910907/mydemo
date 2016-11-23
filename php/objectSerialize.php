<?php

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

$t = new Test('1', '2');

file_put_contents('./test.log', serialize($t));