<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\Schema\Schema;
use Poles\Json\SchemaSerializer;
use Poles\Json\SerializerConfig;
use Poles\Json\Tests\Support\EmptyClass;
use Poles\Json\Tests\Support\SchemaHelper;
use Poles\Json\Tests\Support\StringClass;

class SchemaSerializerTest extends TestCase
{
    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     * @dataProvider getThrowsOnNonObjectsData
     */
    public function testThrowsOnNonObjects($input)
    {
        (new SchemaSerializer(new Schema(EmptyClass::class, []), new SerializerConfig()))
            ->deserialize(json_encode($input));
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
        $results = (new SchemaSerializer(SchemaHelper::infer(StringClass::class), new SerializerConfig()))
            ->deserialize('{"prop": "abc"}');
        $this->assertEquals($expected, $results);
    }
}
