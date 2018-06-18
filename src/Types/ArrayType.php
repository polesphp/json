<?php

namespace Poles\Json\Types;

class ArrayType implements Type
{
    /** @var null|Type */
    private $elementType;

    public function __construct(?Type $elementType = null)
    {
        $this->elementType = $elementType;
    }

    public function check($value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        if ($this->elementType) {
            for ($i=0, $c = count($value); $i < $c; $i++) {
                if (!$this->elementType->check($value[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    public function coerce($value): array
    {
        if ($this->elementType) {
            return array_map([$this->elementType, 'coerce'], (array)$value);
        }
        return (array)$value;
    }
}