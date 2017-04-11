<?php
require_once '../vendor/autoload.php';

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;

$mailbox = new ImapMailbox('{imap.exmail.qq.com:993/imap/ssl}INBOX', 'notice@jjshouse.no', 'JJShouse!789', __DIR__);

$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
    die('Mailbox is empty');
}

$mail = array();
foreach ($mailsIds as $mailId) {
    $mail[] = $mailbox->getMail(714);
}

echo 'get email end', PHP_EOL;