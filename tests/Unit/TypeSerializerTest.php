<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\SerializerConfig;
use Poles\Json\TypeSerializer;
use Poles\Json\Types\IntegerType;

class TypeSerializerTest extends TestCase
{
    /**
     * @expectedException \Poles\Json\Exceptions\DecodeException
     */
    public function testThrowsOnMalformedJson()
    {
        (new TypeSerializer(new IntegerType(), new SerializerConfig()))
            ->deserialize('{');
    }

    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     */
    public function testThrowsOnTypeMismatch()
    {
        (new TypeSerializer(new IntegerType(), new SerializerConfig()))
            ->deserialize('{}');
    }

    public function testReturnsResultOfCoerce()
    {
        $res = (new TypeSerializer(new IntegerType(), new SerializerConfig()))
            ->deserialize(json_encode(42));
        $this->assertSame(42, $res);
    }
}
