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
