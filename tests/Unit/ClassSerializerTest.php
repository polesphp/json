<?php

namespace Poles\Json\Tests\Unit;

use const JSON_PRETTY_PRINT;
use PHPUnit\Framework\TestCase;
use Poles\Json\ClassSerializer;
use Poles\Json\Schema\SchemaCache;
use Poles\Json\SerializerConfig;
use Poles\Json\Tests\Support\AllTypesClass;
use Poles\Json\Tests\Support\CompositeClass;
use Poles\Json\Tests\Support\EmptyClass;
use Poles\Json\Tests\Support\IntegerClass;
use Poles\Json\Tests\Support\MixedClass;
use Poles\Json\Tests\Support\SchemaHelper;
use Poles\Json\Tests\Support\StringClass;
use Poles\Json\Tests\Support\TypedArrayClass;
use function sys_get_temp_dir;

class ClassSerializerTest extends TestCase
{
    public function testInfersSchemaFromClassName()
    {
        $expected = new StringClass();
        $expected->prop = 'abc';
        $deserializer = new ClassSerializer(StringClass::class, new SerializerConfig());
        $this->assertEquals($expected, $deserializer->deserialize('{"prop": "abc"}'));
    }

    public function testSerializePlain()
    {
        $s = new ClassSerializer(MixedClass::class, new SerializerConfig());
        $this->assertEquals('{"prop":null}', $s->serialize(new MixedClass()));
    }

    public function testSerializeWithOptions()
    {
        $conf = new SerializerConfig();
        $conf->setOptions(JSON_PRETTY_PRINT);
        $s = new ClassSerializer(MixedClass::class, $conf);
        $expected = <<<JSON
{
    "prop": null
}
JSON;
        $this->assertEquals($expected, $s->serialize(new MixedClass()));
    }

    public function testSerializerChecksCacheIfCacheDirIsSet()
    {
        $dir = sys_get_temp_dir();
        $schemaCache = new SchemaCache($dir);

        $emptySchema = SchemaHelper::infer(EmptyClass::class);
        $schemaCache->write(AllTypesClass::class, $emptySchema);

        $config = new SerializerConfig();
        $config->setCacheDirectory($dir);

        $serializer = new ClassSerializer(AllTypesClass::class, $config);
        $this->assertInstanceOf(EmptyClass::class, $serializer->deserialize('{}'));
    }

    /**
     * @expectedException \Poles\Json\Exceptions\EncodeException
     * @expectedExceptionMessage Maximum stack depth exceeded
     */
    public function testSerializeWithMaxDepth()
    {
        $conf = new SerializerConfig();
        $conf->setMaxDepth(2);
        $s = new ClassSerializer(CompositeClass::class, $conf);
        $subject = new CompositeClass();
        $subject->prop = new TypedArrayClass();
        $subject->prop->strings = ['a', 'b', 'c'];
        $s->serialize($subject);
    }

    /**
     * @expectedException \Poles\Json\Exceptions\TypeMismatchException
     */
    public function testTypeIsChecked()
    {
        (new ClassSerializer(IntegerClass::class, new SerializerConfig()))
            ->serialize(new StringClass());
    }
}
