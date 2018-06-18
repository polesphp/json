<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\StringType;

class StringTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($input, $expected)
    {
        $this->assertEquals($expected, (new StringType())->check($input));
    }

    public function getTestCheckData()
    {
        return [
            ['', true],
            [0, false],
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
        $this->assertEquals($expected, (new StringType())->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            ['', ''],
            ['abc', 'abc'],
            [1, '1'],
            [1.2, '1.2'],
            [true, '1'],
            [false, '']
        ];
    }
}
