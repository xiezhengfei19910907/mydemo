<?php
namespace lestore\util\paymentgateway;

use esmeralda\base\Util;
use esmeralda\payment\PaymentConstants;
use esmeralda\user\order\OrderStatus;
use esmeralda\user\UserUtil;
use lestore\util\Http;

class Trustly extends Payment {

    public static $paymentCode = 'trustly';

    private $email;
    private $lastName;
    private $firstName;
    private $mobilePhone;
    private $amount;
    private $currency;
    private $returnURL;
    private $trustlyConfig;
    private $country;
    private $orderSN;
    private $address;
    private $city;
    private $zipcode;
    private $state;
    private $paymentName;
    private $userID;
    private $clientIP;
    private $merchantTransactionID;

    public function __construct() {
        parent::$paymentCode = self::$paymentCode;
        parent::__construct();

        $this->trustlyConfig = Util::conf('trustly');
    }

    /**
     * Please reference https://docs.smart2pay.com/category/payments-api/create-a-payment/
     * @param object $order
     * @return string $redirectUrl
     */
    public function gen_code($order) {
        global $container;
        $this->outputParams($order);

        $apiParameters = array();
        $apiParameters['api_key'] = $this->trustlyConfig['apiKey'];
        $apiParameters['site_id'] = $this->trustlyConfig['siteID'];
        $apiParameters['environment'] = $this->trustlyConfig['environment'];

        $apiParameters['method'] = 'payments';
        $apiParameters['func'] = 'payment_init';

        $apiParameters['get_variables'] = array();
        $apiParameters['method_params'] = array(
            'payment' => array(
                'merchanttransactionid' => $this->merchantTransactionID,
                'amount'                => $this->amount,
                'currency'              => $this->currency,
                'returnurl'             => $this->returnURL,
                'methodid'              => $this->trustlyConfig['methodID'],
                'siteid'                => $this->trustlyConfig['siteID'],
                'description'           => $this->paymentName,
                'clientip'              => $this->clientIP,
                'customer'              => array(
                    'merchantcustomerid' => $this->userID,
                    'email'              => $this->email,
                    'firstname'          => $this->firstName,
                    'lastname'           => $this->lastName,
                    'phone'              => $this->mobilePhone,
                ),
                'billingaddress'        => array(
                    'country' => $this->country,
                    'city'    => $this->city,
                    'zipcode' => $this->zipcode,
                    'state'   => $this->state,
                    'street'  => $this->address,
                ),
                'shippingaddress'       => array(
                    'country' => $this->country,
                    'city'    => $this->city,
                    'zipcode' => $this->zipcode,
                    'state'   => $this->state,
                    'street'  => $this->address,
                ),
                'tokenlifetime'         => 15,
            ),
        );

        $callParams = array();
        $callParams['curl_params'] = array();

        $finalizeParams = array();
        $finalizeParams['redirect_now'] = false;

        if (!($responseData = \S2P_SDK\S2P_SDK_Module::quick_call($apiParameters, $callParams, $finalizeParams))) {
            $errorInfo = \S2P_SDK\S2P_SDK_Module::st_get_error();
            $container['alert']->sev2('Trustly', 'payment init error:', array('errorInfo' => $errorInfo));
        }

        $redirectUrl = '';
        if (!empty($responseData['finalize_result']['should_redirect']) && !empty($responseData['finalize_result']['redirect_to'])) {
            $redirectUrl = $responseData['finalize_result']['redirect_to'];
        }

        // record transaction log
        $this->recordSmart2payID($responseData['call_result']['payment']['id']);
        if (!empty($this->log_handle)) {
            $this->log_handle->add(__FILE__, print_r($responseData, true), 'OK', $this->orderSN);
        }

        return $redirectUrl;
    }

    private function recordSmart2payID($smart2payID) {
        global $container;

        $order = $container['order']->getOrderBySn($this->orderSN);

        $order_extension = array(
            'ext_name'  => 'smart2payID',
            'ext_value' => $smart2payID,
        );

        $container['user.order']->insertOrderExts($order['order_id'], array($order_extension));
    }

