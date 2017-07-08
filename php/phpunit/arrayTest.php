<?php

/**
 * phpunitat57 unitTest ./php/phpunit/arrayTest.php
 */

class ArrayTest extends PHPUnit\Framework\TestCase {

    public function testArrayPush() {
        $array = [];
        $this->assertEquals([], $array);

        array_push($array, '1');
        $this->assertEquals(1, $array[count($array) - 1]);

        return $array;
    }

    /**
     * @depends testArrayPush
     */
    public function testArrayPop($array) {

        $this->assertEquals(1, array_pop($array));

        $this->assertEquals([], $array);
    }

}