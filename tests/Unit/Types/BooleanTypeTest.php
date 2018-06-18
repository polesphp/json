<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\BooleanType;

class BooleanTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new BooleanType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            ['', false],
            [0, false],
            [1244.55, false],
            [true, true],
            [false, true],
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
        $this->assertSame($expected, (new BooleanType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            [true, true],
            [false, false],
            [1.55, true],
            [15, true],
            [0, false],
            ['1.25', true],
            ['', false],
            ['0', false]
        ];
    }

}