<?php

namespace Poles\Json\Types;

class EnumType implements Type
{
    /** @var Type[] */
    private $types;

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function check($value): bool
    {
        foreach ($this->types as $type) {
            if ($type->check($value)) {
                return true;
            }
        }
        return false;
    }

    public function coerce($value)
    {
        return $value;
    }
}
