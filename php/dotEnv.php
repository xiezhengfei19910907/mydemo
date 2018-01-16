<?php
/**
 * Created by allen
 * Date: 2018-01-16
 */

$vendorDir = dirname(dirname(__FILE__)) . '/vendor';

require_once $vendorDir . '/autoload.php';

$baseDir = dirname($vendorDir);

// 加载.env文件的配置
$dotEnv = new \Dotenv\Dotenv($baseDir);

// 加载.env之外的其它文件, 第二个参数是文件名
//$dotEnv = new \Dotenv\Dotenv($baseDir, '.env.example');
$dotEnv->load();


$dotEnv->required('MYSQL_PORT')->notEmpty();

$dotEnv->required('MYSQL_PORT')->isInteger();

$dotEnv->required([
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_PORT',
]);


$mysqlUser = getenv('MYSQL_USER');
$mysqlPassword = getenv('MYSQL_PASSWORD');
$mysqlPort = getenv('MYSQL_PORT');

var_dump($mysqlUser, $mysqlPassword, $mysqlPort);


//var_dump($dotEnv);