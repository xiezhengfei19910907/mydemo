<?php
require_once __DIR__ . '/../vendor/autoload.php';

$objPHPExcel = PHPExcel_IOFactory::load("trustly.xlsx");
$sheet = $objPHPExcel->getActiveSheet();    // TODO 循环遍历每一个sheet

$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();

$range = sprintf('A1:%s', $highestColumn . $highestRow);

$regionArray = $sheet->rangeToArray($range);


$dbLink = new PDO('mysql:host=192.168.1.233;dbname=jjshouse;charset=UTF8', 'root', 'root');


//$languageMap = array_shift($regionArray);
//array_shift($languageMap);
//array_pop($languageMap);  // TODO array_filter

// TODO 查询一次数据库
foreach ($regionArray as $key => &$value) {
    $language = trim($value[0]);
    $selectObject = $dbLink->prepare('SELECT languages_id FROM languages WHERE NAME = :NAME ');
    $selectObject->bindValue(':NAME', $language);
    $selectObject->execute();
    $languageObject = $selectObject->fetchAll(\PDO::FETCH_OBJ);

    $regionArray[$key]['lanid'] = $languageObject[0]->languages_id;
}
//unset($language);


$sql = 'INSERT INTO payment_language(payment_id, language_id, payment_desc, payment_name, lang_acct_name, create_time) VALUES ';


foreach ($regionArray as $regionInfo) {
    //$region = array_shift($regionInfo);
    //array_pop($regionInfo);

    //foreach ($regionInfo as $language => $regionName) {
        //$language = $languageMap[$language];

        $sql .= sprintf('(%s, %s, %s, %s, "", now()),', 192, $regionInfo['lanid'], $dbLink->quote($regionInfo[1]), $dbLink->quote("Trustly"));
    //}
}
$sql = substr($sql, 0, -1);
$resultRows = $dbLink->query($sql);

var_dump($dbLink->errorInfo());