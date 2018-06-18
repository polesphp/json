<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\ClassDeserializer;
use Poles\Json\Tests\Support\StringClass;

class ClassDeserializerTest extends TestCase
{
    public function testInfersSchemaFromClassName()
    {
        $expected = new StringClass();
        $expected->prop = 'abc';
        $deserializer = new ClassDeserializer(StringClass::class);
        $this->assertEquals($expected, $deserializer->deserialize('{"prop": "abc"}'));
    }
}
