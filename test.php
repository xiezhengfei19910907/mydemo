<?php

echo '今天是:' . date('Y-m-d'), PHP_EOL;
echo '-100天是:' . date("Y-m-d", strtotime("- 100 day"));