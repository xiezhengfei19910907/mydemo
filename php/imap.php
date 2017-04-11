<?php

class MailFetcher {
    public $hostname;
    public $username;
    public $password;

    public $port;
    public $encryption;

    public $mbox;

    public $charset = 'UTF-8';

    public function __construct($username, $password, $hostname, $port, $encryption = '') {

        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->encryption = $encryption;

        $this->serverstr = sprintf('{%s:%d', $this->hostname, $this->port);
        if (!strcasecmp($this->encryption, 'SSL')) {
            $this->serverstr .= '/ssl}';
        }

        //Set timeouts
        if (function_exists('imap_timeout')) {
            imap_timeout(1, 20);
        } //Open timeout.
    }

    public function open() {

        if ($this->mbox && imap_ping($this->mbox)) {
            return $this->mbox;
        }

        $this->mbox = @imap_open($this->serverstr, $this->username, $this->password);

        return $this->mbox;
    }

    public function close() {
        imap_close($this->mbox, CL_EXPUNGE);
    }

    public function mailcount() {
        return count(imap_headers($this->mbox));
    }


    public function decode($encoding, $text) {

        switch ($encoding) {
            case 1:
                $text = imap_8bit($text);
                break;
            case 2:
                $text = imap_binary($text);
                break;
            case 3:
                $text = imap_base64($text);
                break;
            case 4:
                $text = imap_qprint($text);
                break;
            case 5:
            default:
                $text = $text;
        }

        return $text;
    }

    //Convert text to desired encoding..defaults to utf8
    public function mime_encode($text, $charset = null, $enc = 'utf-8') { //Thank in part to afterburner

        $encodings = array('UTF-8', 'WINDOWS-1251', 'ISO-8859-5', 'ISO-8859-1', 'KOI8-R');
        if (function_exists("iconv") and $text) {
            if ($charset) {
                return iconv($charset, $enc . '//IGNORE', $text);
            } elseif (function_exists("mb_detect_encoding")) {
                return iconv(mb_detect_encoding($text, $encodings), $enc, $text);
            }
        }

        return utf8_encode($text);
    }

    //Generic decoder - mirrors imap_utf8
    public function mime_decode($text) {

        $a = imap_mime_header_decode($text);
        $str = '';
        foreach ($a as $k => $part) {
            //对编码不是UTF-8的进行强制转换
            if (strtolower($part->charset) != 'utf-8') {
                $str .= iconv($part->charset, 'utf-8//IGNORE', $part->text);
            } else {
                $str .= $part->text;
            }
        }

        return $str ? $str : iconv_mime_decode($text, 0, 'UTF-8');  //imap_utf8($text);//imap_utf8()不能转换GBK编码的汉字
    }

    public function getLastError() {
        return imap_last_error();
    }

    public function getMimeType($struct) {
        $mimeType = array('TEXT', 'MULTIPART', 'MESSAGE', 'APPLICATION', 'AUDIO', 'IMAGE', 'VIDEO', 'OTHER');
        if (!$struct || !$struct->subtype) {
            return 'TEXT/PLAIN';
        }

        return $mimeType[(int) $struct->type] . '/' . $struct->subtype;
    }

    public function getHeaderInfo($mid) {

        $headerinfo = imap_headerinfo($this->mbox, $mid);
        $sender = $headerinfo->from[0];

        $to = array();
        if (!empty($headerinfo->to)) {
            foreach ($headerinfo->to as $t) {
                $to[] = array('name' => @$t->personal, 'email' => strtolower($t->mailbox) . '@' . $t->host);
            }
        }
        $cc = array();
        if (!empty($headerinfo->cc)) {
            foreach ($headerinfo->cc as $c) {
                $cc[] = array('name' => @$c->personal, 'email' => strtolower($c->mailbox) . '@' . $c->host);
            }
        }

        if (empty($to) && empty($cc)) {
            $to[] = array('email' => $this->username);
        }

        //Parse what we need...
        $header = array(
            'from'    => array('name' => @$sender->personal, 'email' => strtolower($sender->mailbox) . '@' . $sender->host),
            'subject' => @$headerinfo->subject,
            'mid'     => $headerinfo->message_id,
            'to'      => $to,
            'cc'      => $cc,
        );

        return $header;
    }

