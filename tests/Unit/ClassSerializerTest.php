<?php

namespace Poles\Json\Tests\Unit;

use const JSON_PRETTY_PRINT;
use PHPUnit\Framework\TestCase;
use Poles\Json\ClassSerializer;
use Poles\Json\Tests\Support\CompositeClass;
use Poles\Json\Tests\Support\IntegerClass;
use Poles\Json\Tests\Support\MixedClass;
use Poles\Json\Tests\Support\StringClass;
use Poles\Json\Tests\Support\TypedArrayClass;

class ClassSerializerTest extends TestCase
{
    public function testInfersSchemaFromClassName()
    {
        $expected = new StringClass();
        $expected->prop = 'abc';
        $deserializer = new ClassSerializer(StringClass::class);
        $this->assertEquals($expected, $deserializer->deserialize('{"prop": "abc"}'));
    }

    public function testSerializePlain()
    {
        $s = new ClassSerializer(MixedClass::class);
        $this->assertEquals('{"prop":null}', $s->serialize(new MixedClass()));
    }

    public function testSerializeWithOptions()
    {
        $s = new ClassSerializer(MixedClass::class);
        $expected = <<<JSON
{
    "prop": null
}
JSON;
        $this->assertEquals($expected, $s->serialize(new MixedClass(), JSON_PRETTY_PRINT));
    }

    /**
     * @expectedException \Poles\Json\Exceptions\EncodeException
     * @expectedExceptionMessage Maximum stack depth exceeded
     */
    public function testSerializeWithMaxDepth()
    {
        $s = new ClassSerializer(CompositeClass::class);
        $subject = new CompositeClass();
        $subject->prop = new TypedArrayClass();
        $subject->prop->strings = ['a', 'b', 'c'];
        $s->serialize($subject, 0, 2);
    }

    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     */
    public function testTypeIsChecked()
    {
        (new ClassSerializer(IntegerClass::class))->serialize(new StringClass());
    }
}
