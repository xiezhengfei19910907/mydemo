<?php
require_once 'vendor/autoload.php';

// �ú�����ɨ��ָ��Ŀ¼��ַ�µ��ļ�, �������б�׼����ע����ȾΪjson����
$openapi = \OpenApi\scan(['doc/dir']);
header('Content-Type: application/json');
echo $openapi->toJson();

