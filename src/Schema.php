<?php

namespace Poles\Json;

use function class_exists;
use Poles\Json\Types\ArrayType;
use Poles\Json\Types\BooleanType;
use Poles\Json\Types\EnumType;
use Poles\Json\Types\FloatType;
use Poles\Json\Types\IntegerType;
use Poles\Json\Types\NullType;
use Poles\Json\Types\ObjectType;
use Poles\Json\Types\StringType;
use Poles\Json\Types\Type;
use ReflectionClass;

class Schema
{
    /** @var string */
    private $className;

    /** @var Type[] */
    private $properties;

    public function __construct(string $className, array $properties = [])
    {
        $this->className = $className;
        $this->properties = $properties;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return Type[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public static function infer(string $className): Schema
    {
        $refClass = new ReflectionClass($className);
        $properties = [];
        foreach ($refClass->getProperties() as $property) {
            $pname = $property->getName();
            $doc = $property->getDocComment();
            $type = null;
            if (!empty($doc)) {
                if (preg_match('/@var\s+([^\s]+)/', $doc, $matches)) {
                    $type = self::inferType($matches[1]);
                }
            }
            $properties[$pname] = $type;
        }
        $schema = new Schema($refClass->getName(), $properties);
        return $schema;
    }

    private static function inferType(string $typeStr): ?Type
    {
        $atomTypes = explode('|', $typeStr);
        if (count($atomTypes) > 1) {
            return new EnumType(array_map([self::class, 'inferAtomType'], $atomTypes));
        }
        return self::inferAtomType($typeStr);
    }

    private static function inferAtomType(string $typeStr): ?Type
    {
        if (substr($typeStr, -2) === '[]') {
            $elemTypeStr = substr($typeStr, 0, strlen($typeStr) - 2);
            $elemType = self::inferType($elemTypeStr);
            return new ArrayType($elemType);
        }
        return self::inferPrimitiveType($typeStr);
    }

    private static function inferPrimitiveType(string $typeStr): ?Type
    {
        $type = null;
        switch ($typeStr) {
            case 'null':
                $type = new NullType();
                break;
            case 'string':
                $type = new StringType();
                break;
            case 'int':
            case 'integer':
                $type = new IntegerType();
                break;
            case 'float':
                $type = new FloatType();
                break;
            case 'bool':
            case 'boolean':
                $type = new BooleanType();
                break;
            case 'mixed[]':
            case 'array':
                $type = new ArrayType();
                break;
            default:
                if (class_exists($typeStr)) {
                    $type = new ObjectType(self::infer($typeStr));
                }
        }
        return $type;
    }
}