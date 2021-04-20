<?php
require_once 'vendor/autoload.php';

// 该函数会扫描指定目录地址下的文件, 并将其中标准化的注释渲染为json数据
$openapi = \OpenApi\scan(['doc/dir']);
header('Content-Type: application/json');
echo $openapi->toJson();

