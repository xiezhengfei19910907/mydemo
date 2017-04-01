<?php

class InitEmailBox {
    private static $emailBox = null;

    private function __construct() {

    }

    private function __clone() {
        // TODO: Implement __clone() method.
    }

    public static function getEmailBox($config, $email, $password) {
        if (!is_null(self::$emailBox) && imap_ping(self::$emailBox)) {
            return self::$emailBox;
        }

        $mbox = imap_open($config, $email, $password);
        if (empty($mbox) || !imap_ping($mbox)) {
            return false;
        }

        self::$emailBox = $mbox;
    }
}


$emailBox = InitEmailBox::getEmailBox("{hwimap.exmail.qq.com:993/imap/ssl/novalidate-cert}INBOX", "service@jjshouse.co.uk", "jjsHOUSE!789") or die('连接失败');
$emailCount = imap_num_msg($emailBox);
for ($i = $emailCount; $i > 0; $i--) {
    $emailHeaderInfo = imap_headerinfo($emailBox, $i);

    foreach ($emailHeaderInfo as &$headerInfo) {
        $headerInfo = imap_mime_header_decode($headerInfo);
    }
}