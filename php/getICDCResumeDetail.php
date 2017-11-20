<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

$worker_name = 'icdc_online';
$host = '192.168.8.70:4730';
$client_tmp = new GearmanClient();
$client_tmp->addServers($host);

$id = !empty($argv[1]) && is_numeric($argv[1]) ? $argv[1] : 0;
if (empty($id)) {
    echo 'id 不能为空', PHP_EOL;
    exit();
}

$output = 'basic,contact';

$param = array(
    'c' => 'resumes/logic_resume',
    'm' => 'get_multi_all',
    'p' => array(
        'ids'      => is_array($id) ? $id : array($id),
        'selected' => $output,
    ),
);

$request = array(
    'header'  => array(
        'uid'      => '1',
        'uname'    => 'zyh',
        'version'  => '1',
        'signid'   => '2132',
        'provider' => 'haifeng',
        'ip'       => '192.168.8.23',
        'appid'       => '5',
        'log_id' => gen_sign_id(),
    ),
    'request' => $param,
);
$request = json_encode($request);
$result = $client_tmp->doNormal($worker_name, $request);

var_dump($client_tmp->returnCode());
var_dump($client_tmp->getErrno());
var_dump($client_tmp);

if (GEARMAN_SUCCESS == $client_tmp->returnCode()) {
    $result = json_decode($result);
    print_r($result);
}




function gen_sign_id(){
    $arr = gettimeofday();
    return sprintf('%u',((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) | 0x80000000));
}
