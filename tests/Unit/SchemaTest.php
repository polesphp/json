<?php

namespace Poles\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Poles\Json\Schema;
use Poles\Json\Types\ArrayType;
use Poles\Json\Types\BooleanType;
use Poles\Json\Types\EnumType;
use Poles\Json\Types\FloatType;
use Poles\Json\Types\IntegerType;
use Poles\Json\Types\NullType;
use Poles\Json\Types\ObjectType;
use Poles\Json\Types\StringType;
use Poles\Json\Tests\Support\BooleanClass;
use Poles\Json\Tests\Support\CompositeClass;
use Poles\Json\Tests\Support\EmptyClass;
use Poles\Json\Tests\Support\EnumClass;
use Poles\Json\Tests\Support\FloatClass;
use Poles\Json\Tests\Support\IntegerClass;
use Poles\Json\Tests\Support\MixedArrayClass;
use Poles\Json\Tests\Support\MixedClass;
use Poles\Json\Tests\Support\NullClass;
use Poles\Json\Tests\Support\StringClass;
use Poles\Json\Tests\Support\TypedArrayClass;
use Poles\Json\Tests\Support\UntypedClass;

class SchemaTest extends TestCase
{
    /**
     * @dataProvider getInferData
     */
    public function testInferTyped($class, $expectedSchema)
    {
        $this->assertEquals($expectedSchema, Schema::infer($class));
    }

    public function getInferData()
    {
        return [
            [
                EmptyClass::class,
                new Schema(EmptyClass::class)
            ],
            [
                UntypedClass::class,
                new Schema(UntypedClass::class, [
                    'prop' => null
                ])
            ],
            [
                MixedClass::class,
                new Schema(MixedClass::class, [
                    'prop' => null
                ])
            ],
            [
                NullClass::class,
                new Schema(NullClass::class, [
                    'prop' => new NullType()
                ])
            ],
            [
                StringClass::class,
                new Schema(StringClass::class, [
                    'prop' => new StringType()
                ])
            ],
            [
                IntegerClass::class,
                new Schema(IntegerClass::class, [
                    'prop1' => new IntegerType(),
                    'prop2' => new IntegerType()
                ])
            ],
            [
                FloatClass::class,
                new Schema(FloatClass::class, [
                    'prop' => new FloatType()
                ])
            ],
            [
                BooleanClass::class,
                new Schema(BooleanClass::class, [
                    'prop1' => new BooleanType(),
                    'prop2' => new BooleanType()
                ])
            ],
            [
                MixedArrayClass::class,
                new Schema(MixedArrayClass::class, [
                    'prop1' => new ArrayType(),
                    'prop2' => new ArrayType()
                ])
            ],
            [
                TypedArrayClass::class,
                new Schema(TypedArrayClass::class, [
                    'ints' => new ArrayType(new IntegerType()),
                    'integers' => new ArrayType(new IntegerType()),
                    'booleans' => new ArrayType(new BooleanType()),
                    'bools' => new ArrayType(new BooleanType()),
                    'strings' => new ArrayType(new StringType()),
                    'arrays' => new ArrayType(new ArrayType()),
                    'arraysOfInts' => new ArrayType(new ArrayType(new IntegerType()))
                ])
            ],
            [
                CompositeClass::class,
                new Schema(CompositeClass::class, [
                    'prop' => new ObjectType(
                        new Schema(TypedArrayClass::class, [
                            'ints' => new ArrayType(new IntegerType()),
                            'integers' => new ArrayType(new IntegerType()),
                            'booleans' => new ArrayType(new BooleanType()),
                            'bools' => new ArrayType(new BooleanType()),
                            'strings' => new ArrayType(new StringType()),
                            'arrays' => new ArrayType(new ArrayType()),
                            'arraysOfInts' => new ArrayType(new ArrayType(new IntegerType()))
                        ])
                    )
                ])
            ],
            [
                EnumClass::class,
                new Schema(EnumClass::class, [
                    'prop' => new EnumType([
                        new IntegerType(),
                        new StringType(),
                        new NullType(),
                        new ArrayType(new BooleanType())
                    ])
                ])
            ]
        ];
    }
}