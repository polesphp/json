<?php


namespace Poles\Json;


use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
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
use ReflectionProperty;

class SchemaReflector
{
    /** @var string */
    private $className;

    /** @var ReflectionProperty */
    private $currentProperty;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function reflect(): Schema
    {
        $refClass = new ReflectionClass($this->className);
        $properties = [];
        foreach ($refClass->getProperties() as $property) {
            $this->currentProperty = $property;
            $pname = $property->getName();
            $doc = $property->getDocComment();
            $type = null;
            if (!empty($doc)) {
                if (preg_match('/@var\s+([^\s]+)/', $doc, $matches)) {
                    $type = $this->reflectType($matches[1]);
                }
            }
            $properties[$pname] = $type;
        }
        $schema = new Schema($refClass->getName(), $properties);
        return $schema;
    }

    private function reflectType(string $typeStr): ?Type
    {
        $atomTypes = explode('|', $typeStr);
        if (count($atomTypes) > 1) {
            return new EnumType(array_map([$this, 'reflectAtomType'], $atomTypes));
        }
        return $this->reflectAtomType($typeStr);
    }

    private function reflectAtomType(string $typeStr): ?Type
    {
        if (substr($typeStr, -2) === '[]') {
            $elemTypeStr = substr($typeStr, 0, strlen($typeStr) - 2);
            $elemType = $this->reflectType($elemTypeStr);
            return new ArrayType($elemType);
        }
        return $this->reflectPrimitiveType($typeStr);
    }

    private function reflectPrimitiveType(string $typeStr): ?Type
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
            case 'mixed':
                break;
            default:
                $contextFactory = new ContextFactory();
                $context = $contextFactory->createFromReflector($this->currentProperty);
                $resolver = new FqsenResolver();
                $resolvedFqsen = (string)$resolver->resolve($typeStr, $context);
                if (class_exists($resolvedFqsen)) {
                    $subScanner = new SchemaReflector($resolvedFqsen);
                    $type = new ObjectType($subScanner->reflect());
                }
        }
        return $type;
    }
}
