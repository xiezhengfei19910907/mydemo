<?php

$requestXML = <<<XML
<XML>
    <REQUEST>
        <ACTION>INSERT_ORDERWITHPAYMENT</ACTION>
        <META>
            <MERCHANTID>7442</MERCHANTID>
            <IPADDRESS>50.19.255.229</IPADDRESS>
            <VERSION>2.0</VERSION>
        </META>
        <PARAMS>
            <ORDER>
                <ORDERID>1932394929</ORDERID>
                <MERCHANTREFERENCE>_MJE1932394929_170331021206</MERCHANTREFERENCE>
                <AMOUNT>16397</AMOUNT>
                <CURRENCYCODE>USD</CURRENCYCODE>
                <LANGUAGECODE>en</LANGUAGECODE>
                <COUNTRYCODE>US</COUNTRYCODE>
                <SURNAME>4324</SURNAME>
                <CITY>3432</CITY>
                <FIRSTNAME>32432</FIRSTNAME>
                <STREET>34324</STREET>
                <ADDITIONALADDRESSINFO></ADDITIONALADDRESSINFO>
                <ZIP>432432432</ZIP>
                <STATE>American Samoa</STATE>
            </ORDER>
            <PAYMENT>
                <PAYMENTPRODUCTID>1501</PAYMENTPRODUCTID>
                <AMOUNT>16397</AMOUNT>
                <CURRENCYCODE>USD</CURRENCYCODE>
                <COUNTRYCODE>US</COUNTRYCODE>
                <LANGUAGECODE>en</LANGUAGECODE>
                <HOSTEDINDICATOR>0</HOSTEDINDICATOR>
            </PAYMENT>
        </PARAMS>
    </REQUEST>
</XML>
XML;

$params = array(
    "merchantid"        => 7442,
    "user_ip"           => "54.65.130.253",
    "merchantreference" => "_MJE2526437212_170331022037",
    "socks_proxy"       => array(),
    "url"               => 'https://ps.gcsip.com/wdl/wdl',
    "currency_code"     => "USD",
    "lang_code"         => "en",
    "city"              => "3432",
    "province"          => "American Samoa",
    "country_code"      => "US",
    "last_name"         => "4324",
    "first_name"        => "32432",
    "email"             => "zf@tetx.com",
    "user_id"           => "646046",
    "address"           => "34324",
    "zipcode"           => "432432432",
    "address_add"       => '',
    "order_id"          => "2526437212",
    "amount"            => "15598",
);

$timeout = 70;

$ch = curl_init($params['url']);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout * 2);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

if (isset($params['http_proxy']) && $params['http_proxy']) {
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);
    curl_setopt($ch, CURLOPT_PROXY, $params['http_proxy']['host'] . ($params['http_proxy']['port'] ? ':' . $params['http_proxy']['port'] : ''));
    if ($params['http_proxy']['user'] && $params['http_proxy']['passwd']) {
        curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$params['http_proxy']['user']}:{$params['http_proxy']['passwd']}");
    }
} elseif (isset($params['socks_proxy']) && $params['socks_proxy']) {
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($ch, CURLOPT_PROXY, $params['socks_proxy']['host'] . ($params['socks_proxy']['port'] ? ':' . $params['socks_proxy']['port'] : ''));
    if ($params['socks_proxy']['user'] && $params['socks_proxy']['passwd']) {
        curl_setopt($ch, CURLOPT_USERPWD, "{$params['socks_proxy']['user']}:{$params['socks_proxy']['passwd']}");
    }
}

$content = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
