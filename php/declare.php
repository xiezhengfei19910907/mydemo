<?php
declare(strict_types=1);
function sum(int ...$ints) {
	return array_sum($ints);
}

echo sum(6, '6', 6.1);
