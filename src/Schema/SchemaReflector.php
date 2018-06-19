<?php

namespace Poles\Json\Schema;

use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\ContextFactory;
use Poles\Json\Exceptions\UnresolvableClassException;
use Poles\Json\Exceptions\UnsupportedTypeException;
use Poles\Json\Types\ArrayType;
use Poles\Json\Types\BooleanType;
use Poles\Json\Types\EnumType;
use Poles\Json\Types\FloatType;
use Poles\Json\Types\IntegerType;
use Poles\Json\Types\MixedType;
use Poles\Json\Types\NullType;
use Poles\Json\Types\ObjectType;
use Poles\Json\Types\StringType;
use Poles\Json\Types\Type;
use ReflectionClass;
use ReflectionProperty;

class SchemaReflector
{
    /** @var string */
    private $className;

    /** @var ReflectionProperty */
    private $currentProperty;

    /** @var Context */
    private $context;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function reflect(): Schema
    {
        $refClass = new ReflectionClass($this->className);
        $contextFactory = new ContextFactory();
        $this->context = $contextFactory->createFromReflector($refClass);
        $properties = [];
        foreach ($refClass->getProperties() as $property) {
            $this->currentProperty = $property;
            $pname = $property->getName();
            $doc = $property->getDocComment();
            if (!empty($doc) && preg_match('/@var\s+([^\s]+)/', $doc, $matches)) {
                $type = $this->reflectType($matches[1]);
            } else {
                $type = new MixedType();
            }
            $properties[$pname] = $type;
        }
        $schema = new Schema($refClass->getName(), $properties);
        return $schema;
    }

    private function reflectType(string $typeStr): Type
    {
        $atomTypes = explode('|', $typeStr);
        if (count($atomTypes) > 1) {
            return new EnumType(array_map([$this, 'reflectAtomType'], $atomTypes));
        }
        return $this->reflectAtomType($typeStr);
    }

    private function reflectAtomType(string $typeStr): Type
    {
        if (substr($typeStr, -2) === '[]') {
            $elemTypeStr = substr($typeStr, 0, strlen($typeStr) - 2);
            $elemType = $this->reflectType($elemTypeStr);
            return new ArrayType($elemType);
        }
        return $this->reflectPrimitiveType($typeStr);
    }

    private function reflectPrimitiveType(string $typeStr): Type
    {
        switch ($typeStr) {
            case 'null':
                return new NullType();
            case 'string':
                return new StringType();
            case 'int':
            case 'integer':
                return new IntegerType();
            case 'float':
                return new FloatType();
            case 'bool':
            case 'boolean':
                return new BooleanType();
            case 'mixed[]':
            case 'array':
                return new ArrayType(new MixedType());
            case 'mixed':
                return new MixedType();
            case 'callable':
            case 'true':
            case 'false':
            case 'object':
            case 'resource':
            case 'self':
            case 'static':
            case '$this':
            case 'void':
                throw new UnsupportedTypeException("phpDocumentor type {$typeStr} is not supported!");
            default:
                $resolver = new FqsenResolver();
                $resolvedFqsen = (string)$resolver->resolve($typeStr, $this->context);
                if (class_exists($resolvedFqsen)) {
                    $subScanner = new SchemaReflector($resolvedFqsen);
                    return new ObjectType($subScanner->reflect());
                } else {
                    throw new UnresolvableClassException("Class {$typeStr} does not exist!");
                }
        }
    }
}
