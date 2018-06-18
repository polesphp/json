<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\FloatType;

class FloatTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new FloatType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            [1244.55, true],
            [0.0, true],
            ['', false],
            [0, false],
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
        $this->assertEquals($expected, (new FloatType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            [1.55, 1.55],
            [15, 15.0],
            [0, 0.0],
            [true, 1.0],
            [false, 0.0],
            ['1.25', 1.25],
            ['', 0.0]
        ];
    }
}
