<?php

$link = imap_open("{hwimap.exmail.qq.com:993/imap/ssl/novalidate-cert}INBOX", "service@jjshouse.co.uk", "jjsHOUSE!789");

var_dump($link);
var_dump('test');