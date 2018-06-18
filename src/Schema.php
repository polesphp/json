<?php

namespace Poles\Json;

use Poles\Json\Types\Type;

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

    public static function infer(string $className): Schema
    {
        return (new SchemaReflector($className))->reflect();
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
}
