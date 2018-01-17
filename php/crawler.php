<?php
/**
 * Created by allen
 * Date: 2018-01-16
 */

$vendorDir = dirname(dirname(__FILE__)) . '/vendor';
require_once $vendorDir . '/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

$html = '<html>
<body>
    <span id="article-100" class="article">Article 1</span>
    <span id="article-101" class="article">Article 2</span>
    <span id="article-102" class="article">Article 3</span>
</body>
</html>';

$crawler = new Crawler();
$crawler->addHtmlContent($html);

$result = $crawler->filterXPath('//span[contains(@id, "article-")]')->evaluate('substring-after(@id, "-")');

var_dump($result);