    private function outputParams($order) {
        global $container, $WEB_ROOT;

        $this->orderSN = $order->order_sn;
        $this->merchantTransactionID = $this->orderSN . '_' . time();

        if (Http::isIE8()) {
            $host = Http::httpHost();
        } else {
            $host = Http::httpHost('s');
        }
        $this->returnURL = $host . $WEB_ROOT . 'checkout_success.php?action=orderconfirm&order_sn=' . $this->orderSN;

        $currencyInfo = $container['currency']->getCurrency($order->order_currency_id);
        $this->currency = $currencyInfo->name;
        $this->amount = $order->order_amount_exchange * 100;
        $this->paymentName = $order->payment_name;

        $this->mobilePhone = $order->tel;

        list ($this->firstName, $this->lastName) = explode(' ', $order->consignee, 2);

        $this->country = $order->country_code;
        $this->email = $order->email;
        $this->userID = $order->user_id;

        $this->clientIP = UserUtil::realIP2();

        $this->zipcode = $order->zipcode;
        $this->city = !empty($order->city_name) ? $order->city_name : $order->city_text;
        $this->address = $order->address;
        $this->state = !empty($order->province_name) ? $order->province_name : $order->province_text;
    }

    /**
     * Please reference https://docs.smart2pay.com/category/payments-api/payment-notification/
     */
    public function respond() {
        global $container;

        // check request method
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return array('res' => '', 'code' => 'REQUEST_METHOD_ERROR');
        }

