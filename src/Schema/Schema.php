<?php

namespace Poles\Json\Schema;

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

    public static function __set_state($props)
    {
        return new static($props['className'], $props['properties']);
    }
}
