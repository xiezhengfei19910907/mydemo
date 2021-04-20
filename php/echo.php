<?php

/**
 * @return string
 */
function getUserRealIp()
{
    $realIp = isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '';
    if(!filter_var($realIp, FILTER_VALIDATE_IP)) {
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

    return !empty($realIp) ? $realIp : $_SERVER['REMOTE_ADDR'];
}




exit;


$gs = 999217;
$userid = 211814;

$check = 'MTY5Njg3OTA5MzgwMDA=';

$miyao='yunda_wdkjtj';
          if(!preg_match("/^\d{10,}$/",base64_decode($check))){
          	 die('参数格式错误');
          }
          $expir=base64_decode($check)/110;
          $miyao=$gs.$userid.$miyao.$check;
          $miyao=md5(md5(md5($miyao)));       
          if($expir<time()){
          	 die('访问超时，请关闭系统重新进入');
          }   

echo $miyao;exit;








$a = 'aaaaa';


$b = "b='{$a}'";
echo $b, PHP_EOL;


exit;

echo 1, PHP_EOL;


