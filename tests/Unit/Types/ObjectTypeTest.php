<?php

namespace Poles\Json\Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use Poles\Json\Tests\Support\IntegerClass;
use Poles\Json\Tests\Support\SchemaHelper;
use Poles\Json\Types\ObjectType;

class ObjectTypeTest extends TestCase
{
    /**
     * @dataProvider getTestCheckData
     */
    public function testCheck($schema, $input, $expected)
    {
        $this->assertEquals($expected, (new ObjectType($schema))->check($input));
    }

    public function getTestCheckData()
    {
        $integerSchema = SchemaHelper::infer(IntegerClass::class);
        return [
            [$integerSchema, '', false],
            [$integerSchema, 0, false],
            [$integerSchema, 4.5, false],
            [$integerSchema, true, false],
            [$integerSchema, [], false],
            [$integerSchema, (object)[], false],
            [$integerSchema, (object)['prop1' => '1', 'prop2' => 2], false],
            [$integerSchema, (object)['prop1' => 1], false],
            [$integerSchema, (object)['prop1' => 1, 'prop2' => 2], true]
        ];
    }

    /**
     * @dataProvider getTestCoerceData
     */
    public function testCoerce($schema, $input, $expected)
    {
        $this->assertEquals($expected, (new ObjectType($schema))->coerce($input));
    }

    public function getTestCoerceData()
    {
        $integerSchema = SchemaHelper::infer(IntegerClass::class);
        $expected = new IntegerClass();
        $expected->prop1 = 1;
        $expected->prop2 = 2;
        return [
            [$integerSchema, (object)['prop1' => 1, 'prop2' => 2], $expected],
        ];
    }
}