    //search for specific mime type parts....encoding is the desired encoding.
    public function getPart($mid, $mimeType, $encoding = false, $struct = null, $partNumber = false) {

        if (!$struct && $mid) {
            $struct = @imap_fetchstructure($this->mbox, $mid);
        }
        //Match the mime type.
        if ($struct && !$struct->ifdparameters && strcasecmp($mimeType, $this->getMimeType($struct)) == 0) {
            $partNumber = $partNumber ? $partNumber : 1;
            if (($text = imap_fetchbody($this->mbox, $mid, $partNumber))) {
                if ($struct->encoding == 3 or $struct->encoding == 4) //base64 and qp decode.
                {
                    $text = $this->decode($struct->encoding, $text);
                }

                $charset = null;
                if ($encoding) { //Convert text to desired mime encoding...
                    if ($struct->ifparameters) {
                        if (!strcasecmp($struct->parameters[0]->attribute, 'CHARSET') && strcasecmp($struct->parameters[0]->value, 'US-ASCII')) {
                            $charset = trim($struct->parameters[0]->value);
                        }
                    }
                    $text = $this->mime_encode($text, $charset, $encoding);
                }

                return $text;
            }
        }
        //Do recursive search
        $text = '';
        if ($struct && $struct->parts) {
            while (list($i, $substruct) = each($struct->parts)) {
                if ($partNumber) {
                    $prefix = $partNumber . '.';
                }
                if (($result = $this->getPart($mid, $mimeType, $encoding, $substruct, $prefix . ($i + 1)))) {
                    $text .= $result;
                }
            }
        }

        return $text;
    }

    public function getHeader($mid) {
        return imap_fetchheader($this->mbox, $mid, FT_PREFETCHTEXT);
    }

    public function getBody($mid) {
        return $this->getpart($mid, 'TEXT/PLAIN', $this->charset) || $this->getPart($mid, 'TEXT/HTML', $this->charset);
    }

    public function createTicket($mid) {
        $mailinfo = $this->getHeaderInfo($mid);

        $var['name'] = $this->mime_decode($mailinfo['from']['name']);
        $var['email'] = $mailinfo['from']['email'];
        $var['subject'] = $mailinfo['subject'] ? $this->mime_decode($mailinfo['subject']) : '[No Subject]';

        if (empty($var['subject'])) {
            $var['subject'] = "No Subject";
        }
        $var['message'] = $this->getBody($mid);
        if (empty($var['message'])) {
            $var['message'] = "No Message";
        }

        $var['header'] = $this->getHeader($mid);
        $var['name'] = $var['name'] ? $var['name'] : $var['email']; //No name? use email
        $var['mid'] = $mailinfo['mid'];

        //Save attachments if any.
        if (($struct = imap_fetchstructure($this->mbox, $mid)) && $struct->parts) {
            $this->saveAttachments($mid, $struct);
        }
    }

    public function saveAttachments($ticket, $mid, $part, $index = 0) {
        global $cfg;

        if ($part && in_array($part->type, array(3, 4, 5, 6, 7))) { //attachment
            $filename = "";
            if (isset ($part->parameters)) {
                foreach ($part->parameters as $k => $attr) {
                    if (isset($attr->attribute) && strcasecmp($attr->attribute, 'name') == 0) {
                        $filename = $this->mime_decode($attr->value);
                    }
                }
            }
            if (empty($filename)) {
                return;
            }
            $index = $index ? $index : 1;
            if ($cfg->canUploadFileType($filename) && $cfg->getMaxFileSize() >= $part->bytes) {
                //extract the attachments...and do the magic.
                $att = imap_fetchbody($this->mbox, $mid, $index);
                $data = $this->decode($part->encoding, $att);

                //$ticket->saveAttachment($filename,$data,$ticket->getLastMsgId(),'M');
                return;
            }
        }

        //Recursive attachment search!
        if ($part && $part->parts) {
            foreach ($part->parts as $k => $struct) {
                if ($index) {
                    $prefix = $index . '.';
                }
                $this->saveAttachments($ticket, $mid, $struct, $prefix . ($k + 1));
            }
        }

    }

    public function fetchTickets() {
        $nummsgs = imap_num_msg($this->mbox);
        echo "New Emails:  $nummsgs\n";
        for ($i = $nummsgs; $i > 0; $i--) { //process messages in reverse. Latest first. FILO.
            $this->createTicket($i);
            //imap_setflag_full($this->mbox, imap_uid($this->mbox, $i), "\\Seen", ST_UID); //IMAP only??
            //imap_delete($this->mbox, $i);
        }

        //@imap_expunge($this->mbox);
    }
}

//We require imap ext to fetch emails via IMAP/POP3
if (!function_exists('imap_open')) {
    die('PHP must be compiled with IMAP extension enabled for IMAP/POP3 fetch to work!');
}

$fetcher = new MailFetcher('allenxie@yahoo.com', 'P@ssword19910907', 'imap.mail.yahoo.com', '993', 'SSL');
if ($fetcher->open()) {
    $fetcher->fetchTickets();
    $fetcher->close();
} else {
    echo 'Failed to connect ' . $row['userid'] . PHP_EOL;
}