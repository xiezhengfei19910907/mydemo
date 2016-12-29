<?php
$files = scandir('./dressfirst');

array_shift($files);
array_shift($files);

chdir('./dressfirst');

foreach ($files as $file) {
    rename($file, str_replace(array('_wd', '_bd', '_sod'), array('_v2', '', ''), $file));
}