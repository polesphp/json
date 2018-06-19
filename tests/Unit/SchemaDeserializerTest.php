<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\Schema\Schema;
use Poles\Json\SchemaDeserializer;
use Poles\Json\Tests\Support\EmptyClass;
use Poles\Json\Tests\Support\StringClass;

class SchemaDeserializerTest extends TestCase
{
    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     * @dataProvider getThrowsOnNonObjectsData
     */
    public function testThrowsOnNonObjects($input)
    {
        (new SchemaDeserializer(new Schema(EmptyClass::class, [])))->deserialize(json_encode($input));
    }

    public function getThrowsOnNonObjectsData()
    {
        return [
            [42],
            [5.55],
            [true],
            [false],
            [null],
            [[1,2,3]]
        ];
    }

    public function testDeserializesObjectsUsingSchema()
    {
        $expected = new StringClass();
        $expected->prop = 'abc';
        $results = (new SchemaDeserializer(Schema::infer(StringClass::class)))->deserialize('{"prop": "abc"}');
        $this->assertEquals($expected, $results);
    }
}
