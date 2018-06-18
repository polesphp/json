<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\TypeDeserializer;
use Poles\Json\Types\IntegerType;

class TypeDeserializerTest extends TestCase
{
    /**
     * @expectedException \Poles\Json\Exceptions\DecodeException
     */
    public function testThrowsOnMalformedJson()
    {
        (new TypeDeserializer(new IntegerType()))->deserialize('{');
    }

    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     */
    public function testThrowsOnTypeMismatch()
    {
        (new TypeDeserializer(new IntegerType()))->deserialize('{}');
    }

    public function testReturnsResultOfCoerce()
    {
        $res = (new TypeDeserializer(new IntegerType()))->deserialize(json_encode(42));
        $this->assertSame(42, $res);
    }
}
