<?php

$_SERVER['HTTP_X_REAL_IP'] = '10.9.8.253';
$_SERVER['HTTP_X_FORWARDED_FOR'] = '13,10.9.8.255,10.9.8.1233';

/**
 * @return string
 */
function getUserRealIp()
{
    $realIp = isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '';
    if(!filter_var($realIp, FILTER_VALIDATE_IP)) {
        $realIp = '';
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $forwardedFor = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($forwardedFor as $forward) {
                if (filter_var($forward, FILTER_VALIDATE_IP)) {
                    $realIp = $forward;
                    break;
                }
            }
        }
    }

    $remoteAddr = isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

    return !empty($realIp) ? $realIp : $remoteAddr;
}

echo getUserRealIp();

exit();

//$a = 3;
//echo $a;
//function & aa($a)
//{
//    $a = $a * 3;
//    return $a;
//}
//
//aa($a);
//echo $a;
//exit();


class A {
//    public $b;
}

$a = new A();
//$a->b = 3;

$a2 = new A();
//$a2->b = 4;

var_dump($a, $a2);exit();




//$a = '1';

$a = 0.99999999999999999;
var_dump($a);

$b = intval($a);

var_dump($b);

if (is_numeric($a)) {
    echo 'ok', PHP_EOL;
} else {
    echo 'fail', PHP_EOL;
}


if ($a !== $b) {
    echo '!==', PHP_EOL;
}






exit;



if (is_int($a)) {
    echo 'a';
} else {
    echo 'b';
}

exit;



$str = 'a:2:{s:4:"user";b:1;s:4:"pass";b:1;}';
$data = unserialize($str);
if ($data['user'] === 'root' && $data['pass'] === 'myPass') {
    echo '登录成功';
} else {
    echo '登录失败';
}