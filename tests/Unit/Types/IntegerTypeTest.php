<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\IntegerType;

class IntegerTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new IntegerType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            [1244, true],
            [0, true],
            ['', false],
            [5.55, false],
            [true, false],
            [null, false],
            [[1,2,3], false],
            [(object)[], false]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($input, $expected)
    {
        $this->assertEquals($expected, (new IntegerType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            [155, 155],
            [1.15, 1],
            ['', 0],
            ['abc', 0],
            [true, 1],
            [false, 0]
        ];
    }
}