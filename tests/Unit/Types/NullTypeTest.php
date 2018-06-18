<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\NullType;

class NullTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new NullType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            [null, true],
            [0.0, false],
            ['', false],
            [0, false],
            [true, false],
            [[1,2,3], false],
            [(object)[], false]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($input, $expected)
    {
        $this->assertEquals($expected, (new NullType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            [null, null],
            [0.0, null],
            ['', null],
            [0, null],
            [true, null],
            [[1,2,3], null],
            [(object)[], null]
        ];
    }
}