        // check authentication
        if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ||
            ($_SERVER['PHP_AUTH_USER'] != $this->trustlyConfig['siteID']) || ($_SERVER['PHP_AUTH_PW'] != $this->trustlyConfig['apiKey'])) {
            return array('res' => '', 'code' => 'AUTHENTICATION_ERROR');
        }

        $notificationJSON = file_get_contents('php://input');
        if (empty($notificationJSON)) {
            return array('res' => '', 'code' => 'EMPTY_NOTIFICATION_DATA');
        }

        $notificationArray = json_decode($notificationJSON, true);
        $method = key($notificationArray);
        $methodData = current($notificationArray);

        // Please reference https://docs.smart2pay.com/category/payments-api/redirection-status-vs-notification-status/
        $paymentStatus = $methodData['Status']['ID'];
        list($orderSN, $timestamp) = explode('_', $methodData['MerchantTransactionID']);

        $orderInfo = $container['order']->getOrderBySn($orderSN);
        if (empty($orderInfo)) {
            return array('res' => '', 'code' => 'NON_ORDER');
        }

        if ($orderInfo['pay_status'] == PaymentConstants::STATUS_PAID) {
            return array('res' => '', 'code' => 'HAVE_PAID');
        }

        // check amount and currency
        if ($methodData['Amount'] < ($orderInfo['order_amount_exchange'] * 100)) {
            $txnResult = 'AMOUNT_UNEQUAL';
            $this->recordNotificationLog($orderSN, $methodData['ID'], $notificationJSON, $txnResult);

            return array('res' => '', 'code' => $txnResult);
        }
        $currency = $container['currency']->getCurrency($orderInfo['order_currency_id']);
        if (!empty($currency->name) && $currency->name != $methodData['Currency']) {
            $txnResult = 'CURRENCY_ERROR';
            $this->recordNotificationLog($orderSN, $methodData['ID'], $notificationJSON, $txnResult);

            return array('res' => '', 'code' => $txnResult);
        }

        if (($paymentStatus == \S2P_SDK\S2P_SDK_Meth_Payments::STATUS_SUCCESS) &&
            ($orderInfo['order_status'] == OrderStatus::STATUS_CANCELED)) {
            $container['order']->activateOrder($orderSN);
        }

        if (($method == 'Payment') && ($paymentStatus == \S2P_SDK\S2P_SDK_Meth_Payments::STATUS_SUCCESS)) {
            $container['order']->updatePayStatus($orderSN, PaymentConstants::STATUS_PAID);

            $txnResult = 'PAID_SUCCESS';
            $this->recordNotificationLog($orderSN, $methodData['ID'], $notificationJSON, $txnResult, 'OK');

            return array('res' => '', 'code' => $txnResult);
        }
    }

    private function recordNotificationLog($orderSN, $smart2payID, $txnPost, $txnResult, $txnStatus = 'NOK') {
        $txnData = array(
            'txn_id'     => $smart2payID,
            'order_sn'   => $orderSN,
            'txn_post'   => $txnPost,
            'txn_status' => $txnStatus,
            'site_code'  => $txnResult,
            'txn_result' => $txnResult,
        );

        $this->log($txnData);
    }

    public function getPaymentDetail($paymentID) {
        global $container;

        $apiParameters = array();
        $apiParameters['api_key'] = $this->trustlyConfig['apiKey'];
        $apiParameters['site_id'] = $this->trustlyConfig['siteID'];
        $apiParameters['environment'] = $this->trustlyConfig['environment'];

        $apiParameters['method'] = 'payments';
        $apiParameters['func'] = 'payment_details';

        $apiParameters['get_variables'] = array(
            'id' => $paymentID,
        );
        $apiParameters['method_params'] = array();

        $callParams = array();
        $callParams['curl_params'] = array();

        $finalizeParams = array();
        $finalizeParams['redirect_now'] = false;

        if (!($responseData = \S2P_SDK\S2P_SDK_Module::quick_call($apiParameters, $callParams, $finalizeParams))) {
            $errorInfo = \S2P_SDK\S2P_SDK_Module::st_get_error();
            $container['alert']->sev2('Trustly', 'payment details error:', array('errorInfo' => $errorInfo));
        }

        return $responseData;
    }

    public function processPaymentResult($orderSN) {
        global $container;

        $orderExtension = $container['order']->getOrderExts($orderSN);
        $smart2payID = !empty($orderExtension['smart2payID']) ? $orderExtension['smart2payID'] : '';
        if (empty($smart2payID)) {
            return false;
        }

        $responseData = $this->getPaymentDetail($smart2payID);
        if (empty($responseData)) {
            return false;
        }

        $method = key($responseData['call_result']);
        $methodData = current($responseData['call_result']);

        // Please reference src/php/includes/smart2pay/methods/s2p_sdk_meth_payments.inc.php for status
        $paymentStatus = $methodData['status']['id'];
        list($orderSN, $timestamp) = explode('_', $methodData['merchanttransactionid']);

        $orderInfo = $container['order']->getOrderBySn($orderSN);
        if (empty($orderInfo)) {
            return false;
        }

        if (($method != 'payment') || !in_array($paymentStatus, array(\S2P_SDK\S2P_SDK_Meth_Payments::STATUS_SUCCESS, \S2P_SDK\S2P_SDK_Meth_Payments::STATUS_PENDING_PROVIDER))) {
            $this->recordNotificationLog($orderSN, $methodData['id'], print_r($responseData, true), 'PAID_FAILED');

            return false;
        }
        $this->recordNotificationLog($orderSN, $methodData['id'], print_r($responseData, true), 'PAID_SUCCESS', 'OK');

        $currency = $container['currency']->getCurrency($orderInfo['order_currency_id']);
        if (!empty($currency->name) && $currency->name != $methodData['currency']) {
            return false;
        }

        if ($orderInfo['order_status'] == OrderStatus::STATUS_CANCELED) {
            $container['order']->activateOrder($orderSN);
        }

        if (($methodData['amount'] < ($orderInfo['order_amount_exchange'] * 100)) || ($paymentStatus == \S2P_SDK\S2P_SDK_Meth_Payments::STATUS_PENDING_PROVIDER)) {
            $container['order']->updatePayStatus($orderSN, PaymentConstants::STATUS_PAYING);
        } else {
            $container['order']->updatePayStatus($orderSN, PaymentConstants::STATUS_PAID);
        }

        return true;
    }
}