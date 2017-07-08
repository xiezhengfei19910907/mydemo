<?php

/**
 * phpunitat57 --verbose ./php/phpunit/DataTest
 */

class DataTest extends \PHPUnit\Framework\TestCase {

    public function getTestDataWithoutKey() {
        return [
            [1, 2, 3],
            [1, 1, 2],
            [1, 3, 4],
            [1, 4, 5],
        ];
    }

    public function getTestData() {
        return [
            '1and2equal3' => [1, 2, 3],
            '1and1equal2' => [1, 1, 2],
            '1and3equal4' => [1, 3, 4],
            '1and4equal5' => [1, 4, 5],
        ];
    }

    public function getTestData1() {
        return [
            ['data1'],
            ['data2'],
        ];
    }

    /**
     * @dataProvider getTestData
     */
    public function testDataAdd($a, $b, $addResult) {
        $this->assertEquals($addResult, $a + $b);
    }

    /**
     * @@dataProvider getTestDataWithoutKey
     */
    public function testDataAdd1($a, $b, $addResult) {
        $this->assertEquals($addResult, $a + $b);
    }

    public function testOne() {
        $this->assertTrue(true);

        return 'one';
    }

    public function testTwo() {
        $this->assertTrue(true);

        return 'two';
    }

    /**
     * @depends testOne
     * @depends testTwo
     * @dataProvider getTestData1
     */
    public function testEqual() {
        //var_dump(func_get_args());
        $this->assertEquals([current(func_get_args()), 'one', 'two'], func_get_args());
    }

    /**
     * @depends testEqual
     */
    public function testDepends() {
        $this->assertTrue(true);
    }

}