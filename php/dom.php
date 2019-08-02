<?php

$html = file_get_contents('./20190328_pdf.html');


$dom = new DOMDocument();

$dom->loadHTML($html);

$node = $dom->getElementById('resume_preview');


//var_dump($node);
if (!is_null($node)) {
$address = $node->getAttribute('src');


var_dump(file_get_contents($address));
}
