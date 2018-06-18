<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Types\ArrayType;
use Poles\Json\Types\IntegerType;

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
            [null, [], true],
            [null, 0, false],
            [null, '', false],
            [null, 4.5, false],
            [null, true, false],
            [null, null, false],
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
            [null, [], []],
            [null, [1,'a',true], [1,'a',true]],
            [null, 1, [1]],
            [new IntegerType(), [1,2,3], [1,2,3]],
            [new IntegerType(), '1', [1]]
        ];
    }
}