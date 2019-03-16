<?php


require_once __DIR__ . '/../vendor/autoload.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', '样衣ID')
    ->setCellValue('B1', '样衣颜色')
    ->setCellValue('C1', '样衣面料')
    ->setCellValue('D1', '样衣长度')
    ->setCellValue('E1', '样衣尺寸')
    ->setCellValue('F1', '参考采购价')
    ->setCellValue('G1', '售价')
    ->setCellValue('H1', '美元汇率')
    ->setCellValue('I1', '倍率')
    ->setCellValue('J1', '备注')
    ->setCellValue('E2', '2码')
    ->setCellValue('H2', '6.15')
    ->setCellValue('J2', '样衣，喉咙到地60inch。图上有披肩就一律做带披肩的。如没有特殊注明请做图片色，如有疑问请及时提出。');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('样衣订单模板');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

ob_start();
$objWriter->save('php://output');

$abc = ob_get_clean();

var_dump($abc);

//$objWriter->save('b.xlsx');
exit();