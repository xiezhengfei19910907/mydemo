<?php
/**
 * 如果图片高度大于4096 切割图片
 * Created by allen
 * Date: 2019-03-16
 */

use Intervention\Image\ImageManager;

$vendorDir = dirname(dirname(__FILE__)) . '/vendor';
require_once $vendorDir . '/autoload.php';

$maxHeight = $maxWidth = 2048;
$extersion = '';

$imageStream = file_get_contents('./boss.txt');

$imageManager = new ImageManager();
$imageManager = $imageManager->make($imageStream);
unset($imageStream);
//$imageManager->save("origin.{$extersion}");
$height = $imageManager->height();
$width = $imageManager->width();
$mime = $imageManager->mime();
$temp = !empty($mime) ? explode('/', $mime) : [];
if (!empty($temp)) {
    $extersion = array_pop($temp);
}

$currentHeight = $height;
$imageList = [];
$x = $y = 0;

while (true) {
    echo 'x:' . $x . ' y:' . $y, PHP_EOL;
    echo 'currentHeight:' . $currentHeight, PHP_EOL;

    if ($currentHeight <= $maxHeight) {
        $cloneImage = clone $imageManager;
        $imageList[] = $cloneImage->crop($width, $currentHeight, $x, $y);
        //$cloneImage->save("{$currentHeight}.{$extersion}");
        break;
    }

    $cloneImage = clone $imageManager;
    $imageList[] = $cloneImage->crop($width, $maxHeight, $x, $y);
    //$cloneImage->save("{$currentHeight}.{$extersion}");

    $y += $maxHeight;
    $currentHeight -= $maxHeight;
}
unset($imageManager, $cloneImage);

//var_dump($imageList);exit();
$encodeImageList = [];
foreach ($imageList as $index => $image) {
    // $image->save("{$index}.{$extersion}");
    ob_start();
    $image->save("php://output");
    $stream = ob_get_clean();

    $encodeImage = base64_encode($stream);
    $encodeImageList[] = $encodeImage;
}