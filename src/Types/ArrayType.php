<?php

namespace Poles\Json\Types;

class ArrayType implements Type
{
    /** @var Type */
    private $elementType;

    public function __construct(Type $elementType)
    {
        $this->elementType = $elementType;
    }

    public function check($value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        for ($i = 0, $c = count($value); $i < $c; $i++) {
            if (!$this->elementType->check($value[$i])) {
                return false;
            }
        }
        return true;
    }

    public function coerce($value): array
    {
        return array_map([$this->elementType, 'coerce'], (array)$value);
    }

    public static function __set_state($props)
    {
        return new static($props['elementType']);
    }
}
