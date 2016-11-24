<?php
require_once __DIR__ . '/../vendor/autoload.php';

$objPHPExcel = PHPExcel_IOFactory::load("Country_List_Translation.xlsx");
$sheet = $objPHPExcel->getActiveSheet();

$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();

$range = sprintf('A1:%s', $highestColumn . $highestRow);

$regionArray = $sheet->rangeToArray($range);


$dbLink = new PDO('mysql:host=192.168.1.233;dbname=jjshouse;charset=UTF8', 'root', 'root');


$languageMap = array_shift($regionArray);
array_shift($languageMap);
array_pop($languageMap);

// TODO 查询一次数据库
foreach ($languageMap as &$language) {
    $selectObject = $dbLink->prepare('SELECT languages_id FROM languages WHERE code = :code');
    $selectObject->bindValue(':code', $language);
    $selectObject->execute();
    $languageObject = $selectObject->fetchAll(\PDO::FETCH_OBJ);

    $language = $languageObject[0]->languages_id;
}
unset($language);


$sql = 'INSERT INTO region_language(region_id, language_id, region_name) VALUES ';


foreach ($regionArray as $regionInfo) {
    $region = array_shift($regionInfo);
    array_pop($regionInfo);

    foreach ($regionInfo as $language => $regionName) {
        $language = $languageMap[$language];

        $sql .= sprintf('(%d, %d, %s),', (int) $region, (int) $language, $dbLink->quote(trim($regionName)));
    }
}
$sql = substr($sql, 0, -1);
$dbLink->query($sql);