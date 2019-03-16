<?php
/**
 * Created by allen
 * Date: 2019-03-16
 */

use Intervention\Image\ImageManager;


$imageList = [
    base64_encode(file_get_contents('./WechatIMG3476.jpeg')),
    base64_encode(file_get_contents('./WechatIMG3475.jpeg')),
];


$vendorDir = dirname(dirname(__FILE__)) . '/vendor';

require_once $vendorDir . '/autoload.php';

$imageManagerList = [];
$maxHeight = $maxWidth = 0;
$extersion = '';
foreach ($imageList as $index => $image) {
    $imageManager = new ImageManager();
    $imageManager = $imageManager->make($image);
    $height = $imageManager->height();
    $width = $imageManager->width();

    $maxWidth = max($maxWidth, $width);
    $maxHeight += $height;

    $imageManagerList[$index] = [
        'obj'    => $imageManager,
        'width'  => $width,
        'height' => $height,
    ];

    if (empty($extersion)) {
        $mime = $imageManager->mime();
        $temp = !empty($mime) ? explode('/', $mime) : [];
        if (!empty($temp)) {
            $extersion = array_pop($temp);
        }
    }
}

$x = $y = 0;
$imageManager = new ImageManager();
$canvas = $imageManager->canvas($maxWidth, $maxHeight);
foreach ($imageManagerList as $item) {
    $canvas->insert($item['obj'], 'top-left', $x, $y);
    $y += $item['height'];
}
//ob_start();
$canvas->save("./a.{$extersion}");
//$imageStream = ob_get_clean();
