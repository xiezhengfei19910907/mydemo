<?php

$merchantID = 7441;
$orderSN = 7625570632;

$requestXML = <<<XML
<XML>
    <REQUEST>
        <ACTION>GET_ORDERSTATUS</ACTION>
        <META>
            <MERCHANTID>{$merchantID}</MERCHANTID>
            <IPADDRESS>50.19.255.229</IPADDRESS>
            <VERSION>2.0</VERSION>
        </META>
        <PARAMS>
            <ORDER>
                <ORDERID>{$orderSN}</ORDERID>
            </ORDER>
        </PARAMS>
    </REQUEST>
</XML>
XML;

$timeout = 70;
$serverUrl = 'https://ps.gcsip.com/wdl/wdl';

$ch = curl_init($serverUrl);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout * 2);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$content = curl_exec($ch);
curl_close($ch);

var_export($content);