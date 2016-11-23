<?php

/**
 * 在paypal回调url中验证订单
 */

$requestJSON = '{
    "address_city": "Whiteville",
    "address_country": "United States",
    "address_country_code": "US",
    "address_name": "Smith, Hannah",
    "address_state": "Tennessee",
    "address_status": "confirmed",
    "address_street": "5296 eurekaton Rd",
    "address_zip": "38075",
    "business": "accounting@readmob.com",
    "charset": "Big5",
    "contact_phone": "731-518-7482",
    "custom": "",
    "first_name": "Hannah",
    "invoice": "6458129287",
    "ipn_track_id": "8ac4b7c39864c",
    "item_name": "6458129287",
    "item_number": "",
    "last_name": "Lewis",
    "mc_currency": "USD",
    "mc_fee": "4.37",
    "mc_gross": "140.48",
    "notify_version": "3.8",
    "payer_email": "hannahlew92@hotmail.com",
    "payer_id": "HKKURU7YTT3BS",
    "payer_status": "unverified",
    "payment_date": "07:59:38 Aug 08, 2016 PDT",
    "payment_fee": "4.37",
    "payment_gross": "140.48",
    "payment_status": "Completed",
    "payment_type": "instant",
    "protection_eligibility": "Eligible",
    "quantity": "1",
    "receiver_email": "accounting@readmob.com",
    "receiver_id": "4E6HVDAXMGWV8",
    "residence_country": "US",
    "shipping": "19.99",
    "transaction_subject": "",
    "txn_id": "0F2976824H6123511",
    "txn_type": "web_accept",
    "verify_sign": "AzpDr76UYbFV6.q9Y8haEP5lm4s1Ai9YkZZi83FKAl2uCvaRD04GekpY"
}';
$request = json_decode($requestJSON, true);

$req = 'cmd=_notify-validate';
foreach ($request as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}

$timeout = 70;
$url = "https://www.paypal.com/cgi-bin/webscr";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout * 2);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
$res = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

var_dump($res, $status);