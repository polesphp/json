<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\ArrayType;
use Poles\Json\Types\IntegerType;
use Poles\Json\Types\MixedType;

class ArrayTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($elemType, $input, $expected)
    {
        $this->assertEquals($expected, (new ArrayType($elemType))->check($input));
    }

    public function getTestCheckData()
    {
        return [
            [new MixedType(), [], true],
            [new MixedType(), 0, false],
            [new MixedType(), '', false],
            [new MixedType(), 4.5, false],
            [new MixedType(), true, false],
            [new MixedType(), null, false],
            [new IntegerType(), [], true],
            [new IntegerType(), [1,2,3], true],
            [new IntegerType(), [1,'2',3], false]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($elemType ,$input, $expected)
    {
        $this->assertEquals($expected, (new ArrayType($elemType))->coerce($input));
    }

    public function getTestCoerceData()
    {
        return [
            [new MixedType(), [], []],
            [new MixedType(), [1,'a',true], [1,'a',true]],
            [new MixedType(), 1, [1]],
            [new IntegerType(), [1,2,3], [1,2,3]],
            [new IntegerType(), '1', [1]]
        ];
    }
}
