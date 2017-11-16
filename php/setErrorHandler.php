<?php
/**
 * Created by allen
 * Date: 2017-11-16
 */

function errorHandler() {
    echo 'customer', PHP_EOL;
    //echo $errno, PHP_EOL;
    //echo $errstr, PHP_EOL;
    //echo $errfile, PHP_EOL;
    //echo $errline, PHP_EOL;



    $lastError = error_get_last();

    var_dump($lastError);
}

set_error_handler('errorHandler');


sss;