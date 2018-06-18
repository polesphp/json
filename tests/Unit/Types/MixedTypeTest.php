<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\MixedType;

class MixedTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new MixedType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            [1244, true],
            [0, true],
            ['', true],
            ['abc', true],
            [5.55, true],
            [true, true],
            [false, true],
            [null, true],
            [[1,2,3], true],
            [(object)[], true]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($input, $expected)
    {
        $this->assertEquals($expected, (new MixedType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        $obj = (object)[];
        return [
            [1244, 1244],
            [0, 0],
            ['', ''],
            ['abc', 'abc'],
            [5.55, 5.55],
            [true, true],
            [false, false],
            [null, null],
            [[1,2,3], [1,2,3]],
            [$obj, $obj]
        ];
    }
}
