<?php

xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

for ($i = 0; $i <= 1000; $i++) {
    $a = $i * $i;
}

$xhprof_data = xhprof_disable();

require_once './xhprof/xhprof_lib/utils/xhprof_lib.php';
require_once './xhprof/xhprof_lib/utils/xhprof_runs.php';
$xhprofDefault = new XHProfRuns_Default();
$runID = $xhprofDefault->save_run($xhprof_data, "xhprof_testing");



echo "<a href = 'http://local.mydemo.com/xhprof/xhprof_html/index.php?run={$runID}&source=xhprof_testing'>$runID</a> ";
