<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\EnumType;
use Poles\Json\Types\IntegerType;
use Poles\Json\Types\StringType;

class EnumTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($types, $input, $expected)
    {
        $this->assertEquals($expected, (new EnumType($types))->check($input));
    }

    public function getTestCheckData()
    {
        $types = [new IntegerType(), new StringType()];
        return [
            [$types, 14 ,true],
            [$types, 0, true],
            [$types, '', true],
            [$types, 'abc', true],
            [$types, 5.55, false],
            [$types, true, false],
            [$types, null, false],
            [$types, [1,2,3], false],
            [$types, (object)[], false]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($types, $input, $expected)
    {
        $this->assertEquals($expected, (new EnumType($types))->coerce($input));
    }

    public function getTestCoerceData()
    {
        $types = [new IntegerType(), new StringType()];
        return [
            [$types, 14, 14],
            [$types, 0, 0],
            [$types, '', ''],
            [$types, 'abc', 'abc'],
            [$types, 5.55, 5.55],
            [$types, true, true],
            [$types, null, null],
            [$types, [1,2,3], [1,2,3]],
            [$types, (object)[], (object)[]]
        ];
    }
}